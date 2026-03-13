<?php
require_once('wp-config.php');

echo "=== FLUSHING REWRITE RULES ===\n";

// Flush rewrite rules
flush_rewrite_rules();

echo "Rewrite rules flushed successfully!\n";

// Test if the product URL works
echo "\n=== TESTING PRODUCT URLS ===\n";

global $wpdb;
$products = $wpdb->get_results("SELECT slug, title FROM {$wpdb->prefix}pt_products WHERE is_active = 1 LIMIT 3");

foreach($products as $product) {
    $url = home_url('/product/' . $product->slug);
    echo "Product: " . $product->title . "\n";
    echo "URL: " . $url . "\n";
    echo "---\n";
}

echo "\nYou can now access products using URLs like:\n";
echo "http://localhost/travel/product/orange\n";
echo "http://localhost/travel/product/lingaraj-temple-darshan\n";
?>