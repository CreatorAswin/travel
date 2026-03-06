<?php
/**
 * Template Name: Car Rental City
 * Template for city-specific car rental pages
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading text-center">
                        <h1 class="title"><?php the_title(); ?></h1>
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>

            <!-- Available Cars Section -->
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12">
                    <h2 class="text-center">Available <span class="text-color">Vehicles</span></h2>
                </div>
            </div>

            <div class="row" style="margin-top: 30px;">
                <?php
                // Get all car types
                $car_args = array(
                    'post_type' => 'car_type',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                );
                $car_query = new WP_Query($car_args);

                if ($car_query->have_posts()) :
                    while ($car_query->have_posts()) : $car_query->the_post();
                        $capacity = get_post_meta(get_the_ID(), 'capacity', true);
                        $base_price = get_post_meta(get_the_ID(), 'base_price', true);
                        $features = get_post_meta(get_the_ID(), 'features', true);
                ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="car-type-box" style="background: #fff; border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="car-image" style="text-align: center; margin-bottom: 15px;">
                                        <?php the_post_thumbnail('medium', array('class' => 'img-responsive', 'style' => 'max-height: 200px;')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <h4><?php the_title(); ?></h4>
                                
                                <?php if ($capacity) : ?>
                                    <p><strong>Capacity:</strong> <?php echo esc_html($capacity); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($base_price) : ?>
                                    <p><strong>Starting From:</strong> <span style="color: #f8580e; font-size: 18px;"><i class="fa fa-rupee"></i><?php echo esc_html($base_price); ?></span></p>
                                <?php endif; ?>
                                
                                <?php if ($features) : ?>
                                    <div class="car-features">
                                        <?php echo wpautop($features); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="#booking-form" class="df-button1 btn-block">Book Now</a>
                            </div>
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="col-md-12"><p class="text-center">No vehicles available at the moment.</p></div>';
                endif;
                ?>
            </div>

            <!-- Booking Form Section -->
            <div class="row" style="margin-top: 50px;" id="booking-form">
                <div class="col-md-12">
                    <div class="section-heading text-center">
                        <h2>Book Your <span class="text-color">Ride</span></h2>
                    </div>
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <div style="background: #f5f5f5; padding: 30px; border-radius: 5px;">
                        <form action="#" method="POST" class="booking-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name *</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone *</label>
                                        <input type="tel" name="phone" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Car Type *</label>
                                        <select name="car_type" class="form-control selectpicker" required>
                                            <option value="">Select Car Type</option>
                                            <?php
                                            $car_query->rewind_posts();
                                            while ($car_query->have_posts()) : $car_query->the_post();
                                                echo '<option value="' . esc_attr(get_the_title()) . '">' . esc_html(get_the_title()) . '</option>';
                                            endwhile;
                                            wp_reset_postdata();
                                            ?>
                                        </select>
                                    </div>
                                </div>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Special Requirements</label>
                                        <textarea name="message" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="df-button1">Submit Booking Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
