<?php
/**
 * Update Products Table Structure
 * Manually alter the existing table to match the new structure
 */

require_once('wp-config.php');

global $wpdb;

echo "Updating pt_products table structure...\n";

// Add new columns
$alter_queries = [
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN discounted_price DECIMAL(10,2) NULL AFTER price_per_person",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN discount_percentage DECIMAL(5,2) NULL AFTER discounted_price",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN review_count INT DEFAULT 0 AFTER rating",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN travel_suggestions TEXT NULL AFTER cancellation_policy",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN available_seasons TEXT NULL AFTER travel_suggestions",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN age_requirements TEXT NULL AFTER available_seasons",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN difficulty_level VARCHAR(20) DEFAULT 'easy' AFTER age_requirements",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN physical_requirements TEXT NULL AFTER difficulty_level",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN what_to_expect TEXT NULL AFTER physical_requirements",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN what_to_bring TEXT NULL AFTER what_to_expect",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN safety_measures TEXT NULL AFTER what_to_bring",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN guide_language TEXT NULL AFTER safety_measures",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN group_size_limit INT DEFAULT 15 AFTER guide_language",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN seo_title VARCHAR(255) NULL AFTER gallery_images",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN seo_description TEXT NULL AFTER seo_title",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN meta_keywords TEXT NULL AFTER seo_description",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN is_available BOOLEAN DEFAULT TRUE AFTER meta_keywords",
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN is_active BOOLEAN DEFAULT TRUE",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN child_price",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN infant_price",
    "ALTER TABLE {$wpdb->prefix}pt_products DROP COLUMN total_reviews",
    "ALTER TABLE {$wpdb->prefix}pt_products CHANGE COLUMN is_active is_available BOOLEAN DEFAULT TRUE",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN is_featured BOOLEAN DEFAULT FALSE AFTER meta_keywords",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN sort_order INT DEFAULT 0 AFTER is_featured"
];

foreach($alter_queries as $query) {
    echo "Executing: $query\n";
    $result = $wpdb->query($query);
    if($result === false) {
        echo "Error: " . $wpdb->last_error . "\n";
    } else {
        echo "Success\n";
    }
}

echo "\nVerifying updated structure:\n";

$columns = $wpdb->get_results("DESCRIBE {$wpdb->prefix}pt_products");

foreach($columns as $column) {
    echo "- {$column->Field}: {$column->Type} " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . " " . ($column->Key ? "KEY:{$column->Key}" : "") . "\n";
}

echo "\nProducts table structure updated successfully!";
?>