<?php
require_once('wp-config.php');

global $wpdb;

echo "=== CURRENT TABLE STRUCTURE ===\n";
$columns = $wpdb->get_results("DESCRIBE wp_pt_products");
foreach($columns as $col) {
    echo $col->Field . " " . $col->Type . " " . $col->Null . " " . $col->Key . "\n";
}

echo "\n=== UPDATING TABLE STRUCTURE ===\n";

// Add missing columns
$alter_queries = [
    "ALTER TABLE wp_pt_products ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER sort_order",
    "ALTER TABLE wp_pt_products ADD COLUMN is_featured TINYINT(1) DEFAULT 0 AFTER is_active",
    "ALTER TABLE wp_pt_products ADD COLUMN sort_order INT(11) DEFAULT 0 AFTER is_featured",
    "ALTER TABLE wp_pt_products ADD COLUMN seo_title VARCHAR(255) NULL AFTER sort_order",
    "ALTER TABLE wp_pt_products ADD COLUMN seo_description TEXT NULL AFTER seo_title",
    "ALTER TABLE wp_pt_products ADD COLUMN seo_keywords TEXT NULL AFTER seo_description"
];

foreach($alter_queries as $query) {
    $result = $wpdb->query($query);
    if($result === false) {
        echo "Failed: " . $wpdb->last_error . "\n";
    } else {
        echo "Success: " . $query . "\n";
    }
}

echo "\n=== NEW TABLE STRUCTURE ===\n";
$columns = $wpdb->get_results("DESCRIBE wp_pt_products");
foreach($columns as $col) {
    echo $col->Field . " " . $col->Type . " " . $col->Null . " " . $col->Key . "\n";
}

echo "\n=== TESTING INSERT ===\n";
$test_data = array(
    'title' => 'Test Product ' . time(),
    'slug' => 'test-product-' . time(),
    'description' => 'This is a test product',
    'product_type' => 'physical',
    'location_id' => 1,
    'price_regular' => 100.00,
    'price_sale' => 80.00,
    'discount_percentage' => 20.00,
    'sku' => 'TEST-' . time(),
    'stock_quantity' => 10,
    'is_active' => 1,
    'created_at' => current_time('mysql'),
    'updated_at' => current_time('mysql')
);

$result = $wpdb->insert('wp_pt_products', $test_data);

if($result !== false) {
    echo "Test insert successful! Inserted ID: " . $wpdb->insert_id . "\n";
    echo "Current table count: " . $wpdb->get_var("SELECT COUNT(*) FROM wp_pt_products") . " records\n";
} else {
    echo "Test insert failed: " . $wpdb->last_error . "\n";
}
?>