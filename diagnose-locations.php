<?php
require_once('wp-config.php');

echo "=== PRODUCT LOCATION DIAGNOSTIC ===\n\n";

global $wpdb;

// Get all products and their location info
$products = $wpdb->get_results("SELECT id, title, location_id, slug FROM {$wpdb->prefix}pt_products WHERE is_active = 1");

echo "ACTIVE PRODUCTS AND THEIR LOCATIONS:\n";
echo str_repeat("-", 50) . "\n";

foreach($products as $product) {
    echo "Product: " . $product->title . " (ID: " . $product->id . ")\n";
    echo "Location ID: " . $product->location_id . "\n";
    
    if ($product->location_id) {
        // Check if location exists and is active
        $location = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d", 
            $product->location_id
        ));
        
        if ($location) {
            echo "Location Found: " . $location->title . " (ID: " . $location->id . ")\n";
            echo "Location Status: " . ($location->is_active ? 'ACTIVE' : 'INACTIVE') . "\n";
        } else {
            echo "Location NOT FOUND in database!\n";
        }
        
        // Check if there's an active location with this ID
        $active_location = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d AND is_active = 1", 
            $product->location_id
        ));
        
        if ($active_location) {
            echo "Active Location: " . $active_location->title . "\n";
        } else {
            echo "No ACTIVE location found with this ID\n";
        }
    } else {
        echo "No location assigned\n";
    }
    
    echo "Product URL: " . home_url('/product/' . $product->slug) . "\n";
    echo str_repeat("-", 30) . "\n";
}

echo "\n=== ALL ACTIVE LOCATIONS IN DATABASE ===\n";
echo str_repeat("-", 40) . "\n";

$all_locations = $wpdb->get_results("SELECT id, title, is_active FROM {$wpdb->prefix}pt_locations ORDER BY id");

foreach($all_locations as $loc) {
    echo "ID: " . $loc->id . " | Name: " . $loc->title . " | Status: " . ($loc->is_active ? 'ACTIVE' : 'INACTIVE') . "\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
?>