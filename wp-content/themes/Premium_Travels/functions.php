<?php
/**
 * Premium_Travels functions and definitions
 */

function premium_travels_setup()
{
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'premium-travels'),
        'footer_company' => esc_html__('Footer Company Menu', 'premium-travels'),
        'footer_services' => esc_html__('Footer Services Menu', 'premium-travels'),
        'footer_partner' => esc_html__('Footer Partner Menu', 'premium-travels'),
    ));
}
add_action('after_setup_theme', 'premium_travels_setup');

/**
 * Add rewrite rules for product URLs
 */
function pt_add_product_rewrite_rules() {
    add_rewrite_rule(
        '^product/([^/]+)/?$',
        'index.php?pagename=product-detail&product_slug=$matches[1]',
        'top'
    );
}
add_action('init', 'pt_add_product_rewrite_rules');

/**
 * Add query var for product slug
 */
function pt_add_product_query_vars($vars) {
    $vars[] = 'product_slug';
    return $vars;
}
add_filter('query_vars', 'pt_add_product_query_vars');

/**
 * Template redirect for product pages - handle both URL formats
 */
function pt_product_template_redirect() {
    global $wp_query;

    // Handle /products listing page
    if (is_page('products') || (isset($_SERVER['REQUEST_URI']) && preg_match('#^/travel/products/?(\?.*)?$#', $_SERVER['REQUEST_URI']))) {
        $template = get_template_directory() . '/page-products.php';
        if (file_exists($template)) { include $template; exit; }
    }

    // Handle /cart page
    if (is_page('cart') || (isset($_SERVER['REQUEST_URI']) && preg_match('#^/travel/cart(/?\?.*)?$#', $_SERVER['REQUEST_URI']))) {
        $template = get_template_directory() . '/page-cart.php';
        if (file_exists($template)) { include $template; exit; }
    }

    // Handle /buy-now page
    if (is_page('buy-now') || (isset($_SERVER['REQUEST_URI']) && preg_match('#^/travel/buy-now(/?\?.*)?$#', $_SERVER['REQUEST_URI']))) {
        $template = get_template_directory() . '/page-buy-now.php';
        if (file_exists($template)) { include $template; exit; }
    }

    // Handle custom product URLs: /product/orange
    if (isset($wp_query->query_vars['product_slug'])) {
        include get_template_directory() . '/product-detail.php';
        exit;
    }

    // Handle WordPress CPT permalinks: /pt_product/orange
    if (is_singular('pt_product')) {
        $post = get_post();
        if ($post) {
            $wp_query->set('product_slug', $post->post_name);
            include get_template_directory() . '/product-detail.php';
            exit;
        }
    }
}
add_action('template_redirect', 'pt_product_template_redirect');


/**
 * Enqueue scripts and styles.
 */
