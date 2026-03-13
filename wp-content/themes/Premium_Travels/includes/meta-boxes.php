<?php
/**
 * Custom Meta Boxes for Premium Travels Theme
 * Adds admin fields for all Custom Post Types
 */

// ============================================================
// 1. TAXI PACKAGE Meta Box
// ============================================================
function pt_taxi_package_meta_box()
{
    add_meta_box(
        'pt_taxi_package_details',
        '🚕 Taxi Package Details',
        'pt_taxi_package_meta_box_callback',
        'taxi_package',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pt_taxi_package_meta_box');

function pt_taxi_package_meta_box_callback($post)
{
    wp_nonce_field('pt_taxi_package_save', 'pt_taxi_package_nonce');
    $price = get_post_meta($post->ID, 'price', true);
    $pickup = get_post_meta($post->ID, 'pickup_location', true);
    $person_count = get_post_meta($post->ID, 'person_count', true);
    $duration = get_post_meta($post->ID, 'duration', true);
    $inclusions = get_post_meta($post->ID, 'inclusions', true);
    $exclusions = get_post_meta($post->ID, 'exclusions', true);
    $distance_km = get_post_meta($post->ID, 'distance_km', true);
    $nights = get_post_meta($post->ID, 'nights', true);
    ?>
    <table class="pt-meta-table">
        <tr>
            <th><label for="pt_price">Package Price (₹)</label></th>
            <td><input type="text" id="pt_price" name="pt_price" value="<?php echo esc_attr($price); ?>"
                    placeholder="e.g. 3,119.00" /></td>
        </tr>
        <tr>
            <th><label for="pt_pickup">Pickup Location</label></th>
            <td><input type="text" id="pt_pickup" name="pt_pickup" value="<?php echo esc_attr($pickup); ?>"
                    placeholder="e.g. Bhubaneswar, Odisha" /></td>
        </tr>
        <tr>
            <th><label for="pt_person_count">Person Count</label></th>
            <td><input type="number" id="pt_person_count" name="pt_person_count"
                    value="<?php echo esc_attr($person_count); ?>" min="1" max="50" placeholder="4" /></td>
        </tr>
        <tr>
            <th><label for="pt_duration">Duration</label></th>
            <td><input type="text" id="pt_duration" name="pt_duration" value="<?php echo esc_attr($duration); ?>"
                    placeholder="e.g. 3 Days / 2 Nights" /></td>
        </tr>
        <tr>
            <th><label for="pt_nights">Nights</label></th>
            <td><input type="number" id="pt_nights" name="pt_nights" value="<?php echo esc_attr($nights); ?>" min="0"
                    max="30" placeholder="2" /></td>
        </tr>
        <tr>
            <th><label for="pt_distance_km">Total Distance (km)</label></th>
            <td><input type="text" id="pt_distance_km" name="pt_distance_km" value="<?php echo esc_attr($distance_km); ?>"
                    placeholder="e.g. 450" /></td>
        </tr>
        <tr>
            <th><label for="pt_inclusions">Inclusions</label></th>
            <td><textarea id="pt_inclusions" name="pt_inclusions"
                    placeholder="Fuel, Toll, Parking, Driver Allowance..."><?php echo esc_textarea($inclusions); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="pt_exclusions">Exclusions</label></th>
            <td><textarea id="pt_exclusions" name="pt_exclusions"
                    placeholder="Hotel, Food, Entry Tickets..."><?php echo esc_textarea($exclusions); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function pt_taxi_package_save($post_id)
{
    if (!isset($_POST['pt_taxi_package_nonce']) || !wp_verify_nonce($_POST['pt_taxi_package_nonce'], 'pt_taxi_package_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $fields = [
        'pt_price' => 'price',
        'pt_pickup' => 'pickup_location',
        'pt_person_count' => 'person_count',
        'pt_duration' => 'duration',
        'pt_nights' => 'nights',
        'pt_distance_km' => 'distance_km',
        'pt_inclusions' => 'inclusions',
        'pt_exclusions' => 'exclusions'
    ];

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$input]));
        }
    }
}
add_action('save_post_taxi_package', 'pt_taxi_package_save');


