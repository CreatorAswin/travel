<?php
/**
 * Template Name: Testimonials Archive
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">Customer <span class="text-color">Testimonials</span></h1>
                    <p>Read what our happy customers have to say about us</p>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'testimonial',
                'posts_per_page' => 12,
                'paged' => $paged,
                'orderby' => 'date',
                'order' => 'DESC',
            );
            $testimonials_query = new WP_Query($args);

            if ($testimonials_query->have_posts()) :
                while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
                    $rating = get_post_meta(get_the_ID(), 'rating', true) ?: 5;
                    $journey_date = get_post_meta(get_the_ID(), 'journey_date', true);
            ?>
                    <div class="col-md-6">
                        <div class="testimonial-box" style="background: #fff; border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
                            <div class="testimonial-header" style="display: flex; align-items: center; margin-bottom: 15px;">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="client-image" style="margin-right: 15px;">
                                        <?php the_post_thumbnail('thumbnail', array('style' => 'width: 60px; height: 60px; border-radius: 50%; object-fit: cover;')); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="client-image" style="margin-right: 15px;">
                                        <img src="https://www.patratravels.com/images/user.png" alt="User" style="width: 60px; height: 60px; border-radius: 50%;">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="client-info">
                                    <h4 style="margin: 0;"><?php the_title(); ?></h4>
                                    <?php if ($journey_date) : ?>
                                        <small style="color: #666;">Traveled on: <?php echo date('d M Y', strtotime($journey_date)); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="rating" style="margin-bottom: 10px; color: #f8580e;">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <i class="fa fa-star<?php echo ($i <= $rating) ? '' : '-o'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            
                            <div class="testimonial-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;
            else :
                echo '<div class="col-md-12"><p class="text-center">No testimonials yet.</p></div>';
            endif;
            ?>
        </div>

        <!-- Pagination -->
        <?php if ($testimonials_query->max_num_pages > 1) : ?>
            <div class="row">
                <div class="col-md-12 text-center" style="margin-top: 30px;">
                    <?php
                    echo paginate_links(array(
                        'total' => $testimonials_query->max_num_pages,
                        'current' => $paged,
                    ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>

        <!-- Leave a Review Section -->
        <div class="row" style="margin-top: 50px; background: #f5f5f5; padding: 30px; border-radius: 5px;">
            <div class="col-md-12 text-center">
                <h3>Share Your Experience</h3>
                <p>We'd love to hear about your journey with us!</p>
                <a href="https://patratravels.com/online/testimonial.php" class="df-button1">Write a Review</a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
