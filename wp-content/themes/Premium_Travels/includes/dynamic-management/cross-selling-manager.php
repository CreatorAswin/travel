<?php
/**
 * Cross-Selling Manager
 * Handles cross-selling functionality between travel packages and products
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once 'packages-manager.php';
require_once 'products-manager.php';

class PT_Cross_Selling_Manager {
    
    private $packages_manager;
    private $products_manager;
    
    public function __construct() {
        $this->packages_manager = new PT_Packages_Manager();
        $this->products_manager = new PT_Products_Manager();
    }
    
    /**
     * Get suggested products for a given package
     */
    public function get_suggested_products_for_package($package_id) {
        $package = $this->packages_manager->get_by_id($package_id);
        if (!$package) {
            return array();
        }
        
        // First, try to get products from the package's suggested_products field
        if (!empty($package->suggested_products)) {
            $product_ids = maybe_unserialize($package->suggested_products);
            if (!$product_ids) {
                $product_ids = json_decode($package->suggested_products, true);
            }
            
            if (!$product_ids) {
                // If not serialized or JSON, try comma-separated
                $product_ids = array_map('trim', explode(',', $package->suggested_products));
            }
            
            if (is_array($product_ids) && !empty($product_ids)) {
                $products = array();
                foreach ($product_ids as $product_id) {
                    $product = $this->products_manager->get_by_id($product_id);
                    if ($product) {
                        $products[] = $product;
                    }
                }
                return $products;
            }
        }
        
        // Fallback: Get products from the same location as the package
        if (!empty($package->location_id)) {
            $args = array(
                'limit' => 4,
                'location_id' => $package->location_id,
                'status' => 'active'
            );
            return $this->products_manager->get_all($args);
        }
        
        // Fallback: Get popular products
        return $this->products_manager->get_popular(4);
    }
    
    /**
     * Get suggested packages for a given product
     */
    public function get_suggested_packages_for_product($product_id) {
        $product = $this->products_manager->get_by_id($product_id);
        if (!$product) {
            return array();
        }
        
        // First, try to get packages from the product's suggested_packages field
        if (!empty($product->suggested_packages)) {
            $package_ids = maybe_unserialize($product->suggested_packages);
            if (!$package_ids) {
                $package_ids = json_decode($product->suggested_packages, true);
            }
            
            if (!$package_ids) {
                // If not serialized or JSON, try comma-separated
                $package_ids = array_map('trim', explode(',', $product->suggested_packages));
            }
            
            if (is_array($package_ids) && !empty($package_ids)) {
                $packages = array();
                foreach ($package_ids as $package_id) {
                    $package = $this->packages_manager->get_by_id($package_id);
                    if ($package) {
                        $packages[] = $package;
                    }
                }
                return $packages;
            }
        }
        
        // Fallback: Get packages from the same location as the product
        if (!empty($product->location_id)) {
            $args = array(
                'limit' => 4,
                'location_id' => $product->location_id,
                'status' => 'active'
            );
            return $this->packages_manager->get_all($args);
        }
        
        // Fallback: Get popular packages
        return $this->packages_manager->get_popular(4);
    }
    
    /**
     * Get related products for a location
     */
    public function get_location_related_products($location_id, $limit = 4) {
        $args = array(
            'limit' => $limit,
            'location_id' => $location_id,
            'status' => 'active'
        );
        
        return $this->products_manager->get_all($args);
    }
    
    /**
     * Get related packages for a location
     */
    public function get_location_related_packages($location_id, $limit = 4) {
        $args = array(
            'limit' => $limit,
            'location_id' => $location_id,
            'status' => 'active'
        );
        
        return $this->packages_manager->get_all($args);
    }
    
    /**
     * Suggest travel packages based on purchased products
     */
    public function suggest_travel_based_on_purchased_products($customer_id = null, $limit = 4) {
        // In a real implementation, this would look at customer's purchase history
        // For now, we'll return popular packages
        return $this->packages_manager->get_popular($limit);
    }
    
    /**
     * Suggest products based on visited locations/packages
     */
    public function suggest_products_based_on_visited_locations($location_ids, $limit = 4) {
        if (empty($location_ids)) {
            return array();
        }
        
        global $wpdb;
        
        $placeholders = implode(',', array_fill(0, count($location_ids), '%d'));
        $values = array_merge($location_ids, $location_ids);
        $values[] = $limit;
        
        $query = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}pt_products 
             WHERE location_id IN ({$placeholders}) 
             AND is_active = 1 
             ORDER BY FIELD(location_id, {$placeholders})
             LIMIT %d",
            $values
        );
        
        $results = $wpdb->get_results($query);
        
        foreach ($results as $result) {
            $result = $this->products_manager->format_record($result);
        }
        
        return $results;
    }
    
    /**
     * Get cross-sell recommendations for cart items
     */
    public function get_cart_cross_sells($cart_items, $limit = 4) {
        $recommended = array();
        
        foreach ($cart_items as $item) {
            if ($item['type'] === 'package') {
                // If package in cart, suggest related products
                $related_products = $this->get_suggested_products_for_package($item['id']);
                $recommended = array_merge($recommended, $related_products);
            } elseif ($item['type'] === 'product') {
                // If product in cart, suggest related packages
                $related_packages = $this->get_suggested_packages_for_product($item['id']);
                $recommended = array_merge($recommended, $related_packages);
            }
        }
        
        // Remove duplicates and limit results
        $unique_recs = array();
        $ids_added = array();
        
        foreach ($recommended as $rec) {
            if (!in_array($rec->id, $ids_added)) {
                $unique_recs[] = $rec;
                $ids_added[] = $rec->id;
            }
            
            if (count($unique_recs) >= $limit) {
                break;
            }
        }
        
        return $unique_recs;
    }
    
    /**
     * Save suggested products for a package
     */
    public function save_suggested_products_for_package($package_id, $product_ids) {
        $package = $this->packages_manager->get_by_id($package_id);
        if (!$package) {
            return false;
        }
        
        // Convert to JSON format for storage
        $suggested_products_json = json_encode($product_ids);
        
        return $this->packages_manager->update($package_id, array(
            'suggested_products' => $suggested_products_json
        ));
    }
    
    /**
     * Save suggested packages for a product
     */
    public function save_suggested_packages_for_product($product_id, $package_ids) {
        $product = $this->products_manager->get_by_id($product_id);
        if (!$product) {
            return false;
        }
        
        // Convert to JSON format for storage
        $suggested_packages_json = json_encode($package_ids);
        
        return $this->products_manager->update($product_id, array(
            'suggested_packages' => $suggested_packages_json
        ));
    }
}