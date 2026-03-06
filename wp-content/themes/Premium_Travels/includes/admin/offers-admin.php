<?php
/**
 * Simple Offers Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Offers_Admin {
    public function display_page() {
        // For now, show a simple message
        ?>
        <div class="wrap">
            <h1>Special Offers Management</h1>
            <div class="notice notice-info">
                <p>Special Offers management system is being implemented. This feature will allow you to create and manage discount coupons, promotional offers, and special deals for your customers.</p>
            </div>
        </div>
        <?php
    }
}