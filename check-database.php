<?php
// Check database connection and tables
require_once('wp-config.php');
require_once('wp-includes/wp-db.php');

global $wpdb;

echo "=== Database Connection Check ===\n";
echo "Database Name: " . DB_NAME . "\n";
echo "Database Host: " . DB_HOST . "\n";
echo "Table Prefix: " . $wpdb->prefix . "\n\n";

echo "=== WordPress Tables ===\n";
$tables = $wpdb->get_results("SHOW TABLES LIKE '{$wpdb->prefix}%'", ARRAY_N);
foreach($tables as $table) {
    echo "- " . $table[0] . "\n";
}

echo "\n=== Custom Tables Check ===\n";
$custom_tables = [
    $wpdb->prefix . 'pt_bookings',
    $wpdb->prefix . 'pt_enquiries'
];

foreach($custom_tables as $table) {
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");
    echo ($exists ? "✓ " : "✗ ") . $table . " - " . ($exists ? "EXISTS" : "MISSING") . "\n";
}

echo "\n=== Custom Post Types Count ===\n";
$cpts = ['location', 'taxi_package', 'route', 'testimonial', 'car_type', 'holiday_package', 'special_offer', 'city_service', 'pt_product'];
foreach($cpts as $cpt) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish'");
    echo "$cpt: $count posts\n";
}

echo "\n=== Database Structure Analysis Complete ===\n";
?>