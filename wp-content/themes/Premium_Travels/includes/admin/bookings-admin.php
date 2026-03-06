<?php
/**
 * Simple Bookings Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Bookings_Admin {
    public function display_page() {
        require_once get_template_directory() . '/includes/dynamic-management/bookings-manager.php';
        $manager = new PT_Bookings_Manager();
        
        $bookings = $manager->get_all(array('limit' => 50));
        
        ?>
        <div class="wrap">
            <h1>Bookings Management</h1>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Pickup Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo esc_html($booking->booking_reference); ?></td>
                                <td><?php echo esc_html($booking->customer_name); ?></td>
                                <td><?php echo esc_html($booking->service_type); ?></td>
                                <td><?php echo esc_html(date('M j, Y', strtotime($booking->pickup_date))); ?></td>
                                <td>
                                    <span class="status-<?php echo esc_attr($booking->status); ?>">
                                        <?php echo esc_html(ucfirst($booking->status)); ?>
                                    </span>
                                </td>
                                <td>₹<?php echo esc_html(number_format($booking->total_price, 2)); ?></td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=pt-bookings&action=view&id=' . $booking->id); ?>" class="button">View</a>
                                    <a href="<?php echo admin_url('admin.php?page=pt-bookings&action=edit&id=' . $booking->id); ?>" class="button">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <style>
                .status-pending { color: #FFA500; }
                .status-confirmed { color: #00a32a; }
                .status-cancelled { color: #dc3232; }
                .status-completed { color: #0073aa; }
            </style>
        </div>
        <?php
    }
}