// ============================================================
// 2. CAR TYPE Meta Box
// ============================================================
function pt_car_type_meta_box()
{
    add_meta_box(
        'pt_car_type_details',
        '🚗 Car Type Details',
        'pt_car_type_meta_box_callback',
        'car_type',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pt_car_type_meta_box');

function pt_car_type_meta_box_callback($post)
{
    wp_nonce_field('pt_car_type_save', 'pt_car_type_nonce');
    $capacity = get_post_meta($post->ID, 'capacity', true);
    $price_per_km = get_post_meta($post->ID, 'price_per_km', true);
    $ac_status = get_post_meta($post->ID, 'ac_status', true);
    $features = get_post_meta($post->ID, 'features', true);
    $luggage = get_post_meta($post->ID, 'luggage', true);
    $fuel_type = get_post_meta($post->ID, 'fuel_type', true);
    ?>
    <table class="pt-meta-table">
        <tr>
            <th><label for="pt_capacity">Seating Capacity</label></th>
            <td><input type="text" id="pt_capacity" name="pt_capacity" value="<?php echo esc_attr($capacity); ?>"
                    placeholder="e.g. 4 G + 1 D" /></td>
        </tr>
        <tr>
            <th><label for="pt_price_per_km">Price per km (₹)</label></th>
            <td><input type="text" id="pt_price_per_km" name="pt_price_per_km"
                    value="<?php echo esc_attr($price_per_km); ?>" placeholder="e.g. 12" /></td>
        </tr>
        <tr>
            <th><label for="pt_ac_status">AC Status</label></th>
            <td>
                <select id="pt_ac_status" name="pt_ac_status">
                    <option value="AC" <?php selected($ac_status, 'AC'); ?>>AC</option>
                    <option value="Non-AC" <?php selected($ac_status, 'Non-AC'); ?>>Non-AC</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="pt_fuel_type">Fuel Type</label></th>
            <td>
                <select id="pt_fuel_type" name="pt_fuel_type">
                    <option value="Petrol" <?php selected($fuel_type, 'Petrol'); ?>>Petrol</option>
                    <option value="Diesel" <?php selected($fuel_type, 'Diesel'); ?>>Diesel</option>
                    <option value="CNG" <?php selected($fuel_type, 'CNG'); ?>>CNG</option>
                    <option value="Electric" <?php selected($fuel_type, 'Electric'); ?>>Electric</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="pt_luggage">Luggage Bags</label></th>
            <td><input type="number" id="pt_luggage" name="pt_luggage" value="<?php echo esc_attr($luggage); ?>" min="0"
                    max="20" placeholder="2" /></td>
        </tr>
        <tr>
            <th><label for="pt_features">Features</label></th>
            <td><textarea id="pt_features" name="pt_features"
                    placeholder="Music System, GPS Tracking, Sanitized..."><?php echo esc_textarea($features); ?></textarea>
            </td>
        </tr>
    </table>
    <?php
}

function pt_car_type_save($post_id)
{
    if (!isset($_POST['pt_car_type_nonce']) || !wp_verify_nonce($_POST['pt_car_type_nonce'], 'pt_car_type_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $fields = [
        'pt_capacity' => 'capacity',
        'pt_price_per_km' => 'price_per_km',
        'pt_ac_status' => 'ac_status',
        'pt_fuel_type' => 'fuel_type',
        'pt_luggage' => 'luggage',
        'pt_features' => 'features'
    ];

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$input]));
        }
    }
}
add_action('save_post_car_type', 'pt_car_type_save');


// ============================================================
// 3. HOLIDAY PACKAGE Meta Box
// ============================================================
function pt_holiday_package_meta_box()
{
    add_meta_box(
        'pt_holiday_package_details',
        '🌴 Holiday Package Details',
        'pt_holiday_package_meta_box_callback',
        'holiday_package',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pt_holiday_package_meta_box');

function pt_holiday_package_meta_box_callback($post)
{
    wp_nonce_field('pt_holiday_package_save', 'pt_holiday_package_nonce');
    $price = get_post_meta($post->ID, 'price', true);
    $duration = get_post_meta($post->ID, 'duration', true);
    $pickup = get_post_meta($post->ID, 'pickup_location', true);
    $person_count = get_post_meta($post->ID, 'person_count', true);
    $inclusions = get_post_meta($post->ID, 'inclusions', true);
    $exclusions = get_post_meta($post->ID, 'exclusions', true);
    $highlights = get_post_meta($post->ID, 'highlights', true);
    $hotel_stars = get_post_meta($post->ID, 'hotel_stars', true);
    ?>
    <table class="pt-meta-table">
        <tr>
            <th><label for="hp_price">Package Price (₹)</label></th>
            <td><input type="text" id="hp_price" name="hp_price" value="<?php echo esc_attr($price); ?>"
                    placeholder="e.g. 35,000.00" /></td>
        </tr>
        <tr>
            <th><label for="hp_duration">Duration</label></th>
            <td><input type="text" id="hp_duration" name="hp_duration" value="<?php echo esc_attr($duration); ?>"
                    placeholder="e.g. 7 Days / 6 Nights" /></td>
        </tr>
        <tr>
            <th><label for="hp_pickup">Pickup City</label></th>
            <td><input type="text" id="hp_pickup" name="hp_pickup" value="<?php echo esc_attr($pickup); ?>"
                    placeholder="e.g. Bhubaneswar" /></td>
        </tr>
        <tr>
            <th><label for="hp_person_count">Person Count</label></th>
            <td><input type="number" id="hp_person_count" name="hp_person_count"
                    value="<?php echo esc_attr($person_count); ?>" min="1" max="100" placeholder="2" /></td>
        </tr>
        <tr>
            <th><label for="hp_hotel_stars">Hotel Category</label></th>
            <td>
                <select id="hp_hotel_stars" name="hp_hotel_stars">
                    <option value="">-- Select --</option>
                    <option value="Budget" <?php selected($hotel_stars, 'Budget'); ?>>Budget</option>
                    <option value="3 Star" <?php selected($hotel_stars, '3 Star'); ?>>3 Star</option>
                    <option value="4 Star" <?php selected($hotel_stars, '4 Star'); ?>>4 Star</option>
                    <option value="5 Star" <?php selected($hotel_stars, '5 Star'); ?>>5 Star</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="hp_highlights">Highlights</label></th>
            <td><textarea id="hp_highlights" name="hp_highlights"
                    placeholder="Key sightseeing spots..."><?php echo esc_textarea($highlights); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="hp_inclusions">Inclusions</label></th>
            <td><textarea id="hp_inclusions" name="hp_inclusions"
                    placeholder="Hotel, Cab, Breakfast..."><?php echo esc_textarea($inclusions); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="hp_exclusions">Exclusions</label></th>
            <td><textarea id="hp_exclusions" name="hp_exclusions"
                    placeholder="Flight, Personal Expenses..."><?php echo esc_textarea($exclusions); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function pt_holiday_package_save($post_id)
{
    if (!isset($_POST['pt_holiday_package_nonce']) || !wp_verify_nonce($_POST['pt_holiday_package_nonce'], 'pt_holiday_package_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $fields = [
        'hp_price' => 'price',
        'hp_duration' => 'duration',
        'hp_pickup' => 'pickup_location',
        'hp_person_count' => 'person_count',
        'hp_hotel_stars' => 'hotel_stars',
        'hp_highlights' => 'highlights',
        'hp_inclusions' => 'inclusions',
        'hp_exclusions' => 'exclusions'
    ];

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$input]));
        }
    }
}
add_action('save_post_holiday_package', 'pt_holiday_package_save');


