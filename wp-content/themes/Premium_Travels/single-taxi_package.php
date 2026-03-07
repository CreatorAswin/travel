<?php
/**
 * Template Name: Single Taxi Package
 * Template for displaying individual taxi package details
 */

get_header();
?>

<div class="main-contant pt-taxi-detail" style="padding: 40px 0; background: #fdfdfd;">
    <div class="container">
        <?php while (have_posts()):
    the_post();
    $price = get_post_meta(get_the_ID(), 'price', true);
    $pickup = get_post_meta(get_the_ID(), 'pickup_location', true);
    $duration = get_post_meta(get_the_ID(), 'duration', true);
    $dist = get_post_meta(get_the_ID(), 'distance_km', true);
    $inclusions = get_post_meta(get_the_ID(), 'inclusions', true);
    $exclusions = get_post_meta(get_the_ID(), 'exclusions', true);
    $itinerary = get_post_meta(get_the_ID(), 'itinerary', true);
    $gallery_ids = get_post_meta(get_the_ID(), 'gallery_images', true);
?>

            <!-- Modern Breadcrumb -->
            <nav aria-label="breadcrumb" style="margin-bottom: 30px;">
                <ol style="background: #fff; padding: 15px 25px; margin: 0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e8eef5; display: flex; align-items: center; gap: 5px; list-style: none; flex-wrap: wrap;">
                    <li style="display: flex; align-items: center;">
                        <a href="<?php echo home_url(); ?>" style="color: var(--primary); text-decoration: none; font-weight: 500; padding: 8px 12px; border-radius: 6px; transition: all 0.3s;">
                            <i class="fa fa-home" style="margin-right: 8px;"></i>Home
                            <span style="color: #ccc; margin: 0 4px; font-size: 16px;">›</span>
                        <a href="<?php echo home_url('/taxi_package'); ?>" style="color: var(--text-dark); text-decoration: none; font-weight: 500; padding: 8px 12px; border-radius: 6px; transition: all 0.3s;">Taxi Packages</a>
                        </a>
                        <span style="color: #ccc; margin: 0 4px; font-size: 16px;">›</span>
                        <span style="color: var(--text-mid); font-weight: 600; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                            <?php the_title(); ?>
                        </span>
                    </li>
                </ol>
            </nav>

            <div class="row">
                <!-- Main Content Left -->
                <div class="col-md-8">
                    <!-- Title Section -->
                    <div style="margin-bottom: 25px;">
                        <h1 style="font-size: 32px; font-weight: 800; color: var(--secondary); margin: 0 0 12px; line-height: 1.2;">
                            <?php the_title(); ?>
                        </h1>
                        <div style="display: flex; gap: 20px; flex-wrap: wrap; color: var(--text-light); font-size: 14px;">
                            <?php if ($pickup): ?>
                                <span><i class="fa fa-map-marker-alt" style="color: var(--primary); margin-right: 6px;"></i> Pickup: <strong><?php echo esc_html($pickup); ?></strong></span>
                            <?php
    endif; ?>
                            <?php if ($duration): ?>
                                <span><i class="fa fa-clock" style="color: var(--primary); margin-right: 6px;"></i> Duration: <strong><?php echo esc_html($duration); ?></strong></span>
                            <?php
    endif; ?>
                            <?php if ($dist): ?>
                                <span><i class="fa fa-route" style="color: var(--primary); margin-right: 6px;"></i> Distance: <strong><?php echo esc_html($dist); ?> km</strong></span>
                            <?php
    endif; ?>
                        </div>
                    </div>

                    <!-- Image Gallery -->
                    <div class="package-gallery" style="margin-bottom: 35px;">
                        <div class="main-image" style="border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-md); margin-bottom: 15px; border: 1px solid #e8eef5; position: relative;">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('large', array('class' => 'img-responsive', 'style' => 'width: 100%; height: 450px; object-fit: cover; display: block;', 'id' => 'pt-main-view')); ?>
                            <?php
    else: ?>
                                <div style="height: 450px; background: linear-gradient(135deg, #eee 0%, #f9f9f9 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-image" style="font-size: 80px; color: #ccc;"></i>
                                </div>
                            <?php
    endif; ?>
                        </div>

                        <?php if ($gallery_ids): ?>
                            <div class="thumbnails" style="display: flex; gap: 10px; overflow-x: auto; padding: 5px 0;">
                                <?php if (has_post_thumbnail()): ?>
                                    <div class="thumb-item active" style="width: 100px; height: 75px; flex-shrink: 0; border-radius: 8px; overflow: hidden; cursor: pointer; border: 2px solid var(--primary); transition: all 0.3s;">
                                        <?php $full_url = get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" data-full="<?php echo esc_url($full_url); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                <?php
        endif; ?>
                                <?php
        $ids = explode(',', $gallery_ids);
        foreach ($ids as $g_id):
            $g_id = intval(trim($g_id));
            $thumb = wp_get_attachment_image_url($g_id, 'thumbnail');
            $full = wp_get_attachment_image_url($g_id, 'large');
            if ($thumb):
