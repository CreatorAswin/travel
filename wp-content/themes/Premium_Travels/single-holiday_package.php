<?php
/**
 * Template Name: Single Holiday Package
 * Single template for holiday packages
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <?php while (have_posts()):
            the_post(); ?>

            <div class="row">
                <div class="col-md-8">
                    <h1>
                        <?php the_title(); ?>
                    </h1>

                    <?php if (has_post_thumbnail()): ?>
                        <div class="package-image" style="margin: 20px 0;">
                            <?php the_post_thumbnail('large', array('class' => 'img-responsive')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="package-content">
                        <?php the_content(); ?>
                    </div>

                    <?php $itinerary = get_post_meta(get_the_ID(), 'itinerary', true); ?>
                    <?php if ($itinerary): ?>
                        <div class="itinerary-section" style="margin-top: 30px;">
                            <h3>Day-wise Itinerary</h3>
                            <?php echo wpautop($itinerary); ?>
                        </div>
                    <?php endif; ?>

                    <?php $inclusions = get_post_meta(get_the_ID(), 'inclusions', true); ?>
                    <?php if ($inclusions): ?>
                        <div class="inclusions-section" style="margin-top: 30px;">
                            <h3>Inclusions</h3>
                            <?php echo wpautop($inclusions); ?>
                        </div>
                    <?php endif; ?>

                    <?php $exclusions = get_post_meta(get_the_ID(), 'exclusions', true); ?>
                    <?php if ($exclusions): ?>
                        <div class="exclusions-section" style="margin-top: 30px;">
                            <h3>Exclusions</h3>
                            <?php echo wpautop($exclusions); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <div class="package-sidebar"
                        style="background: #f5f5f5; padding: 20px; border-radius: 5px; position: sticky; top: 20px;">
                        <?php $price = get_post_meta(get_the_ID(), 'price', true); ?>
                        <?php if ($price): ?>
                            <div class="price-box" style="margin-bottom: 20px;">
                                <h4>Package Price</h4>
                                <div style="font-size: 28px; color: #f8580e; font-weight: bold;">
                                    <i class="fa fa-rupee"></i>
                                    <?php echo esc_html($price); ?>
                                </div>
                                <small>Per Person</small>
                            </div>
                        <?php endif; ?>

                        <?php $duration = get_post_meta(get_the_ID(), 'duration', true); ?>
                        <?php if ($duration): ?>
                            <div style="margin-bottom: 20px;">
                                <h4>Duration</h4>
                                <p>
                                    <?php echo esc_html($duration); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="booking-form">
                            <h4>Enquire Now</h4>
                            <form action="#" method="POST">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" name="phone" class="form-control" placeholder="Phone" required>
                                </div>
                                <div class="form-group">
                                    <input type="date" name="travel_date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <input type="number" name="travelers" class="form-control"
                                        placeholder="No. of Travelers" min="1" required>
                                </div>
                                <button type="submit" class="df-button1 btn-block">Submit Enquiry</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>