<?php
require_once('wp-load.php');
ob_start();
include(get_template_directory() . '/front-page.php');
$content = ob_get_clean();

// Extract the city filter section
preg_match('/<div class="form-group pt-city-filter-wrap".*?<\/div>/s', $content, $filter_matches);
preg_match('/<div class="products-list-container".*?<!-- Client-side filter script -->/s', $content, $list_matches);

echo "--- FILTER HTML ---\n";
echo ($filter_matches[0] ?? 'NOT FOUND') . "\n\n";
echo "--- PRODUCT CARD HTML (First one) ---\n";
if (preg_match('/<div class="pt-sidebar-product-card.*?<\/div>/s', $list_matches[0] ?? '', $card_matches)) {
    echo $card_matches[0] . "\n";
} else {
    echo "NO CARDS FOUND\n";
}

echo "\n--- JS SCRIPT ---\n";
preg_match('/<script>.*?jQuery\(document\).ready.*?<\/script>/s', $content, $script_matches);
echo ($script_matches[0] ?? 'NOT FOUND') . "\n";