// ============================================================
// 4. TESTIMONIAL Meta Box
// ============================================================
function pt_testimonial_meta_box()
{
    add_meta_box(
        'pt_testimonial_details',
        '⭐ Testimonial Details',
        'pt_testimonial_meta_box_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pt_testimonial_meta_box');

function pt_testimonial_meta_box_callback($post)
{
    wp_nonce_field('pt_testimonial_save', 'pt_testimonial_nonce');
    $rating = get_post_meta($post->ID, 'rating', true) ?: 5;
    $designation = get_post_meta($post->ID, 'designation', true);
    $location = get_post_meta($post->ID, 'location', true);
    $trip_type = get_post_meta($post->ID, 'trip_type', true);
    $photo_url = get_post_meta($post->ID, 'photo_url', true);
    ?>
    <table class="pt-meta-table">
        <tr>
            <th><label for="testi_rating">Star Rating (1–5)</label></th>
            <td>
                <select id="testi_rating" name="testi_rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>>
                            <?php echo $i; ?> Star
                            <?php echo $i > 1 ? 's' : ''; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="testi_designation">Designation / Profession</label></th>
            <td><input type="text" id="testi_designation" name="testi_designation"
                    value="<?php echo esc_attr($designation); ?>" placeholder="e.g. Software Engineer" /></td>
        </tr>
        <tr>
            <th><label for="testi_location">Client Location</label></th>
            <td><input type="text" id="testi_location" name="testi_location" value="<?php echo esc_attr($location); ?>"
                    placeholder="e.g. Bhubaneswar, Odisha" /></td>
        </tr>
        <tr>
            <th><label for="testi_trip_type">Trip Type</label></th>
            <td>
                <select id="testi_trip_type" name="testi_trip_type">
                    <option value="">-- Select Trip --</option>
                    <option value="Local Trip" <?php selected($trip_type, 'Local Trip'); ?>>Local Trip</option>
                    <option value="Airport Transfer" <?php selected($trip_type, 'Airport Transfer'); ?>>Airport Transfer
                    </option>
                    <option value="Outstation" <?php selected($trip_type, 'Outstation'); ?>>Outstation</option>
                    <option value="Holiday Package" <?php selected($trip_type, 'Holiday Package'); ?>>Holiday Package
                    </option>
                    <option value="Round Trip" <?php selected($trip_type, 'Round Trip'); ?>>Round Trip</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="testi_photo_url">Client Photo URL</label></th>
            <td><input type="text" id="testi_photo_url" name="testi_photo_url" value="<?php echo esc_attr($photo_url); ?>"
                    placeholder="https://..." /></td>
        </tr>
    </table>
    <?php
}

function pt_testimonial_save($post_id)
{
    if (!isset($_POST['pt_testimonial_nonce']) || !wp_verify_nonce($_POST['pt_testimonial_nonce'], 'pt_testimonial_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $fields = [
        'testi_rating' => 'rating',
        'testi_designation' => 'designation',
        'testi_location' => 'location',
        'testi_trip_type' => 'trip_type',
        'testi_photo_url' => 'photo_url'
    ];

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$input]));
        }
    }
}
add_action('save_post_testimonial', 'pt_testimonial_save');


