<?php
/**
 * Update Product Type Enum
 * Update the product_type column to include new values
 */

require_once('wp-config.php');

global $wpdb;

echo "Updating product_type ENUM values...\n";

// Update the product_type column to include new values
$result = $wpdb->query("ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN product_type ENUM('tour', 'activity', 'experience', 'package', 'service', 'attraction', 'adventure', 'cultural') NOT NULL");

if ($result === false) {
    echo "Error updating product_type: " . $wpdb->last_error . "\n";
} else {
    echo "Product type column updated successfully!\n";
}

echo "Verification - Current structure:\n";
$columns = $wpdb->get_results("DESCRIBE {$wpdb->prefix}pt_products");

foreach($columns as $column) {
    if ($column->Field === 'product_type') {
        echo "- {$column->Field}: {$column->Type} " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
        break;
    }
}

echo "Done!";
?>