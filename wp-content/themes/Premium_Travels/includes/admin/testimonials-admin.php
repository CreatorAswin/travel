<?php
/**
 * Simple Testimonials Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Testimonials_Admin {
    public function display_page() {
        // For now, show a simple message
        ?>
        <div class="wrap">
            <h1>Testimonials Management</h1>
            <div class="notice notice-info">
                <p>Testimonials management system is being implemented. This feature will allow you to collect, manage, and display customer reviews and ratings.</p>
            </div>
        </div>
        <?php
    }
}