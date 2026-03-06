<?php
/**
 * Products/Tours Manager
 * Handles all product and tour-related database operations
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once 'base-manager.php';

class PT_Products_Manager extends PT_Base_Manager {
    
    protected $table_name;
    protected $primary_key = 'id';
    
    public function __construct() {
        parent::__construct();
        $this->table_name = $this->wpdb->prefix . 'pt_products';
    }
    
    /**
     * Get all products with additional filters
     */
    public function get_all($args = array()) {
        $defaults = array(
            'limit' => -1,
            'offset' => 0,
            'orderby' => 'sort_order',
            'order' => 'ASC',
            'status' => 'active',
            'search' => '',
            'product_type' => '',
            'location_id' => 0,
            'is_featured' => '',
            'min_price' => 0,
            'max_price' => 0,
            'availability' => 'all' // all, available, unavailable
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where_clauses = array("p.is_active = 1");
        $where_values = array();
        
        // Status filter
        if (!empty($args['status']) && $args['status'] !== 'all') {
            $where_clauses[] = "p.is_active = %d";
            $where_values[] = ($args['status'] === 'active') ? 1 : 0;
        }
        
        // Product type filter
        if (!empty($args['product_type'])) {
            $where_clauses[] = "p.product_type = %s";
            $where_values[] = $args['product_type'];
        }
        
        // Location filter
        if (!empty($args['location_id'])) {
            $where_clauses[] = "p.location_id = %d";
            $where_values[] = $args['location_id'];
        }
        
        // Featured filter
        if ($args['is_featured'] !== '') {
            $where_clauses[] = "p.is_featured = %d";
            $where_values[] = $args['is_featured'] ? 1 : 0;
        }
        
        // Price range filter
        if (!empty($args['min_price']) && $args['min_price'] > 0) {
            $where_clauses[] = "p.price_per_person >= %f";
            $where_values[] = $args['min_price'];
        }
        
        if (!empty($args['max_price']) && $args['max_price'] > 0) {
            $where_clauses[] = "p.price_per_person <= %f";
            $where_values[] = $args['max_price'];
        }
        
        // Availability filter
        if ($args['availability'] === 'available') {
            $where_clauses[] = "(p.availability_type = 'always' OR 
                                (p.availability_type = 'custom' AND 
                                 FIND_IN_SET(DAYOFWEEK(CURDATE()), p.available_days)))";
        } elseif ($args['availability'] === 'unavailable') {
            $where_clauses[] = "(p.availability_type = 'custom' AND 
                                NOT FIND_IN_SET(DAYOFWEEK(CURDATE()), p.available_days))";
        }
        
        // Search filter
        if (!empty($args['search'])) {
            $where_clauses[] = "(p.title LIKE %s OR p.description LIKE %s OR p.highlights LIKE %s)";
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
                    l.title as location_name,
                    l.description as location_description
             FROM {$this->table_name} p
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
     * Create new product
     */
    public function create($data) {
        global $wpdb;
        
        $defaults = array(
            'title' => '',
            'slug' => '',
            'description' => '',
            'product_type' => 'physical_product',
            'location_id' => null,
            'price_regular' => 0.00,
            'price_sale' => null,
            'sale_start_date' => null,
            'sale_end_date' => null,
            'discount_percentage' => null,
            'currency' => 'INR',
            'sku' => '',
            'stock_quantity' => 0,
            'stock_status' => 'instock',
            'weight' => null,
            'dimensions' => '',
            'shipping_class' => '',
            'shipping_required' => true,
            'tax_status' => 'taxable',
            'tax_class' => '',
            'virtual' => false,
            'downloadable' => false,
            'download_limit' => 0,
            'download_expiry' => 0,
            'purchase_note' => '',
            'featured' => false,
            'catalog_visibility' => 'visible',
            'featured_image' => '',
            'gallery_images' => '',
            'short_description' => '',
            'meta_description' => '',
            'tags' => '',
            'categories' => '',
            'related_products' => '',
            'upsell_ids' => '',
            'cross_sell_ids' => '',
            'shipping_width' => null,
            'shipping_height' => null,
            'shipping_length' => null,
            'shipping_weight' => null,
            'shipping_from_location' => '',
            'shipping_to_locations' => '',
            'min_purchase_quantity' => 1,
            'max_purchase_quantity' => 999,
            'sold_individually' => false,
            'allow_backorders' => 'no',
            'manage_stock' => false,
            'low_stock_threshold' => 5,
            'sold_dates' => '',
            'popularity_score' => 0.00,
            'trending_score' => 0.00,
            'seo_title' => '',
            'seo_description' => '',
            'meta_keywords' => '',
            'suggested_packages' => '',
            'is_available' => true,
            'sort_order' => 0
        );
        
        $data = wp_parse_args($data, $defaults);
        
        // Validate required fields
        if (empty($data['title'])) {
            throw new Exception('Title is required');
        }
        
        // Sanitize and validate data
        $data['title'] = sanitize_text_field($data['title']);
        $data['slug'] = !empty($data['slug']) ? sanitize_title($data['slug']) : sanitize_title($data['title']);
        $data['product_type'] = sanitize_text_field($data['product_type']);
        $data['location_id'] = $data['location_id'] ? absint($data['location_id']) : null;
        $data['price_regular'] = floatval($data['price_regular']);
        $data['price_sale'] = $data['price_sale'] ? floatval($data['price_sale']) : null;
        $data['sale_start_date'] = $data['sale_start_date'] ? sanitize_text_field($data['sale_start_date']) : null;
        $data['sale_end_date'] = $data['sale_end_date'] ? sanitize_text_field($data['sale_end_date']) : null;
        $data['discount_percentage'] = $data['discount_percentage'] ? floatval($data['discount_percentage']) : null;
        $data['currency'] = sanitize_text_field($data['currency']);
        $data['sku'] = sanitize_text_field($data['sku']);
        $data['stock_quantity'] = absint($data['stock_quantity']);
        $data['stock_status'] = sanitize_text_field($data['stock_status']);
        $data['weight'] = $data['weight'] ? floatval($data['weight']) : null;
        $data['dimensions'] = sanitize_text_field($data['dimensions']);
        $data['shipping_class'] = sanitize_text_field($data['shipping_class']);
        $data['shipping_required'] = (bool)$data['shipping_required'];
        $data['tax_status'] = sanitize_text_field($data['tax_status']);
        $data['tax_class'] = sanitize_text_field($data['tax_class']);
        $data['virtual'] = (bool)$data['virtual'];
        $data['downloadable'] = (bool)$data['downloadable'];
        $data['download_limit'] = absint($data['download_limit']);
        $data['download_expiry'] = absint($data['download_expiry']);
        $data['purchase_note'] = sanitize_textarea_field($data['purchase_note']);
        $data['featured'] = (bool)$data['featured'];
        $data['catalog_visibility'] = sanitize_text_field($data['catalog_visibility']);
        $data['featured_image'] = esc_url_raw($data['featured_image']);
        $data['gallery_images'] = sanitize_textarea_field($data['gallery_images']);
        $data['short_description'] = sanitize_textarea_field($data['short_description']);
        $data['meta_description'] = sanitize_textarea_field($data['meta_description']);
        $data['tags'] = sanitize_textarea_field($data['tags']);
        $data['categories'] = sanitize_textarea_field($data['categories']);
        $data['related_products'] = sanitize_textarea_field($data['related_products']);
        $data['upsell_ids'] = sanitize_textarea_field($data['upsell_ids']);
        $data['cross_sell_ids'] = sanitize_textarea_field($data['cross_sell_ids']);
        $data['shipping_width'] = $data['shipping_width'] ? floatval($data['shipping_width']) : null;
        $data['shipping_height'] = $data['shipping_height'] ? floatval($data['shipping_height']) : null;
        $data['shipping_length'] = $data['shipping_length'] ? floatval($data['shipping_length']) : null;
        $data['shipping_weight'] = $data['shipping_weight'] ? floatval($data['shipping_weight']) : null;
        $data['shipping_from_location'] = sanitize_text_field($data['shipping_from_location']);
        $data['shipping_to_locations'] = sanitize_textarea_field($data['shipping_to_locations']);
        $data['min_purchase_quantity'] = absint($data['min_purchase_quantity']);
        $data['max_purchase_quantity'] = absint($data['max_purchase_quantity']);
        $data['sold_individually'] = (bool)$data['sold_individually'];
        $data['allow_backorders'] = sanitize_text_field($data['allow_backorders']);
        $data['manage_stock'] = (bool)$data['manage_stock'];
        $data['low_stock_threshold'] = absint($data['low_stock_threshold']);
        $data['sold_dates'] = sanitize_textarea_field($data['sold_dates']);
        $data['popularity_score'] = floatval($data['popularity_score']);
        $data['trending_score'] = floatval($data['trending_score']);
        $data['seo_title'] = sanitize_text_field($data['seo_title']);
        $data['seo_description'] = sanitize_textarea_field($data['seo_description']);
        $data['meta_keywords'] = sanitize_text_field($data['meta_keywords']);
        $data['is_available'] = (bool)$data['is_available'];
        $data['sort_order'] = absint($data['sort_order']);
        
        // Check if slug already exists
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_products WHERE slug = %s AND id != %d",
            $data['slug'],
            isset($data['id']) ? $data['id'] : 0
        ));
        
        if ($existing) {
            throw new Exception('Slug already exists');
        }
        
        // Calculate discount percentage if sale price is provided
        if ($data['price_sale'] && $data['price_regular'] > 0) {
            $data['discount_percentage'] = (($data['price_regular'] - $data['price_sale']) / $data['price_regular']) * 100;
        }
        
        // Insert the record
        $result = $wpdb->insert(
            $wpdb->prefix . 'pt_products',
            array(
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'product_type' => $data['product_type'],
                'location_id' => $data['location_id'],
                'price_regular' => $data['price_regular'],
                'price_sale' => $data['price_sale'],
                'sale_start_date' => $data['sale_start_date'],
                'sale_end_date' => $data['sale_end_date'],
                'discount_percentage' => $data['discount_percentage'],
                'currency' => $data['currency'],
                'sku' => $data['sku'],
                'stock_quantity' => $data['stock_quantity'],
                'stock_status' => $data['stock_status'],
                'weight' => $data['weight'],
                'dimensions' => $data['dimensions'],
                'shipping_class' => $data['shipping_class'],
                'shipping_required' => $data['shipping_required'],
                'tax_status' => $data['tax_status'],
                'tax_class' => $data['tax_class'],
                'virtual' => $data['virtual'],
                'downloadable' => $data['downloadable'],
                'download_limit' => $data['download_limit'],
                'download_expiry' => $data['download_expiry'],
                'purchase_note' => $data['purchase_note'],
                'featured' => $data['featured'],
                'catalog_visibility' => $data['catalog_visibility'],
                'featured_image' => $data['featured_image'],
                'gallery_images' => $data['gallery_images'],
                'short_description' => $data['short_description'],
                'meta_description' => $data['meta_description'],
                'tags' => $data['tags'],
                'categories' => $data['categories'],
                'related_products' => $data['related_products'],
                'upsell_ids' => $data['upsell_ids'],
                'cross_sell_ids' => $data['cross_sell_ids'],
                'shipping_width' => $data['shipping_width'],
                'shipping_height' => $data['shipping_height'],
                'shipping_length' => $data['shipping_length'],
                'shipping_weight' => $data['shipping_weight'],
                'shipping_from_location' => $data['shipping_from_location'],
                'shipping_to_locations' => $data['shipping_to_locations'],
                'min_purchase_quantity' => $data['min_purchase_quantity'],
                'max_purchase_quantity' => $data['max_purchase_quantity'],
                'sold_individually' => $data['sold_individually'],
                'allow_backorders' => $data['allow_backorders'],
                'manage_stock' => $data['manage_stock'],
                'low_stock_threshold' => $data['low_stock_threshold'],
                'sold_dates' => $data['sold_dates'],
                'popularity_score' => $data['popularity_score'],
                'trending_score' => $data['trending_score'],
                'seo_title' => $data['seo_title'],
                'seo_description' => $data['seo_description'],
                'meta_keywords' => $data['meta_keywords'],
                'suggested_packages' => $data['suggested_packages'],
                'is_available' => $data['is_available'],
                'sort_order' => $data['sort_order'],
            ),
            array(
                '%s', '%s', '%s', '%s', '%d',
                '%f', '%f', '%s', '%s', '%f',
                '%s', '%s', '%d', '%s', '%f',
                '%s', '%s', '%d', '%s', '%s',
                '%d', '%d', '%d', '%d', '%s',
                '%d', '%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', '%s',
                '%f', '%f', '%f', '%f', '%s',
                '%s', '%d', '%d', '%d', '%s',
                '%d', '%d', '%d', '%f', '%f',
                '%s', '%s', '%s', '%s', '%d'
            )
        );
        
        if ($result === false) {
            throw new Exception('Failed to create product');
        }
        
        return $wpdb->insert_id;
    }
    
    /**
     * Update existing product
     */
    public function update($id, $data) {
        global $wpdb;
        
        // Get existing product to merge with defaults
        $existing = $this->get_by_id($id);
        if (!$existing) {
            throw new Exception('Product not found');
        }
        
        $defaults = array(
            'title' => $existing->title,
            'slug' => $existing->slug,
            'description' => $existing->description,
            'product_type' => $existing->product_type,
            'location_id' => $existing->location_id,
            'price_regular' => $existing->price_regular,
            'price_sale' => $existing->price_sale,
            'sale_start_date' => $existing->sale_start_date,
            'sale_end_date' => $existing->sale_end_date,
            'discount_percentage' => $existing->discount_percentage,
            'currency' => $existing->currency,
            'sku' => $existing->sku,
            'stock_quantity' => $existing->stock_quantity,
            'stock_status' => $existing->stock_status,
            'weight' => $existing->weight,
            'dimensions' => $existing->dimensions,
            'shipping_class' => $existing->shipping_class,
            'shipping_required' => $existing->shipping_required,
            'tax_status' => $existing->tax_status,
            'tax_class' => $existing->tax_class,
            'virtual' => $existing->virtual,
            'downloadable' => $existing->downloadable,
            'download_limit' => $existing->download_limit,
            'download_expiry' => $existing->download_expiry,
            'purchase_note' => $existing->purchase_note,
            'featured' => $existing->featured,
            'catalog_visibility' => $existing->catalog_visibility,
            'featured_image' => $existing->featured_image,
            'gallery_images' => $existing->gallery_images,
            'short_description' => $existing->short_description,
            'meta_description' => $existing->meta_description,
            'tags' => $existing->tags,
            'categories' => $existing->categories,
            'related_products' => $existing->related_products,
            'upsell_ids' => $existing->upsell_ids,
            'cross_sell_ids' => $existing->cross_sell_ids,
            'shipping_width' => $existing->shipping_width,
            'shipping_height' => $existing->shipping_height,
            'shipping_length' => $existing->shipping_length,
            'shipping_weight' => $existing->shipping_weight,
            'shipping_from_location' => $existing->shipping_from_location,
            'shipping_to_locations' => $existing->shipping_to_locations,
            'min_purchase_quantity' => $existing->min_purchase_quantity,
            'max_purchase_quantity' => $existing->max_purchase_quantity,
            'sold_individually' => $existing->sold_individually,
            'allow_backorders' => $existing->allow_backorders,
            'manage_stock' => $existing->manage_stock,
            'low_stock_threshold' => $existing->low_stock_threshold,
            'sold_dates' => $existing->sold_dates,
            'popularity_score' => $existing->popularity_score,
            'trending_score' => $existing->trending_score,
            'seo_title' => $existing->seo_title,
            'seo_description' => $existing->seo_description,
            'meta_keywords' => $existing->meta_keywords,
            'suggested_packages' => $existing->suggested_packages,
            'is_available' => $existing->is_available,
            'sort_order' => $existing->sort_order
        );
        
        $data = wp_parse_args($data, $defaults);
        
        // Sanitize and validate data
        $data['title'] = sanitize_text_field($data['title']);
        $data['slug'] = !empty($data['slug']) ? sanitize_title($data['slug']) : sanitize_title($data['title']);
        $data['product_type'] = sanitize_text_field($data['product_type']);
        $data['location_id'] = $data['location_id'] ? absint($data['location_id']) : null;
        $data['price_regular'] = floatval($data['price_regular']);
        $data['price_sale'] = $data['price_sale'] ? floatval($data['price_sale']) : null;
        $data['sale_start_date'] = $data['sale_start_date'] ? sanitize_text_field($data['sale_start_date']) : null;
        $data['sale_end_date'] = $data['sale_end_date'] ? sanitize_text_field($data['sale_end_date']) : null;
        $data['discount_percentage'] = $data['discount_percentage'] ? floatval($data['discount_percentage']) : null;
        $data['currency'] = sanitize_text_field($data['currency']);
        $data['sku'] = sanitize_text_field($data['sku']);
        $data['stock_quantity'] = absint($data['stock_quantity']);
        $data['stock_status'] = sanitize_text_field($data['stock_status']);
        $data['weight'] = $data['weight'] ? floatval($data['weight']) : null;
        $data['dimensions'] = sanitize_text_field($data['dimensions']);
        $data['shipping_class'] = sanitize_text_field($data['shipping_class']);
        $data['shipping_required'] = (bool)$data['shipping_required'];
        $data['tax_status'] = sanitize_text_field($data['tax_status']);
        $data['tax_class'] = sanitize_text_field($data['tax_class']);
        $data['virtual'] = (bool)$data['virtual'];
        $data['downloadable'] = (bool)$data['downloadable'];
        $data['download_limit'] = absint($data['download_limit']);
        $data['download_expiry'] = absint($data['download_expiry']);
        $data['purchase_note'] = sanitize_textarea_field($data['purchase_note']);
        $data['featured'] = (bool)$data['featured'];
        $data['catalog_visibility'] = sanitize_text_field($data['catalog_visibility']);
        $data['featured_image'] = esc_url_raw($data['featured_image']);
        $data['gallery_images'] = sanitize_textarea_field($data['gallery_images']);
        $data['short_description'] = sanitize_textarea_field($data['short_description']);
        $data['meta_description'] = sanitize_textarea_field($data['meta_description']);
        $data['tags'] = sanitize_textarea_field($data['tags']);
        $data['categories'] = sanitize_textarea_field($data['categories']);
        $data['related_products'] = sanitize_textarea_field($data['related_products']);
        $data['upsell_ids'] = sanitize_textarea_field($data['upsell_ids']);
        $data['cross_sell_ids'] = sanitize_textarea_field($data['cross_sell_ids']);
        $data['shipping_width'] = $data['shipping_width'] ? floatval($data['shipping_width']) : null;
        $data['shipping_height'] = $data['shipping_height'] ? floatval($data['shipping_height']) : null;
        $data['shipping_length'] = $data['shipping_length'] ? floatval($data['shipping_length']) : null;
        $data['shipping_weight'] = $data['shipping_weight'] ? floatval($data['shipping_weight']) : null;
        $data['shipping_from_location'] = sanitize_text_field($data['shipping_from_location']);
        $data['shipping_to_locations'] = sanitize_textarea_field($data['shipping_to_locations']);
        $data['min_purchase_quantity'] = absint($data['min_purchase_quantity']);
        $data['max_purchase_quantity'] = absint($data['max_purchase_quantity']);
        $data['sold_individually'] = (bool)$data['sold_individually'];
        $data['allow_backorders'] = sanitize_text_field($data['allow_backorders']);
        $data['manage_stock'] = (bool)$data['manage_stock'];
        $data['low_stock_threshold'] = absint($data['low_stock_threshold']);
        $data['sold_dates'] = sanitize_textarea_field($data['sold_dates']);
        $data['popularity_score'] = floatval($data['popularity_score']);
        $data['trending_score'] = floatval($data['trending_score']);
        $data['seo_title'] = sanitize_text_field($data['seo_title']);
        $data['seo_description'] = sanitize_textarea_field($data['seo_description']);
        $data['meta_keywords'] = sanitize_text_field($data['meta_keywords']);
        $data['suggested_packages'] = sanitize_textarea_field($data['suggested_packages']);
        $data['is_available'] = (bool)$data['is_available'];
        $data['sort_order'] = absint($data['sort_order']);
        
        // Calculate discount percentage if sale price is provided
        if ($data['price_sale'] && $data['price_regular'] > 0) {
            $data['discount_percentage'] = (($data['price_regular'] - $data['price_sale']) / $data['price_regular']) * 100;
        }
        
        // Update the record
        $result = $wpdb->update(
            $wpdb->prefix . 'pt_products',
            array(
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'product_type' => $data['product_type'],
                'location_id' => $data['location_id'],
                'price_regular' => $data['price_regular'],
                'price_sale' => $data['price_sale'],
                'sale_start_date' => $data['sale_start_date'],
                'sale_end_date' => $data['sale_end_date'],
                'discount_percentage' => $data['discount_percentage'],
                'currency' => $data['currency'],
                'sku' => $data['sku'],
                'stock_quantity' => $data['stock_quantity'],
                'stock_status' => $data['stock_status'],
                'weight' => $data['weight'],
                'dimensions' => $data['dimensions'],
                'shipping_class' => $data['shipping_class'],
                'shipping_required' => $data['shipping_required'],
                'tax_status' => $data['tax_status'],
                'tax_class' => $data['tax_class'],
                'virtual' => $data['virtual'],
                'downloadable' => $data['downloadable'],
                'download_limit' => $data['download_limit'],
                'download_expiry' => $data['download_expiry'],
                'purchase_note' => $data['purchase_note'],
                'featured' => $data['featured'],
                'catalog_visibility' => $data['catalog_visibility'],
                'featured_image' => $data['featured_image'],
                'gallery_images' => $data['gallery_images'],
                'short_description' => $data['short_description'],
                'meta_description' => $data['meta_description'],
                'tags' => $data['tags'],
                'categories' => $data['categories'],
                'related_products' => $data['related_products'],
                'upsell_ids' => $data['upsell_ids'],
                'cross_sell_ids' => $data['cross_sell_ids'],
                'shipping_width' => $data['shipping_width'],
                'shipping_height' => $data['shipping_height'],
                'shipping_length' => $data['shipping_length'],
                'shipping_weight' => $data['shipping_weight'],
                'shipping_from_location' => $data['shipping_from_location'],
                'shipping_to_locations' => $data['shipping_to_locations'],
                'min_purchase_quantity' => $data['min_purchase_quantity'],
                'max_purchase_quantity' => $data['max_purchase_quantity'],
                'sold_individually' => $data['sold_individually'],
                'allow_backorders' => $data['allow_backorders'],
                'manage_stock' => $data['manage_stock'],
                'low_stock_threshold' => $data['low_stock_threshold'],
                'sold_dates' => $data['sold_dates'],
                'popularity_score' => $data['popularity_score'],
                'trending_score' => $data['trending_score'],
                'seo_title' => $data['seo_title'],
                'seo_description' => $data['seo_description'],
                'meta_keywords' => $data['meta_keywords'],
                'suggested_packages' => $data['suggested_packages'],
                'is_available' => $data['is_available'],
                'sort_order' => $data['sort_order'],
            ),
            array('id' => $id),
            array(
                '%s', '%s', '%s', '%s', '%d',
                '%f', '%f', '%s', '%s', '%f',
                '%s', '%s', '%d', '%s', '%f',
                '%s', '%s', '%d', '%s', '%s',
                '%d', '%d', '%d', '%d', '%s',
                '%d', '%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', '%s',
                '%f', '%f', '%f', '%f', '%s',
                '%s', '%d', '%d', '%d', '%s',
                '%d', '%d', '%d', '%f', '%f',
                '%s', '%s', '%s', '%s', '%d'
            ),
            array('%d')
        );
        
        if ($result === false) {
            throw new Exception('Failed to update product');
        }
        
        return $result;
    }
    
    /**
     * Get featured products
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
     * Get products by type
     */
    public function get_by_type($product_type, $limit = -1) {
        $args = array(
            'limit' => $limit,
            'product_type' => $product_type,
            'status' => 'active'
        );
        return $this->get_all($args);
    }
    
    /**
     * Get products by location
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
     * Get popular products based on bookings
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
     * Calculate price for booking
     */
    public function calculate_price($product_id, $persons, $child_count = 0, $infant_count = 0) {
        $product = $this->get_by_id($product_id);
        if (!$product) {
            return false;
        }
        
        $total_price = 0;
        
        // Price per person for adults
        if ($product->price_per_person > 0) {
            $total_price += $product->price_per_person * $persons;
        }
        
        // Child price
        if ($child_count > 0 && $product->child_price > 0) {
            $total_price += $product->child_price * $child_count;
        }
        
        // Infant price
        if ($infant_count > 0 && $product->infant_price > 0) {
            $total_price += $product->infant_price * $infant_count;
        }
        
        return array(
            'total_price' => $total_price,
            'price_per_person' => $product->price_per_person,
            'child_price' => $product->child_price,
            'infant_price' => $product->infant_price
        );
    }
    
    /**
     * Check product availability
     */
    public function check_availability($product_id, $date = '', $time = '', $persons = 1) {
        $product = $this->get_by_id($product_id);
        if (!$product) {
            return false;
        }
        
        // Check if product is active
        if (!$product->is_active) {
            return false;
        }
        
        // Check day availability for custom availability
        if ($product->availability_type === 'custom' && !empty($product->available_days)) {
            $check_date = $date ? strtotime($date) : time();
            $day_of_week = date('N', $check_date); // 1-7 (Monday-Sunday)
            $available_days = explode(',', $product->available_days);
            
            if (!in_array($day_of_week, $available_days)) {
                return false;
            }
        }
        
        // Check time availability
        if (!empty($product->start_time) && !empty($product->end_time)) {
            $check_time = $time ? strtotime($time) : time();
            $start_time = strtotime($product->start_time);
            $end_time = strtotime($product->end_time);
            
            if ($check_time < $start_time || $check_time > $end_time) {
                return false;
            }
        }
        
        // Check maximum bookings per slot
        if ($product->max_bookings_per_slot > 0) {
            // Count bookings for this date/time slot (would need to check bookings table)
            $booking_count = 0; // This would be implemented when booking system is enhanced
            if ($booking_count + $persons > $product->max_bookings_per_slot) {
                return false;
            }
        }
        
        // Check minimum and maximum persons
        $total_persons = $persons;
        if ($total_persons < $product->min_persons || $total_persons > $product->max_persons) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get available time slots for a product
     */
    public function get_time_slots($product_id, $date = '') {
        $product = $this->get_by_id($product_id);
        if (!$product || empty($product->start_time) || empty($product->end_time)) {
            return array();
        }
        
        $slots = array();
        $start_time = strtotime($product->start_time);
        $end_time = strtotime($product->end_time);
        $interval = 3600; // 1 hour intervals
        
        for ($time = $start_time; $time <= $end_time; $time += $interval) {
            $slots[] = date('H:i', $time);
        }
        
        return $slots;
    }
    
    /**
     * Get related products
     */
    public function get_related($product_id, $location_id = 0, $limit = 4) {
        $product = $this->get_by_id($product_id);
        if (!$product) {
            return array();
        }
        
        $args = array(
            'limit' => $limit,
            'location_id' => $location_id ? $location_id : $product->location_id,
            'product_type' => $product->product_type,
            'status' => 'active'
        );
        
        $products = $this->get_all($args);
        
        // Remove the current product from results
        $products = array_filter($products, function($p) use ($product_id) {
            return $p->id != $product_id;
        });
        
        return array_slice($products, 0, $limit);
    }
    
    /**
     * Get product with full details
     */
    public function get_detailed($id) {
        $query = $this->wpdb->prepare(
            "SELECT p.*, 
                    l.title as location_name,
                    l.description as location_description,
                    l.coordinates as location_coordinates
             FROM {$this->table_name} p
             LEFT JOIN {$this->wpdb->prefix}pt_locations l ON p.location_id = l.id
             WHERE p.id = %d AND p.is_active = 1",
            $id
        );
        
        $product = $this->wpdb->get_row($query);
        return $this->format_record($product);
    }
    
    /**
     * Update booking count
     */
    public function update_booking_count($product_id, $increment = 1) {
        $query = $this->wpdb->prepare(
            "UPDATE {$this->table_name} 
             SET current_bookings = current_bookings + %d,
                 updated_at = %s
             WHERE id = %d",
            $increment,
            current_time('mysql'),
            $product_id
        );
        
        return $this->wpdb->query($query);
    }
    
    /**
     * Get product categories/types
     */
    public function get_types() {
        $query = "SELECT DISTINCT product_type FROM {$this->table_name} WHERE is_active = 1 ORDER BY product_type";
        return $this->wpdb->get_col($query);
    }
    
    /**
     * Get product statistics
     */
    public function get_stats() {
        $total = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
        $active = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE is_active = 1");
        $featured = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE is_featured = 1 AND is_active = 1");
        $tours = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE product_type = 'tour' AND is_active = 1");
        $activities = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE product_type = 'activity' AND is_active = 1");
        $attractions = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE product_type = 'attraction' AND is_active = 1");
        $average_rating = $this->wpdb->get_var("SELECT AVG(rating) FROM {$this->table_name} WHERE is_active = 1");
        
        return array(
            'total_products' => $total,
            'active_products' => $active,
            'featured_products' => $featured,
            'tours' => $tours,
            'activities' => $activities,
            'attractions' => $attractions,
            'average_rating' => round($average_rating, 2)
        );
    }
    
    /**
     * Get products with upcoming availability
     */
    public function get_upcoming($days_ahead = 30, $limit = 10) {
        $query = $this->wpdb->prepare(
            "SELECT p.*, l.title as location_name
             FROM {$this->table_name} p
             LEFT JOIN {$this->wpdb->prefix}pt_locations l ON p.location_id = l.id
             WHERE p.is_active = 1 
             AND (p.availability_type = 'always' 
                  OR (p.availability_type = 'custom' 
                      AND FIND_IN_SET(DAYOFWEEK(DATE_ADD(CURDATE(), INTERVAL 1 DAY)), p.available_days)))
             ORDER BY p.sort_order ASC, p.rating DESC
             LIMIT %d",
            $limit
        );
        
        $results = $this->wpdb->get_results($query);
        
        foreach ($results as $result) {
            $result = $this->format_record($result);
        }
        
        return $results;
    }
}