// ============================================================
// 5. SPECIAL OFFER Meta Box
// ============================================================
function pt_special_offer_meta_box()
{
    add_meta_box(
        'pt_special_offer_details',
        '🏷️ Special Offer Details',
        'pt_special_offer_meta_box_callback',
        'special_offer',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pt_special_offer_meta_box');

function pt_special_offer_meta_box_callback($post)
{
    wp_nonce_field('pt_special_offer_save', 'pt_special_offer_nonce');
    $discount = get_post_meta($post->ID, 'discount_percentage', true);
    $valid_till = get_post_meta($post->ID, 'valid_till', true);
    $applicable = get_post_meta($post->ID, 'applicable_for', true);
    $coupon_code = get_post_meta($post->ID, 'coupon_code', true);
    $min_booking = get_post_meta($post->ID, 'minimum_booking', true);
    ?>
    <table class="pt-meta-table">
        <tr>
            <th><label for="offer_discount">Discount Percentage (%)</label></th>
            <td><input type="number" id="offer_discount" name="offer_discount" value="<?php echo esc_attr($discount); ?>"
                    min="1" max="100" placeholder="20" /></td>
        </tr>
        <tr>
            <th><label for="offer_valid_till">Valid Till (Date)</label></th>
            <td><input type="date" id="offer_valid_till" name="offer_valid_till"
                    value="<?php echo esc_attr($valid_till); ?>" /></td>
        </tr>
        <tr>
            <th><label for="offer_coupon">Coupon Code</label></th>
            <td><input type="text" id="offer_coupon" name="offer_coupon" value="<?php echo esc_attr($coupon_code); ?>"
                    placeholder="e.g. SAVE20" /></td>
        </tr>
        <tr>
            <th><label for="offer_applicable">Applicable For</label></th>
            <td>
                <select id="offer_applicable" name="offer_applicable">
                    <option value="">-- All Services --</option>
                    <option value="Local Trip" <?php selected($applicable, 'Local Trip'); ?>>Local Trip</option>
                    <option value="Airport Transfer" <?php selected($applicable, 'Airport Transfer'); ?>>Airport Transfer
                    </option>
                    <option value="Outstation" <?php selected($applicable, 'Outstation'); ?>>Outstation</option>
                    <option value="Holiday Package" <?php selected($applicable, 'Holiday Package'); ?>>Holiday Package
                    </option>
                    <option value="All Services" <?php selected($applicable, 'All Services'); ?>>All Services</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="offer_min_booking">Minimum Booking (₹)</label></th>
            <td><input type="text" id="offer_min_booking" name="offer_min_booking"
                    value="<?php echo esc_attr($min_booking); ?>" placeholder="e.g. 3000" /></td>
        </tr>
    </table>
    <?php
}

function pt_special_offer_save($post_id)
{
    if (!isset($_POST['pt_special_offer_nonce']) || !wp_verify_nonce($_POST['pt_special_offer_nonce'], 'pt_special_offer_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $fields = [
        'offer_discount' => 'discount_percentage',
        'offer_valid_till' => 'valid_till',
        'offer_coupon' => 'coupon_code',
        'offer_applicable' => 'applicable_for',
        'offer_min_booking' => 'minimum_booking'
    ];

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$input]));
        }
    }
}
add_action('save_post_special_offer', 'pt_special_offer_save');


// ============================================================
// 6. ROUTE Meta Box
// ============================================================
function pt_route_meta_box()
{
    add_meta_box(
        'pt_route_details',
        '🗺️ Route Details',
        'pt_route_meta_box_callback',
        'route',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pt_route_meta_box');

function pt_route_meta_box_callback($post)
{
    wp_nonce_field('pt_route_save', 'pt_route_nonce');
    $from_city = get_post_meta($post->ID, 'from_city', true);
    $to_city = get_post_meta($post->ID, 'to_city', true);
    $distance_km = get_post_meta($post->ID, 'distance_km', true);
    $price_per_km = get_post_meta($post->ID, 'price_per_km', true);
    $travel_time = get_post_meta($post->ID, 'travel_time', true);
    $route_type = get_post_meta($post->ID, 'route_type', true);
    ?>
    <table class="pt-meta-table">
        <tr>
            <th><label for="route_from">From City</label></th>
            <td><input type="text" id="route_from" name="route_from" value="<?php echo esc_attr($from_city); ?>"
                    placeholder="e.g. Bhubaneswar" /></td>
        </tr>
        <tr>
            <th><label for="route_to">To City</label></th>
            <td><input type="text" id="route_to" name="route_to" value="<?php echo esc_attr($to_city); ?>"
                    placeholder="e.g. Puri" /></td>
        </tr>
        <tr>
            <th><label for="route_distance">Distance (km)</label></th>
            <td><input type="number" id="route_distance" name="route_distance" value="<?php echo esc_attr($distance_km); ?>"
                    min="1" placeholder="60" /></td>
        </tr>
        <tr>
            <th><label for="route_price_per_km">Price per km (₹)</label></th>
            <td><input type="text" id="route_price_per_km" name="route_price_per_km"
                    value="<?php echo esc_attr($price_per_km); ?>" placeholder="e.g. 12" /></td>
        </tr>
        <tr>
            <th><label for="route_travel_time">Travel Time</label></th>
            <td><input type="text" id="route_travel_time" name="route_travel_time"
                    value="<?php echo esc_attr($travel_time); ?>" placeholder="e.g. 1.5 Hours" /></td>
        </tr>
        <tr>
            <th><label for="route_type">Route Type</label></th>
            <td>
                <select id="route_type" name="route_type">
                    <option value="">-- Select --</option>
                    <option value="One-Way" <?php selected($route_type, 'One-Way'); ?>>One-Way</option>
                    <option value="Round-Trip" <?php selected($route_type, 'Round-Trip'); ?>>Round-Trip</option>
                    <option value="Airport Transfer" <?php selected($route_type, 'Airport Transfer'); ?>>Airport Transfer
                    </option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

function pt_route_save($post_id)
{
    if (!isset($_POST['pt_route_nonce']) || !wp_verify_nonce($_POST['pt_route_nonce'], 'pt_route_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $fields = [
        'route_from' => 'from_city',
        'route_to' => 'to_city',
        'route_distance' => 'distance_km',
        'route_price_per_km' => 'price_per_km',
        'route_travel_time' => 'travel_time',
        'route_type' => 'route_type'
    ];

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$input]));
        }
    }
}
add_action('save_post_route', 'pt_route_save');


