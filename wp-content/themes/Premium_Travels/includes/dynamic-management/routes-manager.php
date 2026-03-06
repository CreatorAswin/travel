<?php
/**
 * Routes Manager
 * Handles all route-related database operations
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once 'base-manager.php';

class PT_Routes_Manager extends PT_Base_Manager {
    
    protected $table_name;
    protected $primary_key = 'id';
    
    public function __construct() {
        parent::__construct();
        $this->table_name = $this->wpdb->prefix . 'pt_routes';
    }
    
    /**
     * Get all routes with additional filters
     */
    public function get_all($args = array()) {
        $defaults = array(
            'limit' => -1,
            'offset' => 0,
            'orderby' => 'sort_order',
            'order' => 'ASC',
            'status' => 'active',
            'search' => '',
            'from_location_id' => 0,
            'to_location_id' => 0,
            'route_type' => '',
            'is_popular' => '',
            'min_distance' => 0,
            'max_distance' => 0
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where_clauses = array("r.is_active = 1");
        $where_values = array();
        
        // Status filter
        if (!empty($args['status']) && $args['status'] !== 'all') {
            $where_clauses[] = "r.is_active = %d";
            $where_values[] = ($args['status'] === 'active') ? 1 : 0;
        }
        
        // From location filter
        if (!empty($args['from_location_id'])) {
            $where_clauses[] = "r.from_location_id = %d";
            $where_values[] = $args['from_location_id'];
        }
        
        // To location filter
        if (!empty($args['to_location_id'])) {
            $where_clauses[] = "r.to_location_id = %d";
            $where_values[] = $args['to_location_id'];
        }
        
        // Route type filter
        if (!empty($args['route_type'])) {
            $where_clauses[] = "r.route_type = %s";
            $where_values[] = $args['route_type'];
        }
        
        // Popular filter
        if ($args['is_popular'] !== '') {
            $where_clauses[] = "r.is_popular = %d";
            $where_values[] = $args['is_popular'] ? 1 : 0;
        }
        
        // Distance filters
        if (!empty($args['min_distance']) && $args['min_distance'] > 0) {
            $where_clauses[] = "r.distance_km >= %f";
            $where_values[] = $args['min_distance'];
        }
        
        if (!empty($args['max_distance']) && $args['max_distance'] > 0) {
            $where_clauses[] = "r.distance_km <= %f";
            $where_values[] = $args['max_distance'];
        }
        
        // Search filter
        if (!empty($args['search'])) {
            $where_clauses[] = "(r.title LIKE %s OR from_l.title LIKE %s OR to_l.title LIKE %s)";
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
            "SELECT r.*, 
                    from_l.title as from_location_name,
                    from_l.state as from_state,
                    from_l.coordinates as from_coordinates,
                    to_l.title as to_location_name,
                    to_l.state as to_state,
                    to_l.coordinates as to_coordinates
             FROM {$this->table_name} r
             LEFT JOIN {$this->wpdb->prefix}pt_locations from_l ON r.from_location_id = from_l.id
             LEFT JOIN {$this->wpdb->prefix}pt_locations to_l ON r.to_location_id = to_l.id
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
     * Create new route
     */
    public function create($data) {
        // Validate that from and to locations are different
        if (isset($data['from_location_id']) && isset($data['to_location_id']) && 
            $data['from_location_id'] == $data['to_location_id']) {
            return false; // Cannot have same from and to location
        }
        
        // Handle array data serialization
        $array_fields = ['via_points', 'map_coordinates'];
        foreach ($array_fields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field]);
            }
        }
        
        // Set default values
        $data['sort_order'] = isset($data['sort_order']) ? $data['sort_order'] : 0;
        $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : 1;
        $data['is_popular'] = isset($data['is_popular']) ? $data['is_popular'] : 0;
        $data['route_type'] = isset($data['route_type']) ? $data['route_type'] : 'one-way';
        
        // Set default multipliers
        $data['night_charge_multiplier'] = isset($data['night_charge_multiplier']) ? $data['night_charge_multiplier'] : 1.00;
        $data['peak_hour_multiplier'] = isset($data['peak_hour_multiplier']) ? $data['peak_hour_multiplier'] : 1.00;
        
        return parent::create($data);
    }
    
    /**
     * Update existing route
     */
    public function update($id, $data) {
        // Validate that from and to locations are different
        if (isset($data['from_location_id']) && isset($data['to_location_id']) && 
            $data['from_location_id'] == $data['to_location_id']) {
            return false;
        }
        
        // Handle array data serialization
        $array_fields = ['via_points', 'map_coordinates'];
        foreach ($array_fields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field]);
            }
        }
        
        return parent::update($id, $data);
    }
    
    /**
     * Get route by locations
     */
    public function get_by_locations($from_location_id, $to_location_id) {
        $query = $this->wpdb->prepare(
            "SELECT r.*, 
                    from_l.title as from_location_name,
                    to_l.title as to_location_name
             FROM {$this->table_name} r
             LEFT JOIN {$this->wpdb->prefix}pt_locations from_l ON r.from_location_id = from_l.id
             LEFT JOIN {$this->wpdb->prefix}pt_locations to_l ON r.to_location_id = to_l.id
             WHERE r.from_location_id = %d 
             AND r.to_location_id = %d 
             AND r.is_active = 1",
            $from_location_id,
            $to_location_id
        );
        
        $route = $this->wpdb->get_row($query);
        return $this->format_record($route);
    }
    
    /**
     * Get popular routes
     */
    public function get_popular($limit = 10) {
        $args = array(
            'limit' => $limit,
            'is_popular' => true,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get routes from specific location
     */
    public function get_from_location($from_location_id, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'from_location_id' => $from_location_id,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get routes to specific location
     */
    public function get_to_location($to_location_id, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'to_location_id' => $to_location_id,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get routes by type
     */
    public function get_by_type($route_type, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'route_type' => $route_type,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Calculate route price
     */
    public function calculate_price($route_id, $car_type_id = 0, $is_night = false, $is_peak_hour = false) {
        $route = $this->get_by_id($route_id);
        if (!$route) {
            return false;
        }
        
        $total_price = 0;
        $breakdown = array();
        
        // Base price calculation
        if ($route->base_price > 0) {
            $base_cost = $route->base_price;
            $total_price += $base_cost;
            $breakdown['base_cost'] = $base_cost;
        } elseif ($route->price_per_km > 0 && $route->distance_km > 0) {
            $distance_cost = $route->price_per_km * $route->distance_km;
            $total_price += $distance_cost;
            $breakdown['distance_cost'] = $distance_cost;
        }
        
        // Add toll charges
        if ($route->toll_charges > 0) {
            $total_price += $route->toll_charges;
            $breakdown['toll_charges'] = $route->toll_charges;
        }
        
        // Add parking charges
        if ($route->parking_charges > 0) {
            $total_price += $route->parking_charges;
            $breakdown['parking_charges'] = $route->parking_charges;
        }
        
        // Night charges
        if ($is_night && $route->night_charge_multiplier > 1) {
            $night_surcharge = $total_price * ($route->night_charge_multiplier - 1);
            $total_price += $night_surcharge;
            $breakdown['night_surcharge'] = $night_surcharge;
        }
        
        // Peak hour charges
        if ($is_peak_hour && $route->peak_hour_multiplier > 1) {
            $peak_surcharge = $total_price * ($route->peak_hour_multiplier - 1);
            $total_price += $peak_surcharge;
            $breakdown['peak_hour_surcharge'] = $peak_surcharge;
        }
        
        // If car type is provided, calculate car-specific pricing
        if ($car_type_id > 0) {
            // This would integrate with car types manager for more precise pricing
            // For now, we'll use the route pricing as base
        }
        
        $breakdown['total'] = $total_price;
        
        return $breakdown;
    }
    
    /**
     * Get route suggestions (autocomplete)
     */
    public function get_suggestions($search_term, $limit = 10) {
        $query = $this->wpdb->prepare(
            "SELECT r.id, r.title, 
                    from_l.title as from_location,
                    to_l.title as to_location,
                    r.distance_km
             FROM {$this->table_name} r
             LEFT JOIN {$this->wpdb->prefix}pt_locations from_l ON r.from_location_id = from_l.id
             LEFT JOIN {$this->wpdb->prefix}pt_locations to_l ON r.to_location_id = to_l.id
             WHERE (r.title LIKE %s OR from_l.title LIKE %s OR to_l.title LIKE %s)
             AND r.is_active = 1
             ORDER BY r.is_popular DESC, r.sort_order ASC
             LIMIT %d",
            '%' . $search_term . '%',
            '%' . $search_term . '%',
            '%' . $search_term . '%',
            $limit
        );
        
        return $this->wpdb->get_results($query);
    }
    
    /**
     * Get frequently traveled routes
     */
    public function get_frequent_routes($limit = 10) {
        // This would typically join with bookings table to get actual frequency data
        // For now, we'll return popular routes
        return $this->get_popular($limit);
    }
    
    /**
     * Get route distance
     */
    public function get_distance($route_id) {
        $route = $this->get_by_id($route_id);
        return $route ? $route->distance_km : 0;
    }
    
    /**
     * Get estimated travel time
     */
    public function get_estimated_time($route_id) {
        $route = $this->get_by_id($route_id);
        return $route ? $route->estimated_time : '';
    }
    
    /**
     * Check if route exists between locations
     */
    public function route_exists($from_location_id, $to_location_id) {
        $query = $this->wpdb->prepare(
            "SELECT id FROM {$this->table_name} 
             WHERE from_location_id = %d AND to_location_id = %d AND is_active = 1",
            $from_location_id,
            $to_location_id
        );
        
        return $this->wpdb->get_var($query) ? true : false;
    }
    
    /**
     * Get reverse route (to_location to from_location)
     */
    public function get_reverse_route($route_id) {
        $route = $this->get_by_id($route_id);
        if (!$route) {
            return false;
        }
        
        return $this->get_by_locations($route->to_location_id, $route->from_location_id);
    }
    
    /**
     * Get nearby routes
     */
    public function get_nearby_routes($location_id, $distance_km = 50, $limit = 10) {
        $query = $this->wpdb->prepare(
            "SELECT r.*, 
                    from_l.title as from_location_name,
                    to_l.title as to_location_name,
                    from_l.coordinates as from_coordinates,
                    to_l.coordinates as to_coordinates
             FROM {$this->table_name} r
             LEFT JOIN {$this->wpdb->prefix}pt_locations from_l ON r.from_location_id = from_l.id
             LEFT JOIN {$this->wpdb->prefix}pt_locations to_l ON r.to_location_id = to_l.id
             WHERE (r.from_location_id = %d OR r.to_location_id = %d)
             AND r.distance_km <= %f
             AND r.is_active = 1
             ORDER BY r.distance_km ASC
             LIMIT %d",
            $location_id,
            $location_id,
            $distance_km,
            $limit
        );
        
        $results = $this->wpdb->get_results($query);
        
        foreach ($results as $result) {
            $result = $this->format_record($result);
        }
        
        return $results;
    }
    
    /**
     * Update route popularity
     */
    public function update_popularity($route_id, $increment = 1) {
        $query = $this->wpdb->prepare(
            "UPDATE {$this->table_name} 
             SET is_popular = CASE 
                 WHEN (SELECT COUNT(*) FROM {$this->wpdb->prefix}pt_bookings b 
                       WHERE b.route_id = %d AND b.status IN ('confirmed', 'completed')) >= 10 
                 THEN 1 ELSE 0 END,
                 updated_at = %s
             WHERE id = %d",
            $route_id,
            current_time('mysql'),
            $route_id
        );
        
        return $this->wpdb->query($query);
    }
    
    /**
     * Get route statistics
     */
    public function get_stats() {
        $total = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
        $active = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE is_active = 1");
        $popular = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE is_popular = 1 AND is_active = 1");
        $one_way = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE route_type = 'one-way' AND is_active = 1");
        $round_trip = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE route_type = 'round-trip' AND is_active = 1");
        $average_distance = $this->wpdb->get_var("SELECT AVG(distance_km) FROM {$this->table_name} WHERE is_active = 1");
        
        return array(
            'total_routes' => $total,
            'active_routes' => $active,
            'popular_routes' => $popular,
            'one_way_routes' => $one_way,
            'round_trip_routes' => $round_trip,
            'average_distance' => round($average_distance, 2)
        );
    }
}