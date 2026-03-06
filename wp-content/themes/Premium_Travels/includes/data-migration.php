<?php
/**
 * Data Migration Functions
 * Migrate existing data to new database structure
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Migrate existing custom post types to new database tables
 */
function pt_migrate_existing_data() {
    if (!current_user_can('manage_options')) {
        return false;
    }
    
    $results = array();
    
    // Check if migration already done
    if (get_option('pt_data_migration_completed')) {
        return array('message' => 'Data migration already completed');
    }
    
    // Migrate locations
    $results['locations'] = pt_migrate_locations();
    
    // Migrate packages
    $results['packages'] = pt_migrate_packages();
    
    // Migrate car types
    $results['car_types'] = pt_migrate_car_types();
    
    // Migrate routes
    $results['routes'] = pt_migrate_routes();
    
    // Migrate products
    $results['products'] = pt_migrate_products();
    
    // Mark migration as complete
    update_option('pt_data_migration_completed', true);
    
    return $results;
}

/**
 * Migrate locations from CPT to database table
 */
function pt_migrate_locations() {
    // Load managers
    require_once get_template_directory() . '/includes/dynamic-management/base-manager.php';
    
    global $wpdb;
    
    // Check if new table exists
    $table_name = $wpdb->prefix . 'pt_locations';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        return array('status' => 'error', 'message' => 'Locations table does not exist');
    }
    
    // Get existing locations CPT
    $args = array(
        'post_type' => 'location',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    
    $locations = get_posts($args);
    $migrated = 0;
    
    foreach ($locations as $location) {
        // Check if already migrated
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table_name WHERE title = %s",
                $location->post_title
            )
        );
        
        if (!$existing) {
            $data = array(
                'title' => $location->post_title,
                'slug' => sanitize_title($location->post_title),
                'description' => $location->post_content,
                'state' => get_post_meta($location->ID, 'state', true),
                'airport_name' => get_post_meta($location->ID, 'airport', true),
                'coordinates' => get_post_meta($location->ID, 'coordinates', true),
                'is_active' => (get_post_meta($location->ID, 'is_active', true) === 'yes') ? 1 : 0,
                'is_popular' => 0,
                'sort_order' => 0
            );
            
            $wpdb->insert($table_name, $data);
            $migrated++;
        }
    }
    
    return array('status' => 'success', 'migrated' => $migrated, 'total' => count($locations));
}

/**
 * Migrate packages from CPT to database table
 */
function pt_migrate_packages() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'pt_packages';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        return array('status' => 'error', 'message' => 'Packages table does not exist');
    }
    
    // Get existing packages CPT
    $args = array(
        'post_type' => array('taxi_package', 'holiday_package'),
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    
    $packages = get_posts($args);
    $migrated = 0;
    
    foreach ($packages as $package) {
        // Check if already migrated
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table_name WHERE title = %s",
                $package->post_title
            )
        );
        
        if (!$existing) {
            // Get location ID
            $location_title = get_post_meta($package->ID, 'pickup_location', true);
            $location_id = 0;
            if ($location_title) {
                $location_id = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT id FROM {$wpdb->prefix}pt_locations WHERE title LIKE %s",
                        '%' . $location_title . '%'
                    )
                );
            }
            
            // Determine package type
            $package_type = ($package->post_type === 'holiday_package') ? 'holiday' : 'taxi';
            
            $data = array(
                'title' => $package->post_title,
                'slug' => sanitize_title($package->post_title),
                'description' => $package->post_content,
                'package_type' => $package_type,
                'location_id' => $location_id ? $location_id : null,
                'duration_days' => get_post_meta($package->ID, 'duration', true) ? intval(get_post_meta($package->ID, 'duration', true)) : null,
                'duration_nights' => get_post_meta($package->ID, 'nights', true) ? intval(get_post_meta($package->ID, 'nights', true)) : null,
                'base_price' => floatval(str_replace(',', '', get_post_meta($package->ID, 'price', true))),
                'includes' => serialize(explode(', ', get_post_meta($package->ID, 'inclusions', true))),
                'excludes' => serialize(explode(', ', get_post_meta($package->ID, 'exclusions', true))),
                'is_active' => 1,
                'is_featured' => 0,
                'sort_order' => 0
            );
            
            $wpdb->insert($table_name, $data);
            $migrated++;
        }
    }
    
    return array('status' => 'success', 'migrated' => $migrated, 'total' => count($packages));
}

/**
 * Migrate car types from CPT to database table
 */