// ============================================================
// 7. LOCATION Meta Box
// ============================================================
function pt_location_meta_box()
{
    add_meta_box(
        'pt_location_details',
        '📍 Location Details',
        'pt_location_meta_box_callback',
        'location',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pt_location_meta_box');

function pt_location_meta_box_callback($post)
{
    wp_nonce_field('pt_location_save', 'pt_location_nonce');
    $state = get_post_meta($post->ID, 'state', true);
    $airport = get_post_meta($post->ID, 'airport', true);
    $coordinates = get_post_meta($post->ID, 'coordinates', true);
    $is_active = get_post_meta($post->ID, 'is_active', true);
    ?>
    <table class="pt-meta-table">
        <tr>
            <th><label for="loc_state">State</label></th>
            <td><input type="text" id="loc_state" name="loc_state" value="<?php echo esc_attr($state); ?>"
                    placeholder="e.g. Odisha" /></td>
        </tr>
        <tr>
            <th><label for="loc_airport">Nearest Airport</label></th>
            <td><input type="text" id="loc_airport" name="loc_airport" value="<?php echo esc_attr($airport); ?>"
                    placeholder="e.g. Bhubaneswar Airport (BBI)" /></td>
        </tr>
        <tr>
            <th><label for="loc_coordinates">GPS Coordinates</label></th>
            <td><input type="text" id="loc_coordinates" name="loc_coordinates" value="<?php echo esc_attr($coordinates); ?>"
                    placeholder="e.g. 20.2961, 85.8245" /></td>
        </tr>
        <tr>
            <th><label for="loc_is_active">Show in Dropdown</label></th>
            <td>
                <select id="loc_is_active" name="loc_is_active">
                    <option value="yes" <?php selected($is_active, 'yes'); ?>>Yes</option>
                    <option value="no" <?php selected($is_active, 'no'); ?>>No</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

function pt_location_save($post_id)
{
    if (!isset($_POST['pt_location_nonce']) || !wp_verify_nonce($_POST['pt_location_nonce'], 'pt_location_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $fields = ['loc_state' => 'state', 'loc_airport' => 'airport', 'loc_coordinates' => 'coordinates', 'loc_is_active' => 'is_active'];

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$input]));
        }
    }
}
add_action('save_post_location', 'pt_location_save');


