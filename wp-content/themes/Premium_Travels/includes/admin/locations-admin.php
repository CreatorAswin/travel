<?php
/**
 * Simple Locations Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Locations_Admin {
    public function display_page() {
        // For now, show a simple message
        ?>
        <div class="wrap">
            <h1>Locations Management</h1>
            <div class="notice notice-info">
                <p>Locations management system is being implemented. This feature will allow you to manage service locations, airports, and geographical data.</p>
            </div>
        </div>
        <?php
    }
}