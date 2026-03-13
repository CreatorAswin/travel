<?php
require_once('wp-load.php');
global $wpdb;
$product = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_products WHERE slug = 'orange'"));
$wp_post = get_page_by_path('orange', OBJECT, 'pt_product');
if (!$wp_post) {
    $wp_post = get_page_by_path('orange', OBJECT, 'post');
}
header('Content-Type: application/json');
echo json_encode([
    'product' => $product,
    'wp_post' => $wp_post ? [
        'ID' => $wp_post->ID,
        'post_title' => $wp_post->post_title,
        'post_type' => $wp_post->post_type,
        'has_thumbnail' => has_post_thumbnail($wp_post->ID),
        'thumbnail_url' => get_the_post_thumbnail_url($wp_post->ID, 'large')
    ] : null
], JSON_PRETTY_PRINT);
