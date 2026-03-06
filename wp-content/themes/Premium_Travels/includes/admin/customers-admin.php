<?php
/**
 * Simple Customers Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Customers_Admin {
    public function display_page() {
        // For now, show a simple message
        ?>
        <div class="wrap">
            <h1>Customers Management</h1>
            <div class="notice notice-info">
                <p>Customer management system is being implemented. This feature will allow you to manage customer profiles, booking history, and loyalty programs.</p>
            </div>
        </div>
        <?php
    }
}