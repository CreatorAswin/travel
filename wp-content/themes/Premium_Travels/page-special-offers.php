<?php
/**
 * Template Name: Special Offers
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">Special <span class="text-color">Offers</span></h1>
                    <p>Grab the best deals and save on your travel bookings</p>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <?php
            $args = array(
                'post_type' => 'special_offer',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'valid_till',
                        'value' => date('Y-m-d'),
                        'compare' => '>=',
                        'type' => 'DATE',
                    ),
                ),
            );
            $offers_query = new WP_Query($args);

            if ($offers_query->have_posts()) :
                while ($offers_query->have_posts()) : $offers_query->the_post();
                    $discount = get_post_meta(get_the_ID(), 'discount_percentage', true);
                    $valid_till = get_post_meta(get_the_ID(), 'valid_till', true);
                    $terms = get_post_meta(get_the_ID(), 'terms', true);
            ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="offer-box" style="background: #fff; border: 2px solid #f8580e; padding: 20px; margin-bottom: 20px; border-radius: 10px; position: relative;">
                            <?php if ($discount) : ?>
                                <div class="discount-badge" style="position: absolute; top: -10px; right: -10px; background: #f8580e; color: #fff; padding: 10px 15px; border-radius: 50%; font-size: 20px; font-weight: bold;">
                                    <?php echo esc_html($discount); ?>% OFF
                                </div>
                            <?php endif; ?>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="offer-image" style="margin-bottom: 15px;">
                                    <?php the_post_thumbnail('medium', array('class' => 'img-responsive')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3><?php the_title(); ?></h3>
                            <div class="offer-content">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php if ($valid_till) : ?>
                                <p style="margin-top: 15px;"><strong>Valid Till:</strong> <?php echo date('d M Y', strtotime($valid_till)); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($terms) : ?>
                                <div class="offer-terms" style="margin-top: 15px; font-size: 12px; color: #666;">
                                    <strong>Terms & Conditions:</strong>
                                    <?php echo wpautop($terms); ?>
                                </div>
                            <?php endif; ?>
                            
                            <a href="#" class="df-button1 btn-block" style="margin-top: 15px;">Book Now</a>
                        </div>
                    </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<div class="col-md-12"><p class="text-center">No active offers at the moment. Please check back later!</p></div>';
            endif;
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
