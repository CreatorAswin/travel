<?php
/**
 * Products Admin Interface
 * Handles the admin interface for managing products/tours
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Products_Admin {
    
    private $manager;
    
    public function __construct() {
        $this->manager = new PT_Products_Manager();
    }
    
    public function display_page() {
        global $wpdb;
        
        // Get locations for dropdown
        $locations = $wpdb->get_results("SELECT id, title FROM {$wpdb->prefix}pt_locations WHERE is_active = 1 ORDER BY title ASC");
        
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;
        
        if ($action === 'edit' || $action === 'add') {
            $this->display_form($action, $product_id, $locations);
        } else {
            $this->display_list();
        }
    }
    
    private function display_form($action, $product_id, $locations) {
        $product = null;
        
        if ($action === 'edit' && $product_id) {
            $product = $this->manager->get_by_id($product_id);
            if (!$product) {
                echo '<div class="notice notice-error"><p>Product not found.</p></div>';
                return;
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->process_form($action, $product_id);
            return;
        }
        
        ?>
        <div class="wrap">
            <h1><?php echo $action === 'edit' ? 'Edit Product' : 'Add New Product'; ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field($action === 'edit' ? 'update_product_' . $product_id : 'add_product'); ?>
                
                <input type="hidden" name="action" value="<?php echo $action === 'edit' ? 'update' : 'create'; ?>" />
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                <?php endif; ?>
                
                <table class="form-table">
                    <tr>
                        <th colspan="2"><h2 style="margin: 0 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Basic Information</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Title *</th>
                        <td><input type="text" name="title" value="<?php echo esc_attr($product->title ?? ''); ?>" class="regular-text" required /></td>
                    </tr>
                    <tr>
                        <th scope="row">Slug</th>
                        <td><input type="text" name="slug" value="<?php echo esc_attr($product->slug ?? ''); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Product Type</th>
                        <td>
                            <select name="product_type" class="regular-text">
                                <option value="physical_product" <?php selected($product->product_type ?? 'physical_product', 'physical_product'); ?>>Physical Product</option>
                                <option value="tour" <?php selected($product->product_type ?? 'physical_product', 'tour'); ?>>Tour</option>
                                <option value="activity" <?php selected($product->product_type ?? 'physical_product', 'activity'); ?>>Activity</option>
                                <option value="experience" <?php selected($product->product_type ?? 'physical_product', 'experience'); ?>>Experience</option>
                                <option value="package" <?php selected($product->product_type ?? 'physical_product', 'package'); ?>>Package</option>
                                <option value="service" <?php selected($product->product_type ?? 'physical_product', 'service'); ?>>Service</option>
                                <option value="attraction" <?php selected($product->product_type ?? 'physical_product', 'attraction'); ?>>Attraction</option>
                                <option value="adventure" <?php selected($product->product_type ?? 'physical_product', 'adventure'); ?>>Adventure</option>
                                <option value="cultural" <?php selected($product->product_type ?? 'physical_product', 'cultural'); ?>>Cultural</option>
                                <option value="digital_product" <?php selected($product->product_type ?? 'physical_product', 'digital_product'); ?>>Digital Product</option>
                                <option value="subscription" <?php selected($product->product_type ?? 'physical_product', 'subscription'); ?>>Subscription</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Location</th>
                        <td>
                            <select name="location_id" class="regular-text">
                                <option value="">Select Location (Optional)</option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?php echo esc_attr($location->id); ?>" <?php selected($product->location_id ?? '', $location->id); ?>>
                                        <?php echo esc_html($location->title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Short Description</th>
                        <td>
                            <textarea name="short_description" class="regular-text" rows="3" placeholder="Brief product description"><?php echo esc_textarea($product->short_description ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Descriptions</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Full Description</th>
                        <td>
                            <?php
                            $content = $product->description ?? '';
                            $editor_id = 'description';
                            $settings = array(
                                'textarea_rows' => 10,
                                'media_buttons' => false,
                            );
                            wp_editor($content, $editor_id, $settings);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Pricing Information</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Pricing</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Regular Price:</td>
                                    <td><input type="number" name="price_regular" value="<?php echo esc_attr($product->price_regular ?? ''); ?>" step="0.01" placeholder="Regular price" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Sale Price:</td>
                                    <td><input type="number" name="price_sale" value="<?php echo esc_attr($product->price_sale ?? ''); ?>" step="0.01" placeholder="Sale price (optional)" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Currency:</td>
                                    <td>
                                        <select name="currency">
                                            <option value="INR" <?php selected($product->currency ?? 'INR', 'INR'); ?>>INR (₹)</option>
                                            <option value="USD" <?php selected($product->currency ?? 'INR', 'USD'); ?>>USD ($)</option>
                                            <option value="EUR" <?php selected($product->currency ?? 'INR', 'EUR'); ?>>EUR (€)</option>
                                            <option value="GBP" <?php selected($product->currency ?? 'INR', 'GBP'); ?>>GBP (£)</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Inventory Management</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Inventory</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Sale Start Date:</td>
                                    <td><input type="date" name="sale_start_date" value="<?php echo esc_attr($product->sale_start_date ?? ''); ?>" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Sale End Date:</td>
                                    <td><input type="date" name="sale_end_date" value="<?php echo esc_attr($product->sale_end_date ?? ''); ?>" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Sale Dates</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Sale Start Date:</td>
                                    <td><input type="date" name="sale_start_date" value="<?php echo esc_attr($product->sale_start_date ?? ''); ?>" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Sale End Date:</td>
                                    <td><input type="date" name="sale_end_date" value="<?php echo esc_attr($product->sale_end_date ?? ''); ?>" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Inventory Management</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Inventory</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">SKU:</td>
                                    <td><input type="text" name="sku" value="<?php echo esc_attr($product->sku ?? ''); ?>" placeholder="Stock Keeping Unit" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Manage Stock:</td>
                                    <td><input type="checkbox" name="manage_stock" value="1" <?php checked($product->manage_stock ?? false, true); ?> /> Enable stock management</td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Stock Quantity:</td>
                                    <td><input type="number" name="stock_quantity" value="<?php echo esc_attr($product->stock_quantity ?? 0); ?>" min="0" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Stock Status:</td>
                                    <td>
                                        <select name="stock_status">
                                            <option value="instock" <?php selected($product->stock_status ?? 'instock', 'instock'); ?>>In Stock</option>
                                            <option value="outofstock" <?php selected($product->stock_status ?? 'instock', 'outofstock'); ?>>Out of Stock</option>
                                            <option value="onbackorder" <?php selected($product->stock_status ?? 'instock', 'onbackorder'); ?>>On Backorder</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Low Stock Threshold:</td>
                                    <td><input type="number" name="low_stock_threshold" value="<?php echo esc_attr($product->low_stock_threshold ?? 5); ?>" min="0" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Allow Backorders:</td>
                                    <td>
                                        <select name="allow_backorders">
                                            <option value="no" <?php selected($product->allow_backorders ?? 'no', 'no'); ?>>Do not allow</option>
                                            <option value="notify" <?php selected($product->allow_backorders ?? 'no', 'notify'); ?>>Allow but notify customer</option>
                                            <option value="yes" <?php selected($product->allow_backorders ?? 'no', 'yes'); ?>>Allow</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Shipping Information</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Shipping</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Weight (kg):</td>
                                    <td><input type="number" name="weight" value="<?php echo esc_attr($product->weight ?? ''); ?>" step="0.01" placeholder="Weight in kg" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Dimensions (L×W×H cm):</td>
                                    <td><input type="text" name="dimensions" value="<?php echo esc_attr($product->dimensions ?? ''); ?>" placeholder="Length x Width x Height" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Shipping Required:</td>
                                    <td><input type="checkbox" name="shipping_required" value="1" <?php checked($product->shipping_required ?? true, true); ?> /> Requires shipping</td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Shipping Class:</td>
                                    <td><input type="text" name="shipping_class" value="<?php echo esc_attr($product->shipping_class ?? ''); ?>" placeholder="Standard, Express, etc." /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Virtual Product:</td>
                                    <td><input type="checkbox" name="virtual" value="1" <?php checked($product->virtual ?? false, true); ?> /> Virtual (no shipping required)</td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Downloadable:</td>
                                    <td><input type="checkbox" name="downloadable" value="1" <?php checked($product->downloadable ?? false, true); ?> /> Downloadable product</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Tax Settings</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Tax Settings</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Tax Status:</td>
                                    <td>
                                        <select name="tax_status">
                                            <option value="taxable" <?php selected($product->tax_status ?? 'taxable', 'taxable'); ?>>Taxable</option>
                                            <option value="shipping" <?php selected($product->tax_status ?? 'taxable', 'shipping'); ?>>Tax for shipping only</option>
                                            <option value="none" <?php selected($product->tax_status ?? 'taxable', 'none'); ?>>None</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Tax Class:</td>
                                    <td><input type="text" name="tax_class" value="<?php echo esc_attr($product->tax_class ?? ''); ?>" placeholder="Standard, Reduced rate, Zero rate" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Purchase Options</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Purchase Options</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Sold Individually:</td>
                                    <td><input type="checkbox" name="sold_individually" value="1" <?php checked($product->sold_individually ?? false, true); ?> /> Limit one per order</td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Min Purchase Quantity:</td>
                                    <td><input type="number" name="min_purchase_quantity" value="<?php echo esc_attr($product->min_purchase_quantity ?? 1); ?>" min="1" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Max Purchase Quantity:</td>
                                    <td><input type="number" name="max_purchase_quantity" value="<?php echo esc_attr($product->max_purchase_quantity ?? 999); ?>" min="1" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Product Visibility</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Product Visibility</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Catalog Visibility:</td>
                                    <td>
                                        <select name="catalog_visibility">
                                            <option value="visible" <?php selected($product->catalog_visibility ?? 'visible', 'visible'); ?>>Visible</option>
                                            <option value="catalog" <?php selected($product->catalog_visibility ?? 'visible', 'catalog'); ?>>Catalog only</option>
                                            <option value="search" <?php selected($product->catalog_visibility ?? 'visible', 'search'); ?>>Search only</option>
                                            <option value="hidden" <?php selected($product->catalog_visibility ?? 'visible', 'hidden'); ?>>Hidden</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Featured:</td>
                                    <td><input type="checkbox" name="featured" value="1" <?php checked($product->featured ?? false, true); ?> /> Mark as featured</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Images</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Images</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Featured Image:</td>
                                    <td><input type="url" name="featured_image" value="<?php echo esc_url($product->featured_image ?? ''); ?>" class="regular-text" placeholder="Image URL" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Gallery Images:</td>
                                    <td><textarea name="gallery_images" class="regular-text" rows="3" placeholder="Additional image URLs (comma separated)"><?php echo esc_textarea($product->gallery_images ?? ''); ?></textarea></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Product Relations</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Product Relations</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Related Products:</td>
                                    <td><input type="text" name="related_products" value="<?php echo esc_attr($product->related_products ?? ''); ?>" class="regular-text" placeholder="Comma-separated product IDs" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Upsell IDs:</td>
                                    <td><input type="text" name="upsell_ids" value="<?php echo esc_attr($product->upsell_ids ?? ''); ?>" class="regular-text" placeholder="Comma-separated product IDs for upsells" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Cross-sell IDs:</td>
                                    <td><input type="text" name="cross_sell_ids" value="<?php echo esc_attr($product->cross_sell_ids ?? ''); ?>" class="regular-text" placeholder="Comma-separated product IDs for cross-sells" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Suggested Packages:</td>
                                    <td><input type="text" name="suggested_packages" value="<?php echo esc_attr($product->suggested_packages ?? ''); ?>" class="regular-text" placeholder="Comma-separated package IDs for suggested travel packages" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Tags & Categories</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Tags & Categories</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">Tags:</td>
                                    <td><input type="text" name="tags" value="<?php echo esc_attr($product->tags ?? ''); ?>" class="regular-text" placeholder="Comma-separated tags" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Categories:</td>
                                    <td><input type="text" name="categories" value="<?php echo esc_attr($product->categories ?? ''); ?>" class="regular-text" placeholder="Comma-separated categories" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Travel Suggestions</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Travel Suggestions</th>
                        <td>
                            <textarea name="travel_suggestions" class="regular-text" rows="3" placeholder="Related travel suggestions (comma separated)"><?php echo esc_textarea($product->travel_suggestions ?? ''); ?></textarea>
                            <br><small>Related travel suggestions for this location (comma separated)</small>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">SEO Settings</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">SEO Settings</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;">SEO Title:</td>
                                    <td><input type="text" name="seo_title" value="<?php echo esc_attr($product->seo_title ?? ''); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">SEO Description:</td>
                                    <td><textarea name="seo_description" class="regular-text" rows="2"><?php echo esc_textarea($product->seo_description ?? ''); ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Meta Keywords:</td>
                                    <td><input type="text" name="meta_keywords" value="<?php echo esc_attr($product->meta_keywords ?? ''); ?>" class="regular-text" placeholder="comma, separated, keywords" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2 style="margin: 30px 0 15px 0; padding: 10px 0; border-bottom: 1px solid #ddd;">Status & Sorting</h2></th>
                    </tr>
                    <tr>
                        <th scope="row">Status & Sorting</th>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding-right: 20px;"><input type="checkbox" name="is_available" value="1" <?php checked($product->is_available ?? true, true); ?> /> Available for Purchase</td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 20px;">Sort Order:</td>
                                    <td><input type="number" name="sort_order" value="<?php echo esc_attr($product->sort_order ?? 0); ?>" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button($action === 'edit' ? 'Update Product' : 'Add Product'); ?>
            </form>
        </div>
        <?php
    }
    
    private function process_form($action, $product_id) {
        if (!wp_verify_nonce($_POST['_wpnonce'], $action === 'edit' ? 'update_product_' . $product_id : 'add_product')) {
            wp_die('Security check failed');
        }
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $data = array();
        
        // Basic Information
        $data['title'] = sanitize_text_field($_POST['title'] ?? '');
        $data['slug'] = sanitize_title($_POST['slug'] ?? '');
        $data['description'] = wp_kses_post($_POST['description'] ?? '');
        $data['product_type'] = sanitize_text_field($_POST['product_type'] ?? 'physical_product');
        $data['location_id'] = absint($_POST['location_id'] ?? 0);
        $data['short_description'] = sanitize_textarea_field($_POST['short_description'] ?? '');
        
        // Pricing Information
        $data['price_regular'] = floatval($_POST['price_regular'] ?? 0);
        $data['price_sale'] = floatval($_POST['price_sale'] ?? 0) ?: null;
        $data['currency'] = sanitize_text_field($_POST['currency'] ?? 'INR');
        $data['sale_start_date'] = sanitize_text_field($_POST['sale_start_date'] ?? '');
        $data['sale_end_date'] = sanitize_text_field($_POST['sale_end_date'] ?? '');
        
        // Inventory Management
        $data['sku'] = sanitize_text_field($_POST['sku'] ?? '');
        $data['manage_stock'] = isset($_POST['manage_stock']) ? 1 : 0;
        $data['stock_quantity'] = absint($_POST['stock_quantity'] ?? 0);
        $data['stock_status'] = sanitize_text_field($_POST['stock_status'] ?? 'instock');
        $data['low_stock_threshold'] = absint($_POST['low_stock_threshold'] ?? 5);
        $data['allow_backorders'] = sanitize_text_field($_POST['allow_backorders'] ?? 'no');
        
        // Shipping Information
        $data['weight'] = floatval($_POST['weight'] ?? 0);
        $data['dimensions'] = sanitize_text_field($_POST['dimensions'] ?? '');
        $data['shipping_required'] = isset($_POST['shipping_required']) ? 1 : 1; // Default to required
        $data['shipping_class'] = sanitize_text_field($_POST['shipping_class'] ?? '');
        $data['virtual'] = isset($_POST['virtual']) ? 1 : 0;
        $data['downloadable'] = isset($_POST['downloadable']) ? 1 : 0;
        
        // Tax Settings
        $data['tax_status'] = sanitize_text_field($_POST['tax_status'] ?? 'taxable');
        $data['tax_class'] = sanitize_text_field($_POST['tax_class'] ?? '');
        
        // Purchase Options
        $data['sold_individually'] = isset($_POST['sold_individually']) ? 1 : 0;
        $data['min_purchase_quantity'] = absint($_POST['min_purchase_quantity'] ?? 1);
        $data['max_purchase_quantity'] = absint($_POST['max_purchase_quantity'] ?? 999);
        
        // Product Visibility
        $data['catalog_visibility'] = sanitize_text_field($_POST['catalog_visibility'] ?? 'visible');
        $data['featured'] = isset($_POST['featured']) ? 1 : 0;
        
        // Images
        $data['featured_image'] = esc_url_raw($_POST['featured_image'] ?? '');
        $data['gallery_images'] = sanitize_textarea_field($_POST['gallery_images'] ?? '');
        
        // Product Relations
        $data['related_products'] = sanitize_textarea_field($_POST['related_products'] ?? '');
        $data['upsell_ids'] = sanitize_textarea_field($_POST['upsell_ids'] ?? '');
        $data['cross_sell_ids'] = sanitize_textarea_field($_POST['cross_sell_ids'] ?? '');
        $data['suggested_packages'] = sanitize_textarea_field($_POST['suggested_packages'] ?? '');
        
        // Tags & Categories
        $data['tags'] = sanitize_textarea_field($_POST['tags'] ?? '');
        $data['categories'] = sanitize_textarea_field($_POST['categories'] ?? '');
        
        // Travel Suggestions
        $data['travel_suggestions'] = sanitize_textarea_field($_POST['travel_suggestions'] ?? '');
        
        // SEO Settings
        $data['seo_title'] = sanitize_text_field($_POST['seo_title'] ?? '');
        $data['seo_description'] = sanitize_textarea_field($_POST['seo_description'] ?? '');
        $data['meta_keywords'] = sanitize_text_field($_POST['meta_keywords'] ?? '');
        
        // Status & Sorting
        $data['is_available'] = isset($_POST['is_available']) ? 1 : 1; // Default to available
        $data['sort_order'] = absint($_POST['sort_order'] ?? 0);
        
        // Calculate discount percentage if both prices are provided
        if ($data['price_regular'] > 0 && $data['price_sale'] > 0 && $data['price_sale'] < $data['price_regular']) {
            $data['discount_percentage'] = (($data['price_regular'] - $data['price_sale']) / $data['price_regular']) * 100;
        } else {
            $data['discount_percentage'] = null;
        }
        
        try {
            if ($action === 'edit') {
                $this->manager->update($product_id, $data);
                echo '<div class="notice notice-success"><p>Product updated successfully!</p></div>';
            } else {
                $this->manager->create($data);
                echo '<div class="notice notice-success"><p>Product created successfully!</p></div>';
            }
            
            $this->display_list();
        } catch (Exception $e) {
            echo '<div class="notice notice-error"><p>Error: ' . $e->getMessage() . '</p></div>';
            $this->display_form($action, $product_id, []);
        }
    }
    
    private function display_list() {
        $page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        $args = array(
            'limit' => $per_page,
            'offset' => $offset,
            'orderby' => 'created_at',
            'order' => 'DESC'
        );
        
        $products = $this->manager->get_all($args);
        $total_products = $this->manager->get_count();
        $total_pages = ceil($total_products / $per_page);
        
        ?>
        <div class="wrap">
            <h1>Products/Tours</h1>
            <a href="<?php echo admin_url('admin.php?page=pt-products&action=add'); ?>" class="page-title-action">Add New</a>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product->id; ?></td>
                                <td>
                                    <strong><?php echo esc_html($product->title); ?></strong><br>
                                    <small><?php echo esc_html(wp_trim_words($product->description, 10)); ?></small>
                                </td>
                                <td><?php echo esc_html($product->product_type); ?></td>
                                <td>
                                    <?php
                                    if ($product->location_id) {
                                        global $wpdb;
                                        $location = $wpdb->get_row($wpdb->prepare(
                                            "SELECT title FROM {$wpdb->prefix}pt_locations WHERE id = %d",
                                            $product->location_id
                                        ));
                                        echo $location ? esc_html($location->title) : 'N/A';
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td>
                                    ₹<?php echo number_format($product->price_regular, 2); ?>
                                    <?php if ($product->price_sale && $product->price_sale < $product->price_regular): ?>
                                        <br><span style="color: green; font-size: 0.9em;">Sale: ₹<?php echo number_format($product->price_sale, 2); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($product->discount_percentage): ?>
                                        <span style="color: green;"><?php echo $product->discount_percentage; ?>% OFF</span>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($product->is_available): ?>
                                        <span style="color: green;">Available</span>
                                    <?php else: ?>
                                        <span style="color: red;">Unavailable</span>
                                    <?php endif; ?>
                                    <?php if ($product->is_featured): ?>
                                        <br><span style="color: orange;">Featured</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=pt-products&action=edit&product_id=' . $product->id); ?>">Edit</a> |
                                    <a href="<?php echo admin_url('admin.php?page=pt-products&action=delete&product_id=' . $product->id); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if ($total_pages > 1): ?>
                <div class="tablenav">
                    <div class="tablenav-pages">
                        <span class="pagination-links">
                            <?php if ($page > 1): ?>
                                <a class="prev-page button" href="<?php echo admin_url('admin.php?page=pt-products&paged=' . ($page - 1)); ?>">&laquo;</a>
                            <?php endif; ?>
                            
                            <span class="paging-input">
                                <span class="tablenav-paging-text"><?php echo $page; ?> of <?php echo $total_pages; ?></span>
                            </span>
                            
                            <?php if ($page < $total_pages): ?>
                                <a class="next-page button" href="<?php echo admin_url('admin.php?page=pt-products&paged=' . ($page + 1)); ?>">&raquo;</a>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}