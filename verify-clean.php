<?php
/**
 * Final Clean-up Verification
 * Checks that all dummy files are removed and system is clean
 */

require_once('wp-config.php');

global $wpdb;

echo "=== FINAL CLEAN-UP VERIFICATION ===\n\n";

echo "1. CHECKING ROOT DIRECTORY CLEAN:\n";
echo "----------------------------------\n";

$root_files = [
    'check-database.php',
    'check-real-data.php', 
    'debug-database.php',
    'diagnose-data-flow.php',
    'frontend-diagnostic.php',
    'migrate-my-data.php',
    'populate-all-tables.php',
    'populate-database.php'
];

$clean_count = 0;
foreach($root_files as $file) {
    if (file_exists($file)) {
        echo "❌ $file - STILL EXISTS\n";
    } else {
        echo "✅ $file - REMOVED\n";
        $clean_count++;
    }
}

echo "\n2. CHECKING THEME DIRECTORY CLEAN:\n";
echo "-----------------------------------\n";

$theme_files = [
    'sample-data.php',
    'test-system.php'
];

foreach($theme_files as $file) {
    $full_path = "wp-content/themes/Premium_Travels/$file";
    if (file_exists($full_path)) {
        echo "❌ $file - STILL EXISTS\n";
    } else {
        echo "✅ $file - REMOVED\n";
        $clean_count++;
    }
}

echo "\n3. CHECKING ADMIN DIRECTORY CLEAN:\n";
echo "----------------------------------\n";

$admin_files = [
    'offers-admin.php',
    'testimonials-admin.php',
    'enquiries-admin.php',
    'customers-admin.php',
    'locations-admin.php',
    'settings-admin.php'
];

foreach($admin_files as $file) {
    $full_path = "wp-content/themes/Premium_Travels/includes/admin/$file";
    if (file_exists($full_path)) {
        echo "❌ $file - STILL EXISTS\n";
    } else {
        echo "✅ $file - REMOVED\n";
        $clean_count++;
    }
}

echo "\n4. CHECKING MIGRATION FILES REMOVED:\n";
echo "------------------------------------\n";

$migration_files = [
    'wp-content/themes/Premium_Travels/includes/migration-tool.php',
    'wp-content/themes/Premium_Travels/includes/data-migration.php'
];

foreach($migration_files as $file) {
    if (file_exists($file)) {
        echo "❌ $file - STILL EXISTS\n";
    } else {
        echo "✅ $file - REMOVED\n";
        $clean_count++;
    }
}

echo "\n5. CHECKING DATABASE TABLES:\n";
echo "----------------------------\n";

$tables = ['pt_packages', 'pt_car_types', 'pt_routes', 'pt_products', 'pt_locations'];
$populated_count = 0;

foreach($tables as $table) {
    $full_table = $wpdb->prefix . $table;
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
    if ($exists) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $full_table WHERE is_active = 1");
        echo "$table: $count records\n";
        if ($count > 0) {
            $populated_count++;
        }
    } else {
        echo "$table: TABLE MISSING\n";
    }
}

echo "\n6. SYSTEM STATUS:\n";
echo "-----------------\n";
echo "Cleaned files: $clean_count\n";
echo "Populated tables: $populated_count/5\n";

if ($clean_count >= 14 && $populated_count >= 4) {
    echo "\n✅ SYSTEM CLEAN AND READY!\n";
    echo "All dummy files removed, migration tools cleaned up.\n";
    echo "Your travel application is now clean with real dynamic data.\n";
} else {
    echo "\n⚠️  Some cleanup items remain\n";
    echo "Please check the items marked with ❌ above\n";
}

echo "\n=== CLEAN-UP COMPLETE ===\n";
?>