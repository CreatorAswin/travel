<?php
/**
 * Simplified Products Admin Interface
 * Streamlined version with essential fields only
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Products_Admin_Simple {
    
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
                        <th scope="row">Title *</th>
                        <td><input type="text" name="title" value="<?php echo esc_attr($product->title ?? ''); ?>" class="regular-text" required /></td>
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
                        <th scope="row">Full Description</th>
                        <td>
                            <?php
                            $content = $product->description ?? '';
                            $editor_id = 'description';
                            $settings = array(
                                'textarea_rows' => 8,
                                'media_buttons' => true,
                                'teeny' => true,
                            );
                            wp_editor($content, $editor_id, $settings);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Regular Price (₹)</th>
                        <td><input type="number" name="price_regular" value="<?php echo esc_attr($product->price_regular ?? ''); ?>" step="0.01" class="regular-text" placeholder="Regular price" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Sale Price (₹)</th>
                        <td><input type="number" name="price_sale" value="<?php echo esc_attr($product->price_sale ?? ''); ?>" step="0.01" class="regular-text" placeholder="Sale price (optional)" /></td>
                    </tr>
                    <tr>
                        <th scope="row">SKU</th>
                        <td><input type="text" name="sku" value="<?php echo esc_attr($product->sku ?? ''); ?>" class="regular-text" placeholder="Stock Keeping Unit" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Stock Quantity</th>
                        <td><input type="number" name="stock_quantity" value="<?php echo esc_attr($product->stock_quantity ?? 0); ?>" min="0" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Stock Status</th>
                        <td>
                            <select name="stock_status" class="regular-text">
                                <option value="instock" <?php selected($product->stock_status ?? 'instock', 'instock'); ?>>In Stock</option>
                                <option value="outofstock" <?php selected($product->stock_status ?? 'instock', 'outofstock'); ?>>Out of Stock</option>
                                <option value="onbackorder" <?php selected($product->stock_status ?? 'instock', 'onbackorder'); ?>>On Backorder</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Featured Image</th>
                        <td><input type="url" name="featured_image" value="<?php echo esc_url($product->featured_image ?? ''); ?>" class="regular-text" placeholder="Image URL" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Gallery Images</th>
                        <td><textarea name="gallery_images" class="regular-text" rows="2" placeholder="Additional image URLs (comma separated)"><?php echo esc_textarea($product->gallery_images ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">Tags</th>
                        <td><input type="text" name="tags" value="<?php echo esc_attr($product->tags ?? ''); ?>" class="regular-text" placeholder="Comma-separated tags" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Categories</th>
                        <td><input type="text" name="categories" value="<?php echo esc_attr($product->categories ?? ''); ?>" class="regular-text" placeholder="Comma-separated categories" /></td>
                    </tr>
                    <tr>
                        <th scope="row">SEO Title</th>
                        <td><input type="text" name="seo_title" value="<?php echo esc_attr($product->seo_title ?? ''); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">SEO Description</th>
                        <td><textarea name="seo_description" class="regular-text" rows="2"><?php echo esc_textarea($product->seo_description ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">Status</th>
                        <td>
                            <label><input type="checkbox" name="is_available" value="1" <?php checked($product->is_available ?? true, true); ?> /> Available for Purchase</label><br>
                            <label><input type="checkbox" name="featured" value="1" <?php checked($product->featured ?? false, true); ?> /> Mark as Featured</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Sort Order</th>
                        <td><input type="number" name="sort_order" value="<?php echo esc_attr($product->sort_order ?? 0); ?>" class="regular-text" /></td>
                    </tr>
                </table>
                
                <?php submit_button($action === 'edit' ? 'Update Product' : 'Add Product'); ?>
            </form>
        </div>
        
        <style>
            .form-table th { width: 200px; }
        </style>
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
        
        // Essential fields only
        $data['title'] = sanitize_text_field($_POST['title'] ?? '');
        $data['product_type'] = sanitize_text_field($_POST['product_type'] ?? 'physical_product');
        $data['location_id'] = absint($_POST['location_id'] ?? 0);
        $data['short_description'] = sanitize_textarea_field($_POST['short_description'] ?? '');
        $data['description'] = wp_kses_post($_POST['description'] ?? '');
        $data['price_regular'] = floatval($_POST['price_regular'] ?? 0);
        $data['price_sale'] = floatval($_POST['price_sale'] ?? 0) ?: null;
        $data['sku'] = sanitize_text_field($_POST['sku'] ?? '');
        $data['stock_quantity'] = absint($_POST['stock_quantity'] ?? 0);
        $data['stock_status'] = sanitize_text_field($_POST['stock_status'] ?? 'instock');
        $data['featured_image'] = esc_url_raw($_POST['featured_image'] ?? '');
        $data['gallery_images'] = sanitize_textarea_field($_POST['gallery_images'] ?? '');
        $data['tags'] = sanitize_textarea_field($_POST['tags'] ?? '');
        $data['categories'] = sanitize_textarea_field($_POST['categories'] ?? '');
        $data['seo_title'] = sanitize_text_field($_POST['seo_title'] ?? '');
        $data['seo_description'] = sanitize_textarea_field($_POST['seo_description'] ?? '');
        $data['is_available'] = isset($_POST['is_available']) ? 1 : 1;
        $data['featured'] = isset($_POST['featured']) ? 1 : 0;
        $data['sort_order'] = absint($_POST['sort_order'] ?? 0);
        
        // Calculate discount percentage
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
            <h1>Products</h1>
            <a href="<?php echo admin_url('admin.php?page=pt-products-simple&action=add'); ?>" class="page-title-action">Add New</a>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Price</th>
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
                                    <small><?php echo esc_html(wp_trim_words($product->short_description, 10)); ?></small>
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
                                    <?php if ($product->is_available): ?>
                                        <span style="color: green;">Available</span>
                                    <?php else: ?>
                                        <span style="color: red;">Unavailable</span>
                                    <?php endif; ?>
                                    <?php if ($product->featured): ?>
                                        <br><span style="color: orange;">Featured</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=pt-products-simple&action=edit&product_id=' . $product->id); ?>">Edit</a> |
                                    <a href="<?php echo admin_url('admin.php?page=pt-products-simple&action=delete&product_id=' . $product->id); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if ($total_pages > 1): ?>
                <div class="tablenav">
                    <div class="tablenav-pages">
                        <span class="pagination-links">
                            <?php if ($page > 1): ?>
                                <a class="prev-page button" href="<?php echo admin_url('admin.php?page=pt-products-simple&paged=' . ($page - 1)); ?>">&laquo;</a>
                            <?php endif; ?>
                            
                            <span class="paging-input">
                                <span class="tablenav-paging-text"><?php echo $page; ?> of <?php echo $total_pages; ?></span>
                            </span>
                            
                            <?php if ($page < $total_pages): ?>
                                <a class="next-page button" href="<?php echo admin_url('admin.php?page=pt-products-simple&paged=' . ($page + 1)); ?>">&raquo;</a>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}