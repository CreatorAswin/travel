<?php
/**
 * Theme Setup Script
 * Creates all necessary pages and menus automatically
 */

function premium_travels_create_pages_and_menus()
{
    // Check if setup already done
    if (get_option('premium_travels_setup_complete')) {
        return;
    }

    // 1. Create Pages
    $pages = array(
        // Main Pages
        array(
            'title' => 'Home',
            'template' => 'front-page.php',
            'is_front' => true,
        ),
        array(
            'title' => 'Flight',
            'template' => 'page-flight.php',
        ),
        array(
            'title' => 'Cab Booking',
            'template' => 'page-cab-booking.php',
        ),
        array(
            'title' => 'Taxi Packages',
            'template' => '', // Uses archive
        ),
        array(
            'title' => 'Contact Us',
            'template' => 'page-contact.php',
        ),
        array(
            'title' => 'Special Offers',
            'template' => 'page-special-offers.php',
        ),
        array(
            'title' => 'Testimonials',
            'template' => 'page-testimonials.php',
        ),
        array(
            'title' => 'Cancel Reservation',
            'template' => 'page-cancel-reservation.php',
        ),
        array(
            'title' => 'Forex',
            'template' => 'page-forex.php',
        ),
        array(
            'title' => 'Visa Assistance',
            'template' => 'page-visa.php',
        ),

        array(
            'title' => 'Products',
            'template' => 'page-products.php',
        ),
        array(
            'title' => 'Cart',
            'template' => 'page-cart.php',
        ),
        array(
            'title' => 'Buy Now',
            'template' => 'page-buy-now.php',
        ),

        // Car Rental City Pages
        array(
            'title' => 'Taxi Services in Bhubaneswar',
            'template' => 'page-car-rental-city.php',
            'parent' => 'Car Rentals',
        ),
        array(
            'title' => 'Taxi Services in Cuttack',
            'template' => 'page-car-rental-city.php',
            'parent' => 'Car Rentals',
        ),
        array(
            'title' => 'Taxi Services in Puri',
            'template' => 'page-car-rental-city.php',
            'parent' => 'Car Rentals',
        ),
        array(
            'title' => 'Car Rentals',
            'template' => '',
        ),
    );

    $page_ids = array();
    foreach ($pages as $page_data) {
        // Check if page exists
        $page_query = new WP_Query(array(
            'post_type' => 'page',
            'title'     => $page_data['title'],
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'no_found_rows' => true,
        ));
        $page = $page_query->have_posts() ? $page_query->next_post() : null;

        if (!$page) {
            $page_args = array(
                'post_title' => $page_data['title'],
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page',
            );

            // Set parent if specified
            if (isset($page_data['parent'])) {
                $parent_query = new WP_Query(array(
                    'post_type' => 'page',
                    'title'     => $page_data['parent'],
                    'post_status' => 'publish',
                    'posts_per_page' => 1,
                    'no_found_rows' => true,
                ));
                $parent = $parent_query->have_posts() ? $parent_query->next_post() : null;
                
                if ($parent) {
                    $page_args['post_parent'] = $parent->ID;
                }
            }

            $page_id = wp_insert_post($page_args);

            // Set template
            if (!empty($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }

            // Set as front page
            if (isset($page_data['is_front']) && $page_data['is_front']) {
                update_option('page_on_front', $page_id);
                update_option('show_on_front', 'page');
            }

            $page_ids[$page_data['title']] = $page_id;
        } else {
            $page_ids[$page_data['title']] = $page->ID;
        }
    }

    // 2. Create Primary Menu
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        // Menu structure
        $menu_items = array(
            array('title' => 'Home', 'page' => 'Home'),
            array('title' => 'Flight', 'page' => 'Flight'),
            array(
                'title' => 'CABS',
                'children' => array(
                    array('title' => 'Local Trip', 'url' => home_url('/?page_id=' . $page_ids['Cab Booking'] . '&service=MjQ=')),
                    array('title' => 'Airport Transfer', 'url' => home_url('/?page_id=' . $page_ids['Cab Booking'] . '&service=MjU=')),
                    array('title' => 'One-Way', 'url' => home_url('/?page_id=' . $page_ids['Cab Booking'] . '&service=Mjc=')),
                    array('title' => 'Round-Trip', 'url' => home_url('/?page_id=' . $page_ids['Cab Booking'] . '&service=MTYw')),
                    array('title' => 'Multi-way', 'url' => home_url('/?page_id=' . $page_ids['Cab Booking'] . '&service=MjY=')),
                    array('title' => 'Taxi-Packages', 'url' => home_url('/?page_id=' . $page_ids['Cab Booking'] . '&service=Mjg=')),
                ),
            ),
            array(
                'title' => 'Car Rentals',
                'page' => 'Car Rentals',
                'children' => array(
                    array('title' => 'From Bhubaneswar', 'page' => 'Taxi Services in Bhubaneswar'),
                    array('title' => 'From Cuttack', 'page' => 'Taxi Services in Cuttack'),
                    array('title' => 'From Puri', 'page' => 'Taxi Services in Puri'),
                ),
            ),
            array('title' => 'Taxi Package', 'url' => get_post_type_archive_link('taxi_package')),
            array(
                'title' => 'HOLIDAYS',
                'children' => array(
                    array('title' => 'Odisha Tour Packages', 'url' => get_post_type_archive_link('holiday_package') . '?category=odisha-tour'),
                    array('title' => 'India Tour Packages', 'url' => get_post_type_archive_link('holiday_package') . '?category=india-tour'),
                    array('title' => 'International Tour Packages', 'url' => get_post_type_archive_link('holiday_package') . '?category=international-tour'),
                ),
            ),
            array('title' => 'Forex', 'page' => 'Forex'),
            array('title' => 'Visa', 'page' => 'Visa Assistance'),
            array('title' => 'Cancel Reservation', 'page' => 'Cancel Reservation'),
            array('title' => 'Special Offers', 'page' => 'Special Offers'),
            array('title' => 'Testimonials', 'page' => 'Testimonials'),
            array('title' => 'Contact Us', 'page' => 'Contact Us'),
        );

        // Add menu items
        $parent_id = 0;
        foreach ($menu_items as $item) {
            $item_data = array(
                'menu-item-title' => $item['title'],
                'menu-item-status' => 'publish',
            );

            if (isset($item['page']) && isset($page_ids[$item['page']])) {
                $item_data['menu-item-object-id'] = $page_ids[$item['page']];
                $item_data['menu-item-object'] = 'page';
                $item_data['menu-item-type'] = 'post_type';
            } elseif (isset($item['url'])) {
                $item_data['menu-item-url'] = $item['url'];
                $item_data['menu-item-type'] = 'custom';
            } else {
                $item_data['menu-item-type'] = 'custom';
                $item_data['menu-item-url'] = '#';
            }

            $parent_item_id = wp_update_nav_menu_item($menu_id, 0, $item_data);

            // Add children
            if (isset($item['children'])) {
                foreach ($item['children'] as $child) {
                    $child_data = array(
                        'menu-item-title' => $child['title'],
                        'menu-item-parent-id' => $parent_item_id,
                        'menu-item-status' => 'publish',
                    );

                    if (isset($child['page']) && isset($page_ids[$child['page']])) {
                        $child_data['menu-item-object-id'] = $page_ids[$child['page']];
                        $child_data['menu-item-object'] = 'page';
                        $child_data['menu-item-type'] = 'post_type';
                    } elseif (isset($child['url'])) {
                        $child_data['menu-item-url'] = $child['url'];
                        $child_data['menu-item-type'] = 'custom';
                    }

                    wp_update_nav_menu_item($menu_id, 0, $child_data);
                }
            }
        }

        // Assign menu to location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Mark setup as complete
    update_option('premium_travels_setup_complete', true);
}

add_action('after_setup_theme', 'premium_travels_create_pages_and_menus');
