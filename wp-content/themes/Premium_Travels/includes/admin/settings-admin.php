<?php
/**
 * Simple Settings Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Settings_Admin {
    public function display_page() {
        // For now, show a simple message
        ?>
        <div class="wrap">
            <h1>Travel Management Settings</h1>
            <div class="notice notice-info">
                <p>Settings management system is being implemented. This will include configuration options for pricing, notifications, payment gateways, and system preferences.</p>
            </div>
        </div>
        <?php
    }
}