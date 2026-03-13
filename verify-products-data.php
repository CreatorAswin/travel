<?php
require_once('wp-config.php');

global $wpdb;

echo "=== CURRENT wp_pt_products TABLE CONTENT ===\n";

// Get all records
$records = $wpdb->get_results("SELECT * FROM wp_pt_products ORDER BY created_at DESC");

if($records) {
    echo "Total records: " . count($records) . "\n\n";
    
    foreach($records as $record) {
        echo "ID: {$record->id}\n";
        echo "Title: {$record->title}\n";
        echo "Slug: {$record->slug}\n";
        echo "Price Regular: {$record->price_regular}\n";
        echo "Price Sale: {$record->price_sale}\n";
        echo "Location ID: {$record->location_id}\n";
        echo "Product Type: {$record->product_type}\n";
        echo "SKU: {$record->sku}\n";
        echo "Stock: {$record->stock_quantity}\n";
        echo "Created: {$record->created_at}\n";
        echo "Updated: {$record->updated_at}\n";
        echo "---\n";
    }
} else {
    echo "No records found in the table.\n";
}

echo "\n=== WORDPRESS POSTS FOR COMPARISON ===\n";
$posts = $wpdb->get_results("SELECT ID, post_title, post_date FROM {$wpdb->posts} WHERE post_type = 'pt_product' AND post_status = 'publish' ORDER BY post_date DESC");
if($posts) {
    echo "Total WordPress posts: " . count($posts) . "\n\n";
    foreach($posts as $post) {
        echo "Post ID: {$post->ID}\n";
        echo "Title: {$post->post_title}\n";
        echo "Date: {$post->post_date}\n";
        
        // Check if this post exists in our database
        $db_record = $wpdb->get_var($wpdb->prepare("SELECT id FROM wp_pt_products WHERE title = %s", $post->post_title));
        echo "In database table: " . ($db_record ? "YES (ID: $db_record)" : "NO") . "\n";
        echo "---\n";
    }
}
?>