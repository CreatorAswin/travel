<?php
require_once('wp-config.php');

global $wpdb;

echo "=== ADDING MISSING COLUMNS TO wp_pt_products ===\n";

// Add missing columns one by one
$columns_to_add = [
    "ALTER TABLE wp_pt_products ADD COLUMN is_active TINYINT(1) DEFAULT 1",
    "ALTER TABLE wp_pt_products ADD COLUMN is_featured TINYINT(1) DEFAULT 0", 
    "ALTER TABLE wp_pt_products ADD COLUMN sort_order INT(11) DEFAULT 0",
    "ALTER TABLE wp_pt_products ADD COLUMN seo_title VARCHAR(255) NULL",
    "ALTER TABLE wp_pt_products ADD COLUMN seo_description TEXT NULL",
    "ALTER TABLE wp_pt_products ADD COLUMN seo_keywords TEXT NULL"
];

foreach($columns_to_add as $query) {
    echo "Executing: $query\n";
    $result = $wpdb->query($query);
    if($result === false) {
        echo "FAILED: " . $wpdb->last_error . "\n";
    } else {
        echo "SUCCESS\n";
    }
}

echo "\n=== TESTING INSERT ===\n";
// Test data that was failing
$test_data = array(
    'title' => 'Test Product Fix',
    'slug' => 'test-product-fix',
    'description' => 'Testing the fixed table structure',
    'product_type' => 'digital',
    'location_id' => 20,
    'price_regular' => 500,
    'price_sale' => 500,
    'sku' => 'TEST-FIX',
    'stock_quantity' => 10,
    'weight' => 5,
    'short_description' => 'Test description',
    'is_active' => 1,
    'created_at' => current_time('mysql'),
    'updated_at' => current_time('mysql'),
    'discount_percentage' => 0
);

$result = $wpdb->insert('wp_pt_products', $test_data);

if($result !== false) {
    echo "Test insert SUCCESSFUL! Inserted ID: " . $wpdb->insert_id . "\n";
    echo "Total records in table: " . $wpdb->get_var("SELECT COUNT(*) FROM wp_pt_products") . "\n";
} else {
    echo "Test insert FAILED: " . $wpdb->last_error . "\n";
}
?>