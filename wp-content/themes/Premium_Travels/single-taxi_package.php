<?php
/**
 * Template Name: Single Taxi Package
 * Template for displaying individual taxi package details
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <?php while (have_posts()):
            the_post(); ?>

            <!-- Package Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h1 class="title">
                            <?php the_title(); ?>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Package Content -->
            <div class="row">
                <!-- Main Content -->
                <div class="col-md-8">
                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()): ?>
                        <div class="package-image">
                            <?php the_post_thumbnail('large', array('class' => 'img-responsive')); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Package Description -->
                    <div class="package-description" style="margin-top: 20px;">
                        <h3>Package Details</h3>
                        <?php the_content(); ?>
                    </div>

                    <!-- Itinerary (if available) -->
                    <?php $itinerary = get_post_meta(get_the_ID(), 'itinerary', true); ?>
                    <?php if ($itinerary): ?>
                        <div class="package-itinerary" style="margin-top: 30px;">
                            <h3>Itinerary</h3>
                            <?php echo wpautop($itinerary); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Inclusions -->
                    <?php $inclusions = get_post_meta(get_the_ID(), 'inclusions', true); ?>
                    <?php if ($inclusions): ?>
                        <div class="package-inclusions" style="margin-top: 30px;">
                            <h3>Inclusions</h3>
                            <?php echo wpautop($inclusions); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Exclusions -->
                    <?php $exclusions = get_post_meta(get_the_ID(), 'exclusions', true); ?>
                    <?php if ($exclusions): ?>
                        <div class="package-exclusions" style="margin-top: 30px;">
                            <h3>Exclusions</h3>
                            <?php echo wpautop($exclusions); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <div class="package-sidebar" style="background: #f5f5f5; padding: 20px; border-radius: 5px;">
                        <!-- Price -->
                        <?php $price = get_post_meta(get_the_ID(), 'price', true); ?>
                        <?php if ($price): ?>
                            <div class="package-price" style="margin-bottom: 20px;">
                                <h4>Starting From</h4>
                                <div style="font-size: 28px; color: #f8580e; font-weight: bold;">
                                    <i class="fa fa-rupee"></i>
                                    <?php echo esc_html($price); ?>
                                </div>
                                <?php $person_count = get_post_meta(get_the_ID(), 'person_count', true); ?>
                                <?php if ($person_count): ?>
                                    <small>(For
                                        <?php echo esc_html($person_count); ?> Person)
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Pickup Location -->
                        <?php $pickup = get_post_meta(get_the_ID(), 'pickup_location', true); ?>
                        <?php if ($pickup): ?>
                            <div class="package-pickup" style="margin-bottom: 20px;">
                                <h4>Pickup From</h4>
                                <p>
                                    <?php echo esc_html($pickup); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Duration -->
                        <?php $duration = get_post_meta(get_the_ID(), 'duration', true); ?>
                        <?php if ($duration): ?>
                            <div class="package-duration" style="margin-bottom: 20px;">
                                <h4>Duration</h4>
                                <p>
                                    <?php echo esc_html($duration); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Service Type -->
                        <?php $terms = get_the_terms(get_the_ID(), 'service_type'); ?>
                        <?php if ($terms && !is_wp_error($terms)): ?>
                            <div class="package-service-type" style="margin-bottom: 20px;">
                                <h4>Service Type</h4>
                                <p>
                                    <?php echo esc_html($terms[0]->name); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Booking Form -->
                        <div class="package-booking">
                            <h4>Book Now</h4>
                            <form action="#" method="POST" class="booking-form">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" name="phone" class="form-control" placeholder="Phone" required>
                                </div>
                                <div class="form-group">
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="message" class="form-control" rows="3"
                                        placeholder="Special Requirements"></textarea>
                                </div>
                                <button type="submit" class="df-button1 btn-block">Book Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Packages -->
            <?php
            $related_args = array(
                'post_type' => 'taxi_package',
                'posts_per_page' => 4,
                'post__not_in' => array(get_the_ID()),
            );
            $related_query = new WP_Query($related_args);
            ?>
            <?php if ($related_query->have_posts()): ?>
                <div class="row" style="margin-top: 50px;">
                    <div class="col-md-12">
                        <h3>Related Packages</h3>
                    </div>
                    <?php while ($related_query->have_posts()):
                        $related_query->the_post(); ?>
                        <div class="col-md-3">
                            <div class="destination-thumb thumb gap tr-total">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) {
                                        the_post_thumbnail('medium');
                                    } ?>
                                    <div class="destination-text-box">
                                        <?php the_title(); ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>