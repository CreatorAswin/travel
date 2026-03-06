<?php
/**
 * Bookings Manager
 * Handles all booking-related database operations
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once 'base-manager.php';

class PT_Bookings_Manager extends PT_Base_Manager {
    
    protected $table_name;
    protected $primary_key = 'id';
    
    public function __construct() {
        parent::__construct();
        $this->table_name = $this->wpdb->prefix . 'pt_bookings';
    }
    
    /**
     * Get all bookings with additional filters
     */
    public function get_all($args = array()) {
        $defaults = array(
            'limit' => -1,
            'offset' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC',
            'status' => '',
            'payment_status' => '',
            'service_type' => '',
            'search' => '',
            'date_from' => '',
            'date_to' => '',
            'customer_email' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where_clauses = array();
        $where_values = array();
        
        // Status filter
        if (!empty($args['status'])) {
            $where_clauses[] = "b.status = %s";
            $where_values[] = $args['status'];
        }
        
        // Payment status filter
        if (!empty($args['payment_status'])) {
            $where_clauses[] = "b.payment_status = %s";
            $where_values[] = $args['payment_status'];
        }
        
        // Service type filter
        if (!empty($args['service_type'])) {
            $where_clauses[] = "b.service_type = %s";
            $where_values[] = $args['service_type'];
        }
        
        // Date range filter
        if (!empty($args['date_from'])) {
            $where_clauses[] = "b.pickup_date >= %s";
            $where_values[] = $args['date_from'];
        }
        
        if (!empty($args['date_to'])) {
            $where_clauses[] = "b.pickup_date <= %s";
            $where_values[] = $args['date_to'];
        }
        
        // Customer email filter
        if (!empty($args['customer_email'])) {
            $where_clauses[] = "b.customer_email = %s";
            $where_values[] = $args['customer_email'];
        }
        
        // Search filter
        if (!empty($args['search'])) {
            $where_clauses[] = "(b.booking_reference LIKE %s OR b.customer_name LIKE %s OR b.customer_email LIKE %s)";
            $search_term = '%' . $args['search'] . '%';
            $where_values[] = $search_term;
            $where_values[] = $search_term;
            $where_values[] = $search_term;
        }
        
        $where_sql = '';
        if (!empty($where_clauses)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
        }
        
        $limit_sql = '';
        if ($args['limit'] > 0) {
            $limit_sql = $this->wpdb->prepare('LIMIT %d OFFSET %d', $args['limit'], $args['offset']);
        }
        
        $query = $this->wpdb->prepare(
            "SELECT b.*, 
                    p.title as package_title,
                    c.title as car_type_title,
                    r.title as route_title
             FROM {$this->table_name} b
             LEFT JOIN {$this->wpdb->prefix}pt_packages p ON b.package_id = p.id
             LEFT JOIN {$this->wpdb->prefix}pt_car_types c ON b.car_type_id = c.id
             LEFT JOIN {$this->wpdb->prefix}pt_routes r ON b.route_id = r.id
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
     * Create new booking
     */
    public function create($data) {
        // Generate unique booking reference
        if (empty($data['booking_reference'])) {
            $data['booking_reference'] = $this->generate_booking_reference();
        }
        
        // Set default values
        $data['status'] = isset($data['status']) ? $data['status'] : 'pending';
        $data['payment_status'] = isset($data['payment_status']) ? $data['payment_status'] : 'pending';
        
        $result = parent::create($data);
        
        if ($result && isset($data['package_id']) && $data['package_id'] > 0) {
            // Update package booking count
            $this->update_package_booking_count($data['package_id'], 1);
        }
        
        if ($result && isset($data['car_type_id']) && $data['car_type_id'] > 0) {
            // Update car type trip stats
            $distance = isset($data['total_distance']) ? $data['total_distance'] : 0;
            $this->update_car_type_stats($data['car_type_id'], $distance, 1);
        }
        
        return $result;
    }
    
    /**
     * Update existing booking
     */
    public function update($id, $data) {
        $result = parent::update($id, $data);
        
        if ($result) {
            // Handle status changes
            if (isset($data['status'])) {
                $this->handle_status_change($id, $data['status']);
            }
        }
        
        return $result;
    }
    
    /**
     * Generate unique booking reference
     */
    private function generate_booking_reference() {
        $prefix = 'PTB';
        $date = date('ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        $reference = $prefix . $date . $random;
        
        // Check if reference exists
        $exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT id FROM {$this->table_name} WHERE booking_reference = %s",
                $reference
            )
        );
        
        if ($exists) {
            return $this->generate_booking_reference(); // Recursively generate new one
        }
        
        return $reference;
    }
    
    /**
     * Update package booking count
     */
    private function update_package_booking_count($package_id, $increment) {
        $packages_manager = new PT_Packages_Manager();
        $packages_manager->update_booking_count($package_id, $increment);
    }
    
    /**
     * Update car type statistics
     */
    private function update_car_type_stats($car_type_id, $distance, $increment) {
        $car_types_manager = new PT_Car_Types_Manager();
        $car_types_manager->update_trip_stats($car_type_id, $distance, $increment);
    }
    
    /**
     * Handle status changes
     */
    private function handle_status_change($booking_id, $new_status) {
        // Add any status change logic here
        // For example: send notifications, update related records, etc.
    }
    
    /**
     * Get booking by reference
     */
    public function get_by_reference($reference) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE booking_reference = %s",
            $reference
        );
        return $this->wpdb->get_row($query);
    }
    
    /**
     * Get customer bookings
     */
    public function get_customer_bookings($customer_email, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'customer_email' => $customer_email,
            'orderby' => 'created_at',
            'order' => 'DESC'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get upcoming bookings
     */
    public function get_upcoming($limit = 10) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} 
             WHERE pickup_date >= CURDATE() 
             AND status IN ('confirmed', 'pending')
             ORDER BY pickup_date ASC, pickup_time ASC
             LIMIT %d",
            $limit
        );
        
        return $this->wpdb->get_results($query);
    }
    
    /**
     * Get booking statistics
     */
    public function get_stats() {
        $total = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
        $pending = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE status = 'pending'");
        $confirmed = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE status = 'confirmed'");
        $completed = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE status = 'completed'");
        $cancelled = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE status = 'cancelled'");
        $total_revenue = $this->wpdb->get_var("SELECT SUM(total_price) FROM {$this->table_name} WHERE status = 'completed'");
        
        return array(
            'total_bookings' => $total,
            'pending_bookings' => $pending,
            'confirmed_bookings' => $confirmed,
            'completed_bookings' => $completed,
            'cancelled_bookings' => $cancelled,
            'total_revenue' => $total_revenue ? round($total_revenue, 2) : 0
        );
    }
    
    /**
     * Get bookings by date range
     */
    public function get_by_date_range($start_date, $end_date) {
        $args = array(
            'date_from' => $start_date,
            'date_to' => $end_date
        );
        return $this->get_all($args);
    }
    
    /**
     * Cancel booking
     */
    public function cancel($booking_id, $reason = '') {
        $data = array(
            'status' => 'cancelled',
            'updated_at' => current_time('mysql')
        );
        
        if (!empty($reason)) {
            $data['cancellation_reason'] = $reason;
        }
        
        return $this->update($booking_id, $data);
    }
    
    /**
     * Confirm booking
     */
    public function confirm($booking_id) {
        return $this->update($booking_id, array(
            'status' => 'confirmed',
            'updated_at' => current_time('mysql')
        ));
    }
    
    /**
     * Complete booking
     */
    public function complete($booking_id) {
        return $this->update($booking_id, array(
            'status' => 'completed',
            'updated_at' => current_time('mysql')
        ));
    }
}