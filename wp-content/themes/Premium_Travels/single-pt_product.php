<?php
/**
 * Template Name: Single Product
 * Template for displaying individual product details
 */

get_header();
?>

<div class="main-contant pt-single-product" style="padding: 40px 0; background: #fdfdfd;">
    <div class="container">
        <?php while (have_posts()):
    the_post(); ?>

            <!-- Package Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading" style="margin-bottom: 25px;">
                        <h1 class="title" style="font-size: 28px; font-weight: 800; color: var(--secondary); margin: 0 0 10px;">
                            <?php the_title(); ?>
                        </h1>
                        <?php $p_city = get_post_meta(get_the_ID(), 'product_location', true); ?>
                        <?php if ($p_city): ?>
                            <div style="font-size: 15px; color: var(--text-light);">
                                <i class="fa fa-map-marker-alt" style="color: var(--primary); margin-right: 5px;"></i> <?php echo esc_html($p_city); ?>
                            </div>
                        <?php
    endif; ?>
                    </div>
                </div>
            </div>

            <!-- Package Content -->
            <div class="row">
                <!-- Main Content -->
                <div class="col-md-8">
                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()): ?>
                        <div class="package-image" style="border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); margin-bottom: 25px;">
                            <?php the_post_thumbnail('large', array('class' => 'img-responsive', 'style' => 'width: 100%; height: auto; display: block;')); ?>
                        </div>
                    <?php
    endif; ?>

                    <!-- Package Description -->
                    <div class="package-description" style="background: #fff; padding: 25px; border-radius: 10px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5;">
                        <h3 style="margin-top: 0; font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 15px;">Product Overview</h3>
                        <div style="color: var(--text-mid); line-height: 1.7;">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <div class="package-sidebar" style="background: #fff; padding: 25px; border-radius: 10px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5; position: sticky; top: 20px;">
                        
                        <!-- Price -->
                        <?php $price = get_post_meta(get_the_ID(), 'price', true); ?>
                        <div class="package-price" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px dashed #eee;">
                            <h4 style="font-size: 14px; color: var(--text-light); margin: 0 0 5px; text-transform: uppercase; letter-spacing: 0.5px;">Starting Price</h4>
                            <div style="font-size: 32px; color: var(--primary); font-weight: 800; line-height: 1;">
                                <i class="fa fa-rupee-sign" style="font-size: 24px;"></i>
                                <?php echo esc_html($price ?: 'Get Quote'); ?>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px;">
                            <a href="#" class="df-button2" style="padding: 14px 20px; text-align: center; font-size: 14px; font-weight: 600; text-transform: uppercase;">
                                <i class="fa fa-shopping-cart" style="margin-right: 6px;"></i> Add To Cart
                            </a>
                            <a href="<?php echo esc_url(home_url('/cab-booking')); ?>" class="df-button1" style="padding: 14px 20px; text-align: center; font-size: 14px; font-weight: 600; text-transform: uppercase;">
                                <i class="fa fa-bolt" style="margin-right: 6px;"></i> Buy Now
                            </a>
                        </div>
                        
                        <div style="text-align: center; font-size: 12px; color: var(--text-light);">
                            Secure transaction. 24/7 Support available.<br>
                            Or Call: <strong style="color: var(--text-dark);">1800 120 8464</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <?php
    $related_args = array(
        'post_type' => 'pt_product',
        'posts_per_page' => 4,
        'post__not_in' => array(get_the_ID()),
    );
    $related_query = new WP_Query($related_args);
?>
            <?php if ($related_query->have_posts()): ?>
                <div class="row" style="margin-top: 60px;">
                    <div class="col-md-12">
                        <h3 style="font-size: 22px; font-weight: 700; color: var(--secondary); margin-bottom: 25px; border-bottom: 2px solid #eee; padding-bottom: 10px; display: inline-block;">You May Also Like</h3>
                    </div>
                    <?php while ($related_query->have_posts()):
            $related_query->the_post();
            $rel_price = get_post_meta(get_the_ID(), 'price', true) ?: 'Contact';
?>
                        <div class="col-md-3">
                            <?php 
                            // Get product slug for URL
                            $related_product_slug = $wpdb->get_var($wpdb->prepare(
                                "SELECT slug FROM {$wpdb->prefix}pt_products WHERE title = %s",
                                get_the_title()
                            ));
                            $related_url = $related_product_slug ? home_url('/product/' . $related_product_slug) : get_permalink();
                            ?>
                            <a href="<?php echo esc_url($related_url); ?>" style="display: block; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid #f1f1f1; text-decoration: none; color: inherit; transition: var(--transition);">
                                <div style="padding: 15px;">
                                    <h4 style="font-size: 14px; font-weight: 700; color: var(--secondary); margin: 0 0 8px; line-height: 1.3;"><?php the_title(); ?></h4>
                                    <div style="font-size: 16px; font-weight: 800; color: var(--primary);"><i class="fa fa-rupee-sign"></i> <?php echo esc_html($rel_price); ?></div>
                                </div>
                            </a>
                        </div>
                    <?php
        endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            <?php
    endif; ?>

        <?php
endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
