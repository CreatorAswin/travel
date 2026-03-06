<?php
// Check actual database data
require_once('wp-config.php');

global $wpdb;

echo "=== ACTUAL DATABASE DATA CHECK ===\n\n";

// Check existing WordPress posts
echo "EXISTING CUSTOM POST TYPES:\n";
$cpts = ['location', 'taxi_package', 'route', 'testimonial', 'car_type', 'holiday_package', 'special_offer', 'city_service', 'pt_product'];
foreach($cpts as $cpt) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish'");
    echo "$cpt: $count posts\n";
    
    if ($count > 0) {
        $posts = $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = '$cpt' AND post_status = 'publish' LIMIT 3");
        foreach($posts as $post) {
            echo "  - {$post->post_title}\n";
        }
    }
}

echo "\n=== CUSTOM TABLES DATA ===\n";

// Check custom tables data
$custom_tables = [
    'pt_bookings' => 'Bookings',
    'pt_enquiries' => 'Enquiries',
    'pt_customers' => 'Customers',
    'pt_packages' => 'Packages',
    'pt_car_types' => 'Car Types',
    'pt_routes' => 'Routes',
    'pt_products' => 'Products',
    'pt_locations' => 'Locations'
];

foreach($custom_tables as $table_name => $display_name) {
    $full_table = $wpdb->prefix . $table_name;
    $count = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    
    if ($count) {
        $record_count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table");
        echo "$display_name: $record_count records\n";
        
        if ($record_count > 0) {
            $records = $wpdb->get_results("SELECT * FROM $full_table LIMIT 3");
            foreach($records as $record) {
                if (isset($record->title)) {
                    echo "  - {$record->title}\n";
                } elseif (isset($record->booking_reference)) {
                    echo "  - Booking: {$record->booking_reference}\n";
                } elseif (isset($record->name)) {
                    echo "  - {$record->name}\n";
                }
            }
        }
    } else {
        echo "$display_name: Table does not exist\n";
    }
}

echo "\n=== DASHBOARD DATA ANALYSIS ===\n";

// Get real statistics for dashboard
$package_stats = $wpdb->get_row("
    SELECT 
        COUNT(*) as total_packages,
        COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_packages,
        COUNT(CASE WHEN is_featured = 1 THEN 1 END) as featured_packages
    FROM {$wpdb->prefix}pt_packages
");

$car_stats = $wpdb->get_row("
    SELECT 
        COUNT(*) as total_car_types,
        COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_car_types,
        COUNT(CASE WHEN availability_status = 'available' THEN 1 END) as available_car_types
    FROM {$wpdb->prefix}pt_car_types
");

$booking_stats = $wpdb->get_row("
    SELECT 
        COUNT(*) as total_bookings,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_bookings,
        SUM(CASE WHEN status = 'completed' THEN total_price ELSE 0 END) as total_revenue
    FROM {$wpdb->prefix}pt_bookings
");

echo "REAL DASHBOARD STATISTICS:\n";
echo "Active Packages: " . ($package_stats ? $package_stats->active_packages : 0) . "\n";
echo "Available Car Types: " . ($car_stats ? $car_stats->available_car_types : 0) . "\n";
echo "Total Bookings: " . ($booking_stats ? $booking_stats->total_bookings : 0) . "\n";
echo "Total Revenue: ₹" . ($booking_stats ? number_format($booking_stats->total_revenue, 2) : '0.00') . "\n";

echo "\n=== RECOMMENDATION ===\n";
echo "To populate your database with real data, you can:\n";
echo "1. Run the migration tool to convert existing CPT data\n";
echo "2. Add data manually through the admin interface\n";
echo "3. Use the sample data creation feature in test-system.php\n";
?>