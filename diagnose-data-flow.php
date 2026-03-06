<?php
/**
 * Data Flow Diagnostic Tool
 * Checks where your data is actually being stored and why it's not showing
 */

require_once('wp-config.php');

global $wpdb;

echo "=== DATA FLOW DIAGNOSTIC ===\n\n";

echo "1. CHECKING WHERE YOU ADDED DATA:\n";
echo "-----------------------------------\n";

// Check WordPress Custom Post Types (where you might have added data)
$cpts = ['location', 'taxi_package', 'route', 'car_type', 'holiday_package', 'pt_product'];
echo "WordPress CPT Data:\n";
foreach($cpts as $cpt) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish'");
    echo "  $cpt: $count posts\n";
    if ($count > 0) {
        $posts = $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish' LIMIT 3");
        foreach($posts as $post) {
            echo "    - {$post->post_title} (ID: {$post->ID})\n";
        }
    }
}

echo "\n2. CHECKING NEW DATABASE TABLES:\n";
echo "--------------------------------\n";

// Check new database tables
$new_tables = ['pt_packages', 'pt_car_types', 'pt_routes', 'pt_products', 'pt_locations'];
echo "New Database Tables:\n";
foreach($new_tables as $table) {
    $full_table = $wpdb->prefix . $table;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    if ($exists) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table WHERE is_active = 1");
        echo "  $table: $count records\n";
        if ($count > 0) {
            $records = $wpdb->get_results("SELECT * FROM $full_table WHERE is_active = 1 LIMIT 3");
            foreach($records as $record) {
                if (isset($record->title)) {
                    echo "    - {$record->title}\n";
                }
            }
        }
    } else {
        echo "  $table: TABLE MISSING\n";
    }
}

echo "\n3. CHECKING FRONTEND DISPLAY:\n";
echo "-----------------------------\n";

// Test frontend functions
require_once('wp-content/themes/Premium_Travels/includes/frontend/dynamic-display.php');

echo "Package Display Test:\n";
$package_output = pt_display_packages(array('limit' => 2));
if (strpos($package_output, 'No packages available') !== false) {
    echo "  Showing: No packages available\n";
} else {
    echo "  Showing actual data\n";
}

echo "\nCar Types Display Test:\n";
$car_output = pt_display_car_types(array('limit' => 2));
if (strpos($car_output, 'No car types available') !== false) {
    echo "  Showing: No car types available\n";
} else {
    echo "  Showing actual data\n";
}

echo "\n4. IDENTIFYING THE PROBLEM:\n";
echo "---------------------------\n";

$cpt_total = 0;
$db_total = 0;

foreach($cpts as $cpt) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish'");
    $cpt_total += $count;
}

foreach($new_tables as $table) {
    $full_table = $wpdb->prefix . $table;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    if ($exists) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table WHERE is_active = 1");
        $db_total += $count;
    }
}

echo "Total CPT records: $cpt_total\n";
echo "Total Database records: $db_total\n";

if ($cpt_total > 0 && $db_total == 0) {
    echo "\n🚨 PROBLEM IDENTIFIED: You're adding data to WordPress CPTs, but the system reads from database tables!\n";
    echo "SOLUTION: You need to either:\n";
    echo "1. Migrate your CPT data to database tables\n";
    echo "2. Add data directly to the new database tables\n";
    echo "3. Use the admin interfaces I created for the new system\n";
}

echo "\n5. RECOMMENDED ACTIONS:\n";
echo "----------------------\n";
echo "A. Migrate existing data: Go to Travel Management → Data Migration\n";
echo "B. Add new data: Use the forms in Travel Management → Packages/Car Types/Routes\n";
echo "C. Quick test: Visit populate-database.php?action=populate to add sample data\n";

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
?>