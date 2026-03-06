<?php
/**
 * Frontend Dynamic Display Functions
 * Functions for displaying dynamic travel content
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display dynamic packages
 */
function pt_display_packages($args = array()) {
    $defaults = array(
        'limit' => 6,
        'category_id' => 0,
        'location_id' => 0,
        'is_featured' => false,
        'columns' => 3,
        'show_price' => true,
        'show_location' => true
    );
    
    $args = wp_parse_args($args, $defaults);
    
    // Load manager
    require_once get_template_directory() . '/includes/dynamic-management/packages-manager.php';
    $manager = new PT_Packages_Manager();
    
    // Get packages
    $packages_args = array(
        'limit' => $args['limit'],
        'category_id' => $args['category_id'],
        'location_id' => $args['location_id'],
        'is_featured' => $args['is_featured'],
        'status' => 'active'
    );
    
    $packages = $manager->get_all($packages_args);
    
    if (empty($packages)) {
        return '<p>No packages available at the moment.</p>';
    }
    
    $output = '<div class="pt-packages-grid pt-columns-' . esc_attr($args['columns']) . '">';
    
    foreach ($packages as $package) {
        $output .= '<div class="pt-package-item">';
        $output .= '<div class="pt-package-image">';
        if ($package->featured_image) {
            $output .= '<img src="' . esc_url($package->featured_image) . '" alt="' . esc_attr($package->title) . '">';
        } else {
            $output .= '<div class="pt-placeholder-image">Package Image</div>';
        }
        $output .= '</div>';
        
        $output .= '<div class="pt-package-content">';
        $output .= '<h3 class="pt-package-title">' . esc_html($package->title) . '</h3>';
        
        if ($args['show_location'] && $package->location_name) {
            $output .= '<div class="pt-package-location">';
            $output .= '<i class="fas fa-map-marker-alt"></i> ' . esc_html($package->location_name);
            $output .= '</div>';
        }
        
        if ($package->duration_days) {
            $output .= '<div class="pt-package-duration">';
            $output .= '<i class="fas fa-clock"></i> ' . esc_html($package->duration_days) . ' Days';
            if ($package->duration_nights) {
                $output .= ' / ' . esc_html($package->duration_nights) . ' Nights';
            }
            $output .= '</div>';
        }
        
        if ($package->highlights) {
            $highlights = is_array($package->highlights) ? $package->highlights : unserialize($package->highlights);
            if (is_array($highlights) && !empty($highlights)) {
                $output .= '<ul class="pt-package-highlights">';
                foreach (array_slice($highlights, 0, 3) as $highlight) {
                    $output .= '<li>' . esc_html($highlight) . '</li>';
                }
                $output .= '</ul>';
            }
        }
        
        if ($args['show_price'] && $package->base_price) {
            $output .= '<div class="pt-package-price">';
            $output .= '<span class="pt-price-amount">₹' . esc_html(number_format($package->base_price)) . '</span>';
            $output .= '<span class="pt-price-label">starting from</span>';
            $output .= '</div>';
        }
        
        $output .= '<div class="pt-package-actions">';
        $output .= '<a href="#" class="pt-btn pt-btn-primary pt-book-package" data-package-id="' . esc_attr($package->id) . '">Book Now</a>';
        $output .= '<a href="#" class="pt-btn pt-btn-secondary pt-package-details" data-package-id="' . esc_attr($package->id) . '">View Details</a>';
        $output .= '</div>';
        
        // Cross-selling suggestions
        $cross_selling_html = '';
        require_once get_template_directory() . '/includes/dynamic-management/cross-selling-manager.php';
        $cross_selling_manager = new PT_Cross_Selling_Manager();
        $suggested_products = $cross_selling_manager->get_suggested_products_for_package($package->id);
        
        if (!empty($suggested_products)) {
            $cross_selling_html .= '<div class="pt-cross-sell-suggestions">';
            $cross_selling_html .= '<h4>Suggested Products:</h4>';
            $cross_selling_html .= '<div class="pt-suggested-products">';
            foreach (array_slice($suggested_products, 0, 2) as $prod) {
                $cross_selling_html .= '<div class="pt-suggested-product">';
                $cross_selling_html .= '<a href="#">';
                $cross_selling_html .= '<strong>' . esc_html($prod->title) . '</strong>';
                $cross_selling_html .= '<span class="pt-price">₹' . number_format($prod->price_regular, 2) . '</span>';
                $cross_selling_html .= '</a>';
                $cross_selling_html .= '</div>';
            }
            $cross_selling_html .= '</div>';
            $cross_selling_html .= '</div>';
        }
        
        $output .= $cross_selling_html;
        
        $output .= '</div>'; // .pt-package-content
        $output .= '</div>'; // .pt-package-item
    }
    
    $output .= '</div>'; // .pt-packages-grid
    
    // Add CSS
    $output .= '
    <style>
        .pt-packages-grid {
            display: grid;
            gap: 30px;
            margin: 30px 0;
        }
        .pt-columns-2 { grid-template-columns: repeat(2, 1fr); }
        .pt-columns-3 { grid-template-columns: repeat(3, 1fr); }
        .pt-columns-4 { grid-template-columns: repeat(4, 1fr); }
        
        .pt-package-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .pt-package-item:hover {
            transform: translateY(-5px);
        }
        
        .pt-package-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .pt-placeholder-image {
            width: 100%;
            height: 200px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }
        
        .pt-package-content {
            padding: 20px;
        }
        .pt-package-title {
            margin: 0 0 15px 0;
            font-size: 1.2em;
            color: #333;
        }
        .pt-package-location,
        .pt-package-duration {
            margin: 5px 0;
            color: #666;
            font-size: 0.9em;
        }
        .pt-package-location i,
        .pt-package-duration i {
            margin-right: 5px;
            color: #0073aa;
        }
        .pt-package-highlights {
            margin: 15px 0;
            padding-left: 20px;
        }
        .pt-package-highlights li {
            margin: 5px 0;
            color: #555;
        }
        .pt-package-price {
            margin: 15px 0;
            text-align: center;
        }
        .pt-price-amount {
            display: block;
            font-size: 1.5em;
            font-weight: bold;
            color: #0073aa;
        }
        .pt-price-label {
            font-size: 0.9em;
            color: #666;
        }
        .pt-package-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .pt-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            flex: 1;
            font-size: 0.9em;
        }
        .pt-btn-primary {
            background: #0073aa;
            color: white;
        }
        .pt-btn-secondary {
            background: #f1f1f1;
            color: #333;
            border: 1px solid #ddd;
        }
        .pt-btn:hover {
            opacity: 0.9;
        }
        
        .pt-cross-sell-suggestions {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #ddd;
        }
        
        .pt-cross-sell-suggestions h4 {
            margin: 0 0 10px 0;
            font-size: 0.9em;
            color: #666;
        }
        
        .pt-suggested-products {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .pt-suggested-product {
            flex: 1;
            min-width: 120px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 0.85em;
        }
        
        .pt-suggested-product a {
            text-decoration: none;
            color: #333;
        }
        
        .pt-suggested-product a:hover {
            color: #0073aa;
        }
        
        .pt-price {
            display: block;
            font-weight: bold;
            color: #0073aa;
            font-size: 0.9em;
            margin-top: 3px;
        }
        
        @media (max-width: 768px) {
            .pt-columns-2, .pt-columns-3, .pt-columns-4 {
                grid-template-columns: 1fr;
            }
        }
    </style>';
    
    return $output;
}

