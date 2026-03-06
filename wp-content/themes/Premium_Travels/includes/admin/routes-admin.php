<?php
/**
 * Simple Routes Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Routes_Admin {
    public function display_page() {
        require_once get_template_directory() . '/includes/dynamic-management/routes-manager.php';
        $manager = new PT_Routes_Manager();
        
        $routes = $manager->get_all(array('limit' => 50));
        
        ?>
        <div class="wrap">
            <h1>Routes Management</h1>
            <div class="tablenav top">
                <div class="alignleft actions">
                    <a href="<?php echo admin_url('admin.php?page=pt-routes&action=add'); ?>" class="button-primary">Add New Route</a>
                </div>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Distance</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($routes): ?>
                        <?php foreach ($routes as $route): ?>
                            <tr>
                                <td><?php echo esc_html($route->id); ?></td>
                                <td><?php echo esc_html($route->title); ?></td>
                                <td><?php echo esc_html($route->from_location_name ?: 'N/A'); ?></td>
                                <td><?php echo esc_html($route->to_location_name ?: 'N/A'); ?></td>
                                <td><?php echo esc_html($route->distance_km); ?> km</td>
                                <td>₹<?php echo esc_html(number_format($route->base_price, 2)); ?></td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=pt-routes&action=edit&id=' . $route->id); ?>" class="button">Edit</a>
                                    <a href="<?php echo admin_url('admin.php?page=pt-routes&action=delete&id=' . $route->id); ?>" class="button" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No routes found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}