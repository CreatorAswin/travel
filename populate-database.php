<?php
/**
 * Database Population Tool
 * Helps populate your empty database tables with real data
 */

require_once('wp-config.php');

global $wpdb;

echo "=== DATABASE POPULATION TOOL ===\n\n";

if (isset($_GET['action']) && $_GET['action'] === 'populate') {
    echo "POPULATING DATABASE WITH SAMPLE DATA...\n\n";
    
    // Create sample locations
    $locations = [
        ['title' => 'Bhubaneswar', 'state' => 'Odisha', 'coordinates' => '20.2961,85.8245', 'is_active' => 1],
        ['title' => 'Puri', 'state' => 'Odisha', 'coordinates' => '19.8135,85.8312', 'is_active' => 1],
        ['title' => 'Cuttack', 'state' => 'Odisha', 'coordinates' => '20.4625,85.8830', 'is_active' => 1],
        ['title' => 'Konark', 'state' => 'Odisha', 'coordinates' => '19.8876,86.0974', 'is_active' => 1]
    ];
    
    foreach($locations as $location) {
        $wpdb->insert($wpdb->prefix . 'pt_locations', $location);
        echo "Added location: {$location['title']}\n";
    }
    
    // Create sample packages
    $packages = [
        [
            'title' => 'Bhubaneswar Temple Tour',
            'slug' => 'bhubaneswar-temple-tour',
            'description' => 'Explore the ancient temples of Bhubaneswar including Lingaraj Temple',
            'package_type' => 'city',
            'location_id' => 1,
            'base_price' => 1500.00,
            'duration_days' => 1,
            'is_active' => 1,
            'is_featured' => 1
        ],
        [
            'title' => 'Puri Jagannath Pilgrimage',
            'slug' => 'puri-jagannath-pilgrimage',
            'description' => 'Visit the famous Jagannath Temple in Puri',
            'package_type' => 'pilgrimage',
            'location_id' => 2,
            'base_price' => 2500.00,
            'duration_days' => 2,
            'duration_nights' => 1,
            'is_active' => 1,
            'is_featured' => 1
        ],
        [
            'title' => 'Cuttack Heritage Walk',
            'slug' => 'cuttack-heritage-walk',
            'description' => 'Discover the rich history of Cuttack city',
            'package_type' => 'city',
            'location_id' => 3,
            'base_price' => 1200.00,
            'duration_days' => 1,
            'is_active' => 1,
            'is_featured' => 0
        ]
    ];
    
    foreach($packages as $package) {
        $package['created_at'] = current_time('mysql');
        $package['updated_at'] = current_time('mysql');
        $wpdb->insert($wpdb->prefix . 'pt_packages', $package);
        echo "Added package: {$package['title']}\n";
    }
    
    // Create sample car types
    $car_types = [
        [
            'title' => 'AC Honda City',
            'category' => 'Sedan',
            'capacity' => 4,
            'base_price_per_km' => 12.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Petrol',
            'is_active' => 1,
            'availability_status' => 'available'
        ],
        [
            'title' => 'AC Toyota Innova',
            'category' => 'SUV',
            'capacity' => 6,
            'base_price_per_km' => 15.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Diesel',
            'is_active' => 1,
            'availability_status' => 'available'
        ],
        [
            'title' => 'AC Tempo Traveller',
            'category' => 'Tempo Traveller',
            'capacity' => 14,
            'base_price_per_km' => 22.00,
            'ac_type' => 'AC',
            'fuel_type' => 'Diesel',
            'is_active' => 1,
            'availability_status' => 'available'
        ]
    ];
    
    foreach($car_types as $car) {
        $car['created_at'] = current_time('mysql');
        $car['updated_at'] = current_time('mysql');
        $wpdb->insert($wpdb->prefix . 'pt_car_types', $car);
        echo "Added car type: {$car['title']}\n";
    }
    
    // Create sample routes
    $routes = [
        [
            'title' => 'Bhubaneswar to Puri',
            'from_location_id' => 1,
            'to_location_id' => 2,
            'distance_km' => 60.00,
            'base_price' => 1200.00,
            'price_per_km' => 12.00,
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
            'route_type' => 'one-way',
            'is_active' => 1,
            'is_popular' => 1
        ]
    ];
    
    foreach($routes as $route) {
        $route['created_at'] = current_time('mysql');
        $route['updated_at'] = current_time('mysql');
        $wpdb->insert($wpdb->prefix . 'pt_routes', $route);
        echo "Added route: {$route['title']}\n";
    }
    
    echo "\n✅ DATABASE POPULATION COMPLETE!\n";
    echo "Now your dashboard and frontend should show real data.\n";
}

// Show current database status
echo "\n=== CURRENT DATABASE STATUS ===\n";
$tables = ['pt_locations', 'pt_packages', 'pt_car_types', 'pt_routes', 'pt_products'];
foreach($tables as $table) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}{$table}");
    echo "$table: $count records\n";
}

echo "\n=== ACTIONS ===\n";
echo "1. <a href='?action=populate'>Populate Database with Sample Data</a>\n";
echo "2. <a href='/wp-admin/admin.php?page=pt-travel-management'>View Dashboard</a>\n";
echo "3. <a href='/wp-admin/admin.php?page=pt-migration'>Run Data Migration</a>\n";

echo "\n=== EXPLANATION ===\n";
echo "Your database tables are currently empty, which is why the frontend shows 'No packages available'.\n";
echo "Click 'Populate Database with Sample Data' above to add sample records.\n";
echo "Or use the Data Migration tool to convert your existing CPT data to the new database structure.\n";
?>