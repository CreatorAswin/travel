<?php
/**
 * Base Manager Class
 * Provides common functionality for all dynamic management classes
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Base_Manager {
    
    protected $table_name;
    protected $primary_key;
    protected $wpdb;
    
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }
    
    /**
     * Get all records with optional filters
     */
    public function get_all($args = array()) {
        $defaults = array(
            'limit' => -1,
            'offset' => 0,
            'orderby' => $this->primary_key,
            'order' => 'DESC',
            'status' => '',
            'search' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where_clauses = array();
        $where_values = array();
        
        // Status filter
        if (!empty($args['status'])) {
            $where_clauses[] = "status = %s";
            $where_values[] = $args['status'];
        }
        
        // Search filter
        if (!empty($args['search'])) {
            $where_clauses[] = "(title LIKE %s OR description LIKE %s)";
            $search_term = '%' . $args['search'] . '%';
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
            "SELECT * FROM {$this->table_name} 
             {$where_sql} 
             ORDER BY {$args['orderby']} {$args['order']} 
             {$limit_sql}",
            $where_values
        );
        
        return $this->wpdb->get_results($query);
    }
    
    /**
     * Get single record by ID
     */
    public function get_by_id($id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = %d",
            $id
        );
        return $this->wpdb->get_row($query);
    }
    
    /**
     * Insert new record
     */
    public function create($data) {
        // Sanitize data
        $data = $this->sanitize_data($data);
        
        // Add timestamps
        $data['created_at'] = current_time('mysql');
        $data['updated_at'] = current_time('mysql');
        
        $result = $this->wpdb->insert($this->table_name, $data);
        
        if ($result !== false) {
            return $this->wpdb->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update existing record
     */
    public function update($id, $data) {
        // Sanitize data
        $data = $this->sanitize_data($data);
        
        // Add update timestamp
        $data['updated_at'] = current_time('mysql');
        
        $result = $this->wpdb->update(
            $this->table_name,
            $data,
            array($this->primary_key => $id)
        );
        
        return $result !== false;
    }
    
    /**
     * Delete record
     */
    public function delete($id) {
        $result = $this->wpdb->delete(
            $this->table_name,
            array($this->primary_key => $id)
        );
        
        return $result !== false;
    }
    
    /**
     * Get count with optional filters
     */
    public function get_count($args = array()) {
        $defaults = array(
            'status' => '',
            'search' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where_clauses = array();
        $where_values = array();
        
        // Status filter
        if (!empty($args['status'])) {
            $where_clauses[] = "status = %s";
            $where_values[] = $args['status'];
        }
        
        // Search filter
        if (!empty($args['search'])) {
            $where_clauses[] = "(title LIKE %s OR description LIKE %s)";
            $search_term = '%' . $args['search'] . '%';
            $where_values[] = $search_term;
            $where_values[] = $search_term;
        }
        
        $where_sql = '';
        if (!empty($where_clauses)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
        }
        
        $query = $this->wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table_name} {$where_sql}",
            $where_values
        );
        
        return $this->wpdb->get_var($query);
    }
    
    /**
     * Sanitize data before database operations
     */
    protected function sanitize_data($data) {
        $sanitized = array();
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = serialize($value);
            } elseif (is_numeric($value)) {
                $sanitized[$key] = $value;
            } else {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Generate unique slug
     */
    protected function generate_slug($title, $id = 0) {
        $slug = sanitize_title($title);
        $original_slug = $slug;
        $counter = 1;
        
        do {
            $query = $this->wpdb->prepare(
                "SELECT {$this->primary_key} FROM {$this->table_name} WHERE slug = %s AND {$this->primary_key} != %d",
                $slug,
                $id
            );
            $exists = $this->wpdb->get_var($query);
            
            if ($exists) {
                $slug = $original_slug . '-' . $counter;
                $counter++;
            }
        } while ($exists);
        
        return $slug;
    }
    
    /**
     * Format data for display
     */
    public function format_record($record) {
        if (!$record) {
            return $record;
        }
        
        // Convert serialized data back to arrays
        foreach ($record as $key => $value) {
            if (is_serialized($value)) {
                $record->$key = unserialize($value);
            }
        }
        
        return $record;
    }
}