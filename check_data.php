<?php
require_once('wp-load.php');
global $wpdb;

$products = $wpdb->get_results("
    SELECT p.id, p.title, p.location_id, l.title as location_name 
    FROM {$wpdb->prefix}pt_products p 
    LEFT JOIN {$wpdb->prefix}pt_locations l ON p.location_id = l.id 
    LIMIT 20
");

echo "ID | Title | Location ID | Location Name\n";
echo str_repeat("-", 60) . "\n";
foreach ($products as $p) {
    echo "{$p->id} | {$p->title} | " . ($p->location_id ?? 'NULL') . " | " . ($p->location_name ?? 'NULL') . "\n";
}

$locations = $wpdb->get_results("SELECT id, title FROM {$wpdb->prefix}pt_locations");
echo "\nAvailable Locations:\n";
foreach ($locations as $l) {
    echo "{$l->id} | {$l->title}\n";
}
