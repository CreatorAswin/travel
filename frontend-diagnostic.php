<?php
/**
 * Frontend Data Display Diagnostic
 * Checks why frontend isn't showing database data and identifies empty tables
 */

require_once('wp-config.php');

global $wpdb;

echo "=== FRONTEND DATA DISPLAY DIAGNOSTIC ===\n\n";

echo "1. CHECKING ALL DATABASE TABLES:\n";
echo "--------------------------------\n";

$tables = [
    'pt_packages' => 'Packages',
    'pt_car_types' => 'Car Types',
    'pt_routes' => 'Routes',
    'pt_products' => 'Products',
    'pt_locations' => 'Locations',
    'pt_bookings' => 'Bookings'
];

$empty_tables = array();
$filled_tables = array();

foreach($tables as $table_name => $display_name) {
    $full_table = $wpdb->prefix . $table_name;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    
    if ($exists) {
        $active_count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table WHERE is_active = 1");
        $total_count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table");
        
        echo "$display_name: $active_count active / $total_count total records\n";
        
        if ($active_count == 0) {
            $empty_tables[] = $table_name;
        } else {
            $filled_tables[] = $table_name;
            // Show sample data
            $samples = $wpdb->get_results("SELECT * FROM $full_table WHERE is_active = 1 LIMIT 2");
            foreach($samples as $sample) {
                if (isset($sample->title)) {
                    echo "  - {$sample->title}\n";
                } elseif (isset($sample->booking_reference)) {
                    echo "  - Booking: {$sample->booking_reference}\n";
                }
            }
        }
    } else {
        echo "$display_name: TABLE DOES NOT EXIST\n";
        $empty_tables[] = $table_name;
    }
}

echo "\n2. FRONTEND DISPLAY TEST:\n";
echo "------------------------\n";

// Test frontend functions
require_once('wp-content/themes/Premium_Travels/includes/frontend/dynamic-display.php');

echo "Package Shortcode Test:\n";
$package_output = do_shortcode('[pt_packages limit="3"]');
if (strpos($package_output, 'No packages available') !== false) {
    echo "  ❌ Showing: No packages available\n";
} else {
    echo "  ✅ Showing package data\n";
    // Show first 100 chars to verify content
    echo "  Content preview: " . substr(strip_tags($package_output), 0, 100) . "...\n";
}

echo "\nCar Types Shortcode Test:\n";
$car_output = do_shortcode('[pt_car_types limit="3"]');
if (strpos($car_output, 'No car types available') !== false) {
    echo "  ❌ Showing: No car types available\n";
} else {
    echo "  ✅ Showing car type data\n";
    echo "  Content preview: " . substr(strip_tags($car_output), 0, 100) . "...\n";
}

echo "\nRoutes Shortcode Test:\n";
$route_output = do_shortcode('[pt_routes limit="3"]');
if (strpos($route_output, 'No routes available') !== false) {
    echo "  ❌ Showing: No routes available\n";
} else {
    echo "  ✅ Showing route data\n";
    echo "  Content preview: " . substr(strip_tags($route_output), 0, 100) . "...\n";
}

echo "\n3. EMPTY TABLES THAT NEED DATA:\n";
echo "-------------------------------\n";
if (!empty($empty_tables)) {
    echo "The following tables are empty and need data:\n";
    foreach($empty_tables as $table) {
        echo "  - " . ucfirst(str_replace('pt_', '', $table)) . "\n";
    }
    
    echo "\n4. QUICK DATA ADDITION TOOL:\n";
    echo "---------------------------\n";
    echo "Visit these pages to add data:\n";
    echo "  Dashboard: /wp-admin/admin.php?page=pt-travel-management\n";
    echo "  Packages: /wp-admin/admin.php?page=pt-packages\n";
    echo "  Car Types: /wp-admin/admin.php?page=pt-car-types\n";
    echo "  Routes: /wp-admin/admin.php?page=pt-routes\n";
    echo "  Products: /wp-admin/admin.php?page=pt-products\n";
    
    echo "\nOr add sample data for testing:\n";
    echo "  <a href='populate-database.php?action=populate'>Add Sample Data</a>\n";
} else {
    echo "✅ All tables have data!\n";
    echo "If frontend still not showing data, check:\n";
    echo "1. Shortcode usage on pages\n";
    echo "2. Page template includes\n";
    echo "3. Theme function loading\n";
}

echo "\n5. DEBUGGING FRONTEND ISSUES:\n";
echo "----------------------------\n";

// Check if shortcodes are registered
echo "Shortcode Registration Check:\n";
global $shortcode_tags;
$travel_shortcodes = ['pt_packages', 'pt_car_types', 'pt_routes'];
foreach($travel_shortcodes as $sc) {
    if (isset($shortcode_tags[$sc])) {
        echo "  ✅ [$sc] shortcode is registered\n";
    } else {
        echo "  ❌ [$sc] shortcode is NOT registered\n";
    }
}

// Check if theme functions are loaded
echo "\nTheme Functions Check:\n";
$required_functions = ['pt_display_packages', 'pt_display_car_types', 'pt_display_routes'];
foreach($required_functions as $func) {
    if (function_exists($func)) {
        echo "  ✅ $func() function exists\n";
    } else {
        echo "  ❌ $func() function does NOT exist\n";
    }
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
?>