// ============================================================
// 8. PRODUCT Meta Box
// ============================================================
function pt_product_meta_box()
{
    add_meta_box(
        'pt_product_details',
        '🛍️ Product Details',
        'pt_product_meta_box_callback',
        'pt_product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pt_product_meta_box');

function pt_product_meta_box_callback($post)
{
    wp_nonce_field('pt_product_save', 'pt_product_nonce');
    $price_regular = get_post_meta($post->ID, 'price_regular', true);
    $price_sale = get_post_meta($post->ID, 'price_sale', true);
    $location = get_post_meta($post->ID, 'location_id', true);
    $product_type = get_post_meta($post->ID, 'product_type', true);
    $short_description = get_post_meta($post->ID, 'short_description', true);
    $inventory_qty = get_post_meta($post->ID, 'inventory_qty', true);
    $sku = get_post_meta($post->ID, 'sku', true);
    $weight = get_post_meta($post->ID, 'weight', true);
    $featured_image = get_post_meta($post->ID, 'featured_image', true);
    $gallery_images  = get_post_meta($post->ID, 'gallery_images', true);
    $gallery_urls = array_filter(array_map('trim', explode(',', $gallery_images)));
    
    // Enqueue media uploader
    wp_enqueue_media();
    ?>
    <table class="pt-meta-table">
        <tr>
            <th><label for="product_price_regular">Regular Price (₹)</label></th>
            <td><input type="text" id="product_price_regular" name="product_price_regular" value="<?php echo esc_attr($price_regular); ?>"
                    placeholder="e.g. 2,999.00" /></td>
        </tr>
        <tr>
            <th><label for="product_price_sale">Sale Price (₹)</label></th>
            <td><input type="text" id="product_price_sale" name="product_price_sale" value="<?php echo esc_attr($price_sale); ?>"
                    placeholder="e.g. 1,999.00" /></td>
        </tr>
        <tr>
            <th><label for="product_location">Location</label></th>
            <td>
                <select id="product_location" name="product_location">
                    <option value="">-- Select Location --</option>
                    <?php
                    $locations = get_posts([
                        'post_type' => 'location',
                        'numberposts' => -1,
                        'post_status' => 'publish'
                    ]);
                    foreach ($locations as $loc) {
                        echo '<option value="' . $loc->ID . '" ' . selected($location, $loc->ID, false) . '>' . esc_html($loc->post_title) . '</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="product_type">Product Type</label></th>
            <td>
                <select id="product_type" name="product_type">
                    <option value="">-- Select Type --</option>
                    <option value="physical" <?php selected($product_type, 'physical'); ?>>Physical Product</option>
                    <option value="digital" <?php selected($product_type, 'digital'); ?>>Digital Product</option>
                    <option value="service" <?php selected($product_type, 'service'); ?>>Service</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="product_sku">SKU</label></th>
            <td><input type="text" id="product_sku" name="product_sku" value="<?php echo esc_attr($sku); ?>"
                    placeholder="e.g. PROD-001" /></td>
        </tr>
        <tr>
            <th><label for="product_inventory">Inventory Quantity</label></th>
            <td><input type="number" id="product_inventory" name="product_inventory" value="<?php echo esc_attr($inventory_qty); ?>"
                    min="0" placeholder="100" /></td>
        </tr>
        <tr>
            <th><label for="product_weight">Weight (kg)</label></th>
            <td><input type="text" id="product_weight" name="product_weight" value="<?php echo esc_attr($weight); ?>"
                    placeholder="e.g. 0.5" /></td>
        </tr>
        <tr>
            <th><label for="product_short_desc">Short Description</label></th>
            <td><textarea id="product_short_desc" name="product_short_desc"
                    placeholder="Brief product description..."><?php echo esc_textarea($short_description); ?></textarea></td>
        </tr>
        <!-- ===== PRODUCT IMAGES ===== -->
        <tr>
            <td colspan="2" style="padding: 12px 0 4px; font-weight: 600; font-size: 13px; border-top: 1px solid #ddd; color: #1d2327;">📸 Product Images</td>
        </tr>
        <tr>
            <th><label>Featured Image</label></th>
            <td>
                <div id="pt-mb-featured-preview" style="margin-bottom: 8px; <?php echo $featured_image ? '' : 'display:none;'; ?>">
                    <img src="<?php echo esc_url($featured_image); ?>" style="max-width: 200px; max-height: 140px; border-radius: 5px; border: 1px solid #ddd; display: block; margin-bottom: 5px;" />
                    <a href="#" id="pt-mb-remove-featured" style="color: #d63638; font-size: 12px;">✕ Remove</a>
                </div>
                <input type="hidden" name="product_featured_image" id="pt-mb-featured-url" value="<?php echo esc_attr($featured_image); ?>" />
                <button type="button" class="button" id="pt-mb-upload-featured">
                    <?php echo $featured_image ? '🖼 Change Featured Image' : '🖼 Upload Featured Image'; ?>
                </button>
                <p style="margin: 4px 0 0; color: #666; font-size: 11px;">Main image shown on the product detail page.</p>
            </td>
        </tr>
        <tr>
            <th><label>Gallery Images</label></th>
            <td>
                <div id="pt-mb-gallery-preview" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                    <?php foreach ($gallery_urls as $gurl): ?>
                        <div class="pt-mb-gallery-item" style="position: relative; display: inline-block;">
                            <img src="<?php echo esc_url($gurl); ?>" style="width: 80px; height: 65px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; display: block;" />
                            <a href="#" class="pt-mb-remove-gallery" data-url="<?php echo esc_attr($gurl); ?>" style="position: absolute; top: 1px; right: 2px; background: rgba(214,54,56,0.85); color: #fff; border-radius: 50%; width: 16px; height: 16px; text-align: center; line-height: 15px; font-size: 10px; text-decoration: none; font-weight: bold;">✕</a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="product_gallery_images" id="pt-mb-gallery-input" value="<?php echo esc_attr($gallery_images); ?>" />
                <button type="button" class="button" id="pt-mb-upload-gallery">🖼 Add Gallery Images</button>
                <p style="margin: 4px 0 0; color: #666; font-size: 11px;">Select multiple images. Shown as clickable thumbnails below the main image.</p>
            </td>
        </tr>
    </table>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // --- Featured Image ---
        var ptFeatFrame;
        $('#pt-mb-upload-featured').on('click', function(e) {
            e.preventDefault();
            if (ptFeatFrame) { ptFeatFrame.open(); return; }
            ptFeatFrame = wp.media({ title: 'Select Featured Image', button: { text: 'Use this image' }, multiple: false });
            ptFeatFrame.on('select', function() {
                var url = ptFeatFrame.state().get('selection').first().toJSON().url;
                $('#pt-mb-featured-url').val(url);
                $('#pt-mb-featured-preview img').attr('src', url);
                $('#pt-mb-featured-preview').show();
                $('#pt-mb-upload-featured').text('🖼 Change Featured Image');
            });
            ptFeatFrame.open();
        });
        $('#pt-mb-remove-featured').on('click', function(e) {
            e.preventDefault();
            $('#pt-mb-featured-url').val('');
            $('#pt-mb-featured-preview').hide();
            $('#pt-mb-upload-featured').text('🖼 Upload Featured Image');
        });

        // --- Gallery Images ---
        var ptGalFrame;
        $('#pt-mb-upload-gallery').on('click', function(e) {
            e.preventDefault();
            ptGalFrame = wp.media({ title: 'Add Gallery Images', button: { text: 'Add to Gallery' }, multiple: 'add' });
            ptGalFrame.on('select', function() {
                var sel = ptGalFrame.state().get('selection');
                var cur = $('#pt-mb-gallery-input').val();
                var urls = cur ? cur.split(',').map(s => s.trim()).filter(Boolean) : [];
                sel.each(function(att) {
                    var url = att.toJSON().url;
                    if (urls.indexOf(url) === -1) {
                        urls.push(url);
                        var item = $('<div class="pt-mb-gallery-item" style="position:relative;display:inline-block;">' +
                            '<img src="' + url + '" style="width:80px;height:65px;object-fit:cover;border-radius:4px;border:1px solid #ddd;display:block;"/>' +
                            '<a href="#" class="pt-mb-remove-gallery" data-url="' + url + '" style="position:absolute;top:1px;right:2px;background:rgba(214,54,56,0.85);color:#fff;border-radius:50%;width:16px;height:16px;text-align:center;line-height:15px;font-size:10px;text-decoration:none;font-weight:bold;">✕</a>' +
                            '</div>');
                        $('#pt-mb-gallery-preview').append(item);
                    }
                });
                $('#pt-mb-gallery-input').val(urls.join(','));
            });
            ptGalFrame.open();
        });
        $(document).on('click', '.pt-mb-remove-gallery', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            $(this).closest('.pt-mb-gallery-item').remove();
            var urls = $('#pt-mb-gallery-input').val().split(',').map(s => s.trim()).filter(u => u && u !== url);
            $('#pt-mb-gallery-input').val(urls.join(','));
        });
    });
    </script>
    <?php
}

