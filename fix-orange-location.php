<?php
require_once('wp-config.php');

global $wpdb;

echo "=== AVAILABLE LOCATIONS ===\n";
$locations = $wpdb->get_results("SELECT id, title FROM wp_pt_locations WHERE is_active = 1");
foreach($locations as $loc) {
    echo "ID: " . $loc->id . ", Name: " . $loc->title . "\n";
}

echo "\n=== ORANGE PRODUCT LOCATION ===\n";
$orange_product = $wpdb->get_row("SELECT * FROM wp_pt_products WHERE title = 'Orange'");
if ($orange_product) {
    echo "Orange product location_id: " . $orange_product->location_id . "\n";
    
    // Check if this location exists
    $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_pt_locations WHERE id = %d", $orange_product->location_id));
    if ($location) {
        echo "Location exists: " . $location->title . "\n";
    } else {
        echo "Location does not exist. Updating to valid location...\n";
        // Update to a valid location (New Delhi = ID 1)
        $wpdb->update(
            'wp_pt_products',
            array('location_id' => 1),
            array('id' => $orange_product->id)
        );
        echo "Updated to New Delhi (ID 1)\n";
    }
}
?>