<?php
/**
 * Template Name: Archive Taxi Packages
 * Template for displaying all taxi packages
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <!-- Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">All <span class="text-color">Taxi Packages</span></h1>
                    <p>Explore our comprehensive range of taxi packages for your travel needs</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row" style="margin-bottom: 30px;">
            <div class="col-md-12">
                <div class="package-filters" style="background: #f5f5f5; padding: 20px; border-radius: 5px;">
                    <form method="GET" action="" class="filter-form">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="service_type" class="form-control selectpicker" data-live-search="true">
                                    <option value="">All Service Types</option>
                                    <?php
                                    $service_types = get_terms(array('taxonomy' => 'service_type', 'hide_empty' => false));
                                    foreach ($service_types as $term) {
                                        $selected = (isset($_GET['service_type']) && $_GET['service_type'] == $term->slug) ? 'selected' : '';
                                        echo '<option value="' . esc_attr($term->slug) . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="pickup_city" class="form-control selectpicker" data-live-search="true">
                                    <option value="">All Pickup Cities</option>
                                    <?php echo get_location_options(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="orderby" class="form-control">
                                    <option value="">Sort By</option>
                                    <option value="price_low" <?php echo (isset($_GET['orderby']) && $_GET['orderby'] == 'price_low') ? 'selected' : ''; ?>>Price: Low to High
                                    </option>
                                    <option value="price_high" <?php echo (isset($_GET['orderby']) && $_GET['orderby'] == 'price_high') ? 'selected' : ''; ?>>Price: High to Low
                                    </option>
                                    <option value="title" <?php echo (isset($_GET['orderby']) && $_GET['orderby'] == 'title') ? 'selected' : ''; ?>>Name: A-Z</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="df-button1 btn-block">Apply Filters</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Packages Grid -->
        <div class="row">
            <?php
            // Build query args
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'taxi_package',
                'posts_per_page' => 12,
                'paged' => $paged,
            );

            // Filter by service type
            if (isset($_GET['service_type']) && !empty($_GET['service_type'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'service_type',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['service_type']),
                    ),
                );
            }

            // Filter by pickup city
            if (isset($_GET['pickup_city']) && !empty($_GET['pickup_city'])) {
                $args['meta_query'] = array(
                    array(
                        'key' => 'pickup_location',
                        'value' => sanitize_text_field($_GET['pickup_city']),
                        'compare' => 'LIKE',
                    ),
                );
            }

            // Sort by price or title
            if (isset($_GET['orderby']) && !empty($_GET['orderby'])) {
                switch ($_GET['orderby']) {
                    case 'price_low':
                        $args['meta_key'] = 'price';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'ASC';
                        break;
                    case 'price_high':
                        $args['meta_key'] = 'price';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'DESC';
                        break;
                    case 'title':
                        $args['orderby'] = 'title';
                        $args['order'] = 'ASC';
                        break;
                }
            }

            $package_query = new WP_Query($args);

            if ($package_query->have_posts()):
                while ($package_query->have_posts()):
                    $package_query->the_post();
                    $price = get_post_meta(get_the_ID(), 'price', true) ?: 'Contact for Price';
                    $pickup_loc = get_post_meta(get_the_ID(), 'pickup_location', true) ?: 'Bhubaneswar, Odisha';
                    $person_count = get_post_meta(get_the_ID(), 'person_count', true) ?: '4';
                    ?>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="destination-thumb thumb gap tr-total">
                            <div class="zoomeffects">
                                <div class="zoomEffect_1 new-effect">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) {
                                            the_post_thumbnail('medium', array('alt' => get_the_title()));
                                        } else {
                                            echo '<img src="https://www.patratravels.com/admin/image/tourimage/tourpkgimage_51.jpg" alt="Default Image" />';
                                        } ?>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0;">
                                <div class="destination-text-box pkg-height">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                                <div class="destination-text"><small>Starting From</small></div>
                                <div class="destination-text1"><i class="fa fa-rupee"></i>
                                    <?php echo esc_html($price); ?>
                                    <div class="desti-small">(For
                                        <?php echo esc_html($person_count); ?> Person)
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="destination-text"><small>Pickup From</small></div>
                                <div class="destination-text1">
                                    <?php echo esc_html($pickup_loc); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="booknow bkus-hi8">
                                <a href="<?php the_permalink(); ?>" class="df-button2">View Details</a>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
            else:
                echo '<div class="col-md-12"><p class="text-center">No packages found. Please try different filters.</p></div>';
            endif;
            ?>
        </div>

        <!-- Pagination -->
        <?php if ($package_query->max_num_pages > 1): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="pagination-wrapper text-center" style="margin-top: 30px;">
                        <?php
                        echo paginate_links(array(
                            'total' => $package_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => '&laquo; Previous',
                            'next_text' => 'Next &raquo;',
                        ));
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</div>

<?php get_footer(); ?>