<?php
/**
 * Clean Up Products Table Structure
 * Remove duplicate and old columns
 */

require_once('wp-config.php');

global $wpdb;

echo "Cleaning up products table structure...\n";

// Remove old duplicate columns
$cleanup_queries = [
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN price_per_person",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN discounted_price",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN regular_price",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN sale_price",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN cost_price",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN product_condition",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN brand",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN warranty_period",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN is_featured",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN is_active",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN discount_percentage DECIMAL(5,2) DEFAULT NULL",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN currency VARCHAR(3) DEFAULT 'INR'",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN stock_quantity INT DEFAULT 0",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN stock_status ENUM('instock', 'outofstock', 'onbackorder') DEFAULT 'instock'",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN shipping_required BOOLEAN DEFAULT TRUE",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN tax_status ENUM('taxable', 'shipping', 'none') DEFAULT 'taxable'",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN virtual BOOLEAN DEFAULT FALSE",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN downloadable BOOLEAN DEFAULT FALSE",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN featured BOOLEAN DEFAULT FALSE",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN catalog_visibility ENUM('visible', 'catalog', 'search', 'hidden') DEFAULT 'visible'",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN sold_individually BOOLEAN DEFAULT FALSE",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN allow_backorders ENUM('no', 'notify', 'yes') DEFAULT 'no'",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN manage_stock BOOLEAN DEFAULT FALSE",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN min_purchase_quantity INT DEFAULT 1",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN max_purchase_quantity INT DEFAULT 999"
];

$completed = 0;
$failed = 0;

foreach($cleanup_queries as $query) {
    echo "Executing: " . substr($query, 0, 80) . "...\n";
    $result = $wpdb->query($query);
    if($result === false) {
        echo "  Error: " . $wpdb->last_error . "\n";
        $failed++;
    } else {
        echo "  Success\n";
        $completed++;
    }
}

echo "\nCleanup completed: $completed, Failed: $failed\n";

// Verify the cleaned structure
$columns = $wpdb->get_results("DESCRIBE {$wpdb->prefix}pt_products");
echo "\nCleaned table structure:\n";
foreach($columns as $column) {
    echo "- {$column->Field}: {$column->Type} " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . " " . ($column->Key ? "KEY:{$column->Key}" : "") . "\n";
}

echo "\nProducts table cleanup completed!\n";
?>