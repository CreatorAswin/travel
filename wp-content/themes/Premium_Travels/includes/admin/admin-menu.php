<?php
/**
 * Admin Menu System
 * Creates custom admin menu for travel management
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Admin_Menu {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menus'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu pages
     */
    public function add_admin_menus() {
        // Main menu
        add_menu_page(
            'Travel Management',
            'Travel Management',
            'manage_options',
            'pt-travel-management',
            array($this, 'dashboard_page'),
            'dashicons-airplane',
            30
        );
        
        // Dashboard
        add_submenu_page(
            'pt-travel-management',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'pt-travel-management',
            array($this, 'dashboard_page')
        );
        
        // Packages
        add_submenu_page(
            'pt-travel-management',
            'Packages',
            'Packages',
            'manage_options',
            'pt-packages',
            array($this, 'packages_page')
        );
        
        // Car Types
        add_submenu_page(
            'pt-travel-management',
            'Car Types',
            'Car Types',
            'manage_options',
            'pt-car-types',
            array($this, 'car_types_page')
        );
        
        // Routes
        add_submenu_page(
            'pt-travel-management',
            'Routes',
            'Routes',
            'manage_options',
            'pt-routes',
            array($this, 'routes_page')
        );
        
        // Products/Tours
        add_submenu_page(
            'pt-travel-management',
            'Products & Tours',
            'Products & Tours',
            'manage_options',
            'pt-products',
            array($this, 'products_page')
        );
        
        // Simplified Products (Essential Fields Only)
        add_submenu_page(
            'pt-travel-management',
            'Products - Simple',
            'Products - Simple',
            'manage_options',
            'pt-products-simple',
            array($this, 'products_simple_page')
        );
        
        // Special Offers
        add_submenu_page(
            'pt-travel-management',
            'Special Offers',
            'Special Offers',
            'manage_options',
            'pt-offers',
            array($this, 'offers_page')
        );
        
        // Testimonials
        add_submenu_page(
            'pt-travel-management',
            'Testimonials',
            'Testimonials',
            'manage_options',
            'pt-testimonials',
            array($this, 'testimonials_page')
        );
        
        // Enquiries
        add_submenu_page(
            'pt-travel-management',
            'Enquiries',
            'Enquiries',
            'manage_options',
            'pt-enquiries',
            array($this, 'enquiries_page')
        );
        
        // Customers
        add_submenu_page(
            'pt-travel-management',
            'Customers',
            'Customers',
            'manage_options',
            'pt-customers',
            array($this, 'customers_page')
        );
        
        // Data Migration (Removed - no longer needed)
        // add_submenu_page(
        //     'pt-travel-management',
        //     'Data Migration',
        //     'Data Migration',
        //     'manage_options',
        //     'pt-migration',
        //     array($this, 'migration_page')
        // );
    }
    
    /**
     * Dashboard page
     */
    public function dashboard_page() {
        // Load managers
        require_once get_template_directory() . '/includes/dynamic-management/packages-manager.php';
        require_once get_template_directory() . '/includes/dynamic-management/car-types-manager.php';
        require_once get_template_directory() . '/includes/dynamic-management/bookings-manager.php';
        
        $packages_manager = new PT_Packages_Manager();
        $car_types_manager = new PT_Car_Types_Manager();
        $bookings_manager = new PT_Bookings_Manager();
        
        // Get REAL statistics from database
        global $wpdb;
        
        // Package statistics
        $package_stats = $wpdb->get_row("
            SELECT 
                COUNT(*) as total_packages,
                COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_packages,
                COUNT(CASE WHEN is_featured = 1 AND is_active = 1 THEN 1 END) as featured_packages,
                AVG(rating) as average_rating
            FROM {$wpdb->prefix}pt_packages
        ");
        
        // Car types statistics
        $car_stats = $wpdb->get_row("
            SELECT 
                COUNT(*) as total_car_types,
                COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_car_types,
                COUNT(CASE WHEN availability_status = 'available' AND is_active = 1 THEN 1 END) as available_car_types,
                COUNT(CASE WHEN is_featured = 1 AND is_active = 1 THEN 1 END) as featured_car_types,
                AVG(rating) as average_rating
            FROM {$wpdb->prefix}pt_car_types
        ");
        
        // Booking statistics
        $booking_stats = $wpdb->get_row("
            SELECT 
                COUNT(*) as total_bookings,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_bookings,
                COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed_bookings,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_bookings,
                COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_bookings,
                SUM(CASE WHEN status = 'completed' THEN total_price ELSE 0 END) as total_revenue
            FROM {$wpdb->prefix}pt_bookings
        ");
        
        // Get REAL recent bookings
        $recent_bookings = $wpdb->get_results("
            SELECT 
                booking_reference,
                customer_name,
                service_type,
                status,
                total_price,
                created_at
            FROM {$wpdb->prefix}pt_bookings 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        
        // Get REAL popular packages
        $popular_packages = $wpdb->get_results("
            SELECT 
                title,
                base_price,
                current_bookings,
                rating
            FROM {$wpdb->prefix}pt_packages 
            WHERE is_active = 1 
            ORDER BY current_bookings DESC, rating DESC 
            LIMIT 5
        ");
        
        ?>
        <div class="wrap">
            <h1>Travel Management Dashboard</h1>
            
            <div class="dashboard-widgets">
                <!-- Real Statistics -->
                <div class="postbox">
                    <h2 class="hndle">Real-Time Statistics</h2>
                    <div class="inside">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <h3><?php echo $package_stats ? $package_stats->active_packages : 0; ?></h3>
                                <p>Active Packages</p>
                            </div>
                            <div class="stat-card">
                                <h3><?php echo $car_stats ? $car_stats->available_car_types : 0; ?></h3>
                                <p>Available Car Types</p>
                            </div>
                            <div class="stat-card">
                                <h3><?php echo $booking_stats ? $booking_stats->total_bookings : 0; ?></h3>
                                <p>Total Bookings</p>
                            </div>
                            <div class="stat-card">
                                <h3>₹<?php echo $booking_stats ? number_format($booking_stats->total_revenue, 2) : '0.00'; ?></h3>
                                <p>Total Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Real Recent Bookings -->
                <div class="postbox">
                    <h2 class="hndle">Recent Bookings</h2>
                    <div class="inside">
                        <?php if ($recent_bookings): ?>
                            <table class="wp-list-table widefat fixed striped">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_bookings as $booking): ?>
                                        <tr>
                                            <td><?php echo esc_html($booking->booking_reference); ?></td>
                                            <td><?php echo esc_html($booking->customer_name); ?></td>
                                            <td><?php echo esc_html($booking->service_type); ?></td>
                                            <td>
                                                <span class="status-<?php echo esc_attr($booking->status); ?>">
                                                    <?php echo esc_html(ucfirst($booking->status)); ?>
                                                </span>
                                            </td>
                                            <td>₹<?php echo esc_html(number_format($booking->total_price, 2)); ?></td>
                                            <td><?php echo esc_html(date('M j, Y', strtotime($booking->created_at))); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No bookings found in database. Add bookings through the booking system.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Real Popular Packages -->
                <div class="postbox">
                    <h2 class="hndle">Popular Packages</h2>
                    <div class="inside">
                        <?php if ($popular_packages): ?>
                            <table class="wp-list-table widefat fixed striped">
                                <thead>
                                    <tr>
                                        <th>Package Name</th>
                                        <th>Price</th>
                                        <th>Bookings</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($popular_packages as $package): ?>
                                        <tr>
                                            <td><?php echo esc_html($package->title); ?></td>
                                            <td>₹<?php echo esc_html(number_format($package->base_price, 2)); ?></td>
                                            <td><?php echo esc_html($package->current_bookings); ?></td>
                                            <td><?php echo esc_html($package->rating ? number_format($package->rating, 1) : '0.0'); ?>/5</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No packages found in database. Add packages through the packages management system.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <style>
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 20px;
                    margin: 20px 0;
                }
                .stat-card {
                    background: #f9f9f9;
                    padding: 20px;
                    text-align: center;
                    border-radius: 5px;
                    border: 1px solid #ddd;
                }
                .stat-card h3 {
                    margin: 0 0 10px 0;
                    font-size: 2em;
                    color: #0073aa;
                }
                .status-pending { color: #FFA500; }
                .status-confirmed { color: #00a32a; }
                .status-cancelled { color: #dc3232; }
                .status-completed { color: #0073aa; }
            </style>
        </div>
        <?php
    }
    
    /**
     * Packages page
     */
    public function packages_page() {
        // Include packages management interface
        require_once get_template_directory() . '/includes/admin/packages-admin.php';
        $admin = new PT_Packages_Admin();
        $admin->display_page();
    }
    
    /**
     * Car Types page
     */
    public function car_types_page() {
        // Include car types management interface
        require_once get_template_directory() . '/includes/admin/car-types-admin.php';
        $admin = new PT_Car_Types_Admin();
        $admin->display_page();
    }
    
    /**
     * Routes page
     */
    public function routes_page() {
        // Include routes management interface
        require_once get_template_directory() . '/includes/admin/routes-admin.php';
        $admin = new PT_Routes_Admin();
        $admin->display_page();
    }
    
    /**
     * Products page
     */
    public function products_page() {
        // Include products management interface
        require_once get_template_directory() . '/includes/admin/products-admin.php';
        $admin = new PT_Products_Admin();
        $admin->display_page();
    }
    
    /**
     * Simplified Products page
     */
    public function products_simple_page() {
        // Include simplified products management interface
        require_once get_template_directory() . '/includes/admin/products-admin-simple.php';
        $admin = new PT_Products_Admin_Simple();
        $admin->display_page();
    }
    
    /**
     * Offers page
     */
    public function offers_page() {
        echo '<div class="wrap">';
        echo '<h1>Special Offers Management</h1>';
        echo '<div class="notice notice-info"><p>Special Offers management system is being implemented. This feature will allow you to create and manage discount coupons, promotional offers, and special deals for your customers.</p></div>';
        echo '</div>';
    }
    
    /**
     * Testimonials page
     */
    public function testimonials_page() {
        echo '<div class="wrap">';
        echo '<h1>Testimonials Management</h1>';
        echo '<div class="notice notice-info"><p>Testimonials management system is being implemented. This feature will allow you to collect, manage, and display customer reviews and ratings.</p></div>';
        echo '</div>';
    }
    
    /**
     * Bookings page
     */
    public function bookings_page() {
        // Include bookings management interface
        require_once get_template_directory() . '/includes/admin/bookings-admin.php';
        $admin = new PT_Bookings_Admin();
        $admin->display_page();
    }
    
    /**
     * Enquiries page
     */
    public function enquiries_page() {
        echo '<div class="wrap">';
        echo '<h1>Enquiries Management</h1>';
        echo '<div class="notice notice-info"><p>Enquiries management system is being implemented. This feature will allow you to manage customer inquiries and support requests.</p></div>';
        echo '</div>';
    }
    
    /**
     * Customers page
     */
    public function customers_page() {
        echo '<div class="wrap">';
        echo '<h1>Customers Management</h1>';
        echo '<div class="notice notice-info"><p>Customer management system is being implemented. This feature will allow you to manage customer profiles, booking history, and loyalty programs.</p></div>';
        echo '</div>';
    }
    
    /**
     * Migration page (Removed - no longer needed)
     */
    // public function migration_page() {
    //     // Include migration tool
    //     require_once get_template_directory() . '/includes/migration-tool.php';
    //     PT_Migration_Tool::display_migration_page();
    // }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin pages
        if (strpos($hook, 'pt-') !== false) {
            // Admin styling removed - using WordPress default styles
        }
    }
}

// Initialize admin menu
new PT_Admin_Menu();