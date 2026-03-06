<?php
/**
 * Data Migration and Sync Tool
 * Moves your CPT data to the new database tables
 */

require_once('wp-config.php');

global $wpdb;

echo "=== DATA MIGRATION TOOL ===\n\n";

if (isset($_GET['action']) && $_GET['action'] === 'migrate') {
    echo "MIGRATING YOUR DATA FROM CPTS TO DATABASE TABLES...\n\n";
    
    $migration_results = array();
    
    // Migrate locations
    echo "Migrating Locations...\n";
    $locations = get_posts(array(
        'post_type' => 'location',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    $migrated_locations = 0;
    foreach($locations as $location) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_locations WHERE title = %s",
            $location->post_title
        ));
        
        if (!$existing) {
            $data = array(
                'title' => $location->post_title,
                'slug' => sanitize_title($location->post_title),
                'description' => $location->post_content,
                'state' => get_post_meta($location->ID, 'state', true),
                'airport_name' => get_post_meta($location->ID, 'airport', true),
                'coordinates' => get_post_meta($location->ID, 'coordinates', true),
                'is_active' => 1,
                'is_popular' => 0,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            );
            
            $wpdb->insert($wpdb->prefix . 'pt_locations', $data);
            $migrated_locations++;
            echo "  Migrated: {$location->post_title}\n";
        }
    }
    $migration_results['locations'] = $migrated_locations;
    
    // Migrate car types
    echo "\nMigrating Car Types...\n";
    $car_types = get_posts(array(
        'post_type' => 'car_type',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    $migrated_cars = 0;
    foreach($car_types as $car) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_car_types WHERE title = %s",
            $car->post_title
        ));
        
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
                'availability_status' => 'available',
                'is_featured' => 0,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            );
            
            $wpdb->insert($wpdb->prefix . 'pt_car_types', $data);
            $migrated_cars++;
            echo "  Migrated: {$car->post_title}\n";
        }
    }
    $migration_results['car_types'] = $migrated_cars;
    
    // Migrate packages
    echo "\nMigrating Packages...\n";
    $packages = get_posts(array(
        'post_type' => array('taxi_package', 'holiday_package'),
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    $migrated_packages = 0;
    foreach($packages as $package) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_packages WHERE title = %s",
            $package->post_title
        ));
        
        if (!$existing) {
            // Get location ID
            $location_title = get_post_meta($package->ID, 'pickup_location', true);
            $location_id = 0;
            if ($location_title) {
                $location_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}pt_locations WHERE title LIKE %s",
                    '%' . $location_title . '%'
                ));
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
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            );
            
            $wpdb->insert($wpdb->prefix . 'pt_packages', $data);
            $migrated_packages++;
            echo "  Migrated: {$package->post_title}\n";
        }
    }
    $migration_results['packages'] = $migrated_packages;
    
    echo "\n✅ MIGRATION COMPLETE!\n";
    echo "Results:\n";
    foreach($migration_results as $type => $count) {
        echo "  $type: $count records migrated\n";
    }
    
    echo "\nNow your data should appear in the dashboard and frontend!\n";
}

// Show current status
echo "CURRENT DATA STATUS:\n";
echo "--------------------\n";

$cpts = ['location', 'car_type', 'taxi_package', 'holiday_package'];
$new_tables = ['pt_locations', 'pt_car_types', 'pt_packages'];

echo "WordPress CPT Data:\n";
$cpt_total = 0;
foreach($cpts as $cpt) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish'");
    echo "  $cpt: $count records\n";
    $cpt_total += $count;
}

echo "\nNew Database Tables:\n";
$db_total = 0;
foreach($new_tables as $table) {
    $full_table = $wpdb->prefix . $table;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    if ($exists) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table WHERE is_active = 1");
        echo "  $table: $count records\n";
        $db_total += $count;
    } else {
        echo "  $table: TABLE MISSING\n";
    }
}

echo "\nTOTAL CPT RECORDS: $cpt_total\n";
echo "TOTAL DATABASE RECORDS: $db_total\n";

if ($cpt_total > 0 && $db_total == 0) {
    echo "\n🚨 ACTION REQUIRED: Your data is in CPTs but needs to be migrated!\n";
    echo "<a href='?action=migrate'>CLICK HERE TO MIGRATE ALL YOUR DATA NOW</a>\n";
} elseif ($db_total > 0) {
    echo "\n✅ Your data is properly in the database tables!\n";
    echo "Check your dashboard and frontend - data should be visible.\n";
} else {
    echo "\n📭 No data found in either system.\n";
    echo "Add data through the new admin interfaces or run the migration.\n";
}

echo "\n=== EXPLANATION ===\n";
echo "You've been adding data through the old WordPress admin (CPT system),\n";
echo "but the new travel management system reads from database tables.\n";
echo "This tool migrates your existing data to the new system.\n";
?>