<?php
/**
 * Data Migration Tool
 * Migrates existing CPT data to new database tables
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Migration_Tool {
    
    public static function display_migration_page() {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }
        
        global $wpdb;
        
        // Handle migration actions
        if (isset($_POST['run_migration'])) {
            $results = self::run_complete_migration();
            echo '<div class="notice notice-success"><p>Migration completed! Results:</p>';
            echo '<pre>' . print_r($results, true) . '</pre></div>';
        }
        
        // Show current data status
        $cpt_counts = self::get_cpt_counts();
        $table_counts = self::get_table_counts();
        
        ?>
        <div class="wrap">
            <h1>Data Migration Tool</h1>
            <p>This tool will migrate your existing Custom Post Types data to the new database structure.</p>
            
            <div class="postbox">
                <h2 class="hndle">Current Data Status</h2>
                <div class="inside">
                    <h3>Custom Post Types:</h3>
                    <ul>
                        <?php foreach($cpt_counts as $cpt => $count): ?>
                            <li><?php echo ucfirst(str_replace('_', ' ', $cpt)); ?>: <?php echo $count; ?> items</li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <h3>Database Tables:</h3>
                    <ul>
                        <?php foreach($table_counts as $table => $count): ?>
                            <li><?php echo $table; ?>: <?php echo $count; ?> records</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <form method="post" style="margin: 20px 0;">
                <?php submit_button('Run Complete Migration', 'primary', 'run_migration'); ?>
            </form>
            
            <div class="postbox">
                <h2 class="hndle">Migration Options</h2>
                <div class="inside">
                    <p>You can also migrate individual components:</p>
                    <ul>
                        <li><a href="<?php echo admin_url('admin.php?page=pt-packages&action=migrate'); ?>">Migrate Packages</a></li>
                        <li><a href="<?php echo admin_url('admin.php?page=pt-car-types&action=migrate'); ?>">Migrate Car Types</a></li>
                        <li><a href="<?php echo admin_url('admin.php?page=pt-routes&action=migrate'); ?>">Migrate Routes</a></li>
                        <li><a href="<?php echo admin_url('admin.php?page=pt-products&action=migrate'); ?>">Migrate Products</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="postbox">
                <h2 class="hndle">Create Sample Data</h2>
                <div class="inside">
                    <p>If you want to test the system with sample data:</p>
                    <a href="<?php echo get_template_directory_uri(); ?>/test-system.php?create_sample_data=1" class="button">Create Sample Data</a>
                </div>
            </div>
        </div>
        <?php
    }
    
    private static function get_cpt_counts() {
        global $wpdb;
        
        $cpts = ['location', 'taxi_package', 'route', 'testimonial', 'car_type', 'holiday_package', 'special_offer', 'city_service', 'pt_product'];
        $counts = array();
        
        foreach($cpts as $cpt) {
            $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish'");
            $counts[$cpt] = $count;
        }
        
        return $counts;
    }
    
    private static function get_table_counts() {
        global $wpdb;
        
        $tables = [
            'pt_packages' => 'Packages',
            'pt_car_types' => 'Car Types', 
            'pt_routes' => 'Routes',
            'pt_products' => 'Products',
            'pt_locations' => 'Locations',
            'pt_bookings' => 'Bookings'
        ];
        
        $counts = array();
        foreach($tables as $table_name => $display_name) {
            $full_table = $wpdb->prefix . $table_name;
            $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
            if ($exists) {
                $count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table");
                $counts[$display_name] = $count;
            } else {
                $counts[$display_name] = 'Table missing';
            }
        }
        
        return $counts;
    }
    
    private static function run_complete_migration() {
        $results = array();
        
        // Run individual migrations
        $results['locations'] = self::migrate_locations();
        $results['packages'] = self::migrate_packages();
        $results['car_types'] = self::migrate_car_types();
        $results['routes'] = self::migrate_routes();
        $results['products'] = self::migrate_products();
        
        return $results;
    }
    
    private static function migrate_locations() {
        global $wpdb;
        
        $args = array(
            'post_type' => 'location',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );
        
        $locations = get_posts($args);
        $migrated = 0;
        
        foreach ($locations as $location) {
            $existing = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}pt_locations WHERE title = %s",
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
                    'sort_order' => 0,
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                );
                
                $wpdb->insert($wpdb->prefix . 'pt_locations', $data);
                $migrated++;
            }
        }
        
        return array('status' => 'success', 'migrated' => $migrated, 'total' => count($locations));
    }
    
    private static function migrate_packages() {
        global $wpdb;
        
        $args = array(
            'post_type' => array('taxi_package', 'holiday_package'),
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );
        
        $packages = get_posts($args);
        $migrated = 0;
        
        foreach ($packages as $package) {
            $existing = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}pt_packages WHERE title = %s",
                    $package->post_title
                )
            );
            
            if (!$existing) {
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
                    'sort_order' => 0,
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                );
                
                $wpdb->insert($wpdb->prefix . 'pt_packages', $data);
                $migrated++;
            }
        }
        
        return array('status' => 'success', 'migrated' => $migrated, 'total' => count($packages));
    }
    
    private static function migrate_car_types() {
        global $wpdb;
        
        $args = array(
            'post_type' => 'car_type',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );
        
        $car_types = get_posts($args);
        $migrated = 0;
        
        foreach ($car_types as $car) {
            $existing = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}pt_car_types WHERE title = %s",
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
                    'availability_status' => 'available',
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                );
                
                $wpdb->insert($wpdb->prefix . 'pt_car_types', $data);
                $migrated++;
            }
        }
        
        return array('status' => 'success', 'migrated' => $migrated, 'total' => count($car_types));
    }
    
    private static function migrate_routes() {
        global $wpdb;
        
        $args = array(
            'post_type' => 'route',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );
        
        $routes = get_posts($args);
        $migrated = 0;
        
        foreach ($routes as $route) {
            $existing = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}pt_routes WHERE title = %s",
                    $route->post_title
                )
            );
            
            if (!$existing) {
                $title_parts = explode(' to ', $route->post_title);
                $from_location = trim($title_parts[0]);
                $to_location = isset($title_parts[1]) ? trim($title_parts[1]) : '';
                
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
                        'sort_order' => 0,
                        'created_at' => current_time('mysql'),
                        'updated_at' => current_time('mysql')
                    );
                    
                    $wpdb->insert($wpdb->prefix . 'pt_routes', $data);
                    $migrated++;
                }
            }
        }
        
        return array('status' => 'success', 'migrated' => $migrated, 'total' => count($routes));
    }
    
    private static function migrate_products() {
        global $wpdb;
        
        $args = array(
            'post_type' => 'pt_product',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );
        
        $products = get_posts($args);
        $migrated = 0;
        
        foreach ($products as $product) {
            $existing = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}pt_products WHERE title = %s",
                    $product->post_title
                )
            );
            
            if (!$existing) {
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
                    'sort_order' => 0,
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                );
                
                $wpdb->insert($wpdb->prefix . 'pt_products', $data);
                $migrated++;
            }
        }
        
        return array('status' => 'success', 'migrated' => $migrated, 'total' => count($products));
    }
}