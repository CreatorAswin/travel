<?php
/**
 * Update Database Tables
 * Run this script to update the database structure
 */

require_once('wp-config.php');

// Include the database tables file
require_once('wp-content/themes/Premium_Travels/includes/database-tables.php');

// Run the table creation/update function
premium_travels_create_tables();

echo "Database tables updated successfully!\n";

// Verify the products table structure
global $wpdb;

$columns = $wpdb->get_results("DESCRIBE {$wpdb->prefix}pt_products");

echo "\nCurrent pt_products table structure:\n";
foreach($columns as $column) {
    echo "- {$column->Field}: {$column->Type} " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . " " . ($column->Key ? "KEY:{$column->Key}" : "") . "\n";
}

echo "\nDatabase update completed!";
?>