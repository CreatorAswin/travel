<?php
require_once('wp-config.php');

global $wpdb;

echo "=== PRODUCTS TABLE CHECK ===\n\n";

// Check if table exists
$table = $wpdb->prefix . 'pt_products';
$exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");

echo "Table exists: " . ($exists ? "YES" : "NO") . "\n";

if ($exists) {
    // Check record count
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
    echo "Record count: $count\n\n";
    
    // Show table structure
    echo "Table structure:\n";
    $columns = $wpdb->get_results("DESCRIBE $table");
    foreach ($columns as $column) {
        echo "- {$column->Field}: {$column->Type} " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
    echo "\nRecent records:\n";
    $records = $wpdb->get_results("SELECT id, title, price_regular, price_sale, created_at FROM $table ORDER BY created_at DESC LIMIT 5");
    foreach ($records as $record) {
        echo "ID: {$record->id}, Title: {$record->title}, Price: {$record->price_regular}, Sale: {$record->price_sale}, Created: {$record->created_at}\n";
    }
} else {
    echo "Table does not exist. Creating it...\n";
    
    // Create the table
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table (
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
}

echo "\n=== WORDPRESS POSTS CHECK ===\n";
$post_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'pt_product' AND post_status = 'publish'");
echo "WordPress pt_product posts: $post_count\n";

if ($post_count > 0) {
    echo "Recent WordPress posts:\n";
    $posts = $wpdb->get_results("SELECT ID, post_title, post_date FROM {$wpdb->posts} WHERE post_type = 'pt_product' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 5");
    foreach ($posts as $post) {
        echo "ID: {$post->ID}, Title: {$post->post_title}, Date: {$post->post_date}\n";
    }
}
?>