?>
                                        <div class="thumb-item" style="width: 100px; height: 75px; flex-shrink: 0; border-radius: 8px; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.3s; opacity: 0.7;">
                                            <img src="<?php echo esc_url($thumb); ?>" data-full="<?php echo esc_url($full); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                <?php
            endif;
        endforeach;
?>
                            </div>
                            <script>
                                jQuery(document).ready(function($) {
                                    $('.thumb-item').click(function() {
                                        var fullUrl = $(this).find('img').data('full');
                                        $('#pt-main-view').attr('src', fullUrl);
                                        $('.thumb-item').css({
                                            'border-color': 'transparent',
                                            'opacity': '0.7'
                                        });
                                        $(this).css({
                                            'border-color': 'var(--primary)',
                                            'opacity': '1'
                                        });
                                    });
                                });
                            </script>
                        <?php
    endif; ?>
                    </div>

                    <!-- Description & Content -->
                    <div style="background: #fff; padding: 35px; border-radius: 16px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5; margin-bottom: 30px;">
                        <h3 style="margin-top: 0; font-size: 22px; font-weight: 700; color: var(--secondary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <i class="fa fa-file-alt" style="color: var(--primary);"></i> Description
                        </h3>
                        <div class="package-content" style="color: var(--text-mid); line-height: 1.8; font-size: 15px;">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Itinerary Section -->
                    <?php if ($itinerary): ?>
                        <div style="background: #fff; padding: 35px; border-radius: 16px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5; margin-bottom: 30px;">
                            <h3 style="margin-top: 0; font-size: 22px; font-weight: 700; color: var(--secondary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-map-signs" style="color: var(--primary);"></i> Tour Itinerary
                            </h3>
                            <div style="color: var(--text-mid); line-height: 1.8; font-size: 15px; border-left: 3px solid #f0f4f8; padding-left: 20px;">
                                <?php echo wpautop(esc_html($itinerary)); ?>
                            </div>
                        </div>
                    <?php
    endif; ?>

                    <!-- Inclusions & Exclusions -->
                    <div class="row">
                        <div class="col-md-6">
                            <div style="background: #f0fff4; padding: 25px; border-radius: 12px; border: 1px solid #c6f6d5; height: 100%;">
                                <h4 style="margin-top: 0; font-size: 18px; font-weight: 700; color: #234e33; margin-bottom: 15px;">
                                    <i class="fa fa-check-circle" style="color: #38a169; margin-right: 8px;"></i> Inclusions
                                </h4>
                                <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px; color: #2f855a; line-height: 1.8;">
                                    <?php
    $inc_list = explode("\n", $inclusions);
    foreach ($inc_list as $item) {
        if (trim($item))
            echo '<li style="margin-bottom: 8px; position:relative; padding-left: 20px;"><i class="fa fa-arrow-right" style="position:absolute; left:0; top:6px; font-size:10px; opacity:0.6;"></i> ' . esc_html($item) . '</li>';
    }
?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="background: #fff5f5; padding: 25px; border-radius: 12px; border: 1px solid #fed7d7; height: 100%;">
                                <h4 style="margin-top: 0; font-size: 18px; font-weight: 700; color: #742a2a; margin-bottom: 15px;">
                                    <i class="fa fa-times-circle" style="color: #e53e3e; margin-right: 8px;"></i> Exclusions
                                </h4>
                                <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px; color: #9b2c2c; line-height: 1.8;">
                                    <?php
    $exc_list = explode("\n", $exclusions);
    foreach ($exc_list as $item) {
        if (trim($item))
            echo '<li style="margin-bottom: 8px; position:relative; padding-left: 20px;"><i class="fa fa-minus" style="position:absolute; left:0; top:8px; font-size:10px; opacity:0.6;"></i> ' . esc_html($item) . '</li>';
    }
