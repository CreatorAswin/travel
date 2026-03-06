<?php
require_once('wp-config.php');

// Check if the products table exists and has data
global $wpdb;

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}pt_products'");
if (!$table_exists) {
    echo "Table {$wpdb->prefix}pt_products does not exist!\n";
} else {
    echo "Table exists.\n";
    
    // Count products
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}pt_products");
    echo "Products count: $count\n";
    
    // Check if products manager class exists
    if (class_exists('PT_Products_Manager')) {
        echo "PT_Products_Manager class exists.\n";
    } else {
        echo "PT_Products_Manager class does NOT exist.\n";
        // Include the necessary files
        require_once('wp-content/themes/Premium_Travels/includes/dynamic-management/base-manager.php');
        require_once('wp-content/themes/Premium_Travels/includes/dynamic-management/products-manager.php');
        if (class_exists('PT_Products_Manager')) {
            echo "PT_Products_Manager class now exists after including files.\n";
        }
    }
}
?>