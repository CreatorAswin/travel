<?php
require_once('wp-config.php');

global $wpdb;

echo "=== LOCATION TABLE STRUCTURE ===\n";
$columns = $wpdb->get_results("DESCRIBE wp_pt_locations");
foreach($columns as $col) {
    echo $col->Field . " " . $col->Type . "\n";
}

echo "\n=== SAMPLE LOCATIONS ===\n";
$locations = $wpdb->get_results("SELECT * FROM wp_pt_locations LIMIT 5");
foreach($locations as $loc) {
    echo "ID: " . $loc->id . ", Name: " . (isset($loc->name) ? $loc->name : $loc->title) . "\n";
}
?>