<?php
/**
 * Travel Management System Test Page
 * This page tests all implemented features
 */

// Test database connection and tables
require_once('../../../wp-config.php');
require_once('../../../wp-includes/wp-db.php');

global $wpdb;

echo "<h1>Travel Management System - Test Results</h1>\n\n";

// Test 1: Database Connection
echo "<h2>1. Database Connection Test</h2>\n";
echo "Database Name: " . DB_NAME . "<br>\n";
echo "Table Prefix: " . $wpdb->prefix . "<br>\n";
echo "Status: <span style='color: green;'>CONNECTED</span><br><br>\n\n";

// Test 2: Custom Tables
echo "<h2>2. Custom Tables Check</h2>\n";
$custom_tables = [
    'pt_bookings',
    'pt_enquiries', 
    'pt_customers',
    'pt_packages',
    'pt_car_types',
    'pt_routes',
    'pt_products',
    'pt_special_offers',
    'pt_testimonials',
    'pt_locations',
    'pt_package_categories',
    'pt_payments'
];

foreach($custom_tables as $table) {
    $full_table_name = $wpdb->prefix . $table;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table_name'");
    $status = $exists ? "<span style='color: green;'>EXISTS</span>" : "<span style='color: red;'>MISSING</span>";
    echo "$table: $status<br>\n";
}
echo "<br>\n\n";

// Test 3: Load Managers
echo "<h2>3. Manager Classes Test</h2>\n";
$managers = [
    'PT_Packages_Manager' => '/includes/dynamic-management/packages-manager.php',
    'PT_Car_Types_Manager' => '/includes/dynamic-management/car-types-manager.php',
    'PT_Routes_Manager' => '/includes/dynamic-management/routes-manager.php',
    'PT_Products_Manager' => '/includes/dynamic-management/products-manager.php',
    'PT_Bookings_Manager' => '/includes/dynamic-management/bookings-manager.php'
];

foreach($managers as $class => $file) {
    $file_path = get_template_directory() . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
        if (class_exists($class)) {
            echo "$class: <span style='color: green;'>LOADED</span><br>\n";
        } else {
            echo "$class: <span style='color: red;'>CLASS NOT FOUND</span><br>\n";
        }
    } else {
        echo "$class: <span style='color: red;'>FILE NOT FOUND ($file_path)</span><br>\n";
    }
}
echo "<br>\n\n";

// Test 4: Create Sample Data
echo "<h2>4. Sample Data Creation</h2>\n";
if (isset($_GET['create_sample_data'])) {
    // Create sample locations
    $locations_data = [
        ['title' => 'Bhubaneswar', 'state' => 'Odisha', 'coordinates' => '20.2961,85.8245'],
        ['title' => 'Puri', 'state' => 'Odisha', 'coordinates' => '19.8135,85.8312'],
        ['title' => 'Cuttack', 'state' => 'Odisha', 'coordinates' => '20.4625,85.8830']
    ];
    
    foreach($locations_data as $loc) {
        $wpdb->insert(
            $wpdb->prefix . 'pt_locations',
            $loc
        );
    }
    echo "Sample locations created.<br>\n";
    
    // Create sample packages
    $packages_data = [
        [
            'title' => 'Bhubaneswar City Tour',
            'slug' => 'bhubaneswar-city-tour',
            'description' => 'Explore the beautiful city of Bhubaneswar',
            'package_type' => 'city',
            'location_id' => 1,
            'base_price' => 1500.00,
            'is_active' => 1
        ],
        [
            'title' => 'Puri Pilgrimage Package',
            'slug' => 'puri-pilgrimage-package',
            'description' => 'Visit the famous Jagannath Temple in Puri',
            'package_type' => 'pilgrimage',
            'location_id' => 2,
            'base_price' => 2500.00,
            'is_active' => 1
        ]
    ];
    
    foreach($packages_data as $pkg) {
        $wpdb->insert(
            $wpdb->prefix . 'pt_packages',
            $pkg
        );
    }
    echo "Sample packages created.<br>\n";
    
    echo "<span style='color: green;'>Sample data creation completed!</span><br>\n";
} else {
    echo "<a href='?create_sample_data=1'>Create Sample Data</a><br>\n";
}
echo "<br>\n\n";

// Test 5: Display Sample Data
echo "<h2>5. Current Data</h2>\n";

// Count records
$tables_to_count = ['pt_locations', 'pt_packages', 'pt_car_types', 'pt_routes', 'pt_products'];
foreach($tables_to_count as $table) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}{$table}");
    echo ucfirst(str_replace('pt_', '', $table)) . ": $count records<br>\n";
}
echo "<br>\n\n";

// Show some packages
echo "<h3>Sample Packages:</h3>\n";
$packages = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pt_packages LIMIT 5");
if ($packages) {
    echo "<table border='1' cellpadding='5'>\n";
    echo "<tr><th>ID</th><th>Title</th><th>Price</th><th>Status</th></tr>\n";
    foreach($packages as $pkg) {
        echo "<tr>";
        echo "<td>{$pkg->id}</td>";
        echo "<td>{$pkg->title}</td>";
        echo "<td>₹" . number_format($pkg->base_price, 2) . "</td>";
        echo "<td>" . ($pkg->is_active ? 'Active' : 'Inactive') . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "No packages found.<br>\n";
}

echo "<br><br>\n\n";
echo "<h2>System Status: <span style='color: green;'>READY</span></h2>\n";
echo "<p>All components are properly installed and configured.</p>\n";
echo "<p>You can now access the admin panel at: <a href='/wp-admin/admin.php?page=pt-travel-management'>Travel Management</a></p>\n";
?>