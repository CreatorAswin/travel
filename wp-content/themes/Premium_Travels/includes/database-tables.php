<?php
/**
 * Create Custom Database Tables
 * This file creates all custom tables for the travel management system
 */

function premium_travels_create_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Table 1: Enhanced Bookings
    $table_bookings = $wpdb->prefix . 'pt_bookings';
    $sql_bookings = "CREATE TABLE IF NOT EXISTS $table_bookings (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        booking_reference VARCHAR(50) UNIQUE NOT NULL,
        user_id BIGINT(20) UNSIGNED,
        service_type VARCHAR(50) NOT NULL,
        package_id BIGINT(20) UNSIGNED,
        car_type_id BIGINT(20) UNSIGNED,
        route_id BIGINT(20) UNSIGNED,
        pickup_location VARCHAR(255),
        drop_location VARCHAR(255),
        pickup_date DATE,
        pickup_time TIME,
        return_date DATE,
        return_time TIME,
        passengers INT,
        total_distance DECIMAL(8,2),
        base_price DECIMAL(10,2),
        additional_charges DECIMAL(10,2) DEFAULT 0.00,
        discount_amount DECIMAL(10,2) DEFAULT 0.00,
        total_price DECIMAL(10,2),
        customer_name VARCHAR(255),
        customer_email VARCHAR(255),
        customer_phone VARCHAR(20),
        customer_address TEXT,
        special_requests TEXT,
        status VARCHAR(20) DEFAULT 'pending',
        payment_status VARCHAR(20) DEFAULT 'pending',
        payment_method VARCHAR(50),
        transaction_id VARCHAR(100),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_booking_ref (booking_reference),
        INDEX idx_status (status),
        INDEX idx_payment_status (payment_status),
        INDEX idx_pickup_date (pickup_date),
        INDEX idx_customer_email (customer_email),
        INDEX idx_package_id (package_id),
        INDEX idx_car_type_id (car_type_id)
    ) $charset_collate;";

    // Table 2: Enhanced Enquiries
    $table_enquiries = $wpdb->prefix . 'pt_enquiries';
    $sql_enquiries = "CREATE TABLE IF NOT EXISTS $table_enquiries (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100),
        last_name VARCHAR(100),
        email VARCHAR(255),
        phone VARCHAR(20),
        subject VARCHAR(255),
        message TEXT,
        service_type VARCHAR(50),
        package_id BIGINT(20) UNSIGNED,
        enquiry_type VARCHAR(50) DEFAULT 'general',
        status VARCHAR(20) DEFAULT 'new',
        priority VARCHAR(20) DEFAULT 'normal',
        assigned_to BIGINT(20) UNSIGNED,
        response_text TEXT,
        responded_at DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_enquiry_type (enquiry_type),
        INDEX idx_created (created_at),
        INDEX idx_email (email),
        INDEX idx_package_id (package_id)
    ) $charset_collate;";

    // Table 3: Customers
    $table_customers = $wpdb->prefix . 'pt_customers';
    $sql_customers = "CREATE TABLE IF NOT EXISTS $table_customers (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT(20) UNSIGNED,
        customer_code VARCHAR(20) UNIQUE,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        phone VARCHAR(20),
        alternate_phone VARCHAR(20),
        date_of_birth DATE,
        gender VARCHAR(10),
        address TEXT,
        city VARCHAR(100),
        state VARCHAR(100),
        country VARCHAR(100) DEFAULT 'India',
        postal_code VARCHAR(20),
        id_proof_type VARCHAR(50),
        id_proof_number VARCHAR(100),
        is_verified BOOLEAN DEFAULT FALSE,
        total_bookings INT DEFAULT 0,
        total_spent DECIMAL(12,2) DEFAULT 0.00,
        loyalty_points INT DEFAULT 0,
        preferred_car_type VARCHAR(100),
        special_preferences TEXT,
        status VARCHAR(20) DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_customer_code (customer_code),
        INDEX idx_email (email),
        INDEX idx_phone (phone),
        INDEX idx_status (status),
        INDEX idx_user_id (user_id)
    ) $charset_collate;";

    // Table 4: Packages
    $table_packages = $wpdb->prefix . 'pt_packages';
    $sql_packages = "CREATE TABLE IF NOT EXISTS $table_packages (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE,
        description TEXT,
        package_type VARCHAR(50) NOT NULL,
        category_id BIGINT(20) UNSIGNED,
        location_id BIGINT(20) UNSIGNED,
        duration_days INT,
        duration_nights INT,
        min_persons INT DEFAULT 1,
        max_persons INT DEFAULT 10,
        base_price DECIMAL(10,2),
        price_per_person DECIMAL(10,2),
        child_price DECIMAL(10,2),
        infant_price DECIMAL(10,2),
        includes TEXT,
        excludes TEXT,
        highlights TEXT,
        itinerary TEXT,
        cancellation_policy TEXT,
        terms_conditions TEXT,
        featured_image VARCHAR(500),
        gallery_images TEXT,
        availability_type VARCHAR(20) DEFAULT 'always', -- always, seasonal, custom
        start_date DATE,
        end_date DATE,
        available_days VARCHAR(20), -- comma separated: 1,2,3,4,5,6,7
        max_bookings_per_day INT DEFAULT 0, -- 0 for unlimited
        current_bookings INT DEFAULT 0,
        rating DECIMAL(3,2) DEFAULT 0.00,
        total_reviews INT DEFAULT 0,
        is_featured BOOLEAN DEFAULT FALSE,
        is_active BOOLEAN DEFAULT TRUE,
        sort_order INT DEFAULT 0,
        seo_title VARCHAR(255),
        seo_description TEXT,
        meta_keywords TEXT,
        suggested_products TEXT, -- JSON array of product IDs suggested with this package
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_slug (slug),
        INDEX idx_package_type (package_type),
        INDEX idx_category_id (category_id),
        INDEX idx_location_id (location_id),
        INDEX idx_is_active (is_active),
        INDEX idx_is_featured (is_featured),
        INDEX idx_rating (rating)
    ) $charset_collate;";

    // Table 5: Car Types
    $table_car_types = $wpdb->prefix . 'pt_car_types';
    $sql_car_types = "CREATE TABLE IF NOT EXISTS $table_car_types (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE,
        description TEXT,
        category VARCHAR(50) NOT NULL,
        capacity INT DEFAULT 4,
        luggage_capacity INT DEFAULT 2,
        ac_type VARCHAR(20) DEFAULT 'AC', -- AC, Non-AC
        fuel_type VARCHAR(20) DEFAULT 'Petrol', -- Petrol, Diesel, CNG, Electric
        transmission VARCHAR(20) DEFAULT 'Manual', -- Manual, Automatic
        base_price_per_km DECIMAL(8,2),
        extra_km_price DECIMAL(8,2),
        waiting_charge_per_hour DECIMAL(8,2),
        night_charge_multiplier DECIMAL(3,2) DEFAULT 1.2,
        driver_allowance_per_day DECIMAL(8,2),
        features TEXT, -- comma separated features
        specifications TEXT, -- JSON specifications
        featured_image VARCHAR(500),
        gallery_images TEXT,
        availability_status VARCHAR(20) DEFAULT 'available', -- available, maintenance, booked
        total_trips INT DEFAULT 0,
        total_distance DECIMAL(10,2) DEFAULT 0.00,
        rating DECIMAL(3,2) DEFAULT 0.00,
        total_reviews INT DEFAULT 0,
        is_featured BOOLEAN DEFAULT FALSE,
        is_active BOOLEAN DEFAULT TRUE,
        sort_order INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_slug (slug),
        INDEX idx_category (category),
        INDEX idx_availability (availability_status),
        INDEX idx_is_active (is_active),
        INDEX idx_rating (rating)
    ) $charset_collate;";

    // Table 6: Routes
    $table_routes = $wpdb->prefix . 'pt_routes';
    $sql_routes = "CREATE TABLE IF NOT EXISTS $table_routes (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        from_location_id BIGINT(20) UNSIGNED NOT NULL,
        to_location_id BIGINT(20) UNSIGNED NOT NULL,
        distance_km DECIMAL(8,2),
        estimated_time VARCHAR(20),
        base_price DECIMAL(10,2),
        price_per_km DECIMAL(8,2),
        toll_charges DECIMAL(8,2) DEFAULT 0.00,
        parking_charges DECIMAL(8,2) DEFAULT 0.00,
        night_charge_multiplier DECIMAL(3,2) DEFAULT 1.00,
        peak_hour_multiplier DECIMAL(3,2) DEFAULT 1.00,
        route_type VARCHAR(50) DEFAULT 'one-way', -- one-way, round-trip, multi-way
        via_points TEXT, -- JSON array of via points
        map_coordinates TEXT, -- JSON coordinates
        is_popular BOOLEAN DEFAULT FALSE,
        is_active BOOLEAN DEFAULT TRUE,
        sort_order INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_from_location (from_location_id),
        INDEX idx_to_location (to_location_id),
        INDEX idx_route_type (route_type),
        INDEX idx_is_active (is_active),
        INDEX idx_is_popular (is_popular)
    ) $charset_collate;";

    // Table 7: Enhanced E-commerce Style Products with Travel Integration
    $table_products = $wpdb->prefix . 'pt_products';
    $sql_products = "CREATE TABLE IF NOT EXISTS $table_products (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE,
        description TEXT,
        product_type ENUM('tour', 'activity', 'experience', 'package', 'service', 'attraction', 'adventure', 'cultural', 'physical_product', 'digital_product', 'subscription') NOT NULL,
        location_id BIGINT(20) UNSIGNED,
        price_regular DECIMAL(10,2),
        price_sale DECIMAL(10,2),
        sale_start_date DATE,
        sale_end_date DATE,
        discount_percentage DECIMAL(5,2),
        currency VARCHAR(3) DEFAULT 'INR',
        sku VARCHAR(100),
        stock_quantity INT DEFAULT 0,
        stock_status ENUM('instock', 'outofstock', 'onbackorder') DEFAULT 'instock',
        weight DECIMAL(8,2),
        dimensions VARCHAR(50),
        shipping_class VARCHAR(50),
        shipping_required BOOLEAN DEFAULT TRUE,
        tax_status ENUM('taxable', 'shipping', 'none') DEFAULT 'taxable',
        tax_class VARCHAR(50),
        virtual BOOLEAN DEFAULT FALSE,
        downloadable BOOLEAN DEFAULT FALSE,
        download_limit INT DEFAULT 0,
        download_expiry INT DEFAULT 0,
        purchase_note TEXT,
        featured BOOLEAN DEFAULT FALSE,
        catalog_visibility ENUM('visible', 'catalog', 'search', 'hidden') DEFAULT 'visible',
        featured_image VARCHAR(500),
        gallery_images TEXT,
        short_description TEXT,
        meta_description TEXT,
        tags TEXT,
        categories TEXT,
        related_products TEXT,
        upsell_ids TEXT,
        cross_sell_ids TEXT,
        suggested_packages TEXT, -- JSON array of package IDs suggested with this product
        total_sales INT DEFAULT 0,
        average_rating DECIMAL(3,2) DEFAULT 0.00,
        review_count INT DEFAULT 0,
        rating_count TEXT,
        shipping_width DECIMAL(8,2),
        shipping_height DECIMAL(8,2),
        shipping_length DECIMAL(8,2),
        shipping_weight DECIMAL(8,2),
        shipping_from_location VARCHAR(100),
        shipping_to_locations TEXT,
        min_purchase_quantity INT DEFAULT 1,
        max_purchase_quantity INT DEFAULT 999,
        sold_individually BOOLEAN DEFAULT FALSE,
        allow_backorders ENUM('no', 'notify', 'yes') DEFAULT 'no',
        manage_stock BOOLEAN DEFAULT FALSE,
        low_stock_threshold INT DEFAULT 5,
        sold_dates TEXT,
        popularity_score DECIMAL(5,2) DEFAULT 0.00,
        trending_score DECIMAL(5,2) DEFAULT 0.00,
        seo_title VARCHAR(255),
        seo_description TEXT,
        meta_keywords TEXT,
        is_available BOOLEAN DEFAULT TRUE,
        sort_order INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (location_id) REFERENCES {$wpdb->prefix}pt_locations(id) ON DELETE SET NULL,
        INDEX idx_slug (slug),
        INDEX idx_product_type (product_type),
        INDEX idx_location_id (location_id),
        INDEX idx_price_regular (price_regular),
        INDEX idx_price_sale (price_sale),
        INDEX idx_discount (discount_percentage),
        INDEX idx_available (is_available),
        INDEX idx_featured (featured),
        INDEX idx_sku (sku),
        INDEX idx_stock_status (stock_status),
        INDEX idx_total_sales (total_sales),
        INDEX idx_average_rating (average_rating)
    ) $charset_collate;";

    // Table 8: Special Offers
    $table_offers = $wpdb->prefix . 'pt_special_offers';
    $sql_offers = "CREATE TABLE IF NOT EXISTS $table_offers (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        coupon_code VARCHAR(50) UNIQUE,
        discount_type VARCHAR(20) NOT NULL, -- percentage, fixed_amount
        discount_value DECIMAL(10,2),
        minimum_amount DECIMAL(10,2) DEFAULT 0.00,
        maximum_discount DECIMAL(10,2),
        usage_limit INT DEFAULT 0, -- 0 for unlimited
        used_count INT DEFAULT 0,
        per_user_limit INT DEFAULT 1,
        applicable_services TEXT, -- JSON array: taxi, packages, tours
        exclude_services TEXT, -- JSON array
        start_date DATE,
        end_date DATE,
        is_active BOOLEAN DEFAULT TRUE,
        is_featured BOOLEAN DEFAULT FALSE,
        terms_conditions TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_coupon_code (coupon_code),
        INDEX idx_is_active (is_active),
        INDEX idx_start_date (start_date),
        INDEX idx_end_date (end_date)
    ) $charset_collate;";

    // Table 9: Testimonials
    $table_testimonials = $wpdb->prefix . 'pt_testimonials';
    $sql_testimonials = "CREATE TABLE IF NOT EXISTS $table_testimonials (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(255) NOT NULL,
        customer_email VARCHAR(255),
        customer_phone VARCHAR(20),
        rating INT DEFAULT 5,
        title VARCHAR(255),
        content TEXT,
        service_type VARCHAR(50),
        package_id BIGINT(20) UNSIGNED,
        booking_id BIGINT(20) UNSIGNED,
        customer_location VARCHAR(255),
        customer_designation VARCHAR(255),
        is_verified BOOLEAN DEFAULT FALSE,
        is_featured BOOLEAN DEFAULT FALSE,
        status VARCHAR(20) DEFAULT 'pending', -- pending, approved, rejected
        approved_at DATETIME,
        approved_by BIGINT(20) UNSIGNED,
        sort_order INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_rating (rating),
        INDEX idx_is_featured (is_featured),
        INDEX idx_service_type (service_type),
        INDEX idx_package_id (package_id)
    ) $charset_collate;";

    // Table 10: Locations
    $table_locations = $wpdb->prefix . 'pt_locations';
    $sql_locations = "CREATE TABLE IF NOT EXISTS $table_locations (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE,
        description TEXT,
        state VARCHAR(100),
        country VARCHAR(100) DEFAULT 'India',
        coordinates VARCHAR(100), -- lat,lng
        airport_name VARCHAR(255),
        airport_code VARCHAR(10),
        is_popular BOOLEAN DEFAULT FALSE,
        is_active BOOLEAN DEFAULT TRUE,
        sort_order INT DEFAULT 0,
        seo_title VARCHAR(255),
        seo_description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_slug (slug),
        INDEX idx_is_active (is_active),
        INDEX idx_is_popular (is_popular)
    ) $charset_collate;";

    // Table 11: Package Categories
    $table_package_categories = $wpdb->prefix . 'pt_package_categories';
    $sql_package_categories = "CREATE TABLE IF NOT EXISTS $table_package_categories (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE,
        description TEXT,
        parent_id BIGINT(20) UNSIGNED DEFAULT 0,
        featured_image VARCHAR(500),
        is_active BOOLEAN DEFAULT TRUE,
        sort_order INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_slug (slug),
        INDEX idx_parent_id (parent_id),
        INDEX idx_is_active (is_active)
    ) $charset_collate;";

    // Table 12: Payments
    $table_payments = $wpdb->prefix . 'pt_payments';
    $sql_payments = "CREATE TABLE IF NOT EXISTS $table_payments (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        booking_id BIGINT(20) UNSIGNED NOT NULL,
        customer_id BIGINT(20) UNSIGNED,
        payment_method VARCHAR(50) NOT NULL,
        transaction_id VARCHAR(100) UNIQUE,
        amount DECIMAL(10,2) NOT NULL,
        currency VARCHAR(10) DEFAULT 'INR',
        status VARCHAR(20) DEFAULT 'pending', -- pending, completed, failed, refunded
        payment_gateway VARCHAR(50),
        gateway_response TEXT,
        notes TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_booking_id (booking_id),
        INDEX idx_transaction_id (transaction_id),
        INDEX idx_status (status),
        INDEX idx_customer_id (customer_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    // Create all tables
    dbDelta($sql_bookings);
    dbDelta($sql_enquiries);
    dbDelta($sql_customers);
    dbDelta($sql_packages);
    dbDelta($sql_car_types);
    dbDelta($sql_routes);
    dbDelta($sql_products);
    dbDelta($sql_offers);
    dbDelta($sql_testimonials);
    dbDelta($sql_locations);
    dbDelta($sql_package_categories);
    dbDelta($sql_payments);

    // Mark tables as created
    update_option('premium_travels_tables_created_v2', true);
    update_option('premium_travels_db_version', '2.0');
}

// Run on theme activation
function premium_travels_activate()
{
    premium_travels_create_tables();
    flush_rewrite_rules();
}
// Note: register_activation_hook won't work in themes, so we'll use after_setup_theme

// Also run on init if tables don't exist
function premium_travels_check_tables()
{
    if (!get_option('premium_travels_tables_created_v2')) {
        premium_travels_create_tables();
    }
}
add_action('after_setup_theme', 'premium_travels_check_tables');

// Function to manually trigger table creation
function premium_travels_create_tables_manual()
{
    if (current_user_can('manage_options')) {
        premium_travels_create_tables();
        return true;
    }
    return false;
}
