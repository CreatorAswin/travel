<?php
/**
 * Complete Data Population Tool
 * Adds data to all empty tables to make your application fully functional
 */

require_once('wp-config.php');

global $wpdb;

echo "=== COMPLETE DATA POPULATION TOOL ===\n\n";

if (isset($_GET['action']) && $_GET['action'] === 'populate_all') {
    echo "POPULATING ALL EMPTY TABLES WITH COMPREHENSIVE DATA...\n\n";
    
    // Create comprehensive locations
    echo "1. Adding Locations...\n";
    $locations_data = [
        ['title' => 'Bhubaneswar', 'state' => 'Odisha', 'coordinates' => '20.2961,85.8245', 'airport_name' => 'Biju Patnaik International Airport', 'is_active' => 1],
        ['title' => 'Puri', 'state' => 'Odisha', 'coordinates' => '19.8135,85.8312', 'airport_name' => 'Bhubaneswar Airport', 'is_active' => 1],
        ['title' => 'Cuttack', 'state' => 'Odisha', 'coordinates' => '20.4625,85.8830', 'airport_name' => 'Bhubaneswar Airport', 'is_active' => 1],
        ['title' => 'Konark', 'state' => 'Odisha', 'coordinates' => '19.8876,86.0974', 'airport_name' => 'Bhubaneswar Airport', 'is_active' => 1],
        ['title' => 'Rourkela', 'state' => 'Odisha', 'coordinates' => '22.2604,84.8536', 'airport_name' => 'Rourkela Airport', 'is_active' => 1],
        ['title' => 'Kolkata', 'state' => 'West Bengal', 'coordinates' => '22.5726,88.3639', 'airport_name' => 'Netaji Subhas Chandra Bose International Airport', 'is_active' => 1],
        ['title' => 'Chennai', 'state' => 'Tamil Nadu', 'coordinates' => '13.0827,80.2707', 'airport_name' => 'Chennai International Airport', 'is_active' => 1],
        ['title' => 'New Delhi', 'state' => 'Delhi', 'coordinates' => '28.6139,77.2090', 'airport_name' => 'Indira Gandhi International Airport', 'is_active' => 1]
    ];
    
    foreach($locations_data as $location) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_locations WHERE title = %s",
            $location['title']
        ));
        
        if (!$existing) {
            $location['created_at'] = current_time('mysql');
            $location['updated_at'] = current_time('mysql');
            $wpdb->insert($wpdb->prefix . 'pt_locations', $location);
            echo "  Added: {$location['title']}\n";
        }
    }
    
    // Create comprehensive packages
    echo "\n2. Adding Travel Packages...\n";
    $packages_data = [
        [
            'title' => 'Golden Temple Tour - Bhubaneswar',
            'package_type' => 'city',
            'location_id' => 1,
            'base_price' => 2500.00,
            'duration_days' => 2,
            'duration_nights' => 1,
            'description' => 'Explore the ancient temples of Bhubaneswar including Lingaraj Temple, Udayagiri Caves, and Bindu Sagar Lake.',
            'includes' => serialize(['Temple visits', 'Local guide', 'Transportation', 'Parking']),
            'excludes' => serialize(['Food', 'Accommodation', 'Entry fees']),
            'is_active' => 1,
            'is_featured' => 1
        ],
        [
            'title' => 'Puri Jagannath Pilgrimage Package',
            'package_type' => 'pilgrimage',
            'location_id' => 2,
            'base_price' => 3500.00,
            'duration_days' => 3,
            'duration_nights' => 2,
            'description' => 'Spiritual journey to the famous Jagannath Temple with visits to Puri Beach and Raghurajpur Heritage Village.',
            'includes' => serialize(['Temple darshan', 'Beach visit', 'Heritage tour', 'Local guide']),
            'excludes' => serialize(['Food', 'Accommodation', 'Personal expenses']),
            'is_active' => 1,
            'is_featured' => 1
        ],
        [
            'title' => 'Cuttack Silver Filigree Experience',
            'package_type' => 'cultural',
            'location_id' => 3,
            'base_price' => 1800.00,
            'duration_days' => 1,
            'description' => 'Discover Cuttack\'s world-famous silver filigree craftsmanship with workshop visits and shopping.',
            'includes' => serialize(['Workshop visits', 'Shopping', 'Local guide', 'Transportation']),
            'excludes' => serialize(['Food', 'Personal purchases']),
            'is_active' => 1,
            'is_featured' => 0
        ],
        [
            'title' => 'Konark Sun Temple & Chandrabhaga Beach',
            'package_type' => 'heritage',
            'location_id' => 4,
            'base_price' => 2200.00,
            'duration_days' => 1,
            'description' => 'UNESCO World Heritage site visit to the magnificent Sun Temple and beautiful Chandrabhaga Beach.',
            'includes' => serialize(['Sun Temple visit', 'Beach time', 'Local guide', 'Transportation']),
            'excludes' => serialize(['Food', 'Camera fee', 'Personal expenses']),
            'is_active' => 1,
            'is_featured' => 1
        ],
        [
            'title' => 'Odisha Heritage Circuit - 5 Days',
            'package_type' => 'holiday',
            'location_id' => 1,
            'base_price' => 12500.00,
            'duration_days' => 5,
            'duration_nights' => 4,
            'description' => 'Comprehensive 5-day tour covering Bhubaneswar, Puri, and Konark - the Golden Triangle of Odisha.',
            'includes' => serialize(['All transfers', '4 nights accommodation', 'Daily breakfast', 'Sightseeing', 'Local guides']),
            'excludes' => serialize(['Lunch', 'Dinner', 'Entry tickets', 'Personal expenses']),
            'is_active' => 1,
            'is_featured' => 1
        ]
    ];
    
    foreach($packages_data as $package) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_packages WHERE title = %s",
            $package['title']
        ));
        
        if (!$existing) {
            $package['slug'] = sanitize_title($package['title']);
            $package['created_at'] = current_time('mysql');
            $package['updated_at'] = current_time('mysql');
            $wpdb->insert($wpdb->prefix . 'pt_packages', $package);
            echo "  Added: {$package['title']}\n";
        }
    }
    
    // Create comprehensive car types
    echo "\n3. Adding Car Types...\n";
    $car_types_data = [
        [
            'title' => 'AC Maruti Swift (Mini)',
            'category' => 'Mini',
            'capacity' => 4,
            'luggage_capacity' => 2,
            'base_price_per_km' => 12.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Petrol',
            'transmission' => 'Manual',
            'features' => serialize(['Music System', 'GPS', 'Sanitized']),
            'is_active' => 1,
            'availability_status' => 'available'
        ],
        [
            'title' => 'AC Honda City (Sedan)',
            'category' => 'Sedan',
            'capacity' => 4,
            'luggage_capacity' => 3,
            'base_price_per_km' => 15.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Petrol',
            'transmission' => 'Automatic',
            'features' => serialize(['Music System', 'GPS', 'Sunroof', 'Charging Point']),
            'is_active' => 1,
            'availability_status' => 'available'
        ],
        [
            'title' => 'AC Toyota Innova (SUV)',
            'category' => 'SUV',
            'capacity' => 6,
            'luggage_capacity' => 4,
            'base_price_per_km' => 18.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Diesel',
            'transmission' => 'Manual',
            'features' => serialize(['Spacious Boot', 'GPS', 'Sanitized', 'Charging Point']),
            'is_active' => 1,
            'availability_status' => 'available'
        ],
        [
            'title' => 'AC Innova Crysta (Premium)',
            'category' => 'Luxury',
            'capacity' => 6,
            'luggage_capacity' => 5,
            'base_price_per_km' => 22.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Diesel',
            'transmission' => 'Automatic',
            'features' => serialize(['Premium Interior', 'Rear AC', 'GPS', 'WiFi Hotspot', 'Entertainment System']),
            'is_active' => 1,
            'availability_status' => 'available'
        ],
        [
            'title' => 'AC 14-Seater Tempo Traveller',
            'category' => 'Tempo Traveller',
            'capacity' => 14,
            'luggage_capacity' => 8,
            'base_price_per_km' => 25.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Diesel',
            'transmission' => 'Manual',
            'features' => serialize(['Push-Back Seats', 'Music System', 'GPS', 'TV', 'Charging Points']),
            'is_active' => 1,
            'availability_status' => 'available'
        ],
        [
            'title' => 'AC 35-Seater Coach',
            'category' => 'Coach',
            'capacity' => 35,
            'luggage_capacity' => 15,
            'base_price_per_km' => 35.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Diesel',
            'transmission' => 'Manual',
            'features' => serialize(['Recliner Seats', 'AC', 'Music System', 'GPS', 'Mic', 'Toilet']),
            'is_active' => 1,
            'availability_status' => 'available'
        ]
    ];
    
    foreach($car_types_data as $car) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_car_types WHERE title = %s",
            $car['title']
        ));
        
        if (!$existing) {
            $car['slug'] = sanitize_title($car['title']);
            $car['created_at'] = current_time('mysql');
            $car['updated_at'] = current_time('mysql');
            $wpdb->insert($wpdb->prefix . 'pt_car_types', $car);
            echo "  Added: {$car['title']}\n";
        }
    }
    
    // Create comprehensive routes
    echo "\n4. Adding Popular Routes...\n";
    $routes_data = [
        [
            'title' => 'Bhubaneswar to Puri',
            'from_location_id' => 1,
            'to_location_id' => 2,
            'distance_km' => 60.00,
            'base_price' => 1200.00,
            'price_per_km' => 12.00,
            'estimated_time' => '1.5 hours',
            'route_type' => 'one-way',
            'is_active' => 1,
            'is_popular' => 1
        ],
        [
            'title' => 'Bhubaneswar to Konark',
            'from_location_id' => 1,
            'to_location_id' => 4,
            'distance_km' => 65.00,
            'base_price' => 1300.00,
            'price_per_km' => 12.00,
            'estimated_time' => '1.5 hours',
            'route_type' => 'one-way',
            'is_active' => 1,
            'is_popular' => 1
        ],
        [
            'title' => 'Puri to Bhubaneswar',
            'from_location_id' => 2,
            'to_location_id' => 1,
            'distance_km' => 60.00,
            'base_price' => 1200.00,
            'price_per_km' => 12.00,
            'estimated_time' => '1.5 hours',
            'route_type' => 'one-way',
            'is_active' => 1,
            'is_popular' => 1
        ],
        [
            'title' => 'Bhubaneswar to Cuttack',
            'from_location_id' => 1,
            'to_location_id' => 3,
            'distance_km' => 28.00,
            'base_price' => 600.00,
            'price_per_km' => 12.00,
            'estimated_time' => '45 minutes',
            'route_type' => 'one-way',
            'is_active' => 1,
            'is_popular' => 0
        ],
        [
            'title' => 'Bhubaneswar Airport Transfer',
            'from_location_id' => 1,
            'to_location_id' => 1,
            'distance_km' => 8.00,
            'base_price' => 400.00,
            'price_per_km' => 15.00,
            'estimated_time' => '20 minutes',
            'route_type' => 'airport',
            'is_active' => 1,
            'is_popular' => 1
        ],
        [
            'title' => 'Puri to Konark (Round Trip)',
            'from_location_id' => 2,
            'to_location_id' => 4,
            'distance_km' => 70.00,
            'base_price' => 2000.00,
            'price_per_km' => 15.00,
            'estimated_time' => '2 hours each way',
            'route_type' => 'round-trip',
            'is_active' => 1,
            'is_popular' => 1
        ]
    ];
    
    foreach($routes_data as $route) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_routes WHERE title = %s",
            $route['title']
        ));
        
        if (!$existing) {
            $route['created_at'] = current_time('mysql');
            $route['updated_at'] = current_time('mysql');
            $wpdb->insert($wpdb->prefix . 'pt_routes', $route);
            echo "  Added: {$route['title']}\n";
        }
    }
    
    // Create sample products/tours
    echo "\n5. Adding Products & Tours...\n";
    $products_data = [
        [
            'title' => 'Lingaraj Temple Darshan',
            'product_type' => 'tour',
            'location_id' => 1,
            'price_per_person' => 500.00,
            'duration' => '3 hours',
            'description' => 'Guided tour of the magnificent 11th-century Lingaraj Temple and Bindu Sagar lake.',
            'highlights' => serialize(['Ancient architecture', 'Religious significance', 'Lake view', 'Local guide']),
            'is_active' => 1
        ],
        [
            'title' => 'Udayagiri & Khandagiri Caves',
            'product_type' => 'tour',
            'location_id' => 1,
            'price_per_person' => 400.00,
            'duration' => '2 hours',
            'description' => 'Explore the ancient Jain rock-cut caves from the 2nd century BCE.',
            'highlights' => serialize(['Historical caves', 'Ancient inscriptions', 'Panoramic views', 'Photography spots']),
            'is_active' => 1
        ],
        [
            'title' => 'Puri Beach & Sea Walk',
            'product_type' => 'activity',
            'location_id' => 2,
            'price_per_person' => 300.00,
            'duration' => '2 hours',
            'description' => 'Relaxing beach walk and sea shore activities at the famous Puri Beach.',
            'highlights' => serialize(['Beach walk', 'Sea view', 'Local food stalls', 'Sunset view']),
            'is_active' => 1
        ],
        [
            'title' => 'Konark Sun Temple Architecture Tour',
            'product_type' => 'tour',
            'location_id' => 4,
            'price_per_person' => 600.00,
            'duration' => '2.5 hours',
            'description' => 'Detailed architectural tour of the UNESCO World Heritage Konark Sun Temple.',
            'highlights' => serialize(['UNESCO site', 'Architectural marvel', 'Stone carvings', 'Historical insights']),
            'is_active' => 1
        ]
    ];
    
    foreach($products_data as $product) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}pt_products WHERE title = %s",
            $product['title']
        ));
        
        if (!$existing) {
            $product['slug'] = sanitize_title($product['title']);
            $product['created_at'] = current_time('mysql');
            $product['updated_at'] = current_time('mysql');
            $wpdb->insert($wpdb->prefix . 'pt_products', $product);
            echo "  Added: {$product['title']}\n";
        }
    }
    
    echo "\n✅ COMPLETE DATA POPULATION FINISHED!\n";
    echo "All tables now have comprehensive, real data.\n";
    echo "Your application should now display dynamic content in both dashboard and frontend.\n";
}

// Show current status
echo "CURRENT TABLE STATUS:\n";
echo "---------------------\n";

$tables = ['pt_locations', 'pt_packages', 'pt_car_types', 'pt_routes', 'pt_products'];
$empty_count = 0;

foreach($tables as $table) {
    $full_table = $wpdb->prefix . $table;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    if ($exists) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table WHERE is_active = 1");
        echo ucfirst(str_replace('pt_', '', $table)) . ": $count records\n";
        if ($count == 0) {
            $empty_count++;
        }
    } else {
        echo ucfirst(str_replace('pt_', '', $table)) . ": TABLE MISSING\n";
        $empty_count++;
    }
}

if ($empty_count > 0) {
    echo "\n🚨 $empty_count tables are empty or missing!\n";
    echo "<a href='?action=populate_all'>CLICK HERE TO POPULATE ALL TABLES WITH REAL DATA</a>\n";
} else {
    echo "\n✅ All tables have data!\n";
    echo "Test your frontend with these shortcodes:\n";
    echo "[pt_packages]\n";
    echo "[pt_car_types]\n";
    echo "[pt_routes]\n";
}

echo "\n=== DATA POPULATION TOOL ===\n";
?>