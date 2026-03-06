<?php
/**
 * Update Products Table to E-commerce Structure
 * Add e-commerce fields and modify existing ones
 */

require_once('wp-config.php');

global $wpdb;

echo "Updating products table to e-commerce structure...\n";

// First, let's add the new columns for e-commerce functionality
$alter_queries = [
    // Rename price_per_person to price_regular and add new pricing fields
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN price_regular DECIMAL(10,2) DEFAULT 0.00 AFTER description",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN price_sale DECIMAL(10,2) DEFAULT NULL AFTER price_regular",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN sale_start_date DATE DEFAULT NULL AFTER price_sale",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN sale_end_date DATE DEFAULT NULL AFTER sale_start_date",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN currency VARCHAR(3) DEFAULT 'INR' AFTER discount_percentage",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN sku VARCHAR(100) DEFAULT NULL AFTER currency",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN stock_quantity INT DEFAULT 0 AFTER sku",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN stock_status ENUM('instock', 'outofstock', 'onbackorder') DEFAULT 'instock' AFTER stock_quantity",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN weight DECIMAL(8,2) DEFAULT NULL AFTER stock_status",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN dimensions VARCHAR(50) DEFAULT NULL AFTER weight",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN shipping_class VARCHAR(50) DEFAULT NULL AFTER dimensions",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN shipping_required BOOLEAN DEFAULT TRUE AFTER shipping_class",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN tax_status ENUM('taxable', 'shipping', 'none') DEFAULT 'taxable' AFTER shipping_required",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN tax_class VARCHAR(50) DEFAULT NULL AFTER tax_status",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN virtual BOOLEAN DEFAULT FALSE AFTER tax_class",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN downloadable BOOLEAN DEFAULT FALSE AFTER virtual",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN download_limit INT DEFAULT 0 AFTER downloadable",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN download_expiry INT DEFAULT 0 AFTER download_limit",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN purchase_note TEXT AFTER download_expiry",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN featured BOOLEAN DEFAULT FALSE AFTER purchase_note",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN catalog_visibility ENUM('visible', 'catalog', 'search', 'hidden') DEFAULT 'visible' AFTER featured",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN short_description TEXT AFTER gallery_images",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN meta_description TEXT AFTER short_description",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN tags TEXT AFTER meta_description",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN categories TEXT AFTER tags",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN related_products TEXT AFTER categories",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN upsell_ids TEXT AFTER related_products",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN cross_sell_ids TEXT AFTER upsell_ids",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN total_sales INT DEFAULT 0 AFTER cross_sell_ids",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN average_rating DECIMAL(3,2) DEFAULT 0.00 AFTER total_sales",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN rating_count TEXT AFTER review_count",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN shipping_width DECIMAL(8,2) DEFAULT NULL AFTER rating_count",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN shipping_height DECIMAL(8,2) DEFAULT NULL AFTER shipping_width",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN shipping_length DECIMAL(8,2) DEFAULT NULL AFTER shipping_height",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN shipping_weight DECIMAL(8,2) DEFAULT NULL AFTER shipping_length",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN shipping_from_location VARCHAR(100) DEFAULT NULL AFTER shipping_weight",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN shipping_to_locations TEXT AFTER shipping_from_location",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN min_purchase_quantity INT DEFAULT 1 AFTER shipping_to_locations",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN max_purchase_quantity INT DEFAULT 999 AFTER min_purchase_quantity",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN sold_individually BOOLEAN DEFAULT FALSE AFTER max_purchase_quantity",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN allow_backorders ENUM('no', 'notify', 'yes') DEFAULT 'no' AFTER sold_individually",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN manage_stock BOOLEAN DEFAULT FALSE AFTER allow_backorders",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN low_stock_threshold INT DEFAULT 5 AFTER manage_stock",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN sold_dates TEXT AFTER low_stock_threshold",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN popularity_score DECIMAL(5,2) DEFAULT 0.00 AFTER sold_dates",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD COLUMN trending_score DECIMAL(5,2) DEFAULT 0.00 AFTER popularity_score",
    
    // Update product_type to include e-commerce types
    "ALTER TABLE {$wpdb->prefix}pt_products MODIFY COLUMN product_type ENUM('tour', 'activity', 'experience', 'package', 'service', 'attraction', 'adventure', 'cultural', 'physical_product', 'digital_product', 'subscription') NOT NULL",
    
    // Update indexes
    "ALTER TABLE {$wpdb->prefix}pt_products ADD INDEX idx_price_regular (price_regular)",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD INDEX idx_price_sale (price_sale)",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD INDEX idx_sku (sku)",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD INDEX idx_stock_status (stock_status)",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD INDEX idx_total_sales (total_sales)",
    "ALTER TABLE {$wpdb->prefix}pt_products ADD INDEX idx_average_rating (average_rating)"
];

$completed = 0;
$failed = 0;

foreach($alter_queries as $query) {
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

echo "\nCompleted: $completed, Failed: $failed\n";

// Now migrate existing data to new structure
echo "\nMigrating existing data...\n";

$products = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pt_products");

foreach($products as $product) {
    // Migrate price_per_person to price_regular
    $price_regular = $product->price_per_person;
    $discounted_price = $product->discounted_price;
    
    if ($discounted_price && $price_regular > 0) {
        $discount_percent = (($price_regular - $discounted_price) / $price_regular) * 100;
        $price_sale = $discounted_price;
    } else {
        $discount_percent = $product->discount_percentage;
        $price_sale = null;
    }
    
    $update_query = $wpdb->prepare(
        "UPDATE {$wpdb->prefix}pt_products 
         SET price_regular = %f, 
             price_sale = %f,
             discount_percentage = %f,
             short_description = %s
         WHERE id = %d",
        $price_regular,
        $price_sale,
        $discount_percent,
        wp_trim_words($product->description, 20),
        $product->id
    );
    
    $result = $wpdb->query($update_query);
    if ($result !== false) {
        echo "  Updated product {$product->id}: {$product->title}\n";
    } else {
        echo "  Error updating product {$product->id}: " . $wpdb->last_error . "\n";
    }
}

echo "\nE-commerce structure update completed!\n";

// Verify the updated structure
$columns = $wpdb->get_results("DESCRIBE {$wpdb->prefix}pt_products");
echo "\nNew table structure:\n";
foreach($columns as $column) {
    echo "- {$column->Field}: {$column->Type} " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . " " . ($column->Key ? "KEY:{$column->Key}" : "") . "\n";
}

?>