?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Right -->
                <div class="col-md-4">
                    <div style="background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e8eef5; overflow: hidden; position: sticky; top: 20px;">
                        <!-- Price Header -->
                        <div style="background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-lt) 100%); padding: 30px 25px; text-align: center;">
                            <span style="color: rgba(255,255,255,0.7); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;">Package Price Starting From</span>
                            <div style="color: #fff; font-size: 38px; font-weight: 800; line-height: 1;">
                                <i class="fa fa-rupee-sign" style="font-size: 24px; vertical-align: super;"></i> <?php echo esc_html($price ?: 'Get Quote'); ?>
                            </div>
                            <span style="color: rgba(255,255,255,0.6); font-size: 11px; margin-top: 5px; display: block;">*Taxes & charges may apply</span>
                        </div>

                        <div style="padding: 25px;">
                            <!-- Info Badges -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 25px;">
                                <?php if ($duration): ?>
                                    <div style="background: #f8f9fa; padding: 12px; border-radius: 10px; text-align: center; border: 1px solid #eee;">
                                        <i class="fa fa-clock" style="color: var(--primary); display: block; font-size: 18px; margin-bottom: 5px;"></i>
                                        <div style="font-size: 11px; color: #999; text-transform: uppercase;">Duration</div>
                                        <div style="font-size: 13px; font-weight: 700; color: var(--secondary);"><?php echo esc_html($duration); ?></div>
                                    </div>
                                <?php
    endif; ?>
                                <?php if ($pickup): ?>
                                    <div style="background: #f8f9fa; padding: 12px; border-radius: 10px; text-align: center; border: 1px solid #eee;">
                                        <i class="fa fa-map-marker-alt" style="color: var(--primary); display: block; font-size: 18px; margin-bottom: 5px;"></i>
                                        <div style="font-size: 11px; color: #999; text-transform: uppercase;">Location</div>
                                        <div style="font-size: 13px; font-weight: 700; color: var(--secondary);"><?php echo esc_html($pickup); ?></div>
                                    </div>
                                <?php
    endif; ?>
                            </div>

                            <!-- Booking Form Title -->
                            <h4 style="font-size: 16px; font-weight: 700; color: var(--secondary); margin-bottom: 20px; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 12px;">Quick Inquiry</h4>

                            <!-- The Form -->
                            <form action="#" method="post" class="sidebar-booking-form">
                                <div style="margin-bottom: 12px;">
                                    <input type="text" placeholder="Full Name *" required style="width: 100%; padding: 12px 15px; border-radius: 8px; border: 1.5px solid #dde3ef; background: #fafbfd; font-size: 13px;">
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <input type="tel" placeholder="Phone Number *" required style="width: 100%; padding: 12px 15px; border-radius: 8px; border: 1.5px solid #dde3ef; background: #fafbfd; font-size: 13px;">
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <select style="width: 100%; padding: 12px 15px; border-radius: 8px; border: 1.5px solid #dde3ef; background: #fafbfd; font-size: 13px;">
                                        <option value="">Vehicle Type</option>
                                        <option>Sedan (Swift Dzire)</option>
                                        <option>SUV (Innova)</option>
                                        <option>Tempo Traveller</option>
                                    </select>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <input type="date" placeholder="Travel Date" style="width: 100%; padding: 12px 15px; border-radius: 8px; border: 1.5px solid #dde3ef; background: #fafbfd; font-size: 13px;">
                                </div>
                                <button type="submit" class="df-button1">
                                    Send Booking Request <i class="fa fa-arrow-right" style="margin-left: 8px;"></i>
                                </button>
                            </form>

                            <div style="margin-top: 25px; text-align: center; font-size: 12px; color: #999;">
                                <p style="margin-bottom: 5px;"><i class="fa fa-shield-alt" style="color:#38a169;"></i> Best Price Guaranteed</p>
                                <p>Call Us: <strong style="color: var(--secondary);">1800 120 8464</strong></p>
                            </div>
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
                <div class="row" style="margin-top: 60px;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; border-bottom: 2px solid #f0f4f8; padding-bottom: 12px;">
                            <h3 style="font-size: 24px; font-weight: 800; color: var(--secondary); margin: 0;">Discover More Packages</h3>
                            <a href="<?php echo home_url('/taxi-packages'); ?>" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 14px;">View All →</a>
                        </div>
                    </div>
                    <?php while ($related_query->have_posts()):
            $related_query->the_post();
            $rel_price = get_post_meta(get_the_ID(), 'price', true);
            $rel_pickup = get_post_meta(get_the_ID(), 'pickup_location', true);
?>
                        <div class="col-md-3">
                            <div class="destination-thumb tr-total" style="box-shadow: var(--shadow-sm); border: 1px solid #f1f1f1; border-radius: 12px; overflow: hidden; height: 100%; display: flex; flex-direction: column;">
                                <div class="zoomeffects" style="height: 180px; overflow: hidden; position: relative;">
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('style' => 'width: 100%; height: 100%; object-fit: cover;')); ?>
                                        </a>
                                    <?php
            else: ?>
                                        <div style="height: 100%; background: #eee; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa fa-image" style="font-size: 40px; color: #ccc;"></i>
                                        </div>
                                    <?php
            endif; ?>
                                    <div style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.6); color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                        <i class="fa fa-map-marker-alt"></i> <?php echo esc_html($rel_pickup); ?>
                                    </div>
                                </div>
                                <div style="padding: 15px; flex: 1; display: flex; flex-direction: column;">
                                    <h4 style="margin: 0 0 10px; font-size: 15px; font-weight: 700; line-height: 1.4;">
                                        <a href="<?php the_permalink(); ?>" style="color: var(--secondary); text-decoration: none;"><?php the_title(); ?></a>
                                    </h4>
                                    <div style="margin-top: auto;">
                                        <div style="color: var(--primary); font-size: 20px; font-weight: 800; margin-bottom: 12px;">
                                            <i class="fa fa-rupee-sign" style="font-size: 14px;"></i> <?php echo esc_html($rel_price ?: 'Contact'); ?>
                                        </div>
                                        <div class="book-buttons">
                                            <a href="<?php the_permalink(); ?>" class="df-button2">Details</a>
                                            <a href="<?php the_permalink(); ?>" class="df-button1">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
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