function pt_product_save($post_id)
{
    if (!isset($_POST['pt_product_nonce']) || !wp_verify_nonce($_POST['pt_product_nonce'], 'pt_product_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    global $wpdb;
    
    // Check if this product already exists in our custom table
    $existing_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_products WHERE title = %s",
            get_the_title($post_id)
        )
    );
    
    // Prepare data for custom table
    $title = get_the_title($post_id);
    $data = array(
        'title' => $title,
        'slug' => sanitize_title($title),
        'description' => get_post_field('post_content', $post_id),
        'product_type' => isset($_POST['product_type']) ? sanitize_text_field($_POST['product_type']) : 'physical',
        'location_id' => isset($_POST['product_location']) ? intval($_POST['product_location']) : 0,
        'price_regular' => isset($_POST['product_price_regular']) ? floatval($_POST['product_price_regular']) : 0,
        'price_sale' => isset($_POST['product_price_sale']) ? floatval($_POST['product_price_sale']) : null,
        'sku' => isset($_POST['product_sku']) ? sanitize_text_field($_POST['product_sku']) : '',
        'stock_quantity' => isset($_POST['product_inventory']) ? intval($_POST['product_inventory']) : 0,
        'weight' => isset($_POST['product_weight']) ? floatval($_POST['product_weight']) : 0,
        'short_description' => isset($_POST['product_short_desc']) ? sanitize_textarea_field($_POST['product_short_desc']) : '',
        'featured_image'    => isset($_POST['product_featured_image']) ? esc_url_raw($_POST['product_featured_image']) : '',
        'gallery_images'    => isset($_POST['product_gallery_images']) ? sanitize_text_field($_POST['product_gallery_images']) : '',
        'is_active' => 1,
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql')
    );
    
    // Calculate discount percentage if sale price exists
    if ($data['price_sale'] && $data['price_regular'] > 0) {
        $data['discount_percentage'] = (($data['price_regular'] - $data['price_sale']) / $data['price_regular']) * 100;
    }
    
    if ($existing_id) {
        // Update existing record
        $data['updated_at'] = current_time('mysql');
        $result = $wpdb->update(
            $wpdb->prefix . 'pt_products',
            $data,
            array('id' => $existing_id)
        );
    } else {
        // Insert new record
        $result = $wpdb->insert(
            $wpdb->prefix . 'pt_products',
            $data
        );
    }
    
    // Also save to WordPress post meta for backward compatibility
    $meta_fields = [
        'product_price_regular'  => 'price_regular',
        'product_price_sale'     => 'price_sale',
        'product_location'       => 'location_id',
        'product_type'           => 'product_type',
        'product_sku'            => 'sku',
        'product_inventory'      => 'inventory_qty',
        'product_weight'         => 'weight',
        'product_short_desc'     => 'short_description',
        'product_featured_image' => 'featured_image',
        'product_gallery_images' => 'gallery_images',
    ];

    foreach ($meta_fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            if ($input === 'product_short_desc' || $input === 'product_gallery_images') {
                $value = sanitize_textarea_field($_POST[$input]);
            } elseif ($input === 'product_featured_image') {
                $value = esc_url_raw($_POST[$input]);
            } else {
                $value = sanitize_text_field($_POST[$input]);
            }
            update_post_meta($post_id, $meta_key, $value);
        }
    }
}
add_action('save_post_pt_product', 'pt_product_save');


