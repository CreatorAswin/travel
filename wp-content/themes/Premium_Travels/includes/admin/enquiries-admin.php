<?php
/**
 * Simple Enquiries Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Enquiries_Admin {
    public function display_page() {
        // For now, show a simple message
        ?>
        <div class="wrap">
            <h1>Enquiries Management</h1>
            <div class="notice notice-info">
                <p>Enquiries management system is being implemented. This feature will allow you to manage customer inquiries and support requests.</p>
            </div>
        </div>
        <?php
    }
}