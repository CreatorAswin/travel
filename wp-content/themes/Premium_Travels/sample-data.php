<?php
/**
 * Enhanced Sample Data for Premium Travels Theme
 * Rich dummy data for all Custom Post Types
 */

/**
 * Helper to replace deprecated get_page_by_title
 */
function pt_get_post_by_title($title, $post_type = 'page') {
    $query = new WP_Query(array(
        'post_type'      => $post_type,
        'title'          => $title,
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'no_found_rows'  => true,
    ));
    return $query->have_posts() ? $query->next_post() : null;
}

function premium_travels_insert_sample_data()
{
    // Check if data already exists to prevent duplicates
    if (get_option('premium_travels_sample_data_v3_imported')) {
        return;
    }

    // ────────────────────────────────────────────────
    // 1. TAXONOMY TERMS
    // ────────────────────────────────────────────────
    $service_types = ['Local Trip', 'Airport Transfer', 'One-Way', 'Round-Trip', 'Multi-way', 'Taxi Packages'];
    foreach ($service_types as $type) {
        if (!term_exists($type, 'service_type'))
            wp_insert_term($type, 'service_type');
    }

    $package_cats = ['Odisha Tour', 'India Tour', 'International Tour'];
    foreach ($package_cats as $cat) {
        if (!term_exists($cat, 'package_category'))
            wp_insert_term($cat, 'package_category');
    }

    $car_cats = ['Mini', 'Sedan', 'SUV', 'Luxury', 'Tempo Traveller', 'Coach'];
    foreach ($car_cats as $cat) {
        if (!term_exists($cat, 'car_category'))
            wp_insert_term($cat, 'car_category');
    }

    // ────────────────────────────────────────────────
    // 2. LOCATIONS (10 cities)
    // ────────────────────────────────────────────────
    $locations = [
        ['title' => 'Bhubaneswar, Odisha', 'state' => 'Odisha', 'airport' => 'Biju Patnaik International Airport (BBI)', 'coordinates' => '20.2961, 85.8245'],
        ['title' => 'Puri, Odisha', 'state' => 'Odisha', 'airport' => 'Bhubaneswar Airport (BBI)', 'coordinates' => '19.8135, 85.8312'],
        ['title' => 'Cuttack, Odisha', 'state' => 'Odisha', 'airport' => 'Bhubaneswar Airport (BBI)', 'coordinates' => '20.4625, 85.8830'],
        ['title' => 'Berhampur, Odisha', 'state' => 'Odisha', 'airport' => 'Bhubaneswar Airport (BBI)', 'coordinates' => '19.3150, 84.7941'],
        ['title' => 'Konark, Odisha', 'state' => 'Odisha', 'airport' => 'Bhubaneswar Airport (BBI)', 'coordinates' => '19.8876, 86.0974'],
        ['title' => 'Rourkela, Odisha', 'state' => 'Odisha', 'airport' => 'Rourkela Airport (RRK)', 'coordinates' => '22.2604, 84.8536'],
        ['title' => 'Khordha, Odisha', 'state' => 'Odisha', 'airport' => 'Bhubaneswar Airport (BBI)', 'coordinates' => '20.1839, 85.6143'],
        ['title' => 'Kolkata, West Bengal', 'state' => 'West Bengal', 'airport' => 'Netaji Subhas Chandra Bose International Airport (CCU)', 'coordinates' => '22.5726, 88.3639'],
        ['title' => 'Chennai, Tamil Nadu', 'state' => 'Tamil Nadu', 'airport' => 'Chennai International Airport (MAA)', 'coordinates' => '13.0827, 80.2707'],
        ['title' => 'New Delhi', 'state' => 'Delhi', 'airport' => 'Indira Gandhi International Airport (DEL)', 'coordinates' => '28.6139, 77.2090'],
    ];

    foreach ($locations as $loc) {
        if (!pt_get_post_by_title($loc['title'], 'location')) {
            $id = wp_insert_post(['post_title' => $loc['title'], 'post_type' => 'location', 'post_status' => 'publish']);
            if ($id) {
                update_post_meta($id, 'state', $loc['state']);
                update_post_meta($id, 'airport', $loc['airport']);
                update_post_meta($id, 'coordinates', $loc['coordinates']);
                update_post_meta($id, 'is_active', 'yes');
            }
        }
    }

    // ────────────────────────────────────────────────
    // 3. TAXI PACKAGES (6 packages)
    // ────────────────────────────────────────────────
    $packages = [
        [
            'title' => 'Bhubaneswar Sightseeing & Puri Drop Package (1Day)',
            'price' => '3,119.00',
            'person_count' => '4',
            'pickup' => 'Bhubaneswar, Odisha',
            'duration' => '1 Day',
            'nights' => '0',
            'distance_km' => '250',
            'service' => 'Taxi Packages',
            'inclusions' => 'Fuel, Toll, Parking, Driver Allowance, GST',
            'exclusions' => 'Hotel, Food, Entry Tickets, Personal Expenses',
            'content' => 'Explore Bhubaneswar temples and iconic Jagannath Temple at Puri in a single comfortable day trip. Includes: Lingaraj Temple, ISKCON, Udayagiri & Khandagiri Caves, Nandankanan Zoo, and drop to Puri Sea Beach.',
        ],
        [
            'title' => 'Golden Triangle of Odisha (3 Days)',
            'price' => '9,876.00',
            'person_count' => '4',
            'pickup' => 'Bhubaneswar, Odisha',
            'duration' => '3 Days / 2 Nights',
            'nights' => '2',
            'distance_km' => '520',
            'service' => 'Taxi Packages',
            'inclusions' => 'Fuel, Toll, Parking, Driver Allowance, GST',
            'exclusions' => 'Hotel, Food, Entry Tickets',
            'content' => 'Cover three iconic destinations — Bhubaneswar, Puri, and Konark Sun Temple — in this popular Odisha circuit package.',
        ],
        [
            'title' => 'Chilika Lake Excursion Package (1 Day)',
            'price' => '2,800.00',
            'person_count' => '4',
            'pickup' => 'Bhubaneswar, Odisha',
            'duration' => '1 Day',
            'nights' => '0',
            'distance_km' => '180',
            'service' => 'Taxi Packages',
            'inclusions' => 'Fuel, Toll, Driver Allowance',
            'exclusions' => 'Boat Ride, Food, Entry Tickets',
            'content' => 'Experience Asia\'s largest brackish water lagoon — Chilika Lake — with a visit to Kalijai Island and Satapada dolphin watch point.',
        ],
        [
            'title' => 'Odisha Tribal Tour – Koraput & Jeypore (4 Days)',
            'price' => '18,500.00',
            'person_count' => '4',
            'pickup' => 'Bhubaneswar, Odisha',
            'duration' => '4 Days / 3 Nights',
            'nights' => '3',
            'distance_km' => '920',
            'service' => 'Taxi Packages',
            'inclusions' => 'Fuel, Toll, Parking, Driver Allowance, GST',
            'exclusions' => 'Hotel, Food, Entry Tickets, Guide Fee',
            'content' => 'Discover the rich tribal culture of southern Odisha — Koraput, Jeypore Tribal Market, Duduma Falls, and Borra Caves.',
        ],
        [
            'title' => 'Puri–Konark–Chilika Round Trip (2 Days)',
            'price' => '6,400.00',
            'person_count' => '4',
            'pickup' => 'Puri, Odisha',
            'duration' => '2 Days / 1 Night',
            'nights' => '1',
            'distance_km' => '380',
            'service' => 'Taxi Packages',
            'inclusions' => 'Fuel, Toll, Driver Allowance, GST',
            'exclusions' => 'Hotel, Food, Entry Tickets',
            'content' => 'A compact 2-day circuit starting from Puri: visit Konark Sun Temple, Marine Drive Road, Chilika Lake, and return.',
        ],
        [
            'title' => 'Bhubaneswar Airport Pickup & City Tour (Half Day)',
            'price' => '1,200.00',
            'person_count' => '4',
            'pickup' => 'Bhubaneswar, Odisha',
            'duration' => 'Half Day (4 Hours)',
            'nights' => '0',
            'distance_km' => '60',
            'service' => 'Local Trip',
            'inclusions' => 'Fuel, Driver Allowance, Parking',
            'exclusions' => 'Entry Tickets, Guide Fee, Food',
            'content' => 'Quick city orientation tour starting from Bhubaneswar Airport: Lingaraj Temple, Ekamra Haat, and hotel drop.',
        ],
    ];

    foreach ($packages as $pkg) {
        if (!pt_get_post_by_title($pkg['title'], 'taxi_package')) {
            $post_id = wp_insert_post([
                'post_title' => $pkg['title'],
                'post_type' => 'taxi_package',
                'post_status' => 'publish',
                'post_content' => $pkg['content'],
            ]);
            if ($post_id) {
                update_post_meta($post_id, 'price', $pkg['price']);
                update_post_meta($post_id, 'person_count', $pkg['person_count']);
                update_post_meta($post_id, 'pickup_location', $pkg['pickup']);
                update_post_meta($post_id, 'duration', $pkg['duration']);
                update_post_meta($post_id, 'nights', $pkg['nights']);
                update_post_meta($post_id, 'distance_km', $pkg['distance_km']);
                update_post_meta($post_id, 'inclusions', $pkg['inclusions']);
                update_post_meta($post_id, 'exclusions', $pkg['exclusions']);
                wp_set_object_terms($post_id, $pkg['service'], 'service_type');
            }
        }
    }

    // ────────────────────────────────────────────────
    // 4. CAR TYPES (6 vehicles)
    // ────────────────────────────────────────────────
    $car_types = [
        ['title' => 'AC Honda City (Sedan)', 'capacity' => '4 G + 1 D', 'price_per_km' => '12', 'ac_status' => 'AC', 'fuel_type' => 'Petrol', 'luggage' => '2', 'category' => 'Sedan', 'features' => 'Music System, GPS Tracking, Sanitized'],
        ['title' => 'AC Toyota Innova (SUV)', 'capacity' => '6 G + 1 D', 'price_per_km' => '15', 'ac_status' => 'AC', 'fuel_type' => 'Diesel', 'luggage' => '3', 'category' => 'SUV', 'features' => 'Spacious Boot, GPS, Sanitized, Charging Point'],
        ['title' => 'AC Innova Crysta (Luxury)', 'capacity' => '6 G + 1 D', 'price_per_km' => '18', 'ac_status' => 'AC', 'fuel_type' => 'Diesel', 'luggage' => '4', 'category' => 'Luxury', 'features' => 'Premium Interior, Rear AC, GPS, WiFi Hotspot'],
        ['title' => 'AC Maruti Swift (Mini)', 'capacity' => '3 G + 1 D', 'price_per_km' => '10', 'ac_status' => 'AC', 'fuel_type' => 'CNG', 'luggage' => '1', 'category' => 'Mini', 'features' => 'Fuel Efficient, Sanitized, GPS'],
        ['title' => 'AC 14-Seater Tempo', 'capacity' => '14 G + 1 D', 'price_per_km' => '22', 'ac_status' => 'AC', 'fuel_type' => 'Diesel', 'luggage' => '8', 'category' => 'Tempo Traveller', 'features' => 'Push-Back Seats, Music System, GPS, TV'],
        ['title' => 'AC 35-Seater Coach', 'capacity' => '35 G + 1 D', 'price_per_km' => '35', 'ac_status' => 'AC', 'fuel_type' => 'Diesel', 'luggage' => '15', 'category' => 'Coach', 'features' => 'Recliner Seats, AC, Music, GPS, Mic'],
    ];

    foreach ($car_types as $car) {
        if (!pt_get_post_by_title($car['title'], 'car_type')) {
            $post_id = wp_insert_post([
                'post_title' => $car['title'],
                'post_type' => 'car_type',
                'post_status' => 'publish',
                'post_content' => 'Well-maintained ' . $car['title'] . ' available for local and outstation travel.',
            ]);
            if ($post_id) {
                update_post_meta($post_id, 'capacity', $car['capacity']);
                update_post_meta($post_id, 'price_per_km', $car['price_per_km']);
                update_post_meta($post_id, 'ac_status', $car['ac_status']);
                update_post_meta($post_id, 'fuel_type', $car['fuel_type']);
                update_post_meta($post_id, 'luggage', $car['luggage']);
                update_post_meta($post_id, 'features', $car['features']);
                wp_set_object_terms($post_id, $car['category'], 'car_category');
            }
        }
    }

    // ────────────────────────────────────────────────
    // 5. HOLIDAY PACKAGES (4 packages)
    // ────────────────────────────────────────────────
    $holidays = [
        [
            'title' => 'Golden Triangle of Odisha – 3 Days',
            'category' => 'Odisha Tour',
            'price' => '9,876.00',
            'duration' => '3 Days / 2 Nights',
            'person_count' => '2',
            'pickup' => 'Bhubaneswar',
            'hotel_stars' => '3 Star',
            'highlights' => 'Puri Jagannath Temple, Konark Sun Temple, Chilika Lake, Bhubaneswar Temples',
            'inclusions' => 'Hotel (2 Nights), Cab, Breakfast, Sightseeing, Driver Allowance, GST',
            'exclusions' => 'Flights, Lunch, Dinner, Entry Tickets, Personal Expenses',
            'content' => 'The most popular Odisha tour covering Bhubaneswar, Puri and Konark — the golden triangle. Perfect for a long weekend getaway.',
        ],
        [
            'title' => 'Incredible India – Delhi–Agra–Jaipur (7 Days)',
            'category' => 'India Tour',
            'price' => '35,000.00',
            'duration' => '7 Days / 6 Nights',
            'person_count' => '2',
            'pickup' => 'New Delhi',
            'hotel_stars' => '4 Star',
            'highlights' => 'Red Fort, Agra Fort, Taj Mahal, Fatehpur Sikri, Amber Fort, Hawa Mahal',
            'inclusions' => 'Hotel (6 Nights), Cab, Breakfast, All Transfers, Driver Allowance, GST',
            'exclusions' => 'Flights to Delhi, Lunch, Dinner, Entry Tickets, Guide Fee',
            'content' => 'India\'s iconic Golden Triangle covering the history-rich cities of Delhi, Agra, and Jaipur — perfect for first-time India visitors.',
        ],
        [
            'title' => 'Bali – Island Paradise (5 Days)',
            'category' => 'International Tour',
            'price' => '65,000.00',
            'duration' => '5 Days / 4 Nights',
            'person_count' => '2',
            'pickup' => 'Bhubaneswar, Odisha',
            'hotel_stars' => '4 Star',
            'highlights' => 'Tanah Lot Temple, Ubud Rice Terraces, Kuta Beach, Uluwatu Sunset, Nusa Dua',
            'inclusions' => 'International Return Airfare, Hotel (4 Nights), Airport Transfer, Breakfast, Guided Tours, GST',
            'exclusions' => 'Visa Fee, Lunch, Dinner, Personal Expenses, Travel Insurance',
            'content' => 'An unforgettable Bali escape — stunning temples, lush rice paddies, pristine beaches and vibrant nightlife in just 5 days.',
        ],
        [
            'title' => 'Odisha Heritage Circuit – 5 Days',
            'category' => 'Odisha Tour',
            'price' => '14,500.00',
            'duration' => '5 Days / 4 Nights',
            'person_count' => '2',
            'pickup' => 'Bhubaneswar',
            'hotel_stars' => '3 Star',
            'highlights' => 'Konark Sun Temple, Puri Jagannath, Chilika Lake Dolphins, Raghurajpur Art Village, Dhauli Peace Pagoda',
            'inclusions' => 'Hotel (4 Nights), Cab, Breakfast, Driver Allowance, GST',
            'exclusions' => 'Flights, Lunch, Dinner, Entry Tickets',
            'content' => 'Deep-dive into Odisha\'s rich heritage over 5 enriching days — ancient temples, tribal art, wildlife, and coastal beauty.',
        ],
    ];

    foreach ($holidays as $holiday) {
        if (!pt_get_post_by_title($holiday['title'], 'holiday_package')) {
            $post_id = wp_insert_post([
                'post_title' => $holiday['title'],
                'post_type' => 'holiday_package',
                'post_status' => 'publish',
                'post_content' => $holiday['content'],
            ]);
            if ($post_id) {
                update_post_meta($post_id, 'price', $holiday['price']);
                update_post_meta($post_id, 'duration', $holiday['duration']);
                update_post_meta($post_id, 'pickup_location', $holiday['pickup']);
                update_post_meta($post_id, 'person_count', $holiday['person_count']);
                update_post_meta($post_id, 'hotel_stars', $holiday['hotel_stars']);
                update_post_meta($post_id, 'highlights', $holiday['highlights']);
                update_post_meta($post_id, 'inclusions', $holiday['inclusions']);
                update_post_meta($post_id, 'exclusions', $holiday['exclusions']);
                wp_set_object_terms($post_id, $holiday['category'], 'package_category');
            }
        }
    }

    // ────────────────────────────────────────────────
    // 6. TESTIMONIALS (6 reviews)
    // ────────────────────────────────────────────────
    $testimonials = [
        ['title' => 'K. Banerjee', 'rating' => 5, 'designation' => 'Business Executive', 'location' => 'Kolkata, WB', 'trip_type' => 'Holiday Package', 'content' => 'It was a wonderful experience. The driver was purely professional and well-behaved. The condition of the vehicle is spot on. Loved every moment of the Odisha trip!'],
        ['title' => 'Shanmugam R.', 'rating' => 5, 'designation' => 'IT Professional', 'location' => 'Chennai, TN', 'trip_type' => 'Airport Transfer', 'content' => 'All staff were behaving very well. The cab arrived on time and the journey was smooth. Will definitely use Patra Travels again on my next visit.'],
        ['title' => 'Priya Mehta', 'rating' => 4, 'designation' => 'Teacher', 'location' => 'Ahmedabad, GJ', 'trip_type' => 'Outstation', 'content' => 'Very clean and sanitized car. The driver knew all routes and was helpful throughout our Puri–Konark trip. Pricing is very transparent.'],
        ['title' => 'Rahul Sharma', 'rating' => 5, 'designation' => 'Government Officer', 'location' => 'Bhubaneswar, OD', 'trip_type' => 'Local Trip', 'content' => 'Best cab service in Bhubaneswar. I use them regularly for local trips. Always punctual, professional drivers and neat cars.'],
        ['title' => 'Anjali Verma', 'rating' => 5, 'designation' => 'Doctor', 'location' => 'New Delhi', 'trip_type' => 'Holiday Package', 'content' => 'Booked a 3-day Odisha package for my family. Everything was perfectly organized — hotels, cabs, guides. Highly recommended!'],
        ['title' => 'Siddharth Nair', 'rating' => 4, 'designation' => 'Software Engineer', 'location' => 'Bengaluru, KA', 'trip_type' => 'Round Trip', 'content' => 'Excellent service! Round trip from Bhubaneswar to Puri was comfortable. The cab was spacious and the driver was knowledgeable about local spots.'],
    ];

    foreach ($testimonials as $testi) {
        if (!pt_get_post_by_title($testi['title'], 'testimonial')) {
            $post_id = wp_insert_post([
                'post_title' => $testi['title'],
                'post_type' => 'testimonial',
                'post_status' => 'publish',
                'post_content' => $testi['content'],
            ]);
            if ($post_id) {
                update_post_meta($post_id, 'rating', $testi['rating']);
                update_post_meta($post_id, 'designation', $testi['designation']);
                update_post_meta($post_id, 'location', $testi['location']);
                update_post_meta($post_id, 'trip_type', $testi['trip_type']);
            }
        }
    }

    // ────────────────────────────────────────────────
    // 7. SPECIAL OFFERS (3 offers)
    // ────────────────────────────────────────────────
    $offers = [
        ['title' => 'Early Bird Discount – 20% Off', 'discount' => '20', 'coupon_code' => 'EARLY20', 'applicable_for' => 'All Services', 'minimum_booking' => '3000', 'valid_till' => date('Y-m-d', strtotime('+30 days')), 'content' => 'Book 7 days in advance and enjoy 20% off on any taxi package or holiday package.'],
        ['title' => 'Weekend Special – Flat ₹500 Off', 'discount' => '10', 'coupon_code' => 'WKND500', 'applicable_for' => 'Holiday Package', 'minimum_booking' => '5000', 'valid_till' => date('Y-m-d', strtotime('+60 days')), 'content' => 'Avail flat ₹500 off on all holiday packages booked for weekends. Use code WKND500 at checkout.'],
        ['title' => 'Festival Offer – 15% Off on All Packages', 'discount' => '15', 'coupon_code' => 'FEST15', 'applicable_for' => 'All Services', 'minimum_booking' => '2000', 'valid_till' => date('Y-m-d', strtotime('+14 days')), 'content' => 'Limited-time festive season offer — get 15% off across all travel packages. Hurry, offer valid for 14 days only!'],
    ];

    foreach ($offers as $offer) {
        if (!pt_get_post_by_title($offer['title'], 'special_offer')) {
            $post_id = wp_insert_post([
                'post_title' => $offer['title'],
                'post_type' => 'special_offer',
                'post_status' => 'publish',
                'post_content' => $offer['content'],
            ]);
            if ($post_id) {
                update_post_meta($post_id, 'discount_percentage', $offer['discount']);
                update_post_meta($post_id, 'coupon_code', $offer['coupon_code']);
                update_post_meta($post_id, 'applicable_for', $offer['applicable_for']);
                update_post_meta($post_id, 'minimum_booking', $offer['minimum_booking']);
                update_post_meta($post_id, 'valid_till', $offer['valid_till']);
            }
        }
    }

    // ────────────────────────────────────────────────
    // 8. ROUTES (5 popular routes)
    // ────────────────────────────────────────────────
    $routes = [
        ['title' => 'Bhubaneswar to Puri', 'from' => 'Bhubaneswar', 'to' => 'Puri', 'distance' => '60', 'price_per_km' => '12', 'travel_time' => '1.5 Hours', 'route_type' => 'One-Way'],
        ['title' => 'Bhubaneswar to Konark', 'from' => 'Bhubaneswar', 'to' => 'Konark', 'distance' => '65', 'price_per_km' => '12', 'travel_time' => '1.5 Hours', 'route_type' => 'One-Way'],
        ['title' => 'Bhubaneswar to Cuttack', 'from' => 'Bhubaneswar', 'to' => 'Cuttack', 'distance' => '28', 'price_per_km' => '12', 'travel_time' => '45 Minutes', 'route_type' => 'One-Way'],
        ['title' => 'Puri to Bhubaneswar', 'from' => 'Puri', 'to' => 'Bhubaneswar', 'distance' => '60', 'price_per_km' => '12', 'travel_time' => '1.5 Hours', 'route_type' => 'One-Way'],
        ['title' => 'Bhubaneswar Airport Transfer', 'from' => 'Bhubaneswar Airport', 'to' => 'City Centre', 'distance' => '4', 'price_per_km' => '15', 'travel_time' => '15 Minutes', 'route_type' => 'Airport Transfer'],
    ];

    foreach ($routes as $route) {
        if (!pt_get_post_by_title($route['title'], 'route')) {
            $post_id = wp_insert_post([
                'post_title' => $route['title'],
                'post_type' => 'route',
                'post_status' => 'publish',
                'post_content' => 'Popular cab route from ' . $route['from'] . ' to ' . $route['to'] . '. Distance: ' . $route['distance'] . ' km.',
            ]);
            if ($post_id) {
                update_post_meta($post_id, 'from_city', $route['from']);
                update_post_meta($post_id, 'to_city', $route['to']);
                update_post_meta($post_id, 'distance_km', $route['distance']);
                update_post_meta($post_id, 'price_per_km', $route['price_per_km']);
                update_post_meta($post_id, 'travel_time', $route['travel_time']);
                update_post_meta($post_id, 'route_type', $route['route_type']);
                wp_set_object_terms($post_id, $route['route_type'], 'service_type');
            }
        }
    }

    // ────────────────────────────────────────────────
    // 9. CITY SERVICES
    // ────────────────────────────────────────────────
    $cities = ['Bhubaneswar', 'Puri', 'Cuttack', 'Konark', 'Berhampur'];
    foreach ($cities as $city) {
        $title = 'Taxi Services in ' . $city;
        if (!pt_get_post_by_title($title, 'city_service')) {
            wp_insert_post([
                'post_title' => $title,
                'post_type' => 'city_service',
                'post_status' => 'publish',
                'post_content' => 'Premium cab and car rental services available in ' . $city . '. Book local trips, airport transfers, outstation cabs and more.',
            ]);
        }
    }

    // ────────────────────────────────────────────────
    // 10. PRODUCTS (Famous Items / Tours)
    // ────────────────────────────────────────────────
    $products = [
        // Bhubaneswar
        ['title' => 'Lingaraj Temple Darshan', 'city' => 'Bhubaneswar, Odisha', 'price' => '800.00', 'content' => 'Guided tour of the magnificent 11th-century Lingaraj Temple and Bindu Sagar lake.'],
        ['title' => 'Khandagiri & Udayagiri Caves', 'city' => 'Bhubaneswar, Odisha', 'price' => '600.00', 'content' => 'Explore the ancient Jain rock-cut caves from the 2nd century BCE.'],
        ['title' => 'Nandankanan Zoo Safari', 'city' => 'Bhubaneswar, Odisha', 'price' => '1200.00', 'content' => 'White tiger safari and botanical garden tour at Nandankanan Zoological Park.'],

        // Puri
        ['title' => 'Shree Jagannath Temple VIP Darshan', 'city' => 'Puri, Odisha', 'price' => '1500.00', 'content' => 'Special guided VIP Darshan experience at the holy Shree Jagannath Temple.'],
        ['title' => 'Raghurajpur Heritage Village', 'city' => 'Puri, Odisha', 'price' => '1000.00', 'content' => 'Visit the artisan village famous for Pattachitra scroll paintings and Gotipua dance.'],
        ['title' => 'Chilika Lake Dolphin Cruise', 'city' => 'Puri, Odisha', 'price' => '2500.00', 'content' => 'Boat ride in Chilika Lake to spot the rare Irrawaddy Dolphins and migratory birds.'],
        ['title' => 'Konark Sun Temple Guided Tour', 'city' => 'Puri, Odisha', 'price' => '1800.00', 'content' => 'Detailed architectural tour of the UNESCO World Heritage Konark Sun Temple.'],

        // Cuttack
        ['title' => 'Silver Filigree (Tarakasi) Tour', 'city' => 'Cuttack, Odisha', 'price' => '900.00', 'content' => 'Shopping and workshop tour of Cuttack\'s world-famous Silver Filigree craftsmanship.'],
        ['title' => 'Barabati Fort & Chandi Temple', 'city' => 'Cuttack, Odisha', 'price' => '700.00', 'content' => 'Visit the ruins of Barabati Fort and the sacred Cuttack Chandi Temple.'],
    ];

    foreach ($products as $prod) {
        if (!pt_get_post_by_title($prod['title'], 'pt_product')) {
            $post_id = wp_insert_post([
                'post_title' => $prod['title'],
                'post_type' => 'pt_product',
                'post_status' => 'publish',
                'post_content' => $prod['content'],
            ]);
            if ($post_id) {
                update_post_meta($post_id, 'price', $prod['price']);
                update_post_meta($post_id, 'product_location', $prod['city']);
            }
        }
    }

    update_option('premium_travels_sample_data_v3_imported', true);
}
add_action('init', 'premium_travels_insert_sample_data');
