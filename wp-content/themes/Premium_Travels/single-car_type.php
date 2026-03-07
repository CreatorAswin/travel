<?php
/**
 * Template Name: Single Car Type
 * Template for displaying individual car type details
 */

get_header();
?>

<div class="main-contant pt-car-detail" style="padding: 40px 0; background: #fdfdfd;">
    <div class="container">
        <?php while (have_posts()):
    the_post();
    $capacity = get_post_meta(get_the_ID(), 'capacity', true);
    $price_per_km = get_post_meta(get_the_ID(), 'price_per_km', true);
    $ac_status = get_post_meta(get_the_ID(), 'ac_status', true) ?: 'AC';
    $fuel_type = get_post_meta(get_the_ID(), 'fuel_type', true);
    $luggage = get_post_meta(get_the_ID(), 'luggage', true);
    $features_raw = get_post_meta(get_the_ID(), 'features', true);
    $features_arr = $features_raw ? explode(',', $features_raw) : [];
?>

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" style="margin-bottom: 25px;">
                <ol style="background: #fff; padding: 15px 25px; margin: 0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e8eef5; display: flex; align-items: center; gap: 5px; list-style: none; flex-wrap: wrap;">
                    <li style="display: flex; align-items: center;">
                        <a href="<?php echo home_url(); ?>" style="color: var(--primary); text-decoration: none; font-weight: 500; padding: 8px 12px; border-radius: 6px; transition: all 0.3s;">
                            <i class="fa fa-home" style="margin-right: 8px;"></i>Home
                        </a>
                    </li>
                    <li style="display: flex; align-items: center;">
                        <span style="color: #ccc; margin: 0 4px; font-size: 16px;">›</span>
                        <span style="color: var(--text-mid); font-weight: 600; padding: 8px 12px; background: #f8f9fa; border-radius: 6px;">
                            <?php the_title(); ?>
                        </span>
                    </li>
                </ol>
            </nav>

            <div class="row">
                <!-- Main Content -->
                <div class="col-md-8">
                    <!-- Car Image -->
                    <div style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5; margin-bottom: 30px;">
                        <?php if (has_post_thumbnail()): ?>
                            <div style="height: 420px; overflow: hidden;">
                                <?php the_post_thumbnail('large', array(
            'style' => 'width: 100%; height: 100%; object-fit: cover; display: block;',
            'alt' => get_the_title()
        )); ?>
                            </div>
                        <?php
    else: ?>
                            <div style="height: 420px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-lt) 100%);">
                                <div style="text-align: center;">
                                    <i class="fa fa-car-side" style="font-size: 80px; color: rgba(255,255,255,.3); margin-bottom: 15px; display: block;"></i>
                                    <span style="color: rgba(255,255,255,.5); font-size: 16px;"><?php the_title(); ?></span>
                                </div>
                            </div>
                        <?php
    endif; ?>

                        <!-- Car Name Badge -->
                        <div style="background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-lt) 100%); padding: 20px 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h1 style="color: #fff; font-size: 24px; font-weight: 800; margin: 0 0 6px;"><?php the_title(); ?></h1>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <?php if ($ac_status): ?>
                                        <span style="background: rgba(255,255,255,.15); color: rgba(255,255,255,.9); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                            <i class="fa fa-snowflake" style="margin-right: 4px;"></i> <?php echo esc_html($ac_status); ?>
                                        </span>
                                    <?php
    endif; ?>
                                    <?php if ($capacity): ?>
                                        <span style="background: rgba(255,255,255,.15); color: rgba(255,255,255,.9); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                            <i class="fa fa-users" style="margin-right: 4px;"></i> <?php echo esc_html($capacity); ?>
                                        </span>
                                    <?php
    endif; ?>
                                    <?php if ($fuel_type): ?>
                                        <span style="background: rgba(255,255,255,.15); color: rgba(255,255,255,.9); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                            <i class="fa fa-gas-pump" style="margin-right: 4px;"></i> <?php echo esc_html($fuel_type); ?>
                                        </span>
                                    <?php
    endif; ?>
                                </div>
                            </div>
                            <?php if ($price_per_km): ?>
                                <div style="text-align: right;">
                                    <div style="color: rgba(255,255,255,.6); font-size: 12px; text-transform: uppercase; letter-spacing: .5px;">Starting Rate</div>
                                    <div style="color: #fff; font-size: 28px; font-weight: 800;">
                                        <i class="fa fa-rupee-sign" style="font-size: 22px;"></i> <?php echo esc_html($price_per_km); ?><span style="font-size: 14px; font-weight: 400;">/km</span>
                                    </div>
                                </div>
                            <?php
    endif; ?>
                        </div>
                    </div>

                    <!-- Car Description -->
                    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5; margin-bottom: 30px;">
                        <h3 style="margin-top: 0; font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 18px;">
                            <i class="fa fa-info-circle" style="color: var(--primary); margin-right: 8px;"></i>About This Vehicle
                        </h3>
                        <div style="color: var(--text-mid); line-height: 1.8; font-size: 15px;">
                            <?php the_content(); ?>
                            <?php if (empty(get_the_content())): ?>
                                <p>A reliable and comfortable <?php echo esc_html($ac_status); ?> vehicle perfect for your travel needs. 
                                <?php if ($capacity): ?>Seats up to <?php echo esc_html($capacity); ?> passengers comfortably.<?php
        endif; ?>
                                <?php if ($fuel_type): ?> Runs on <?php echo esc_html($fuel_type); ?>.<?php
        endif; ?>
                                <?php if ($luggage): ?> Accommodates <?php echo esc_html($luggage); ?> luggage bags.<?php
        endif; ?>
                                </p>
                            <?php
    endif; ?>
                        </div>
                    </div>

                    <!-- Specifications Table -->
                    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5; margin-bottom: 30px;">
                        <h3 style="margin-top: 0; font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 18px;">
                            <i class="fa fa-cogs" style="color: var(--primary); margin-right: 8px;"></i>Specifications
                        </h3>
                        <table style="width: 100%; border-collapse: collapse;">
                            <?php if ($capacity): ?>
                            <tr>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; font-weight: 600; color: var(--text-dark); width: 40%; background: #fafbfd;">
                                    <i class="fa fa-users" style="color: var(--primary); width: 20px; margin-right: 8px;"></i>Seating Capacity
                                </td>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; color: var(--text-mid);"><?php echo esc_html($capacity); ?></td>
                            </tr>
                            <?php
    endif; ?>
                            <?php if ($ac_status): ?>
                            <tr>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; font-weight: 600; color: var(--text-dark); background: #fafbfd;">
                                    <i class="fa fa-snowflake" style="color: var(--primary); width: 20px; margin-right: 8px;"></i>AC Status
                                </td>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; color: var(--text-mid);"><?php echo esc_html($ac_status); ?></td>
                            </tr>
                            <?php
    endif; ?>
                            <?php if ($fuel_type): ?>
                            <tr>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; font-weight: 600; color: var(--text-dark); background: #fafbfd;">
                                    <i class="fa fa-gas-pump" style="color: var(--primary); width: 20px; margin-right: 8px;"></i>Fuel Type
                                </td>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; color: var(--text-mid);"><?php echo esc_html($fuel_type); ?></td>
                            </tr>
                            <?php
    endif; ?>
                            <?php if ($luggage): ?>
                            <tr>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; font-weight: 600; color: var(--text-dark); background: #fafbfd;">
                                    <i class="fa fa-suitcase" style="color: var(--primary); width: 20px; margin-right: 8px;"></i>Luggage Capacity
                                </td>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; color: var(--text-mid);"><?php echo esc_html($luggage); ?> Bags</td>
                            </tr>
                            <?php
    endif; ?>
                            <?php if ($price_per_km): ?>
                            <tr>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; font-weight: 600; color: var(--text-dark); background: #fafbfd;">
                                    <i class="fa fa-rupee-sign" style="color: var(--primary); width: 20px; margin-right: 8px;"></i>Price Per km
                                </td>
                                <td style="padding: 14px 16px; border-bottom: 1px solid #f0f4f8; color: var(--primary); font-weight: 700; font-size: 18px;">₹<?php echo esc_html($price_per_km); ?>/km</td>
                            </tr>
                            <?php
    endif; ?>
                        </table>
                    </div>

                    <!-- Features -->
                    <?php if (!empty($features_arr)): ?>
                    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5;">
                        <h3 style="margin-top: 0; font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 18px;">
                            <i class="fa fa-star" style="color: var(--primary); margin-right: 8px;"></i>Features
                        </h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <?php foreach ($features_arr as $feat): ?>
                                <span style="background: var(--bg-light); border: 1px solid #dde3ef; border-radius: 25px; padding: 8px 18px; font-size: 13px; color: var(--text-mid); display: flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-check-circle" style="color: var(--primary);"></i><?php echo esc_html(trim($feat)); ?>
                                </span>
                            <?php
        endforeach; ?>
                        </div>
                    </div>
                    <?php
    endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <div style="background: #fff; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5; overflow: hidden; position: sticky; top: 20px;">
                        <!-- Sidebar Header -->
                        <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dk) 100%); padding: 25px; text-align: center;">
                            <i class="fa fa-car-side" style="font-size: 36px; color: #fff; margin-bottom: 10px; display: block;"></i>
                            <h4 style="color: #fff; font-size: 18px; font-weight: 700; margin: 0 0 5px;">Book This Car</h4>
                            <p style="color: rgba(255,255,255,.8); font-size: 13px; margin: 0;">Best rates guaranteed</p>
                        </div>

                        <div style="padding: 25px;">
                            <!-- Quick Info -->
                            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px dashed #eee;">
                                <?php if ($price_per_km): ?>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #fff4ef; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fa fa-rupee-sign" style="color: var(--primary); font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: var(--text-light); text-transform: uppercase;">Rate</div>
                                        <div style="font-size: 16px; font-weight: 700; color: var(--primary);">₹<?php echo esc_html($price_per_km); ?>/km</div>
                                    </div>
                                </div>
                                <?php
    endif; ?>
                                <?php if ($capacity): ?>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #f0f4ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fa fa-users" style="color: var(--secondary); font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: var(--text-light); text-transform: uppercase;">Capacity</div>
                                        <div style="font-size: 14px; font-weight: 600; color: var(--text-dark);"><?php echo esc_html($capacity); ?></div>
                                    </div>
                                </div>
                                <?php
    endif; ?>
                                <?php if ($ac_status): ?>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fa fa-snowflake" style="color: #28a745; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: var(--text-light); text-transform: uppercase;">Climate</div>
                                        <div style="font-size: 14px; font-weight: 600; color: var(--text-dark);"><?php echo esc_html($ac_status); ?></div>
                                    </div>
                                </div>
                                <?php
    endif; ?>
                            </div>

                            <!-- Booking Buttons -->
                            <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                                <a href="<?php echo esc_url(home_url('/cab-booking')); ?>" class="df-button1">
                                    <i class="fa fa-bolt" style="margin-right: 6px;"></i> Book Now
                                </a>
                                <a href="<?php echo esc_url(home_url('/car-rentals')); ?>" class="df-button2">
                                    <i class="fa fa-car" style="margin-right: 6px;"></i> View All Cars
                                </a>
                            </div>

                            <!-- Contact -->
                            <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 10px; font-size: 12px; color: var(--text-light);">
                                <i class="fa fa-headset" style="color: var(--primary); margin-right: 4px;"></i>
                                Need help? Call: <strong style="color: var(--text-dark);">1800 120 8464</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Cars -->
            <?php
    $related_args = array(
        'post_type' => 'car_type',
        'posts_per_page' => 3,
        'post__not_in' => array(get_the_ID()),
    );
    $related_query = new WP_Query($related_args);
