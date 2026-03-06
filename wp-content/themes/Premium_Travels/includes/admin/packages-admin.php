<?php
/**
 * Packages Admin Page - Shows Real Database Data
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Packages_Admin {
    
    public function display_page() {
        global $wpdb;
        
        // Handle actions
        $this->handle_actions();
        
        // Get real packages from database
        $packages = $wpdb->get_results("
            SELECT 
                p.*,
                l.title as location_name
            FROM {$wpdb->prefix}pt_packages p
            LEFT JOIN {$wpdb->prefix}pt_locations l ON p.location_id = l.id
            ORDER BY p.sort_order ASC, p.created_at DESC
            LIMIT 50
        ");
        
        // Get package categories
        $categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pt_package_categories ORDER BY sort_order ASC");
        
        // Get locations
        $locations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pt_locations WHERE is_active = 1 ORDER BY title ASC");
        
        ?>
        <div class="wrap">
            <h1>Travel Packages Management</h1>
            
            <div class="tablenav top">
                <div class="alignleft actions">
                    <a href="#" class="button-primary" onclick="showAddPackageForm()">Add New Package</a>
                </div>
                <div class="alignright">
                    <p>Total Packages: <?php echo count($packages); ?> | Active: <?php echo count(array_filter($packages, function($p) { return $p->is_active; })); ?></p>
                </div>
            </div>
            
            <?php if ($packages): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Title</th>
                            <th width="10%">Type</th>
                            <th width="10%">Category</th>
                            <th width="10%">Location</th>
                            <th width="10%">Price</th>
                            <th width="8%">Bookings</th>
                            <th width="7%">Status</th>
                            <th width="10%">Rating</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($packages as $package): ?>
                            <tr>
                                <td><?php echo esc_html($package->id); ?></td>
                                <td>
                                    <strong><?php echo esc_html($package->title); ?></strong>
                                    <div class="row-actions">
                                        <span class="edit"><a href="#" onclick="editPackage(<?php echo $package->id; ?>)">Edit</a></span> | 
                                        <span class="delete"><a href="<?php echo admin_url('admin.php?page=pt-packages&action=delete&id=' . $package->id); ?>" onclick="return confirm('Are you sure?')">Delete</a></span>
                                    </div>
                                </td>
                                <td><?php echo esc_html($package->package_type); ?></td>
                                <td><?php echo $categories ? 'Category TBD' : 'No categories'; ?></td>
                                <td><?php echo esc_html($package->location_name ?: 'No location'); ?></td>
                                <td>₹<?php echo esc_html(number_format($package->base_price, 2)); ?></td>
                                <td><?php echo esc_html($package->current_bookings); ?></td>
                                <td>
                                    <span class="status-<?php echo $package->is_active ? 'active' : 'inactive'; ?>">
                                        <?php echo $package->is_active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($package->rating ? number_format($package->rating, 1) : '0.0'); ?>/5</td>
                                <td>
                                    <a href="#" class="button" onclick="editPackage(<?php echo $package->id; ?>)">Edit</a>
                                    <a href="<?php echo admin_url('admin.php?page=pt-packages&action=delete&id=' . $package->id); ?>" class="button" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="notice notice-info">
                    <p>No packages found in database. You can:</p>
                    <ul>
                        <li><a href="<?php echo admin_url('admin.php?page=pt-packages&action=migrate'); ?>">Migrate existing CPT data</a></li>
                        <li><a href="#" onclick="showAddPackageForm()">Add new package manually</a></li>
                        <li><a href="<?php echo get_template_directory_uri(); ?>/test-system.php?create_sample_data=1">Create sample data</a></li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div id="add-package-form" style="display:none; margin-top: 20px; padding: 20px; border: 1px solid #ddd; background: #f9f9f9;">
                <h2>Add New Package</h2>
                <form method="post" action="<?php echo admin_url('admin.php?page=pt-packages&action=add'); ?>">
                    <table class="form-table">
                        <tr>
                            <th><label for="title">Package Title</label></th>
                            <td><input type="text" name="title" id="title" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th><label for="package_type">Package Type</label></th>
                            <td>
                                <select name="package_type" id="package_type">
                                    <option value="city">City Tour</option>
                                    <option value="holiday">Holiday Package</option>
                                    <option value="pilgrimage">Pilgrimage</option>
                                    <option value="adventure">Adventure</option>
                                    <option value="cultural">Cultural</option>
                                    <option value="nature">Nature & Wildlife</option>
                                    <option value="beach">Beach Vacation</option>
                                    <option value="mountain">Mountain Tourism</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="location_id">Location</label></th>
                            <td>
                                <select name="location_id" id="location_id">
                                    <option value="">Select Location</option>
                                    <?php foreach($locations as $location): ?>
                                        <option value="<?php echo $location->id; ?>"><?php echo esc_html($location->title); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="duration_days">Duration (Days)</label></th>
                            <td><input type="number" name="duration_days" id="duration_days" class="regular-text" value="1"></td>
                        </tr>
                        <tr>
                            <th><label for="duration_nights">Duration (Nights)</label></th>
                            <td><input type="number" name="duration_nights" id="duration_nights" class="regular-text" value="0"></td>
                        </tr>
                        <tr>
                            <th><label for="base_price">Base Price (₹)</label></th>
                            <td><input type="number" name="base_price" id="base_price" class="regular-text" step="0.01" required></td>
                        </tr>
                        <tr>
                            <th><label for="price_per_person">Price Per Person (₹)</label></th>
                            <td><input type="number" name="price_per_person" id="price_per_person" class="regular-text" step="0.01"></td>
                        </tr>
                        <tr>
                            <th><label for="child_price">Child Price (₹)</label></th>
                            <td><input type="number" name="child_price" id="child_price" class="regular-text" step="0.01"></td>
                        </tr>
                        <tr>
                            <th><label for="infant_price">Infant Price (₹)</label></th>
                            <td><input type="number" name="infant_price" id="infant_price" class="regular-text" step="0.01"></td>
                        </tr>
                        <tr>
                            <th><label for="min_persons">Minimum Persons</label></th>
                            <td><input type="number" name="min_persons" id="min_persons" class="regular-text" value="1"></td>
                        </tr>
                        <tr>
                            <th><label for="max_persons">Maximum Persons</label></th>
                            <td><input type="number" name="max_persons" id="max_persons" class="regular-text" value="10"></td>
                        </tr>
                        <tr>
                            <th><label for="description">Description</label></th>
                            <td><textarea name="description" id="description" class="large-text" rows="4"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="includes">Includes</label></th>
                            <td><textarea name="includes" id="includes" class="large-text" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="excludes">Excludes</label></th>
                            <td><textarea name="excludes" id="excludes" class="large-text" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="highlights">Highlights</label></th>
                            <td><textarea name="highlights" id="highlights" class="large-text" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="itinerary">Itinerary</label></th>
                            <td><textarea name="itinerary" id="itinerary" class="large-text" rows="5"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="cancellation_policy">Cancellation Policy</label></th>
                            <td><textarea name="cancellation_policy" id="cancellation_policy" class="large-text" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="terms_conditions">Terms & Conditions</label></th>
                            <td><textarea name="terms_conditions" id="terms_conditions" class="large-text" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="featured_image">Featured Image URL</label></th>
                            <td><input type="url" name="featured_image" id="featured_image" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="gallery_images">Gallery Images (URLs)</label></th>
                            <td><textarea name="gallery_images" id="gallery_images" class="large-text" rows="2" placeholder="Enter image URLs separated by commas"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="availability_type">Availability Type</label></th>
                            <td>
                                <select name="availability_type" id="availability_type">
                                    <option value="always">Always Available</option>
                                    <option value="seasonal">Seasonal</option>
                                    <option value="custom">Custom Days</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="seasonal_dates" style="display:none;">
                            <th><label for="start_date">Start Date</label></th>
                            <td><input type="date" name="start_date" id="start_date"></td>
                        </tr>
                        <tr id="seasonal_end_date" style="display:none;">
                            <th><label for="end_date">End Date</label></th>
                            <td><input type="date" name="end_date" id="end_date"></td>
                        </tr>
                        <tr id="custom_days" style="display:none;">
                            <th><label for="available_days">Available Days</label></th>
                            <td>
                                <label><input type="checkbox" name="available_days[]" value="1"> Monday</label>
                                <label><input type="checkbox" name="available_days[]" value="2"> Tuesday</label>
                                <label><input type="checkbox" name="available_days[]" value="3"> Wednesday</label>
                                <label><input type="checkbox" name="available_days[]" value="4"> Thursday</label>
                                <label><input type="checkbox" name="available_days[]" value="5"> Friday</label>
                                <label><input type="checkbox" name="available_days[]" value="6"> Saturday</label>
                                <label><input type="checkbox" name="available_days[]" value="7"> Sunday</label>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="max_bookings_per_day">Max Bookings Per Day (0 for unlimited)</label></th>
                            <td><input type="number" name="max_bookings_per_day" id="max_bookings_per_day" class="regular-text" value="0"></td>
                        </tr>
                        <tr>
                            <th><label for="is_featured">Featured Package</label></th>
                            <td><input type="checkbox" name="is_featured" id="is_featured" value="1"></td>
                        </tr>
                        <tr>
                            <th><label for="is_active">Status</label></th>
                            <td>
                                <select name="is_active" id="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="suggested_products">Suggested Products</label></th>
                            <td><textarea name="suggested_products" id="suggested_products" class="large-text" rows="3" placeholder="Enter product IDs separated by commas for cross-selling"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="seo_title">SEO Title</label></th>
                            <td><input type="text" name="seo_title" id="seo_title" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="seo_description">SEO Description</label></th>
                            <td><textarea name="seo_description" id="seo_description" class="large-text" rows="2"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="meta_keywords">Meta Keywords</label></th>
                            <td><input type="text" name="meta_keywords" id="meta_keywords" class="regular-text" placeholder="keyword1, keyword2, keyword3"></td>
                        </tr>
                    </table>
                    <?php submit_button('Add Package'); ?>
                </form>
            </div>
            
            <script>
                function showAddPackageForm() {
                    document.getElementById('add-package-form').style.display = 'block';
                }
                
                function editPackage(id) {
                    alert('Edit functionality coming soon. Package ID: ' + id);
                }
                
                // Show/hide availability fields based on selection
                document.addEventListener('DOMContentLoaded', function() {
                    const availabilitySelect = document.getElementById('availability_type');
                    
                    availabilitySelect.addEventListener('change', function() {
                        const selectedValue = this.value;
                        
                        if (selectedValue === 'seasonal') {
                            document.getElementById('seasonal_dates').style.display = 'table-row';
                            document.getElementById('seasonal_end_date').style.display = 'table-row';
                            document.getElementById('custom_days').style.display = 'none';
                        } else if (selectedValue === 'custom') {
                            document.getElementById('seasonal_dates').style.display = 'none';
                            document.getElementById('seasonal_end_date').style.display = 'none';
                            document.getElementById('custom_days').style.display = 'table-row';
                        } else {
                            document.getElementById('seasonal_dates').style.display = 'none';
                            document.getElementById('seasonal_end_date').style.display = 'none';
                            document.getElementById('custom_days').style.display = 'none';
                        }
                    });
                });
            </script>
            
            <style>
                .status-active { color: #00a32a; }
                .status-inactive { color: #dc3232; }
                .form-table th { width: 200px; }
            </style>
        </div>
        <?php
    }
    
    private function handle_actions() {
        if (isset($_GET['action']) && current_user_can('manage_options')) {
            global $wpdb;
            $action = $_GET['action'];
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            switch ($action) {
                case 'delete':
                    if ($id) {
                        $wpdb->delete(
                            $wpdb->prefix . 'pt_packages',
                            array('id' => $id)
                        );
                        echo '<div class="notice notice-success"><p>Package deleted successfully.</p></div>';
                    }
                    break;
                    
                case 'add':
                    if ($_POST) {
                        $data = array(
                            'title' => sanitize_text_field($_POST['title']),
                            'package_type' => sanitize_text_field($_POST['package_type']),
                            'location_id' => intval($_POST['location_id']),
                            'duration_days' => intval($_POST['duration_days']),
                            'duration_nights' => intval($_POST['duration_nights']),
                            'base_price' => floatval($_POST['base_price']),
                            'price_per_person' => floatval($_POST['price_per_person']),
                            'child_price' => floatval($_POST['child_price']),
                            'infant_price' => floatval($_POST['infant_price']),
                            'min_persons' => intval($_POST['min_persons']),
                            'max_persons' => intval($_POST['max_persons']),
                            'description' => sanitize_textarea_field($_POST['description']),
                            'includes' => sanitize_textarea_field($_POST['includes']),
                            'excludes' => sanitize_textarea_field($_POST['excludes']),
                            'highlights' => sanitize_textarea_field($_POST['highlights']),
                            'itinerary' => sanitize_textarea_field($_POST['itinerary']),
                            'cancellation_policy' => sanitize_textarea_field($_POST['cancellation_policy']),
                            'terms_conditions' => sanitize_textarea_field($_POST['terms_conditions']),
                            'featured_image' => esc_url_raw($_POST['featured_image']),
                            'gallery_images' => sanitize_textarea_field($_POST['gallery_images']),
                            'availability_type' => sanitize_text_field($_POST['availability_type']),
                            'start_date' => sanitize_text_field($_POST['start_date']),
                            'end_date' => sanitize_text_field($_POST['end_date']),
                            'available_days' => isset($_POST['available_days']) ? implode(',', $_POST['available_days']) : '',
                            'max_bookings_per_day' => intval($_POST['max_bookings_per_day']),
                            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                            'is_active' => intval($_POST['is_active']),
                            'suggested_products' => sanitize_textarea_field($_POST['suggested_products']),
                            'seo_title' => sanitize_text_field($_POST['seo_title']),
                            'seo_description' => sanitize_textarea_field($_POST['seo_description']),
                            'meta_keywords' => sanitize_text_field($_POST['meta_keywords']),
                            'sort_order' => 0,
                            'created_at' => current_time('mysql'),
                            'updated_at' => current_time('mysql')
                        );
                        
                        $result = $wpdb->insert($wpdb->prefix . 'pt_packages', $data);
                        if ($result !== false) {
                            echo '<div class="notice notice-success"><p>Package added successfully.</p></div>';
                        } else {
                            echo '<div class="notice notice-error"><p>Error adding package.</p></div>';
                        }
                    }
                    break;
                    
                case 'migrate':
                    // Migration removed - data already populated
                    echo '<div class="notice notice-info"><p>Migration functionality removed - data already populated.</p></div>';
                    break;
            }
        }
    }
}