<?php
/**
 * Car Types Admin Page - Shows Real Database Data
 */

if (!defined('ABSPATH')) {
    exit;
}

class PT_Car_Types_Admin {
    public function display_page() {
        global $wpdb;
        
        // Handle actions
        $this->handle_actions();
        
        // Get real car types from database
        $car_types = $wpdb->get_results("
            SELECT * 
            FROM {$wpdb->prefix}pt_car_types 
            ORDER BY sort_order ASC, created_at DESC
            LIMIT 50
        ");
        
        ?>
        <div class="wrap">
            <h1>Car Types Management</h1>
            <div class="tablenav top">
                <div class="alignleft actions">
                    <a href="#" class="button-primary" onclick="showAddCarForm()">Add New Car Type</a>
                </div>
                <div class="alignright">
                    <p>Total Car Types: <?php echo count($car_types); ?> | Available: <?php echo count(array_filter($car_types, function($c) { return $c->is_active && $c->availability_status === 'available'; })); ?></p>
                </div>
            </div>
            
            <?php if ($car_types): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Capacity</th>
                            <th>Price/KM</th>
                            <th>Availability</th>
                            <th>Status</th>
                            <th>Trips</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($car_types as $car): ?>
                            <tr>
                                <td><?php echo esc_html($car->id); ?></td>
                                <td>
                                    <strong><?php echo esc_html($car->title); ?></strong>
                                    <div class="row-actions">
                                        <span class="edit"><a href="#" onclick="editCarType(<?php echo $car->id; ?>)">Edit</a></span> | 
                                        <span class="delete"><a href="<?php echo admin_url('admin.php?page=pt-car-types&action=delete&id=' . $car->id); ?>" onclick="return confirm('Are you sure?')">Delete</a></span>
                                    </div>
                                </td>
                                <td><?php echo esc_html($car->category); ?></td>
                                <td><?php echo esc_html($car->capacity); ?> seats</td>
                                <td>₹<?php echo esc_html(number_format($car->base_price_per_km, 2)); ?></td>
                                <td>
                                    <span class="availability-<?php echo esc_attr($car->availability_status); ?>">
                                        <?php echo esc_html(ucfirst($car->availability_status)); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-<?php echo $car->is_active ? 'active' : 'inactive'; ?>">
                                        <?php echo $car->is_active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($car->total_trips); ?></td>
                                <td><?php echo esc_html($car->rating ? number_format($car->rating, 1) : '0.0'); ?>/5</td>
                                <td>
                                    <a href="#" class="button" onclick="editCarType(<?php echo $car->id; ?>)">Edit</a>
                                    <a href="<?php echo admin_url('admin.php?page=pt-car-types&action=delete&id=' . $car->id); ?>" class="button" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="notice notice-info">
                    <p>No car types found in database. You can:</p>
                    <ul>
                        <li><a href="<?php echo admin_url('admin.php?page=pt-car-types&action=migrate'); ?>">Migrate existing CPT data</a></li>
                        <li><a href="#" onclick="showAddCarForm()">Add new car type manually</a></li>
                        <li><a href="<?php echo get_template_directory_uri(); ?>/test-system.php?create_sample_data=1">Create sample data</a></li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div id="add-car-form" style="display:none; margin-top: 20px; padding: 20px; border: 1px solid #ddd; background: #f9f9f9;">
                <h2>Add New Car Type</h2>
                <form method="post" action="<?php echo admin_url('admin.php?page=pt-car-types&action=add'); ?>">
                    <table class="form-table">
                        <tr>
                            <th><label for="title">Car Title</label></th>
                            <td><input type="text" name="title" id="title" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th><label for="category">Category</label></th>
                            <td>
                                <select name="category" id="category">
                                    <option value="Mini">Mini</option>
                                    <option value="Sedan">Sedan</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Luxury">Luxury</option>
                                    <option value="Tempo Traveller">Tempo Traveller</option>
                                    <option value="Coach">Coach</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="capacity">Capacity</label></th>
                            <td><input type="number" name="capacity" id="capacity" min="1" max="50" value="4" required></td>
                        </tr>
                        <tr>
                            <th><label for="base_price_per_km">Price per KM (₹)</label></th>
                            <td><input type="number" name="base_price_per_km" id="base_price_per_km" step="0.01" required></td>
                        </tr>
                        <tr>
                            <th><label for="ac_type">AC Type</label></th>
                            <td>
                                <select name="ac_type" id="ac_type">
                                    <option value="AC">AC</option>
                                    <option value="Non-AC">Non-AC</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="fuel_type">Fuel Type</label></th>
                            <td>
                                <select name="fuel_type" id="fuel_type">
                                    <option value="Petrol">Petrol</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="CNG">CNG</option>
                                    <option value="Electric">Electric</option>
                                </select>
                            </td>
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
                    </table>
                    <?php submit_button('Add Car Type'); ?>
                </form>
            </div>
            
            <script>
                function showAddCarForm() {
                    document.getElementById('add-car-form').style.display = 'block';
                }
                function editCarType(id) {
                    alert('Edit functionality coming soon. Car Type ID: ' + id);
                }
            </script>
            
            <style>
                .status-active { color: #00a32a; }
                .status-inactive { color: #dc3232; }
                .availability-available { color: #00a32a; }
                .availability-maintenance { color: #FFA500; }
                .availability-booked { color: #dc3232; }
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
                            $wpdb->prefix . 'pt_car_types',
                            array('id' => $id)
                        );
                        echo '<div class="notice notice-success"><p>Car type deleted successfully.</p></div>';
                    }
                    break;
                    
                case 'add':
                    if ($_POST) {
                        $data = array(
                            'title' => sanitize_text_field($_POST['title']),
                            'category' => sanitize_text_field($_POST['category']),
                            'capacity' => intval($_POST['capacity']),
                            'base_price_per_km' => floatval($_POST['base_price_per_km']),
                            'ac_type' => sanitize_text_field($_POST['ac_type']),
                            'fuel_type' => sanitize_text_field($_POST['fuel_type']),
                            'is_active' => intval($_POST['is_active']),
                            'availability_status' => 'available',
                            'is_featured' => 0,
                            'sort_order' => 0,
                            'created_at' => current_time('mysql'),
                            'updated_at' => current_time('mysql')
                        );
                        
                        $result = $wpdb->insert($wpdb->prefix . 'pt_car_types', $data);
                        if ($result !== false) {
                            echo '<div class="notice notice-success"><p>Car type added successfully.</p></div>';
                        } else {
                            echo '<div class="notice notice-error"><p>Error adding car type.</p></div>';
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