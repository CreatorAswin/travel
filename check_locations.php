<?php
include 'wp-load.php';
global $wpdb;
$res = $wpdb->get_results("SELECT id, title, is_active FROM {$wpdb->prefix}pt_locations");
echo json_encode($res, JSON_PRETTY_PRINT);
