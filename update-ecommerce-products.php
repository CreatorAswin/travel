<?php
/**
 * Update Products Table for E-commerce Style
 * Add e-commerce fields to the products table
 */

require_once('wp-config.php');

global $wpdb;

echo "Updating products table for e-commerce style...\n";

// First, add all the new e-commerce fields
$add_fields = [
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN sku VARCHAR(100) NULL AFTER title",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN short_description TEXT NULL AFTER description",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN regular_price DECIMAL(10,2) NULL AFTER short_description",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN sale_price DECIMAL(10,2) NULL AFTER regular_price",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN cost_price DECIMAL(10,2) NULL AFTER sale_price",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN weight DECIMAL(8,2) NULL AFTER max_persons",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN dimensions VARCHAR(100) NULL AFTER weight",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN stock_quantity INT DEFAULT 0 AFTER group_size_limit",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN stock_status ENUM('instock', 'outofstock', 'onbackorder') DEFAULT 'instock' AFTER stock_quantity",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN manage_stock BOOLEAN DEFAULT FALSE AFTER stock_status",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN product_condition ENUM('new', 'used', 'refurbished') DEFAULT 'new' AFTER product_type",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN brand VARCHAR(100) NULL AFTER product_condition",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN warranty_period VARCHAR(50) NULL AFTER brand"
];

foreach ($add_fields as $query) {
    echo "Executing: $query\n";
    $result = $wpdb->query($query);
    if ($result === false) {
        echo "Error: " . $wpdb->last_error . "\n";
    } else {
        echo "Success\n";
    }
}

// Now update the product_type enum to include e-commerce types
$wpdb->query("ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN product_type ENUM('tour', 'activity', 'experience', 'package', 'service', 'attraction', 'adventure', 'cultural', 'physical_product', 'digital_product', 'bundle', 'subscription') NOT NULL");

echo "\nVerifying updated structure:\n";
$columns = $wpdb->get_results("DESCRIBE {$wpdb->prefix}pt_products");

foreach($columns as $column) {
    echo "- {$column->Field}: {$column->Type} " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . " " . ($column->Key ? "KEY:{$column->Key}" : "") . "\n";
}

echo "\nE-commerce style products table updated successfully!";
?>