function premium_travels_scripts()
{
    // Styles
    wp_enqueue_style('premium-travels-style', get_stylesheet_uri());

    // External CSS from oldwebsite.html
    wp_enqueue_style('component-css', 'https://www.patratravels.com/css/component.css');
    wp_enqueue_style('bootstrap-css', 'https://www.patratravels.com/css/bootstrap.css');
    wp_enqueue_style('index-style', 'https://www.patratravels.com/css/index-style.css');
    wp_enqueue_style('responsive-css', 'https://www.patratravels.com/css/responsive.css');
    wp_enqueue_style('header-css', 'https://www.patratravels.com/css/header.css');
    wp_enqueue_style('dropdowns-css', 'https://www.patratravels.com/css/dropdowns-skin-discrete.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');

    // Custom beautification stylesheet (loaded last so it overrides)
    wp_enqueue_style('pt-custom-style', get_template_directory_uri() . '/style-custom.css', array(), '1.0.9');

    // Scripts
    // Deregister WordPress jQuery and register the one from the old site (or use WP's if compatible - relying on old site's version to ensure compatibility with their plugins)
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://www.patratravels.com/js/jquery.min.js', array(), null, false); // Load in head as per old site

    wp_enqueue_script('bootstrap-js', 'https://www.patratravels.com/js/bootstrap.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-ui', 'https://www.patratravels.com/js/jquery-ui.js', array('jquery'), null, true);
    wp_enqueue_script('custom-js', 'https://www.patratravels.com/js/custom.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'premium_travels_scripts');

/**
 * Register Custom Post Types
 */
function premium_travels_register_cpts()
{
    // 1. Locations (Cities for Dropdown)
    register_post_type('location', array(
        'labels' => array(
            'name' => 'Locations',
            'singular_name' => 'Location',
            'add_new_item' => 'Add New Location (City)',
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-location',
    ));

    // 2. Taxi Packages
    register_post_type('taxi_package', array(
        'labels' => array(
            'name' => 'Taxi Packages',
            'singular_name' => 'Taxi Package',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-car',
    ));

    // 3. Routes
    register_post_type('route', array(
        'labels' => array(
            'name' => 'Routes',
            'singular_name' => 'Route',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-admin-site-alt3',
    ));

    // 4. Testimonials
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => 'Testimonials',
            'singular_name' => 'Testimonial',
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'thumbnail'), // Title = Client Name, Editor = Content
        'menu_icon' => 'dashicons-testimonial',
    ));

    // 5. Car Types
    register_post_type('car_type', array(
        'labels' => array(
            'name' => 'Car Types',
            'singular_name' => 'Car Type',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-admin-network',
    ));

    // 6. Holiday Packages
    register_post_type('holiday_package', array(
        'labels' => array(
            'name' => 'Holiday Packages',
            'singular_name' => 'Holiday Package',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-palmtree',
        'rewrite' => array('slug' => 'holiday-package'),
    ));

    // 7. Special Offers
    register_post_type('special_offer', array(
        'labels' => array(
            'name' => 'Special Offers',
            'singular_name' => 'Special Offer',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-tag',
    ));

    // 8. City Services
    register_post_type('city_service', array(
        'labels' => array(
            'name' => 'City Services',
            'singular_name' => 'City Service',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-building',
        'rewrite' => array('slug' => 'cab-rental'),
    ));

    // 9. Products
    register_post_type('pt_product', array(
        'labels' => array(
            'name' => 'Products',
            'singular_name' => 'Product',
            'add_new_item' => 'Add New Product',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-cart',
    ));
}
add_action('init', 'premium_travels_register_cpts');

/**
 * Register Custom Taxonomies
 */
function premium_travels_register_taxonomies()
{
    // 1. Service Type (for Taxi Packages and Routes)
    register_taxonomy('service_type', array('taxi_package', 'route'), array(
        'labels' => array(
            'name' => 'Service Types',
            'singular_name' => 'Service Type',
        ),
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'service'),
    ));

    // 2. Package Category (for Holiday Packages)
    register_taxonomy('package_category', 'holiday_package', array(
        'labels' => array(
            'name' => 'Package Categories',
            'singular_name' => 'Package Category',
        ),
        'hierarchical' => true,
        'show_admin_column' => true,
    ));

    // 3. Car Category (for Car Types)
    register_taxonomy('car_category', 'car_type', array(
        'labels' => array(
            'name' => 'Car Categories',
            'singular_name' => 'Car Category',
        ),
        'hierarchical' => true,
        'show_admin_column' => true,
    ));
}
add_action('init', 'premium_travels_register_taxonomies');

// Helper function to get locations for dropdown
function get_location_options()
{
    $args = array(
        'post_type' => 'location',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );
    $locations = get_posts($args);
    $options = '';
    foreach ($locations as $location) {
        $options .= '<option value="' . esc_attr($location->post_title) . '">' . esc_html($location->post_title) . '</option>';
    }
    return $options;
}

// Include database tables
require_once get_template_directory() . '/includes/database-tables.php';

// Include setup pages and menus
require_once get_template_directory() . '/includes/setup-pages-menus.php';

// Include admin meta boxes for all CPTs
require_once get_template_directory() . '/includes/meta-boxes.php';

// Include dynamic management system
require_once get_template_directory() . '/includes/dynamic-management/base-manager.php';
require_once get_template_directory() . '/includes/dynamic-management/packages-manager.php';
require_once get_template_directory() . '/includes/dynamic-management/car-types-manager.php';
require_once get_template_directory() . '/includes/dynamic-management/routes-manager.php';
require_once get_template_directory() . '/includes/dynamic-management/products-manager.php';
require_once get_template_directory() . '/includes/dynamic-management/bookings-manager.php';
require_once get_template_directory() . '/includes/dynamic-management/cross-selling-manager.php';

// Include admin menu system
require_once get_template_directory() . '/includes/admin/admin-menu.php';

// Include frontend display functions
require_once get_template_directory() . '/includes/frontend/dynamic-display.php';

// Shortcodes for frontend display
function pt_packages_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'category' => '',
        'location' => '',
        'featured' => false,
        'columns' => 3
    ), $atts);
    
    return pt_display_packages($atts);
}
add_shortcode('pt_packages', 'pt_packages_shortcode');

function pt_car_types_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'category' => '',
        'columns' => 3
    ), $atts);
    
    return pt_display_car_types($atts);
}
add_shortcode('pt_car_types', 'pt_car_types_shortcode');

function pt_routes_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'from' => '',
        'to' => '',
        'columns' => 2
    ), $atts);
    
    return pt_display_routes($atts);
}
add_shortcode('pt_routes', 'pt_routes_shortcode');

function pt_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'product_type' => '',
        'location_id' => 0,
        'show_featured_only' => false
    ), $atts);
    
    $atts['limit'] = absint($atts['limit']);
    $atts['location_id'] = absint($atts['location_id']);
    $atts['show_featured_only'] = (bool)$atts['show_featured_only'];
    
    return pt_display_products($atts);
}
add_shortcode('pt_products', 'pt_products_shortcode');