/**
 * Display dynamic car types
 */
function pt_display_car_types($args = array()) {
    $defaults = array(
        'limit' => 6,
        'category' => '',
        'columns' => 3,
        'show_pricing' => true
    );
    
    $args = wp_parse_args($args, $defaults);
    
    // Load manager
    require_once get_template_directory() . '/includes/dynamic-management/car-types-manager.php';
    $manager = new PT_Car_Types_Manager();
    
    // Get car types
    $car_args = array(
        'limit' => $args['limit'],
        'category' => $args['category'],
        'status' => 'active',
        'availability_status' => 'available'
    );
    
    $car_types = $manager->get_all($car_args);
    
    if (empty($car_types)) {
        return '<p>No car types available at the moment.</p>';
    }
    
    $output = '<div class="pt-car-types-grid pt-columns-' . esc_attr($args['columns']) . '">';
    
    foreach ($car_types as $car) {
        $output .= '<div class="pt-car-type-item">';
        $output .= '<div class="pt-car-image">';
        if ($car->featured_image) {
            $output .= '<img src="' . esc_url($car->featured_image) . '" alt="' . esc_attr($car->title) . '">';
        } else {
            $output .= '<div class="pt-placeholder-image">Car Image</div>';
        }
        $output .= '</div>';
        
        $output .= '<div class="pt-car-content">';
        $output .= '<h3 class="pt-car-title">' . esc_html($car->title) . '</h3>';
        $output .= '<div class="pt-car-category">' . esc_html($car->category) . '</div>';
        
        $output .= '<div class="pt-car-specs">';
        $output .= '<div class="pt-spec"><i class="fas fa-users"></i> ' . esc_html($car->capacity) . ' Seats</div>';
        $output .= '<div class="pt-spec"><i class="fas fa-suitcase"></i> ' . esc_html($car->luggage_capacity) . ' Luggage</div>';
        $output .= '<div class="pt-spec"><i class="fas fa-wind"></i> ' . esc_html($car->ac_type) . '</div>';
        $output .= '<div class="pt-spec"><i class="fas fa-gas-pump"></i> ' . esc_html($car->fuel_type) . '</div>';
        $output .= '</div>';
        
        if ($args['show_pricing'] && $car->base_price_per_km) {
            $output .= '<div class="pt-car-price">';
            $output .= '₹' . esc_html(number_format($car->base_price_per_km)) . '/km';
            $output .= '</div>';
        }
        
        $output .= '<div class="pt-car-actions">';
        $output .= '<a href="#" class="pt-btn pt-btn-primary pt-book-car" data-car-id="' . esc_attr($car->id) . '">Book Now</a>';
        $output .= '<a href="#" class="pt-btn pt-btn-secondary pt-car-details" data-car-id="' . esc_attr($car->id) . '">View Details</a>';
        $output .= '</div>';
        
        $output .= '</div>'; // .pt-car-content
        $output .= '</div>'; // .pt-car-type-item
    }
    
    $output .= '</div>'; // .pt-car-types-grid
    
    // Add CSS
    $output .= '
    <style>
        .pt-car-types-grid {
            display: grid;
            gap: 30px;
            margin: 30px 0;
        }
        .pt-columns-2 { grid-template-columns: repeat(2, 1fr); }
        .pt-columns-3 { grid-template-columns: repeat(3, 1fr); }
        .pt-columns-4 { grid-template-columns: repeat(4, 1fr); }
        
        .pt-car-type-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .pt-car-image img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        
        .pt-car-content {
            padding: 20px;
        }
        .pt-car-title {
            margin: 0 0 10px 0;
            font-size: 1.1em;
            color: #333;
        }
        .pt-car-category {
            color: #0073aa;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .pt-car-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 15px 0;
        }
        .pt-spec {
            font-size: 0.9em;
            color: #666;
        }
        .pt-spec i {
            margin-right: 5px;
            color: #0073aa;
        }
        .pt-car-price {
            font-size: 1.2em;
            font-weight: bold;
            color: #0073aa;
            margin: 15px 0;
            text-align: center;
        }
    </style>';
    
    return $output;
}

