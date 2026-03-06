<?php
/**
 * Packages Manager
 * Handles all package-related database operations
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once 'base-manager.php';

class PT_Packages_Manager extends PT_Base_Manager {
    
    protected $table_name;
    protected $primary_key = 'id';
    
    public function __construct() {
        parent::__construct();
        $this->table_name = $this->wpdb->prefix . 'pt_packages';
    }
    
    /**
     * Get all packages with additional filters
     */
    public function get_all($args = array()) {
        $defaults = array(
            'limit' => -1,
            'offset' => 0,
            'orderby' => 'sort_order',
            'order' => 'ASC',
            'status' => 'active',
            'search' => '',
            'category_id' => 0,
            'location_id' => 0,
            'package_type' => '',
            'is_featured' => '',
            'min_price' => 0,
            'max_price' => 0
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where_clauses = array("is_active = 1");
        $where_values = array();
        
        // Status filter (active/inactive)
        if (!empty($args['status']) && $args['status'] !== 'all') {
            $where_clauses[] = "is_active = %d";
            $where_values[] = ($args['status'] === 'active') ? 1 : 0;
        }
        
        // Category filter
        if (!empty($args['category_id'])) {
            $where_clauses[] = "category_id = %d";
            $where_values[] = $args['category_id'];
        }
        
        // Location filter
        if (!empty($args['location_id'])) {
            $where_clauses[] = "location_id = %d";
            $where_values[] = $args['location_id'];
        }
        
        // Package type filter
        if (!empty($args['package_type'])) {
            $where_clauses[] = "package_type = %s";
            $where_values[] = $args['package_type'];
        }
        
        // Featured filter
        if ($args['is_featured'] !== '') {
            $where_clauses[] = "is_featured = %d";
            $where_values[] = $args['is_featured'] ? 1 : 0;
        }
        
        // Price range filter
        if (!empty($args['min_price']) && $args['min_price'] > 0) {
            $where_clauses[] = "base_price >= %f";
            $where_values[] = $args['min_price'];
        }
        
        if (!empty($args['max_price']) && $args['max_price'] > 0) {
            $where_clauses[] = "base_price <= %f";
            $where_values[] = $args['max_price'];
        }
        
        // Search filter
        if (!empty($args['search'])) {
            $where_clauses[] = "(title LIKE %s OR description LIKE %s OR highlights LIKE %s)";
            $search_term = '%' . $args['search'] . '%';
            $where_values[] = $search_term;
            $where_values[] = $search_term;
            $where_values[] = $search_term;
        }
        
        $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
        
        $limit_sql = '';
        if ($args['limit'] > 0) {
            $limit_sql = $this->wpdb->prepare('LIMIT %d OFFSET %d', $args['limit'], $args['offset']);
        }
        
        $query = $this->wpdb->prepare(
            "SELECT p.*, 
                    c.title as category_name,
                    l.title as location_name
             FROM {$this->table_name} p
             LEFT JOIN {$this->wpdb->prefix}pt_package_categories c ON p.category_id = c.id
             LEFT JOIN {$this->wpdb->prefix}pt_locations l ON p.location_id = l.id
             {$where_sql} 
             ORDER BY {$args['orderby']} {$args['order']} 
             {$limit_sql}",
            $where_values
        );
        
        $results = $this->wpdb->get_results($query);
        
        // Format each record
        foreach ($results as $result) {
            $result = $this->format_record($result);
        }
        
        return $results;
    }
    
    /**
     * Create new package
     */
    public function create($data) {
        // Generate slug
        if (!empty($data['title']) && empty($data['slug'])) {
            $data['slug'] = $this->generate_slug($data['title']);
        }
        
        // Handle array data serialization
        $array_fields = ['includes', 'excludes', 'highlights', 'gallery_images', 'meta_keywords', 'suggested_products'];
        foreach ($array_fields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = serialize($data[$field]);
            }
        }
        
        // Set default values
        $data['sort_order'] = isset($data['sort_order']) ? $data['sort_order'] : 0;
        $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : 1;
        $data['is_featured'] = isset($data['is_featured']) ? $data['is_featured'] : 0;
        $data['current_bookings'] = 0;
        $data['rating'] = 0;
        $data['total_reviews'] = 0;
        
        return parent::create($data);
    }
    
    /**
     * Update existing package
     */
    public function update($id, $data) {
        // Generate slug if title changed
        if (isset($data['title']) && !empty($data['title'])) {
            $existing = $this->get_by_id($id);
            if (!$existing || $existing->title !== $data['title']) {
                $data['slug'] = $this->generate_slug($data['title'], $id);
            }
        }
        
        // Handle array data serialization
        $array_fields = ['includes', 'excludes', 'highlights', 'gallery_images', 'meta_keywords', 'suggested_products'];
        foreach ($array_fields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = serialize($data[$field]);
            }
        }
        
        return parent::update($id, $data);
    }
    
    /**
     * Get featured packages
     */
    public function get_featured($limit = 6) {
        $args = array(
            'limit' => $limit,
            'is_featured' => true,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get packages by category
     */
    public function get_by_category($category_id, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'category_id' => $category_id,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get packages by location
     */
    public function get_by_location($location_id, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'location_id' => $location_id,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get related packages
     */
    public function get_related($package_id, $category_id = 0, $limit = 4) {
        $package = $this->get_by_id($package_id);
        if (!$package) {
            return array();
        }
        
        $args = array(
            'limit' => $limit,
            'category_id' => $category_id ? $category_id : $package->category_id,
            'status' => 'active'
        );
        
        $packages = $this->get_all($args);
        
        // Remove the current package from results
        $packages = array_filter($packages, function($p) use ($package_id) {
            return $p->id != $package_id;
        });
        
        return array_slice($packages, 0, $limit);
    }
    
    /**
     * Get package with full details
     */
    public function get_detailed($id) {
        $query = $this->wpdb->prepare(
            "SELECT p.*, 
                    c.title as category_name,
                    c.description as category_description,
                    l.title as location_name,
                    l.description as location_description
             FROM {$this->table_name} p
             LEFT JOIN {$this->wpdb->prefix}pt_package_categories c ON p.category_id = c.id
             LEFT JOIN {$this->wpdb->prefix}pt_locations l ON p.location_id = l.id
             WHERE p.id = %d AND p.is_active = 1",
            $id
        );
        
        $package = $this->wpdb->get_row($query);
        return $this->format_record($package);
    }
    
    /**
     * Get popular packages based on bookings
     */
    public function get_popular($limit = 6) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} 
             WHERE is_active = 1 
             ORDER BY current_bookings DESC, rating DESC 
             LIMIT %d",
            $limit
        );
        
        $results = $this->wpdb->get_results($query);
        
        foreach ($results as $result) {
            $result = $this->format_record($result);
        }
        
        return $results;
    }
    
    /**
     * Update booking count
     */
    public function update_booking_count($package_id, $increment = 1) {
        $query = $this->wpdb->prepare(
            "UPDATE {$this->table_name} 
             SET current_bookings = current_bookings + %d,
                 updated_at = %s
             WHERE id = %d",
            $increment,
            current_time('mysql'),
            $package_id
        );
        
        return $this->wpdb->query($query);
    }
    
    /**
     * Calculate price based on persons and dates
     */
    public function calculate_price($package_id, $persons, $child_count = 0, $infant_count = 0) {
        $package = $this->get_by_id($package_id);
        if (!$package) {
            return false;
        }
        
        $total_price = 0;
        
        // Base price for adults
        if ($package->price_per_person > 0) {
            $total_price += $package->price_per_person * $persons;
        } else {
            $total_price += $package->base_price;
        }
        
        // Child price
        if ($child_count > 0 && $package->child_price > 0) {
            $total_price += $package->child_price * $child_count;
        }
        
        // Infant price
        if ($infant_count > 0 && $package->infant_price > 0) {
            $total_price += $package->infant_price * $infant_count;
        }
        
        return array(
            'total_price' => $total_price,
            'base_price' => $package->base_price,
            'price_per_person' => $package->price_per_person,
            'child_price' => $package->child_price,
            'infant_price' => $package->infant_price
        );
    }
    
    /**
     * Check package availability
     */
    public function check_availability($package_id, $date, $persons = 1) {
        $package = $this->get_by_id($package_id);
        if (!$package) {
            return false;
        }
        
        // Check if package is active
        if (!$package->is_active) {
            return false;
        }
        
        // Check date range if seasonal
        if ($package->availability_type === 'seasonal') {
            $check_date = strtotime($date);
            $start_date = strtotime($package->start_date);
            $end_date = strtotime($package->end_date);
            
            if ($check_date < $start_date || $check_date > $end_date) {
                return false;
            }
        }
        
        // Check day availability
        if ($package->available_days && $package->availability_type === 'custom') {
            $available_days = explode(',', $package->available_days);
            $day_of_week = date('N', strtotime($date)); // 1-7 (Monday-Sunday)
            
            if (!in_array($day_of_week, $available_days)) {
                return false;
            }
        }
        
        // Check maximum bookings per day
        if ($package->max_bookings_per_day > 0) {
            // Count bookings for this date (would need to check bookings table)
            $booking_count = 0; // This would be implemented when booking system is enhanced
            if ($booking_count + $persons > $package->max_bookings_per_day) {
                return false;
            }
        }
        
        // Check minimum and maximum persons
        $total_persons = $persons;
        if ($total_persons < $package->min_persons || $total_persons > $package->max_persons) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get package statistics
     */
    public function get_stats() {
        $total = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
        $active = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE is_active = 1");
        $featured = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE is_featured = 1 AND is_active = 1");
        $average_rating = $this->wpdb->get_var("SELECT AVG(rating) FROM {$this->table_name} WHERE is_active = 1");
        
        return array(
            'total_packages' => $total,
            'active_packages' => $active,
            'featured_packages' => $featured,
            'average_rating' => round($average_rating, 2)
        );
    }
}