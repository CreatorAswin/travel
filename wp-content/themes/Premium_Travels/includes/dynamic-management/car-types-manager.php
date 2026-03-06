<?php
/**
 * Car Types Manager
 * Handles all car type-related database operations
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once 'base-manager.php';

class PT_Car_Types_Manager extends PT_Base_Manager {
    
    protected $table_name;
    protected $primary_key = 'id';
    
    public function __construct() {
        parent::__construct();
        $this->table_name = $this->wpdb->prefix . 'pt_car_types';
    }
    
    /**
     * Get all car types with additional filters
     */
    public function get_all($args = array()) {
        $defaults = array(
            'limit' => -1,
            'offset' => 0,
            'orderby' => 'sort_order',
            'order' => 'ASC',
            'status' => 'active',
            'search' => '',
            'category' => '',
            'ac_type' => '',
            'fuel_type' => '',
            'transmission' => '',
            'min_capacity' => 0,
            'max_capacity' => 0,
            'is_featured' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where_clauses = array("is_active = 1");
        $where_values = array();
        
        // Status filter
        if (!empty($args['status']) && $args['status'] !== 'all') {
            $where_clauses[] = "is_active = %d";
            $where_values[] = ($args['status'] === 'active') ? 1 : 0;
        }
        
        // Category filter
        if (!empty($args['category'])) {
            $where_clauses[] = "category = %s";
            $where_values[] = $args['category'];
        }
        
        // AC type filter
        if (!empty($args['ac_type'])) {
            $where_clauses[] = "ac_type = %s";
            $where_values[] = $args['ac_type'];
        }
        
        // Fuel type filter
        if (!empty($args['fuel_type'])) {
            $where_clauses[] = "fuel_type = %s";
            $where_values[] = $args['fuel_type'];
        }
        
        // Transmission filter
        if (!empty($args['transmission'])) {
            $where_clauses[] = "transmission = %s";
            $where_values[] = $args['transmission'];
        }
        
        // Capacity filters
        if (!empty($args['min_capacity']) && $args['min_capacity'] > 0) {
            $where_clauses[] = "capacity >= %d";
            $where_values[] = $args['min_capacity'];
        }
        
        if (!empty($args['max_capacity']) && $args['max_capacity'] > 0) {
            $where_clauses[] = "capacity <= %d";
            $where_values[] = $args['max_capacity'];
        }
        
        // Featured filter
        if ($args['is_featured'] !== '') {
            $where_clauses[] = "is_featured = %d";
            $where_values[] = $args['is_featured'] ? 1 : 0;
        }
        
        // Search filter
        if (!empty($args['search'])) {
            $where_clauses[] = "(title LIKE %s OR description LIKE %s OR features LIKE %s)";
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
            "SELECT * FROM {$this->table_name} 
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
     * Create new car type
     */
    public function create($data) {
        // Generate slug
        if (!empty($data['title']) && empty($data['slug'])) {
            $data['slug'] = $this->generate_slug($data['title']);
        }
        
        // Handle array data serialization
        $array_fields = ['features', 'gallery_images'];
        foreach ($array_fields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = serialize($data[$field]);
            }
        }
        
        // Handle JSON data
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }
        
        // Set default values
        $data['sort_order'] = isset($data['sort_order']) ? $data['sort_order'] : 0;
        $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : 1;
        $data['is_featured'] = isset($data['is_featured']) ? $data['is_featured'] : 0;
        $data['total_trips'] = 0;
        $data['total_distance'] = 0;
        $data['rating'] = 0;
        $data['total_reviews'] = 0;
        $data['availability_status'] = isset($data['availability_status']) ? $data['availability_status'] : 'available';
        
        return parent::create($data);
    }
    
    /**
     * Update existing car type
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
        $array_fields = ['features', 'gallery_images'];
        foreach ($array_fields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = serialize($data[$field]);
            }
        }
        
        // Handle JSON data
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }
        
        return parent::update($id, $data);
    }
    
    /**
     * Get featured car types
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
     * Get car types by category
     */
    public function get_by_category($category, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'category' => $category,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get available car types
     */
    public function get_available($limit = -1) {
        $args = array(
            'limit' => $limit,
            'status' => 'active',
            'availability_status' => 'available'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get car types with pricing information
     */
    public function get_with_pricing($distance = 100, $hours = 0, $is_night = false) {
        $car_types = $this->get_available();
        
        foreach ($car_types as $car) {
            $car->calculated_price = $this->calculate_price(
                $car->id, 
                $distance, 
                $hours, 
                $is_night
            );
        }
        
        return $car_types;
    }
    
    /**
     * Calculate total price for a trip
     */
    public function calculate_price($car_type_id, $distance, $waiting_hours = 0, $is_night = false) {
        $car_type = $this->get_by_id($car_type_id);
        if (!$car_type) {
            return false;
        }
        
        $total_price = 0;
        $breakdown = array();
        
        // Base price calculation
        if ($car_type->base_price_per_km > 0) {
            $base_cost = $car_type->base_price_per_km * $distance;
            $total_price += $base_cost;
            $breakdown['base_cost'] = $base_cost;
        }
        
        // Extra kilometers (if applicable - for minimum charge scenarios)
        $breakdown['extra_km_cost'] = 0;
        
        // Waiting charges
        if ($waiting_hours > 0 && $car_type->waiting_charge_per_hour > 0) {
            $waiting_cost = $car_type->waiting_charge_per_hour * $waiting_hours;
            $total_price += $waiting_cost;
            $breakdown['waiting_cost'] = $waiting_cost;
        }
        
        // Night charges
        if ($is_night && $car_type->night_charge_multiplier > 1) {
            $night_surcharge = $total_price * ($car_type->night_charge_multiplier - 1);
            $total_price += $night_surcharge;
            $breakdown['night_surcharge'] = $night_surcharge;
        }
        
        // Driver allowance
        $days = ceil($waiting_hours / 24);
        if ($days > 0 && $car_type->driver_allowance_per_day > 0) {
            $driver_allowance = $car_type->driver_allowance_per_day * $days;
            $total_price += $driver_allowance;
            $breakdown['driver_allowance'] = $driver_allowance;
        }
        
        $breakdown['total'] = $total_price;
        
        return $breakdown;
    }
    
    /**
     * Update car availability status
     */
    public function update_availability($car_type_id, $status) {
        $valid_statuses = ['available', 'maintenance', 'booked'];
        if (!in_array($status, $valid_statuses)) {
            return false;
        }
        
        $result = $this->wpdb->update(
            $this->table_name,
            array(
                'availability_status' => $status,
                'updated_at' => current_time('mysql')
            ),
            array('id' => $car_type_id)
        );
        
        return $result !== false;
    }
    
    /**
     * Update trip statistics
     */
    public function update_trip_stats($car_type_id, $distance = 0, $increment_trips = 1) {
        $query = $this->wpdb->prepare(
            "UPDATE {$this->table_name} 
             SET total_trips = total_trips + %d,
                 total_distance = total_distance + %f,
                 updated_at = %s
             WHERE id = %d",
            $increment_trips,
            $distance,
            current_time('mysql'),
            $car_type_id
        );
        
        return $this->wpdb->query($query);
    }
    
    /**
     * Get car type categories
     */
    public function get_categories() {
        $query = "SELECT DISTINCT category FROM {$this->table_name} WHERE is_active = 1 ORDER BY category";
        return $this->wpdb->get_col($query);
    }
    
    /**
     * Get car types by capacity range
     */
    public function get_by_capacity($min_capacity, $max_capacity, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'min_capacity' => $min_capacity,
            'max_capacity' => $max_capacity,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get similar car types
     */
    public function get_similar($car_type_id, $category = '', $limit = 4) {
        $car_type = $this->get_by_id($car_type_id);
        if (!$car_type) {
            return array();
        }
        
        $args = array(
            'limit' => $limit,
            'category' => $category ? $category : $car_type->category,
            'status' => 'active'
        );
        
        $car_types = $this->get_all($args);
        
        // Remove the current car type from results
        $car_types = array_filter($car_types, function($c) use ($car_type_id) {
            return $c->id != $car_type_id;
        });
        
        return array_slice($car_types, 0, $limit);
    }
    
    /**
     * Check if car type is available
     */
    public function is_available($car_type_id) {
        $car_type = $this->get_by_id($car_type_id);
        return $car_type && $car_type->is_active && $car_type->availability_status === 'available';
    }
    
    /**
     * Get car type statistics
     */
    public function get_stats() {
        $total = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
        $active = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE is_active = 1");
        $available = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE availability_status = 'available' AND is_active = 1");
        $featured = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE is_featured = 1 AND is_active = 1");
        $average_rating = $this->wpdb->get_var("SELECT AVG(rating) FROM {$this->table_name} WHERE is_active = 1");
        $total_trips = $this->wpdb->get_var("SELECT SUM(total_trips) FROM {$this->table_name} WHERE is_active = 1");
        $total_distance = $this->wpdb->get_var("SELECT SUM(total_distance) FROM {$this->table_name} WHERE is_active = 1");
        
        return array(
            'total_car_types' => $total,
            'active_car_types' => $active,
            'available_car_types' => $available,
            'featured_car_types' => $featured,
            'average_rating' => round($average_rating, 2),
            'total_trips' => (int)$total_trips,
            'total_distance' => round($total_distance, 2)
        );
    }
    
    /**
     * Get car types with filters for booking form
     */
    public function get_for_booking($passengers = 1, $ac_required = true, $category = '') {
        $args = array(
            'min_capacity' => $passengers,
            'status' => 'active',
            'availability_status' => 'available'
        );
        
        if ($ac_required) {
            $args['ac_type'] = 'AC';
        }
        
        if (!empty($category)) {
            $args['category'] = $category;
        }
        
        return $this->get_all($args);
    }
}