<?php
require_once('wp-config.php');

// Test the products display
require_once('wp-content/themes/Premium_Travels/includes/dynamic-management/products-manager.php');

echo "=== TESTING PRODUCTS DISPLAY ===\n";

$products_manager = new PT_Products_Manager();
$products = $products_manager->get_all(array(
    'limit' => 10,
    'status' => 'active'
));

echo "Found " . count($products) . " active products:\n\n";

if (!empty($products)) {
    foreach ($products as $product) {
        echo "Title: " . $product->title . "\n";
        echo "Price Regular: " . $product->price_regular . "\n";
        echo "Price Sale: " . $product->price_sale . "\n";
        echo "Location ID: " . $product->location_id . "\n";
        echo "Slug: " . $product->slug . "\n";
        echo "Short Description: " . substr($product->short_description, 0, 50) . "...\n";
        echo "---\n";
    }
} else {
    echo "No products found!\n";
}

// Test location lookup
echo "\n=== TESTING LOCATION LOOKUP ===\n";
global $wpdb;

if (!empty($products)) {
    $product = $products[0];
    if ($product->location_id) {
        $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d", $product->location_id));
        if ($location) {
            echo "Location found: " . $location->name . "\n";
        } else {
            echo "Location not found for ID: " . $product->location_id . "\n";
        }
    }
}

echo "\n=== TEST COMPLETE ===\n";
?>