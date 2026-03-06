<?php
/**
 * Template Name: Cab Booking
 * Dynamic template for different cab service types
 */

get_header();

// Get service type from URL parameter
$service_id = isset($_GET['service']) ? base64_decode($_GET['service']) : '';
$service_name = 'Cab Booking';

// Map service IDs to names
$service_map = array(
    '24' => 'Local Trip',
    '25' => 'Airport Transfer',
    '27' => 'One-Way',
    '160' => 'Round-Trip',
    '26' => 'Multi-way',
    '28' => 'Taxi-Packages',
);

if (isset($service_map[$service_id])) {
    $service_name = $service_map[$service_id];
}
?>

<div class="main-contant">
    <div class="container">
        <!-- Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">
                        <?php echo esc_html($service_name); ?> <span class="text-color">Booking</span>
                    </h1>
                    <p>Book your cab for a comfortable and hassle-free journey</p>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="row" style="margin-top: 30px;">
            <div class="col-md-8 col-md-offset-2">
                <div class="booking-form-container"
                    style="background: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 5px;">
                    <form action="#" method="POST" id="cab-booking-form">
                        <input type="hidden" name="service_type" value="<?php echo esc_attr($service_name); ?>">

                        <!-- Personal Details -->
                        <h3>Personal Details</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name *</label>
                                    <input type="text" name="customer_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" name="customer_email" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone *</label>
                                    <input type="tel" name="customer_phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Number of Passengers *</label>
                                    <input type="number" name="passengers" class="form-control" min="1" max="20"
                                        required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Trip Details -->
                        <h3>Trip Details</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pickup Location *</label>
                                    <select name="pickup_location" class="form-control selectpicker"
                                        data-live-search="true" required>
                                        <option value="">Select Pickup City</option>
                                        <?php echo get_location_options(); ?>
                                    </select>
                                </div>
                            </div>

                            <?php if ($service_id !== '24'):  // Not Local Trip ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Drop Location *</label>
                                        <select name="drop_location" class="form-control selectpicker"
                                            data-live-search="true" required>
                                            <option value="">Select Drop City</option>
                                            <?php echo get_location_options(); ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pickup Date *</label>
                                    <input type="date" name="pickup_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pickup Time *</label>
                                    <input type="time" name="pickup_time" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <?php if ($service_id === '160'):  // Round Trip ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Return Date *</label>
                                        <input type="date" name="return_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Return Time *</label>
                                        <input type="time" name="return_time" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($service_id === '24'):  // Local Trip ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Duration (Hours) *</label>
                                        <select name="duration" class="form-control" required>
                                            <option value="">Select Duration</option>
                                            <option value="4">4 Hours</option>
                                            <option value="8">8 Hours</option>
                                            <option value="12">12 Hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr>

                        <!-- Vehicle Selection -->
                        <h3>Vehicle Selection</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Select Car Type *</label>
                                    <select name="car_type" class="form-control selectpicker" required>
                                        <option value="">Choose Vehicle</option>
                                        <?php
                                        $car_args = array('post_type' => 'car_type', 'posts_per_page' => -1);
                                        $cars = get_posts($car_args);
                                        foreach ($cars as $car) {
                                            $capacity = get_post_meta($car->ID, 'capacity', true);
                                            echo '<option value="' . esc_attr($car->post_title) . '">' . esc_html($car->post_title) . ' (' . esc_html($capacity) . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Special Requirements / Additional Information</label>
                                    <textarea name="message" class="form-control" rows="4"
                                        placeholder="Any special requests or additional stops..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="df-button1" style="padding: 12px 40px;">Get Quote & Book
                                    Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Why Choose Us Section -->
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-12">
                <h3 class="text-center">Why Choose <span class="text-color">Patra Travels</span></h3>
            </div>
            <div class="col-md-3 col-sm-6 text-center">
                <div class="feature-box" style="padding: 20px;">
                    <i class="fa fa-shield" style="font-size: 48px; color: #f8580e;"></i>
                    <h4>Safe & Secure</h4>
                    <p>Verified drivers and well-maintained vehicles</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 text-center">
                <div class="feature-box" style="padding: 20px;">
                    <i class="fa fa-clock-o" style="font-size: 48px; color: #f8580e;"></i>
                    <h4>24/7 Service</h4>
                    <p>Round the clock customer support</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 text-center">
                <div class="feature-box" style="padding: 20px;">
                    <i class="fa fa-money" style="font-size: 48px; color: #f8580e;"></i>
                    <h4>Best Prices</h4>
                    <p>Competitive rates with no hidden charges</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 text-center">
                <div class="feature-box" style="padding: 20px;">
                    <i class="fa fa-users" style="font-size: 48px; color: #f8580e;"></i>
                    <h4>Professional Drivers</h4>
                    <p>Experienced and courteous chauffeurs</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>