function pt_migrate_car_types() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'pt_car_types';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        return array('status' => 'error', 'message' => 'Car types table does not exist');
    }
    
    // Get existing car types CPT
    $args = array(
        'post_type' => 'car_type',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    
    $car_types = get_posts($args);
    $migrated = 0;
    
    foreach ($car_types as $car) {
        // Check if already migrated
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table_name WHERE title = %s",
                $car->post_title
            )
        );
        
        if (!$existing) {
            $data = array(
                'title' => $car->post_title,
                'slug' => sanitize_title($car->post_title),
                'description' => $car->post_content,
                'category' => wp_get_post_terms($car->ID, 'car_category', array('fields' => 'names'))[0] ?? 'Sedan',
                'capacity' => intval(get_post_meta($car->ID, 'capacity', true)),
                'base_price_per_km' => floatval(get_post_meta($car->ID, 'price_per_km', true)),
                'ac_type' => get_post_meta($car->ID, 'ac_status', true),
                'fuel_type' => get_post_meta($car->ID, 'fuel_type', true),
                'features' => serialize(explode(', ', get_post_meta($car->ID, 'features', true))),
                'is_active' => 1,
                'is_featured' => 0,
                'sort_order' => 0,
                'availability_status' => 'available'
            );
            
            $wpdb->insert($table_name, $data);
            $migrated++;
        }
    }
    
    return array('status' => 'success', 'migrated' => $migrated, 'total' => count($car_types));
}

/**
 * Migrate routes from CPT to database table
 */
function pt_migrate_routes() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'pt_routes';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        return array('status' => 'error', 'message' => 'Routes table does not exist');
    }
    
    // Get existing routes CPT
    $args = array(
        'post_type' => 'route',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    
    $routes = get_posts($args);
    $migrated = 0;
    
    foreach ($routes as $route) {
        // Check if already migrated
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table_name WHERE title = %s",
                $route->post_title
            )
        );
        
        if (!$existing) {
            // Extract from and to locations from title
            $title_parts = explode(' to ', $route->post_title);
            $from_location = trim($title_parts[0]);
            $to_location = isset($title_parts[1]) ? trim($title_parts[1]) : '';
            
            // Get location IDs
            $from_id = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}pt_locations WHERE title LIKE %s",
                    '%' . $from_location . '%'
                )
            );
            
            $to_id = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}pt_locations WHERE title LIKE %s",
                    '%' . $to_location . '%'
                )
            );
            
            if ($from_id && $to_id) {
                $data = array(
                    'title' => $route->post_title,
                    'from_location_id' => $from_id,
                    'to_location_id' => $to_id,
                    'distance_km' => floatval(get_post_meta($route->ID, 'distance_km', true)),
                    'base_price' => floatval(get_post_meta($route->ID, 'price_per_km', true)) * floatval(get_post_meta($route->ID, 'distance_km', true)),
                    'price_per_km' => floatval(get_post_meta($route->ID, 'price_per_km', true)),
                    'route_type' => get_post_meta($route->ID, 'route_type', true) ?: 'one-way',
                    'is_active' => 1,
                    'is_popular' => 0,
                    'sort_order' => 0
                );
                
                $wpdb->insert($table_name, $data);
                $migrated++;
            }
        }
    }
    
    return array('status' => 'success', 'migrated' => $migrated, 'total' => count($routes));
}

/**
 * Migrate products from CPT to database table
 */
function pt_migrate_products() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'pt_products';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        return array('status' => 'error', 'message' => 'Products table does not exist');
    }
    
    // Get existing products CPT
    $args = array(
        'post_type' => 'pt_product',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    
    $products = get_posts($args);
    $migrated = 0;
    
    foreach ($products as $product) {
        // Check if already migrated
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table_name WHERE title = %s",
                $product->post_title
            )
        );
        
        if (!$existing) {
            // Extract location from meta
            $location_title = get_post_meta($product->ID, 'product_location', true);
            $location_id = 0;
            if ($location_title) {
                $location_id = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT id FROM {$wpdb->prefix}pt_locations WHERE title LIKE %s",
                        '%' . $location_title . '%'
                    )
                );
            }
            
            $data = array(
                'title' => $product->post_title,
                'slug' => sanitize_title($product->post_title),
                'description' => $product->post_content,
                'product_type' => 'tour',
                'location_id' => $location_id ? $location_id : null,
                'price_per_person' => floatval(get_post_meta($product->ID, 'price', true)),
                'is_active' => 1,
                'is_featured' => 0,
                'sort_order' => 0
            );
            
            $wpdb->insert($table_name, $data);
            $migrated++;
        }
    }
    
    return array('status' => 'success', 'migrated' => $migrated, 'total' => count($products));
}

/**
 * Create a test page to trigger migration
 */
function pt_create_migration_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['pt_run_migration'])) {
        $results = pt_migrate_existing_data();
        echo '<div class="notice notice-success"><p>Migration completed! Results:</p>';
        echo '<pre>' . print_r($results, true) . '</pre></div>';
    }
    
    echo '<div class="wrap">';
    echo '<h1>Data Migration Tool</h1>';
    echo '<p>This tool will migrate your existing custom post types to the new database structure.</p>';
    echo '<form method="post">';
    echo '<input type="submit" name="pt_run_migration" class="button-primary" value="Run Migration">';
    echo '</form>';
    echo '</div>';
}