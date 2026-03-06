<?php
/**
 * Template Name: Archive Holiday Packages
 * Archive template for holiday packages
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <!-- Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">Holiday <span class="text-color">Packages</span></h1>
                    <p>Explore our curated tour packages for unforgettable experiences</p>
                </div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="row" style="margin-top: 30px;">
            <div class="col-md-12">
                <ul class="nav nav-tabs text-center" style="border-bottom: 2px solid #f8580e;">
                    <li class="<?php echo (!isset($_GET['category']) || $_GET['category'] == '') ? 'active' : ''; ?>">
                        <a href="<?php echo get_post_type_archive_link('holiday_package'); ?>">All Packages</a>
                    </li>
                    <?php
                    $categories = get_terms(array('taxonomy' => 'package_category', 'hide_empty' => false));
                    foreach ($categories as $cat) {
                        $active = (isset($_GET['category']) && $_GET['category'] == $cat->slug) ? 'active' : '';
                        echo '<li class="' . $active . '"><a href="?category=' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Packages Grid -->
        <div class="row" style="margin-top: 30px;">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'holiday_package',
                'posts_per_page' => 12,
                'paged' => $paged,
            );

            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'package_category',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['category']),
                    ),
                );
            }

            $package_query = new WP_Query($args);

            if ($package_query->have_posts()):
                while ($package_query->have_posts()):
                    $package_query->the_post();
                    $price = get_post_meta(get_the_ID(), 'price', true);
                    $duration = get_post_meta(get_the_ID(), 'duration', true);
                    ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="destination-thumb thumb gap tr-total">
                            <div class="zoomeffects">
                                <div class="zoomEffect_1 new-effect">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) {
                                            the_post_thumbnail('medium');
                                        } else {
                                            echo '<img src="https://www.patratravels.com/admin/image/tourimage/tourpkgimage_51.jpg" alt="Default" />';
                                        } ?>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-12" style="padding:0;">
                                <div class="destination-text-box pkg-height">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </div>
                                <?php if ($duration): ?>
                                    <div class="destination-text"><small>Duration</small></div>
                                    <div class="destination-text1">
                                        <?php echo esc_html($duration); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($price): ?>
                                    <div class="destination-text"><small>Starting From</small></div>
                                    <div class="destination-text1"><i class="fa fa-rupee"></i>
                                        <?php echo esc_html($price); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="booknow bkus-hi8">
                                <a href="<?php the_permalink(); ?>" class="df-button2">View Details</a>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
            else:
                echo '<div class="col-md-12"><p class="text-center">No packages found.</p></div>';
            endif;
            ?>
        </div>

        <!-- Pagination -->
        <?php if ($package_query->max_num_pages > 1): ?>
            <div class="row">
                <div class="col-md-12 text-center" style="margin-top: 30px;">
                    <?php
                    echo paginate_links(array(
                        'total' => $package_query->max_num_pages,
                        'current' => $paged,
                    ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</div>

<?php get_footer(); ?>