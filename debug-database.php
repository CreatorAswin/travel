<?php
// Debug database content
require_once('wp-config.php');

global $wpdb;

echo "=== DATABASE CONTENT DEBUG ===\n\n";

// Check if tables exist
$tables_to_check = [
    'pt_packages',
    'pt_car_types', 
    'pt_routes',
    'pt_products',
    'pt_bookings',
    'pt_locations'
];

echo "TABLE EXISTENCE CHECK:\n";
foreach($tables_to_check as $table) {
    $full_table = $wpdb->prefix . $table;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    echo "$table: " . ($exists ? "EXISTS" : "MISSING") . "\n";
}

echo "\nACTUAL TABLE CONTENTS:\n";
foreach($tables_to_check as $table) {
    $full_table = $wpdb->prefix . $table;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    if ($exists) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table");
        echo "$table: $count records\n";
        if ($count > 0) {
            $records = $wpdb->get_results("SELECT * FROM $full_table LIMIT 3");
            foreach($records as $record) {
                if (isset($record->title)) {
                    echo "  - {$record->title}\n";
                } elseif (isset($record->booking_reference)) {
                    echo "  - {$record->booking_reference}\n";
                }
            }
        }
    }
}

echo "\nWORDPRESS POSTS (CPT DATA):\n";
$cpts = ['location', 'taxi_package', 'route', 'car_type', 'holiday_package', 'pt_product'];
foreach($cpts as $cpt) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish'");
    echo "$cpt: $count posts\n";
}

echo "\nFRONTEND DISPLAY TEST:\n";
// Test if frontend functions are working
require_once('wp-content/themes/Premium_Travels/includes/frontend/dynamic-display.php');

// Test packages display
echo "Packages display function test:\n";
$output = pt_display_packages(array('limit' => 2));
if (strpos($output, 'No packages available') !== false) {
    echo "Showing: No packages available message\n";
} else {
    echo "Showing actual package data\n";
    // Show first 200 chars
    echo substr($output, 0, 200) . "...\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
?>