/**
 * Display dynamic routes
 */
function pt_display_routes($args = array()) {
    $defaults = array(
        'limit' => 6,
        'from_location' => 0,
        'to_location' => 0,
        'columns' => 2,
        'show_pricing' => true
    );
    
    $args = wp_parse_args($args, $defaults);
    
    // Load manager
    require_once get_template_directory() . '/includes/dynamic-management/routes-manager.php';
    $manager = new PT_Routes_Manager();
    
    // Get routes
    $route_args = array(
        'limit' => $args['limit'],
        'from_location_id' => $args['from_location'],
        'to_location_id' => $args['to_location'],
        'status' => 'active'
    );
    
    $routes = $manager->get_all($route_args);
    
    if (empty($routes)) {
        return '<p>No routes available at the moment.</p>';
    }
    
    $output = '<div class="pt-routes-list pt-columns-' . esc_attr($args['columns']) . '">';
    
    foreach ($routes as $route) {
        $output .= '<div class="pt-route-item">';
        $output .= '<div class="pt-route-header">';
        $output .= '<h3 class="pt-route-title">' . esc_html($route->title) . '</h3>';
        $output .= '</div>';
        
        $output .= '<div class="pt-route-details">';
        $output .= '<div class="pt-route-locations">';
        $output .= '<div class="pt-from-location">';
        $output .= '<i class="fas fa-circle"></i> ' . esc_html($route->from_location_name);
        $output .= '</div>';
        $output .= '<div class="pt-route-arrow"><i class="fas fa-arrow-right"></i></div>';
        $output .= '<div class="pt-to-location">';
        $output .= '<i class="fas fa-map-marker-alt"></i> ' . esc_html($route->to_location_name);
        $output .= '</div>';
        $output .= '</div>';
        
        $output .= '<div class="pt-route-info">';
        if ($route->distance_km) {
            $output .= '<div class="pt-distance"><i class="fas fa-road"></i> ' . esc_html($route->distance_km) . ' km</div>';
        }
        if ($route->estimated_time) {
            $output .= '<div class="pt-time"><i class="fas fa-clock"></i> ' . esc_html($route->estimated_time) . '</div>';
        }
        $output .= '</div>';
        
        if ($args['show_pricing'] && ($route->base_price || $route->price_per_km)) {
            $output .= '<div class="pt-route-price">';
            if ($route->base_price) {
                $output .= '₹' . esc_html(number_format($route->base_price));
            } else {
                $output .= '₹' . esc_html(number_format($route->price_per_km)) . '/km';
            }
            $output .= '</div>';
        }
        
        $output .= '<div class="pt-route-actions">';
        $output .= '<a href="#" class="pt-btn pt-btn-primary pt-book-route" data-route-id="' . esc_attr($route->id) . '">Book Now</a>';
        $output .= '</div>';
        
        $output .= '</div>'; // .pt-route-details
        $output .= '</div>'; // .pt-route-item
    }
    
    $output .= '</div>'; // .pt-routes-list
    
    // Add CSS
    $output .= '
    <style>
        .pt-routes-list {
            display: grid;
            gap: 20px;
            margin: 30px 0;
        }
        .pt-columns-2 { grid-template-columns: repeat(2, 1fr); }
        
        .pt-route-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .pt-route-title {
            margin: 0 0 20px 0;
            color: #333;
            text-align: center;
        }
        
        .pt-route-locations {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0;
        }
        
        .pt-from-location, .pt-to-location {
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 20px;
            margin: 5px 0;
            font-weight: 500;
        }
        
        .pt-from-location i {
            color: #28a745;
        }
        
        .pt-to-location i {
            color: #dc3545;
        }
        
        .pt-route-arrow {
            margin: 10px 0;
            color: #0073aa;
        }
        
        .pt-route-info {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .pt-distance, .pt-time {
            text-align: center;
        }
        
        .pt-distance i, .pt-time i {
            margin-right: 5px;
            color: #0073aa;
        }
        
        .pt-route-price {
            text-align: center;
            font-size: 1.3em;
            font-weight: bold;
            color: #0073aa;
            margin: 20px 0;
        }
    </style>';

    return $output;
}

/**
 * Display dynamic products/tours
 */
function pt_display_products($atts = array()) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'product_type' => '',
        'location_id' => 0,
        'show_featured_only' => false,
        'orderby' => 'sort_order',
        'order' => 'ASC'
    ), $atts);

    $manager = new PT_Products_Manager();
    
    $args = array(
        'limit' => $atts['limit'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'status' => 'active'
    );
    
    if (!empty($atts['product_type'])) {
        $args['product_type'] = $atts['product_type'];
    }
    
    if ($atts['location_id'] > 0) {
        $args['location_id'] = $atts['location_id'];
    }
    
    if ($atts['show_featured_only']) {
        $args['is_featured'] = true;
    }
    
    $products = $manager->get_all($args);
    
    if (empty($products)) {
        return '<p>No products available.</p>';
    }
    
    ob_start();
    ?>
    <div class="pt-products-grid">
        <?php foreach ($products as $product): 
            $location = null;
            if ($product->location_id) {
                global $wpdb;
                $location = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d",
                    $product->location_id
                ));
            }
        ?>
            <div class="pt-product-item">
                <?php if ($product->featured_image): ?>
                    <div class="pt-product-image">
                        <img src="<?php echo esc_url($product->featured_image); ?>" alt="<?php echo esc_attr($product->title); ?>" />
                    </div>
                <?php endif; ?>
                
                <div class="pt-product-content">
                    <h3 class="pt-product-title"><?php echo esc_html($product->title); ?></h3>
                    
                    <?php if ($location): ?>
                        <div class="pt-product-location">
                            <strong>Location:</strong> <?php echo esc_html($location->title); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="pt-product-type">
                        <strong>Type:</strong> <?php echo esc_html(ucfirst($product->product_type)); ?>
                    </div>
                    
                    <div class="pt-product-duration">
                        <strong>Duration:</strong> <?php echo esc_html($product->duration); ?>
                    </div>
                    
                    <?php if ($product->difficulty_level): ?>
                        <div class="pt-product-difficulty">
                            <strong>Difficulty:</strong> 
                            <span class="difficulty-<?php echo esc_attr($product->difficulty_level); ?>">
                                <?php echo esc_html(ucfirst($product->difficulty_level)); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product->age_requirements): ?>
                        <div class="pt-product-age-requirements">
                            <strong>Age Requirements:</strong> <?php echo esc_html($product->age_requirements); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product->price_per_person > 0): ?>
                        <div class="pt-product-price">
                            <strong>Price:</strong> 
                            <?php if ($product->discount_percentage): ?>
                                <span class="original-price">₹<?php echo number_format($product->price_per_person, 2); ?></span>
                                <span class="discounted-price">₹<?php echo number_format($product->discounted_price, 2); ?></span>
                                <span class="discount-percent"><?php echo $product->discount_percentage; ?>% OFF</span>
                            <?php else: ?>
                                ₹<?php echo number_format($product->price_per_person, 2); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product->rating > 0): ?>
                        <div class="pt-product-rating">
                            <strong>Rating:</strong> 
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <span class="star <?php echo $i <= $product->rating ? 'filled' : ''; ?>">★</span>
                            <?php endfor; ?>
                            (<?php echo $product->rating; ?>/5)
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product->highlights): ?>
                        <div class="pt-product-highlights">
                            <strong>Highlights:</strong>
                            <ul>
                                <?php 
                                $highlights = is_array($product->highlights) ? $product->highlights : explode(',', $product->highlights);
                                foreach($highlights as $highlight):
                                ?>
                                    <li><?php echo esc_html(trim($highlight)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="pt-product-description">
                        <?php echo wp_trim_words(wp_kses_post($product->description), 20); ?>
                    </div>
                    
                    <div class="pt-product-actions">
                        <a href="#" class="pt-btn pt-btn-primary">Book Now</a>
                        <a href="#" class="pt-btn pt-btn-secondary">View Details</a>
                    </div>
                    
                    <!-- Cross-selling suggestions -->
                    <?php
                    require_once get_template_directory() . '/includes/dynamic-management/cross-selling-manager.php';
                    $cross_selling_manager = new PT_Cross_Selling_Manager();
                    $suggested_packages = $cross_selling_manager->get_suggested_packages_for_product($product->id);
                    
                    if (!empty($suggested_packages)):
                    ?>
                    <div class="pt-cross-sell-suggestions">
                        <h4>Suggested Travel Packages:</h4>
                        <div class="pt-suggested-packages">
                            <?php foreach (array_slice($suggested_packages, 0, 2) as $pkg): ?>
                                <div class="pt-suggested-package">
                                    <a href="#">
                                        <strong><?php echo esc_html($pkg->title); ?></strong>
                                        <span class="pt-price">From ₹<?php echo number_format($pkg->base_price, 2); ?></span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <style>
        .pt-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .pt-product-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .pt-product-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .pt-product-content {
            padding: 15px;
        }
        
        .pt-product-title {
            margin-top: 0;
            color: #333;
        }
        
        .pt-product-price .original-price {
            text-decoration: line-through;
            color: #999;
        }
        
        .pt-product-price .discounted-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.2em;
        }
        
        .pt-product-price .discount-percent {
            background-color: #e74c3c;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.8em;
        }
        
        .pt-product-rating .star {
            color: #ccc;
        }
        
        .pt-product-rating .star.filled {
            color: #f39c12;
        }
        
        .difficulty-easy { color: #27ae60; }
        .difficulty-moderate { color: #f39c12; }
        .difficulty-challenging { color: #e67e22; }
        .difficulty-expert { color: #e74c3c; }
        
        .pt-btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        
        .pt-btn-primary {
            background-color: #3498db;
            color: white;
        }
        
        .pt-btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        
        .pt-cross-sell-suggestions {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #ddd;
        }
        
        .pt-cross-sell-suggestions h4 {
            margin: 0 0 10px 0;
            font-size: 0.9em;
            color: #666;
        }
        
        .pt-suggested-packages {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .pt-suggested-package {
            flex: 1;
            min-width: 120px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 0.85em;
        }
        
        .pt-suggested-package a {
            text-decoration: none;
            color: #333;
        }
        
        .pt-suggested-package a:hover {
            color: #0073aa;
        }
        
        .pt-price {
            display: block;
            font-weight: bold;
            color: #0073aa;
            font-size: 0.9em;
            margin-top: 3px;
        }
    </style>
    <?php
    return ob_get_clean();
}
