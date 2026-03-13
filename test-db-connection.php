<?php
// Test database table creation
require_once('wp-config.php');

global $wpdb;

$table_name = $wpdb->prefix . 'pt_products';

// Check if table exists
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    echo "Table doesn't exist. Creating it...\n";
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL,
        description LONGTEXT,
        short_description TEXT,
        product_type VARCHAR(50) DEFAULT 'physical',
        location_id BIGINT(20) UNSIGNED DEFAULT 0,
        price_regular DECIMAL(10,2) NOT NULL,
        price_sale DECIMAL(10,2) NULL,
        discount_percentage DECIMAL(5,2) NULL,
        currency VARCHAR(3) DEFAULT 'INR',
        sku VARCHAR(100) NULL,
        stock_quantity INT(11) DEFAULT 0,
        stock_status ENUM('instock', 'outofstock', 'onbackorder') DEFAULT 'instock',
        weight DECIMAL(8,2) NULL,
        dimensions VARCHAR(100) NULL,
        is_active TINYINT(1) DEFAULT 1,
        is_featured TINYINT(1) DEFAULT 0,
        sort_order INT(11) DEFAULT 0,
        seo_title VARCHAR(255) NULL,
        seo_description TEXT NULL,
        seo_keywords TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY slug (slug),
        KEY location_id (location_id),
        KEY is_active (is_active),
        KEY product_type (product_type)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    echo "Table created successfully!\n";
} else {
    echo "Table already exists.\n";
}

// Test insert
echo "\nTesting data insertion...\n";
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

$result = $wpdb->insert($table_name, $test_data);

if($result !== false) {
    echo "Test insert successful! Inserted ID: " . $wpdb->insert_id . "\n";
    
    // Verify the insert
    $verify = $wpdb->get_row("SELECT * FROM $table_name WHERE id = " . $wpdb->insert_id);
    if($verify) {
        echo "Verification successful:\n";
        echo "- Title: " . $verify->title . "\n";
        echo "- Price Regular: " . $verify->price_regular . "\n";
        echo "- Price Sale: " . $verify->price_sale . "\n";
    }
} else {
    echo "Test insert failed: " . $wpdb->last_error . "\n";
}

echo "\nCurrent table count: " . $wpdb->get_var("SELECT COUNT(*) FROM $table_name") . " records\n";
?>