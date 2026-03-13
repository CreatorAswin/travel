<?php
require_once('wp-config.php');

global $wpdb;

echo "=== CHECKING INACTIVE LOCATIONS ===\n";

$inactive = $wpdb->get_results("SELECT id, title, is_active FROM {$wpdb->prefix}pt_locations WHERE is_active = 0");

if($inactive) {
    echo "INACTIVE LOCATIONS:\n";
    foreach($inactive as $loc) {
        echo "ID: " . $loc->id . " | Name: " . $loc->title . "\n";
    }
} else {
    echo "No inactive locations found.\n";
}

echo "\n=== CHECKING ORANGE PRODUCT DATA ===\n";

$orange = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pt_products WHERE title = 'Orange'");
if($orange) {
    echo "Orange Product Data:\n";
    echo "ID: " . $orange->id . "\n";
    echo "Title: " . $orange->title . "\n";
    echo "Location ID: " . $orange->location_id . "\n";
    echo "Price Regular: " . $orange->price_regular . "\n";
    echo "Price Sale: " . $orange->price_sale . "\n";
    echo "Updated At: " . $orange->updated_at . "\n";
    
    // Check the location
    $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d", $orange->location_id));
    if($location) {
        echo "Location: " . $location->title . " (ID: " . $location->id . ", Active: " . ($location->is_active ? 'Yes' : 'No') . ")\n";
    } else {
        echo "Location not found!\n";
    }
} else {
    echo "Orange product not found in database!\n";
}
?>