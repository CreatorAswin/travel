<?php
get_header();
?>

<!--======================== ANNOUNCEMENT STRIP ========================-->
<div class="col-md-12 cyntner">
    <div class="container">
        <ul class="nw-bkng">
            <img src="<?php echo get_template_directory_uri(); ?>/images/taxi.png" width="40" alt="Taxi"
                onerror="this.style.display='none'">
            <li>
                <h4>Sanitized Cab &amp; Coach Booking – Local &amp; Outstation</h4>
            </li>
            <li>
                <h6>Book Oneway &bull; Round Trip &bull; Multi-way &bull; Toll Free: <strong>1800 120 8464</strong></h6>
            </li>
        </ul>
    </div>
</div>

<!--======================== BOOKING HERO SECTION ========================-->
<div class="main-banner">
    <div class="search_wraper">
        <div class="container">
            <div class="search_one container">

                <!-- TAB NAV -->
                <ul class="tab-nav-2" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab-local" class="book-icon" role="tab" data-toggle="tab">
                            <i class="fa fa-map-marker-alt"></i>
                            Local Trip
                        </a>
                    </li>
                    <li role="presentation" style="position:relative;">
                        <span class="showtourist blink_me">Tourist</span>
                        <a href="#tab-package" class="book-icon" role="tab" data-toggle="tab">
                            <i class="fa fa-suitcase-rolling"></i>
                            Packages
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tab-oneway" class="book-icon" role="tab" data-toggle="tab">
                            <i class="fa fa-arrow-right"></i>
                            One-Way
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tab-round" class="book-icon" role="tab" data-toggle="tab">
                            <i class="fa fa-sync-alt"></i>
                            Round Trip
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tab-airport" class="book-icon" role="tab" data-toggle="tab">
                            <i class="fa fa-plane-departure"></i>
                            Airport
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tab-multiway" class="book-icon" role="tab" data-toggle="tab">
                            <i class="fa fa-route"></i>
                            Multi-Way
                        </a>
                    </li>
                </ul>

                <!-- TAB PANES -->
                <div class="tab-content">

                    <!-- LOCAL TRIP -->
                    <div role="tabpanel" class="tab-pane active" id="tab-local">
                        <div class="banner-search_tab">
                            <form action="<?php echo esc_url(home_url('/cab-booking')); ?>" method="GET">
                                <input type="hidden" name="service" value="MjQ=">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-map-pin"
                                                style="color:var(--primary);margin-right:5px;"></i> Pickup City</label>
                                        <select name="pickCity" class="selectpicker" data-live-search="true" required>
                                            <option value="">— Select City —</option>
                                            <?php echo get_location_options(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-clock"
                                                style="color:var(--primary);margin-right:5px;"></i> Duration</label>
                                        <select name="duration" class="selectpicker" required>
                                            <option value="">— Select Duration —</option>
                                            <option value="4">4 Hours / 40 Kms</option>
                                            <option value="8">8 Hours / 80 Kms</option>
                                            <option value="12">12 Hours / 120 Kms</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-calendar-alt"
                                                style="color:var(--primary);margin-right:5px;"></i> Pickup Date</label>
                                        <input type="date" name="pickup_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12"
                                        style="margin-bottom:14px;padding-top:22px;">
                                        <button class="df-button1" type="submit" style="width:100%;height:44px;">
                                            <i class="fa fa-search"></i> Search Cabs
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- TAXI PACKAGES -->
                    <div role="tabpanel" class="tab-pane" id="tab-package">
                        <div class="banner-search_tab">
                            <form action="<?php echo get_post_type_archive_link('taxi_package'); ?>" method="GET">
                                <div class="row">
                                    <div class="col-md-9 col-sm-8 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-map-pin"
                                                style="color:var(--primary);margin-right:5px;"></i> Your Pickup
                                            City</label>
                                        <select name="pickup_city" class="selectpicker" data-live-search="true">
                                            <option value="">— Select City —</option>
                                            <?php echo get_location_options(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-12"
                                        style="margin-bottom:14px;padding-top:22px;">
                                        <button class="df-button1" type="submit" style="width:100%;height:44px;">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ONE WAY -->
                    <div role="tabpanel" class="tab-pane" id="tab-oneway">
                        <div class="banner-search_tab">
                            <form action="<?php echo esc_url(home_url('/cab-booking')); ?>" method="GET">
                                <input type="hidden" name="service" value="Mjc=">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-map-pin"
                                                style="color:var(--primary);margin-right:5px;"></i> Pickup City</label>
                                        <select name="pickCity" class="selectpicker" data-live-search="true" required>
                                            <option value="">— Select City —</option>
                                            <?php echo get_location_options(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-flag-checkered"
                                                style="color:var(--primary);margin-right:5px;"></i> Drop City</label>
                                        <select name="dropCity" class="selectpicker" data-live-search="true" required>
                                            <option value="">— Select City —</option>
                                            <?php echo get_location_options(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12"
                                        style="margin-bottom:14px;padding-top:22px;">
                                        <button class="df-button1" type="submit" style="width:100%;height:44px;">
                                            <i class="fa fa-search"></i> Search Cabs
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ROUND TRIP -->
                    <div role="tabpanel" class="tab-pane" id="tab-round">
                        <div class="banner-search_tab">
                            <form action="<?php echo esc_url(home_url('/cab-booking')); ?>" method="GET">
                                <input type="hidden" name="service" value="MTYw">
                                <div class="row">
                                    <div class="col-md-9 col-sm-8 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-map-pin"
                                                style="color:var(--primary);margin-right:5px;"></i> Pickup City</label>
                                        <select name="pickCity" class="selectpicker" data-live-search="true" required>
                                            <option value="">— Select City —</option>
                                            <?php echo get_location_options(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-12"
                                        style="margin-bottom:14px;padding-top:22px;">
                                        <button class="df-button1" type="submit" style="width:100%;height:44px;">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- AIRPORT -->
                    <div role="tabpanel" class="tab-pane" id="tab-airport">
                        <div class="banner-search_tab">
                            <form action="<?php echo esc_url(home_url('/cab-booking')); ?>" method="GET">
                                <input type="hidden" name="service" value="MjU=">
                                <div class="row">
                                    <div class="col-md-9 col-sm-8 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-plane"
                                                style="color:var(--primary);margin-right:5px;"></i> Pickup Airport /
                                            City</label>
                                        <select name="pickCity" class="selectpicker" data-live-search="true" required>
                                            <option value="">— Select City —</option>
                                            <?php echo get_location_options(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-12"
                                        style="margin-bottom:14px;padding-top:22px;">
                                        <button class="df-button1" type="submit" style="width:100%;height:44px;">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- MULTI WAY -->
                    <div role="tabpanel" class="tab-pane" id="tab-multiway">
                        <div class="banner-search_tab">
                            <form action="<?php echo esc_url(home_url('/cab-booking')); ?>" method="GET">
                                <input type="hidden" name="service" value="MjY=">
                                <div class="row">
                                    <div class="col-md-9 col-sm-8 col-xs-12" style="margin-bottom:14px;">
                                        <label><i class="fa fa-route"
                                                style="color:var(--primary);margin-right:5px;"></i> Starting
                                            City</label>
                                        <select name="pickCity" class="selectpicker" data-live-search="true" required>
                                            <option value="">— Select City —</option>
                                            <?php echo get_location_options(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-12"
                                        style="margin-bottom:14px;padding-top:22px;">
                                        <button class="df-button1" type="submit" style="width:100%;height:44px;">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div><!-- /.tab-content -->
            </div>
        </div>
    </div>
</div>
<!--======================== END HERO ========================-->

<div class="main-contant">

    <!-- ============================================================
     SECTION 1: TAXI PACKAGES & PRODUCTS
     ============================================================ -->
    <section id="taxi-packages-section">
        <div class="container">
            <div class="row">
                
                <!-- LEFT SIDEBAR: PRODUCTS -->
                <div class="col-md-3" style="margin-bottom:30px;">
                    <div class="section-heading" style="margin-bottom:15px;text-align:left;">
                        <h3 class="title" style="font-size:24px;">Recommended <span class="text-color">Products</span></h3>
                    </div>
                    
                    <!-- City Filter Dropdown -->
                    <div class="form-group" style="margin-bottom:20px;">
                        <select id="product-city-filter" class="form-control" style="border-radius:8px;border:1.5px solid #dde3ef;height:44px;font-family:'Poppins',sans-serif;font-size:13px;width:100%;">
                            <option value="all">All Cities</option>
                            <?php echo get_location_options(); ?>
                        </select>
                    </div>

                    <!-- Products List Container -->
                    <div class="products-list-container" style="display:flex;flex-direction:column;gap:15px;max-height:600px;overflow-y:auto;padding-right:5px;overflow-x:hidden;">
                        <?php
global $wpdb;

// Load products manager
require_once get_template_directory() . '/includes/dynamic-management/products-manager.php';
$products_manager = new PT_Products_Manager();

// Get products from database (latest first)
$products = $products_manager->get_all(array(
    'limit' => -1,
    'status' => 'active',
    'orderby' => 'created_at',
    'order' => 'DESC'
));

if (!empty($products)):
    foreach ($products as $product):
        $p_price = $product->price_regular ?: 'Contact';
        $p_city = '';
        if ($product->location_id) {
            $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d AND is_active = 1", $product->location_id));
            if ($location) {
                $p_city = $location->title;
            }
            else {
                // Debug info for location issues
                $p_city = '[Location ID ' . $product->location_id . ' not found]';
            }
        }
        $p_city_simple = trim(explode(',', $p_city)[0]);
        $product_url = home_url('/product/' . $product->slug);
?>
                                <div class="product-item tr-total" data-city="<?php echo esc_attr(strtolower($p_city_simple)); ?>" style="background:#fff;border-radius:10px;padding:15px;box-shadow:var(--shadow-sm);border:1px solid #e8eef5;transition:var(--transition);display:flex;flex-direction:column;">
                                    <a href="<?php echo esc_url($product_url); ?>" style="text-decoration:none;color:inherit;flex:1;display:block;">
                                        <h4 style="font-size:15px;font-weight:700;color:var(--secondary);margin:0 0 6px;line-height:1.3;"><?php echo esc_html($product->title); ?></h4>
                                        <div style="font-size:12px;color:var(--text-light);margin-bottom:8px;"><i class="fa fa-map-marker-alt" style="color:var(--primary);"></i> <?php echo esc_html($p_city); ?></div>
                                        <div style="font-size:13px;color:var(--text-mid);margin-bottom:15px;line-height:1.5;"><?php echo wp_trim_words($product->short_description ?: $product->description, 12, '...'); ?></div>
                                        <div style="font-size:18px;font-weight:800;color:var(--primary);margin-bottom:15px;"><i class="fa fa-rupee-sign" style="font-size:16px;"></i> <?php echo esc_html(number_format($p_price, 2)); ?></div>
                                    </a>
                                    <div style="text-align:center;padding-top:4px;">
                                        <a href="<?php echo esc_url(home_url('/cab-booking')); ?>" class="df-button1" style="font-size:11px;padding:5px 14px;"><i class="fa fa-bolt"></i> Buy Now</a>
                                    </div>
                                </div>
                                <?php
    endforeach;
else:
?>
                            <div style="font-size:13px;color:#999;text-align:center;padding:20px;border:1px dashed #ccc;border-radius:8px;">No products available.</div>
                        <?php
endif; ?>
                    </div>

                    <!-- Client-side filter script -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var filter = document.getElementById("product-city-filter");
                            var items = document.querySelectorAll(".product-item");
                            if(filter) {
                                filter.addEventListener("change", function () {
                                    var selected = this.value.toLowerCase().split(",")[0].trim();
                                    items.forEach(function (item) {
                                        var itemCity = item.getAttribute("data-city");
                                        if (selected === "all" || selected === "" || itemCity === selected) {
                                            item.style.display = "block";
                                        } else {
                                            item.style.display = "none";
                                        }
                                    });
                                });
                            }
                        });
                    </script>
                </div>
                <!-- END LEFT SIDEBAR -->

                <!-- RIGHT COLUMN: TAXI PACKAGES -->
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-heading text-center" style="margin-bottom:20px;">
                                <h3 class="title">Recommended <span class="text-color">Taxi Packages</span></h3>
                                <p class="p-box">
                                    <i class="fa fa-check-circle" style="color:var(--primary);"></i> Fuel &nbsp;
                                    <i class="fa fa-check-circle" style="color:var(--primary);"></i> Toll &nbsp;
                                    <i class="fa fa-check-circle" style="color:var(--primary);"></i> Parking &nbsp;
                                    <i class="fa fa-check-circle" style="color:var(--primary);"></i> State Tax &nbsp;
                                    <i class="fa fa-check-circle" style="color:var(--primary);"></i> Driver Allowance
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row equal-height">
                        <?php
$package_query = new WP_Query(array('post_type' => 'taxi_package', 'posts_per_page' => 6));
if ($package_query->have_posts()):
    while ($package_query->have_posts()):
        $package_query->the_post();
        $price = get_post_meta(get_the_ID(), 'price', true) ?: 'On Request';
        $pickup_loc = get_post_meta(get_the_ID(), 'pickup_location', true) ?: 'Bhubaneswar';
        $person_count = get_post_meta(get_the_ID(), 'person_count', true) ?: '4';
        $duration = get_post_meta(get_the_ID(), 'duration', true);
        $distance_km = get_post_meta(get_the_ID(), 'distance_km', true);
?>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="destination-thumb thumb gap tr-total">
                                        <!-- Image -->
                                        <div class="zoomeffects">
                                            <div class="zoomEffect_1">
                                                <?php if (has_post_thumbnail()): ?>
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('medium', ['alt' => get_the_title(), 'loading' => 'lazy']); ?>
                                                    </a>
                                                <?php
        else: ?>
                                                    <a href="<?php the_permalink(); ?>"
                                                        style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:10px;text-decoration:none;">
                                                        <i class="fa fa-car" style="font-size:48px;color:rgba(255,255,255,.5);"></i>
                                                        <span style="color:rgba(255,255,255,.6);font-size:12px;">Taxi Package</span>
                                                    </a>
                                                <?php
        endif; ?>
                                            </div>
                                        </div>

                                        <!-- Title -->
                                        <div class="destination-text-box">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>

                                        <!-- Meta badges -->
                                        <div style="display:flex;flex-wrap:wrap;gap:6px;padding:4px 14px 8px;">
                                            <?php if ($duration): ?>
                                                <span
                                                    style="background:#f0f4ff;color:var(--secondary);font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500;">
                                                    <i class="fa fa-clock" style="color:var(--primary);"></i>
                                                    <?php echo esc_html($duration); ?>
                                                </span>
                                            <?php
        endif; ?>
                                            <?php if ($distance_km): ?>
                                                <span
                                                    style="background:#f0f4ff;color:var(--secondary);font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500;">
                                                    <i class="fa fa-road" style="color:var(--primary);"></i>
                                                    <?php echo esc_html($distance_km); ?> km
                                                </span>
                                            <?php
        endif; ?>
                                        </div>

                                        <!-- Price -->
                                        <div class="destination-text1">
                                            <i class="fa fa-rupee-sign" style="font-size:16px;"></i> <?php echo esc_html($price); ?>
                                            <div class="desti-small">For <?php echo esc_html($person_count); ?> Persons &bull; Incl. All
                                            </div>
                                        </div>

                                        <!-- Pickup -->
                                        <div class="destination-text">
                                            <small><i class="fa fa-map-marker-alt"></i> <?php echo esc_html($pickup_loc); ?></small>
                                        </div>

                                        <!-- CTA -->
                                        <div class="book-buttons">
                                            <a href="<?php the_permalink(); ?>" class="df-button2">Details</a>
                                            <a href="<?php echo esc_url(home_url('/cab-booking')); ?>" class="df-button1">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
    endwhile;
    wp_reset_postdata();
else: ?>
                            <div class="col-md-12 text-center" style="padding:60px 0;">
                                <i class="fa fa-car" style="font-size:60px;color:#dde3ef;margin-bottom:16px;display:block;"></i>
                                <p style="color:#999;font-size:16px;">No packages added yet.</p>
                                <?php if (current_user_can('edit_posts')): ?>
                                    <a href="<?php echo admin_url('post-new.php?post_type=taxi_package'); ?>" class="df-button1"
                                        style="margin-top:12px;">
                                        <i class="fa fa-plus"></i> Add First Package
                                    </a>
                                <?php
    endif; ?>
                            </div>
                        <?php
endif; ?>
                    </div><!-- .row -->

                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-12 text-center">
                            <a href="<?php echo get_post_type_archive_link('taxi_package'); ?>" class="df-button1">
                                View All Packages &nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div><!-- END RIGHT COLUMN -->
            </div><!-- .row -->
        </div><!-- .container -->
    </section>

    <hr class="section-divider">

    <!-- ============================================================
     SECTION 2: CAR FLEET
     ============================================================ -->
    <section id="car-rental-section">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading text-center">
                        <h3 class="title">Our <span class="text-color">Fleet of Cars</span></h3>
                        <p class="p-box">Choose from Mini, Sedan, SUV, Luxury, Tempo Traveller &amp; AC Coaches — all
                            GPS-tracked and sanitized for your safety.</p>
                    </div>
                </div>
            </div>

            <div class="row equal-height">
                <?php
$car_query = new WP_Query(array('post_type' => 'car_type', 'posts_per_page' => 6));
if ($car_query->have_posts()):
    while ($car_query->have_posts()):
        $car_query->the_post();
        $capacity = get_post_meta(get_the_ID(), 'capacity', true);
        $price_per_km = get_post_meta(get_the_ID(), 'price_per_km', true);
        $ac_status = get_post_meta(get_the_ID(), 'ac_status', true) ?: 'AC';
        $fuel_type = get_post_meta(get_the_ID(), 'fuel_type', true);
        $luggage = get_post_meta(get_the_ID(), 'luggage', true);
        $features_raw = get_post_meta(get_the_ID(), 'features', true);
        $features_arr = $features_raw ? array_slice(explode(',', $features_raw), 0, 3) : [];
?>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="destination-thumb thumb gap rnt-gap">
                                <!-- Image area -->
                                <div class="zoomeffects">
                                    <div class="zoomEffect_2">
                                        <?php if (has_post_thumbnail()): ?>
                                            <?php the_post_thumbnail('medium', ['alt' => get_the_title(), 'loading' => 'lazy', 'style' => 'width:100%;height:190px;object-fit:cover;']); ?>
                                        <?php
        else: ?>
                                            <div
                                                style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:10px;">
                                                <i class="fa fa-car-side" style="font-size:60px;color:rgba(255,255,255,.35);"></i>
                                                <span
                                                    style="color:rgba(255,255,255,.5);font-size:12px;"><?php the_title(); ?></span>
                                            </div>
                                        <?php
        endif; ?>
                                    </div>
                                </div>

                                <!-- Car name badge -->
                                <div class="mc-car-name">
                                    <p class="carname">
                                        <span><?php the_title(); ?></span>
                                            <span class="tx">
                                                <?php if ($ac_status): ?>
                                                    <i class="fa fa-snowflake"></i> <?php echo esc_html($ac_status); ?>
                                                <?php
        endif; ?>
                                                <?php if ($capacity): ?>
                                                    &nbsp;·&nbsp; <i class="fa fa-users"></i> <?php echo esc_html($capacity); ?>
                                                <?php
        endif; ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Price row -->
                                <?php if ($price_per_km): ?>
                                    <div class="car-price-row">
                                        <strong><i class="fa fa-rupee-sign"></i> <?php echo esc_html($price_per_km); ?>/km</strong>
                                        &nbsp;Starting Rate
                                    </div>
                                <?php
        endif; ?>

                                <!-- Feature chips -->
                                <?php if (!empty($features_arr)): ?>
                                    <div class="car-features" style="padding:0 16px 10px;">
                                        <?php foreach ($features_arr as $feat): ?>
                                            <span class="chip"><i
                                                    class="fa fa-check-circle"></i><?php echo esc_html(trim($feat)); ?></span>
                                        <?php
            endforeach; ?>
                                        <?php if ($luggage): ?>
                                            <span class="chip"><i class="fa fa-suitcase"></i> <?php echo esc_html($luggage); ?>
                                                Bags</span>
                                        <?php
            endif; ?>
                                        <?php if ($fuel_type): ?>
                                            <span class="chip"><i class="fa fa-gas-pump"></i> <?php echo esc_html($fuel_type); ?></span>
                                        <?php
            endif; ?>
                                    </div>
                                <?php
        endif; ?>

                                <!-- CTA -->
                                <div class="mc-feature" style="margin-top:auto;">
                                    <div class="book-buttons">
                                        <a href="<?php the_permalink(); ?>" class="df-button2">Details</a>
                                        <a href="<?php echo esc_url(home_url('/car-rentals')); ?>" class="df-button1">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
    endwhile;
    wp_reset_postdata();
else: ?>
                    <div class="col-md-12 text-center" style="padding:60px 0;">
                        <i class="fa fa-car-side"
                            style="font-size:60px;color:#dde3ef;margin-bottom:16px;display:block;"></i>
                        <p style="color:#999;font-size:16px;">No cars added yet.</p>
                        <?php if (current_user_can('edit_posts')): ?>
                            <a href="<?php echo admin_url('post-new.php?post_type=car_type'); ?>" class="df-button1"
                                style="margin-top:12px;">
                                <i class="fa fa-plus"></i> Add First Car
                            </a>
                        <?php
    endif; ?>
                    </div>
                <?php
endif; ?>
            </div>

        </div>
    </section>

    <hr class="section-divider">

    <!-- ============================================================
     SECTION 3: WHY CHOOSE US
     ============================================================ -->
    <section id="why-choose-section">
        <div class="container">
            <div class="section-heading text-center">
                <h3 class="title" style="color:#fff;">Why You <span style="color:var(--primary);">Will Love Us</span>
                </h3>
                <p style="color:rgba(255,255,255,.7);font-size:14px;max-width:600px;margin:0 auto;">Trusted by thousands
                    of happy travellers across India since 2010.</p>
            </div>

            <div class="why-grid">
                <div class="why-card">
                    <div class="why-card-inner">
                        <div class="why-number">01</div>
                        <div class="why-icon-wrap">
                            <i class="fa fa-shield-alt"></i>
                        </div>
                        <h4>Sanitized Vehicles</h4>
                        <p>All cabs are regularly cleaned and disinfected for your safety and comfort.</p>
                    </div>
                </div>
                <div class="why-card">
                    <div class="why-card-inner">
                        <div class="why-number">02</div>
                        <div class="why-icon-wrap">
                            <i class="fa fa-tags"></i>
                        </div>
                        <h4>Lowest Prices</h4>
                        <p>We guarantee the lowest cab fares with no hidden charges — ever.</p>
                    </div>
                </div>
                <div class="why-card">
                    <div class="why-card-inner">
                        <div class="why-number">03</div>
                        <div class="why-icon-wrap">
                            <i class="fa fa-award"></i>
                        </div>
                        <h4>10+ Years Experience</h4>
                        <p>Over a decade of serving travellers with professionalism &amp; care.</p>
                    </div>
                </div>
                <div class="why-card">
                    <div class="why-card-inner">
                        <div class="why-number">04</div>
                        <div class="why-icon-wrap">
                            <i class="fa fa-headset"></i>
                        </div>
                        <h4>24/7 Support</h4>
                        <p>Round-the-clock customer support via phone and WhatsApp.</p>
                    </div>
                </div>
                <div class="why-card">
                    <div class="why-card-inner">
                        <div class="why-number">05</div>
                        <div class="why-icon-wrap">
                            <i class="fa fa-map-marked-alt"></i>
                        </div>
                        <h4>GPS Tracking</h4>
                        <p>All vehicles are real-time GPS tracked for your peace of mind.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <!-- ============================================================
     SECTION 4: TESTIMONIALS
     ============================================================ -->
    <section id="testimonials-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading text-center">
                        <h3 class="title">What Our <span class="text-color">Customers Say</span></h3>
                        <p class="p-box">Real experiences from real travellers — discover why thousands trust Patra
                            Travels.</p>
                    </div>
                </div>
            </div>

            <!-- Horizontal Scroll Carousel -->
            <div class="testi-scroll-wrap">
                <div class="testi-scroll-track" id="testiTrack">
                    <?php
$testi_query = new WP_Query(array('post_type' => 'testimonial', 'posts_per_page' => 12));
if ($testi_query->have_posts()):
    while ($testi_query->have_posts()):
        $testi_query->the_post();
        $rating = intval(get_post_meta(get_the_ID(), 'rating', true)) ?: 5;
        $designation = get_post_meta(get_the_ID(), 'designation', true);
        $location = get_post_meta(get_the_ID(), 'location', true);
        $trip_type = get_post_meta(get_the_ID(), 'trip_type', true);
        $content = get_the_content() ?: get_the_excerpt();
?>
                            <div class="testi-card">
                                <div class="main-test-sec">
                                    <div class="test-txt">
                                        <!-- Reviewer header -->
                                        <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;">
                                            <div
                                                style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-dk));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="fa fa-user" style="color:#fff;font-size:16px;"></i>
                                            </div>
                                            <div>
                                                <div style="font-size:13px;font-weight:700;color:var(--secondary);">
                                                    <?php the_title(); ?></div>
                                                <?php if ($designation): ?>
                                                    <div style="font-size:11px;color:var(--text-light);">
                                                        <?php echo esc_html($designation); ?></div><?php
        endif; ?>
                                            </div>
                                        </div>
                                        <!-- Badges -->
                                        <?php if ($location || $trip_type): ?>
                                            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:10px;">
                                                <?php if ($location): ?>
                                                    <span
                                                        style="font-size:10px;background:#f0f4ff;color:var(--secondary);padding:2px 8px;border-radius:20px;"><i
                                                            class="fa fa-map-marker-alt" style="color:var(--primary);"></i>
                                                        <?php echo esc_html($location); ?></span>
                                                <?php
            endif; ?>
                                                <?php if ($trip_type): ?>
                                                    <span
                                                        style="font-size:10px;background:#fff4ef;color:var(--primary);padding:2px 8px;border-radius:20px;border:1px solid rgba(248,88,14,.2);"><i
                                                            class="fa fa-car"></i> <?php echo esc_html($trip_type); ?></span>
                                                <?php
            endif; ?>
                                            </div>
                                        <?php
        endif; ?>
                                        <!-- Quote -->
                                        <div class="test-content" style="font-size:12px;">
                                            &ldquo;<?php echo esc_html(wp_trim_words($content, 28, '...')); ?>&rdquo;</div>
                                        <!-- Stars -->
                                        <div class="icon-clr" style="margin-top:10px;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="<?php echo($i <= $rating) ? 'fas fa-star' : 'far fa-star'; ?>"></i>
                                            <?php
        endfor; ?>
                                            <span
                                                style="font-size:10px;color:var(--text-light);margin-left:4px;"><?php echo $rating; ?>.0
                                                / 5.0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
    endwhile;
    wp_reset_postdata();
else: ?>
                        <div style="padding:60px 20px;text-align:center;width:100%;">
                            <i class="fa fa-comments"
                                style="font-size:60px;color:#dde3ef;margin-bottom:16px;display:block;"></i>
                            <p style="color:#999;">No testimonials yet.</p>
                        </div>
                    <?php
endif; ?>
                </div><!-- /.testi-scroll-track -->
            </div><!-- /.testi-scroll-wrap -->
            <!-- Scroll nav arrows -->
            <div class="testi-nav">
                <button class="testi-arrow" id="testiPrev" aria-label="Previous"><i
                        class="fa fa-chevron-left"></i></button>
                <button class="testi-arrow" id="testiNext" aria-label="Next"><i
                        class="fa fa-chevron-right"></i></button>
            </div>

            <div class="row" style="margin-top:10px;">
                <div class="col-md-12 text-center">
                    <a href="<?php echo esc_url(home_url('/testimonials')); ?>" class="df-button1">
                        View All Reviews &nbsp;<i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div><!-- .main-contant -->

<?php get_footer(); ?>