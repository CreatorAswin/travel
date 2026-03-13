<?php
// Test the product detail page
require_once('wp-config.php');

echo "=== TESTING PRODUCT DETAIL PAGE ===\n";

// Simulate loading a product by slug
$product_slug = 'orange';
global $wpdb;

// Load products manager
require_once('wp-content/themes/Premium_Travels/includes/dynamic-management/products-manager.php');
$products_manager = new PT_Products_Manager();

$product = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}pt_products WHERE slug = %s AND is_active = 1",
    $product_slug
));

if ($product) {
    $product = $products_manager->format_record($product);
    
    echo "Product found: " . $product->title . "\n";
    echo "Price: ₹" . number_format($product->price_regular, 2) . "\n";
    echo "Sale Price: ₹" . ($product->price_sale ? number_format($product->price_sale, 2) : 'N/A') . "\n";
    echo "Location ID: " . $product->location_id . "\n";
    echo "Product Type: " . $product->product_type . "\n";
    echo "SKU: " . $product->sku . "\n";
    echo "Stock: " . $product->stock_quantity . "\n";
    
    if ($product->location_id) {
        $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d", $product->location_id));
        if ($location) {
            echo "Location: " . $location->title . "\n";
        }
    }
    
    echo "\nProduct detail URL: " . home_url('/product/' . $product->slug) . "\n";
    echo "This should now display properly on the frontend!\n";
} else {
    echo "Product not found!\n";
}
?>