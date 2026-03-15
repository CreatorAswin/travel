<?php
include 'wp-load.php';
global $wpdb;
$res = $wpdb->get_results("SELECT id, title, featured_image, gallery_images, location_id FROM {$wpdb->prefix}pt_products");
echo json_encode($res, JSON_PRETTY_PRINT);
