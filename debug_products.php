<?php
include 'wp-load.php';
global $wpdb;
require_once get_template_directory() . '/includes/dynamic-management/products-manager.php';
$products_manager = new PT_Products_Manager();
$products = $products_manager->get_all(array('limit' => 5, 'status' => 'active'));

foreach ($products as $product) {
    echo "PRODUCT ID: " . (isset($product->id) ? $product->id : 'NO ID property') . "\n";
    echo "PRODUCT DATA TYPE: " . gettype($product) . "\n";
    if (is_array($product)) {
        echo "FEATURED IMAGE (array): " . ($product['featured_image'] ?? 'NULL') . "\n";
    } else {
        echo "FEATURED IMAGE (object): " . ($product->featured_image ?? 'NULL') . "\n";
    }
    echo "-------------------\n";
}
