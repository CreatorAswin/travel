<?php
require_once('wp-load.php');
require_once get_template_directory() . '/includes/dynamic-management/products-manager.php';
$products_manager = new PT_Products_Manager();
$products = $products_manager->get_all(array('limit' => 20, 'status' => 'active'));

echo "ID | Title | Location Name | Data City (Simple)\n";
echo str_repeat("-", 80) . "\n";

foreach ($products as $product) {
    $p_city = $product->location_name ?? '';
    $p_city_simple = $p_city ? trim(explode(',', $p_city)[0]) : '';
    $data_city = esc_attr(strtolower($p_city_simple));
    
    echo "{$product->id} | {$product->title} | " . ($p_city ?: 'NULL') . " | {$data_city}\n";
}