// ============================================================
// 10. Product Data Migration Function
// ============================================================
/**
 * Migrate existing pt_product CPT data to custom database table
 */
function pt_migrate_products_to_database() {
    global $wpdb;
    
    // Get all existing pt_product posts
    $args = array(
        'post_type' => 'pt_product',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    
    $products = get_posts($args);
    $migrated = 0;
    $skipped = 0;
    
    foreach ($products as $product) {
        // Check if already exists in custom table
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}pt_products WHERE title = %s",
                $product->post_title
            )
        );
        
        if (!$existing) {
            // Get post meta data
            $price_regular = get_post_meta($product->ID, 'price_regular', true);
            $price_sale = get_post_meta($product->ID, 'price_sale', true);
            $location_id = get_post_meta($product->ID, 'location_id', true);
            $product_type = get_post_meta($product->ID, 'product_type', true);
            $sku = get_post_meta($product->ID, 'sku', true);
            $inventory_qty = get_post_meta($product->ID, 'inventory_qty', true);
            $weight = get_post_meta($product->ID, 'weight', true);
            $short_description = get_post_meta($product->ID, 'short_description', true);
            
            // Prepare data
            $data = array(
                'title' => $product->post_title,
                'description' => $product->post_content,
                'product_type' => $product_type ?: 'physical',
                'location_id' => $location_id ? intval($location_id) : 0,
                'price_regular' => $price_regular ? floatval($price_regular) : 0,
                'price_sale' => $price_sale ? floatval($price_sale) : null,
                'sku' => $sku ?: '',
                'stock_quantity' => $inventory_qty ? intval($inventory_qty) : 0,
                'weight' => $weight ? floatval($weight) : 0,
                'short_description' => $short_description ?: '',
                'is_active' => 1,
                'created_at' => $product->post_date,
                'updated_at' => current_time('mysql')
            );
            
            // Calculate discount percentage
            if ($data['price_sale'] && $data['price_regular'] > 0) {
                $data['discount_percentage'] = (($data['price_regular'] - $data['price_sale']) / $data['price_regular']) * 100;
            }
            
            // Insert into custom table
            $result = $wpdb->insert(
                $wpdb->prefix . 'pt_products',
                $data
            );
            
            if ($result) {
                $migrated++;
            }
        } else {
            $skipped++;
        }
    }
    
    return array(
        'migrated' => $migrated,
        'skipped' => $skipped,
        'total' => count($products)
    );
}

// Add admin notice to trigger migration
add_action('admin_notices', 'pt_products_migration_notice');

function pt_products_migration_notice() {
    global $pagenow, $wpdb;
    
    // Only show on products admin page
    if ($pagenow !== 'edit.php' || !isset($_GET['post_type']) || $_GET['post_type'] !== 'pt_product') {
        return;
    }
    
    // Check if we have products in CPT but not in custom table
    $cpt_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'pt_product' AND post_status = 'publish'");
    $db_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}pt_products WHERE is_active = 1");
    
    if ($cpt_count > 0 && $db_count == 0) {
        echo '<div class="notice notice-warning">';
        echo '<p><strong>Product Data Migration Needed:</strong> You have ' . $cpt_count . ' products in WordPress that need to be migrated to the database.</p>';
        echo '<p><a href="' . admin_url('admin-post.php?action=pt_migrate_products') . '" class="button button-primary">Migrate Products Now</a></p>';
        echo '</div>';
    }
}

// Handle migration action
add_action('admin_post_pt_migrate_products', 'pt_handle_products_migration');

function pt_handle_products_migration() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to perform this action.');
    }
    
    $result = pt_migrate_products_to_database();
    
    // Redirect back with message
    $redirect_url = admin_url('edit.php?post_type=pt_product');
    if ($result['migrated'] > 0) {
        $redirect_url = add_query_arg('migration_success', $result['migrated'], $redirect_url);
    }
    
    wp_redirect($redirect_url);
    exit;
}

// Show success message
add_action('admin_notices', 'pt_products_migration_success');

function pt_products_migration_success() {
    global $pagenow;
    
    if ($pagenow !== 'edit.php' || !isset($_GET['post_type']) || $_GET['post_type'] !== 'pt_product') {
        return;
    }
    
    if (isset($_GET['migration_success'])) {
        $count = intval($_GET['migration_success']);
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>Success!</strong> Migrated ' . $count . ' products to the database.</p>';
        echo '</div>';
    }
}


// ============================================================
// 11. Load admin meta-box stylesheet
// ============================================================
function pt_admin_meta_box_styles()
{
    $screen = get_current_screen();
    $cpts = ['taxi_package', 'car_type', 'holiday_package', 'testimonial', 'special_offer', 'route', 'location', 'pt_product'];
    if ($screen && in_array($screen->post_type, $cpts)) {
        wp_enqueue_style('pt-meta-box-style', get_template_directory_uri() . '/style-custom.css');
    }
}
add_action('admin_enqueue_scripts', 'pt_admin_meta_box_styles');
