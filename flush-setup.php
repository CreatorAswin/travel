<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');

// 1. Reset setup status to trigger premium_travels_create_pages_and_menus again
delete_option('premium_travels_setup_complete');

// 2. Clear out old front page settings to ensure clean re-assignment if needed
// delete_option('page_on_front'); // Optional, but usually good to keep

// 3. Flush rewrite rules
flush_rewrite_rules(true);

echo "Setup status reset and rewrite rules flushed successfully.";
?>
