<?php
// Simulate the frontend display logic
require_once('wp-config.php');

echo "=== SIMULATING FRONTEND DISPLAY ===\n";

global $wpdb;

// Load products manager
require_once('wp-content/themes/Premium_Travels/includes/dynamic-management/products-manager.php');
$products_manager = new PT_Products_Manager();

// Get products from database
$products = $products_manager->get_all(array(
    'limit' => -1,
    'status' => 'active',
    'orderby' => 'sort_order',
    'order' => 'ASC'
));

echo "Found " . count($products) . " products to display:\n\n";

if (!empty($products)):
    foreach ($products as $product):
        $p_price = $product->price_regular ?: 'Contact';
        $p_city = '';
        if ($product->location_id) {
            $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d", $product->location_id));
            if ($location) {
                $p_city = $location->title;
            }
        }
        $p_city_simple = trim(explode(',', $p_city)[0]);
        $product_url = home_url('/product/' . $product->slug);
        
        echo "Product Item HTML would be:\n";
        echo "<div class='product-item tr-total' data-city='" . strtolower($p_city_simple) . "'>\n";
        echo "  <a href='" . $product_url . "'>\n";
        echo "    <h4>" . $product->title . "</h4>\n";
        echo "    <div><i class='fa fa-map-marker-alt'></i> " . $p_city . "</div>\n";
        echo "    <div>" . substr($product->short_description ?: $product->description, 0, 100) . "...</div>\n";
        echo "    <div><i class='fa fa-rupee-sign'></i> " . number_format($p_price, 2) . "</div>\n";
        echo "  </a>\n";
        echo "  <div><a href='/cab-booking' class='df-button1'>Buy Now</a></div>\n";
        echo "</div>\n";
        echo "---\n";
    endforeach;
else:
    echo "No products available.\n";
endif;

echo "\n=== DISPLAY SIMULATION COMPLETE ===\n";
?>