?>
            <?php if ($related_query->have_posts()): ?>
                <div class="row" style="margin-top: 50px;">
                    <div class="col-md-12">
                        <h3 style="font-size: 22px; font-weight: 700; color: var(--secondary); margin-bottom: 25px; border-bottom: 2px solid #eee; padding-bottom: 10px; display: inline-block;">
                            <i class="fa fa-car" style="color: var(--primary); margin-right: 8px;"></i>Other Cars in Our Fleet
                        </h3>
                    </div>
                    <?php while ($related_query->have_posts()):
            $related_query->the_post();
            $rel_capacity = get_post_meta(get_the_ID(), 'capacity', true);
            $rel_price_km = get_post_meta(get_the_ID(), 'price_per_km', true);
            $rel_ac = get_post_meta(get_the_ID(), 'ac_status', true) ?: 'AC';
?>
                        <div class="col-md-4 col-sm-6">
                            <div class="destination-thumb thumb gap rnt-gap">
                                <div class="zoomeffects">
                                    <div class="zoomEffect_2">
                                        <?php if (has_post_thumbnail()): ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium', ['alt' => get_the_title(), 'style' => 'width:100%;height:190px;object-fit:cover;']); ?>
                                            </a>
                                        <?php
            else: ?>
                                            <a href="<?php the_permalink(); ?>" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:10px;text-decoration:none;">
                                                <i class="fa fa-car-side" style="font-size:50px;color:rgba(255,255,255,.35);"></i>
                                                <span style="color:rgba(255,255,255,.5);font-size:12px;"><?php the_title(); ?></span>
                                            </a>
                                        <?php
            endif; ?>
                                    </div>
                                </div>
                                <div class="mc-car-name">
                                        <p class="carname">
                                            <span><?php the_title(); ?></span>
                                            <span class="tx">
                                                <i class="fa fa-snowflake"></i> <?php echo esc_html($rel_ac); ?>
                                                <?php if ($rel_capacity): ?>
                                                    &nbsp;·&nbsp; <i class="fa fa-users"></i> <?php echo esc_html($rel_capacity); ?>
                                                <?php
            endif; ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <?php if ($rel_price_km): ?>
                                    <div class="car-price-row">
                                        <strong><i class="fa fa-rupee-sign"></i> <?php echo esc_html($rel_price_km); ?>/km</strong>
                                        &nbsp;Starting Rate
                                    </div>
                                <?php
            endif; ?>
                                <div class="mc-feature" style="margin-top:auto;">
                                        <div class="book-buttons">
                                            <a href="<?php the_permalink(); ?>" class="df-button2">Details</a>
                                            <a href="<?php echo esc_url(home_url('/car-rentals')); ?>" class="df-button1">Book Now</a>
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
