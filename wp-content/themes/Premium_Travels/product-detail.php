<?php
/**
 * Template Name: Product Detail Page
 * Template for displaying individual product details with e-commerce features
 */

// Make $post globally available throughout this template
global $post;

// Get product slug from URL
$product_slug = get_query_var('product_slug');
if (!$product_slug) {
    // Fallback to post name if coming from WordPress permalink
    if ($post && $post->post_type === 'pt_product') {
        $product_slug = $post->post_name;
    }
}

// Load products manager
require_once get_template_directory() . '/includes/dynamic-management/products-manager.php';
$products_manager = new PT_Products_Manager();

// Get product by slug
$product = null;
if ($product_slug) {
    global $wpdb;
    $product = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}pt_products WHERE slug = %s AND is_active = 1",
        $product_slug
    ));
    
    if ($product) {
        $product = $products_manager->format_record($product);
    }
}

// If no product found, try to get from WordPress post
if (!$product && $post) {
    $product = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}pt_products WHERE title = %s AND is_active = 1",
        $post->post_title
    ));
    
    if ($product) {
        $product = $products_manager->format_record($product);
    }
}

get_header();
?>

<div class="main-content pt-product-detail" style="padding: 40px 0; background: #fdfdfd;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <?php if ($product): ?>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" style="margin-bottom: 25px;">
                <ol class="breadcrumb" style="background: #fff; padding: 15px 25px; margin: 0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e8eef5; display: flex; align-items: center; gap: 5px;">
                    <li class="breadcrumb-item" style="display: flex; align-items: center;">
                        <a href="<?php echo home_url(); ?>" style="color: var(--primary); text-decoration: none; font-weight: 500; display: flex; align-items: center; transition: all 0.3s ease; padding: 8px 12px; border-radius: 6px;" onmouseover="this.style.background='#f8f9fa'; this.style.color='#0056b3'" onmouseout="this.style.background='transparent'; this.style.color='var(--primary)'">
                            <i class="fa fa-home" style="margin-right: 8px; font-size: 14px;"></i>Home
                        </a>
                    </li>
                    <li class="breadcrumb-item" style="display: flex; align-items: center;">
                        <span style="color: #ccc; margin: 0 8px; font-size: 16px; font-weight: 300;">›</span>
                        <a href="<?php echo home_url('/products'); ?>" style="color: var(--primary); text-decoration: none; font-weight: 500; transition: all 0.3s ease; padding: 8px 12px; border-radius: 6px;" onmouseover="this.style.background='#f8f9fa'; this.style.color='#0056b3'" onmouseout="this.style.background='transparent'; this.style.color='var(--primary)'">
                            Products
                        </a>
                    </li>
                    <li class="breadcrumb-item active" style="display: flex; align-items: center;" aria-current="page">
                        <span style="color: #ccc; margin: 0 8px; font-size: 16px; font-weight: 300;">›</span>
                        <span style="color: var(--text-mid); font-weight: 600; padding: 8px 12px; background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                            <?php echo esc_html($product->title); ?>
                        </span>
                    </li>
                </ol>
            </nav>

            <div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -15px;">
                <!-- Product Gallery (65% width) -->
                <div class="col-md-8" style="flex: 0 0 65%; max-width: 65%; padding: 0 15px;">
                    <div class="product-gallery" style="position: sticky; top: 20px;">
                        <?php
                        // Collect gallery image URLs
                        $main_image_url = '';
                        $gallery_image_urls = array();

                        // Priority 1: DB featured_image field (uploaded via admin uploader)
                        if (!empty($product->featured_image)) {
                            $main_image_url = $product->featured_image;
                        }

                        // Priority 2: WordPress post featured image
                        if (empty($main_image_url) && $post && has_post_thumbnail($post->ID)) {
                            $main_image_url = get_the_post_thumbnail_url($post->ID, 'large');
                        }

                        // Collect gallery images from DB
                        if (!empty($product->gallery_images)) {
                            $raw_gallery = explode(',', $product->gallery_images);
                            foreach ($raw_gallery as $gurl) {
                                $gurl = trim($gurl);
                                if (!empty($gurl)) {
                                    $gallery_image_urls[] = $gurl;
                                }
                            }
                        }

                        // If still no main image, use first gallery image
                        if (empty($main_image_url) && !empty($gallery_image_urls)) {
                            $main_image_url = array_shift($gallery_image_urls);
                        }
                        ?>
                        <div class="main-image" id="pt-main-image" style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); margin-bottom: 15px; height: 400px; display: flex; align-items: center; justify-content: center;">
                            <?php if ($main_image_url): ?>
                                <img id="pt-main-img-tag" src="<?php echo esc_url($main_image_url); ?>" alt="<?php echo esc_attr($product->title); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
                            <?php else: ?>
                                <div style="text-align: center; color: #ccc; font-size: 14px; padding: 20px;">
                                    <i class="fa fa-image" style="font-size: 60px; margin-bottom: 15px; color: #e0e0e0;"></i>
                                    <div>No image available</div>
                                    <div style="font-size: 12px; margin-top: 10px; color: #999;">Add images in WordPress admin &rarr; Products</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Thumbnails -->
                        <?php
                        // Build thumbnails: featured + gallery
                        $all_thumbs = array();
                        if ($main_image_url) $all_thumbs[] = $main_image_url;
                        foreach ($gallery_image_urls as $g) $all_thumbs[] = $g;
                        ?>
                        <?php if (count($all_thumbs) > 0): ?>
                        <div class="thumbnails" style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <?php foreach ($all_thumbs as $ti => $turl): ?>
                                <div onclick="document.getElementById('pt-main-img-tag').src='<?php echo esc_js($turl); ?>'" style="width: 80px; height: 80px; background: #f8f9fa; border-radius: 8px; border: 2px solid <?php echo $ti === 0 ? 'var(--primary)' : '#e9ecef'; ?>; overflow: hidden; cursor: pointer; flex-shrink: 0; transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='<?php echo $ti === 0 ? 'var(--primary)' : '#e9ecef'; ?>'">
                                    <img src="<?php echo esc_url($turl); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Info (35% width) -->
                <div class="col-md-4" style="flex: 0 0 35%; max-width: 35%; padding: 0 15px;">
                    <div class="product-info" style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5; position: sticky; top: 20px;">
                        <!-- Product Title -->
                        <h1 class="product-title" style="font-size: 28px; font-weight: 800; color: var(--secondary); margin: 0 0 15px; line-height: 1.2;">
                            <?php echo esc_html($product->title); ?>
                        </h1>

                        <!-- Location -->
                        <?php if ($product->location_id): 
                            global $wpdb;
                            $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d AND is_active = 1", $product->location_id));
                            if ($location): ?>
                                <div class="product-location" style="margin-bottom: 20px; display: flex; align-items: center;">
                                    <i class="fa fa-map-marker-alt" style="color: var(--primary); margin-right: 8px;"></i>
                                    <span style="color: var(--text-mid); font-size: 14px;"><?php echo esc_html($location->title); ?></span>
                                </div>
                            <?php else: ?>
                                <!-- Debug: Location not found -->
                                <div style="margin-bottom: 20px; color: #ff6b6b; font-size: 12px;">
                                    Location ID <?php echo $product->location_id; ?> not found in database
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Price Section -->
                        <div class="price-section" style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                            <div class="price-row" style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                                <?php if ($product->price_sale && $product->price_sale < $product->price_regular): ?>
                                    <div class="sale-price" style="font-size: 32px; font-weight: 800; color: var(--primary);">
                                        <i class="fa fa-rupee-sign"></i> <?php echo number_format($product->price_sale, 2); ?>
                                    </div>
                                    <div class="regular-price" style="font-size: 20px; color: #999; text-decoration: line-through;">
                                        <i class="fa fa-rupee-sign"></i> <?php echo number_format($product->price_regular, 2); ?>
                                    </div>
                                    <div class="discount-badge" style="background: #e74c3c; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <?php echo round($product->discount_percentage); ?>% OFF
                                    </div>
                                <?php else: ?>
                                    <div class="regular-price" style="font-size: 32px; font-weight: 800; color: var(--primary);">
                                        <i class="fa fa-rupee-sign"></i> <?php echo number_format($product->price_regular, 2); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($product->sku): ?>
                                <div class="sku" style="font-size: 13px; color: var(--text-light);">
                                    SKU: <?php echo esc_html($product->sku); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Type -->
                        <?php if ($product->product_type): ?>
                            <div class="product-type" style="margin-bottom: 20px;">
                                <span style="background: #e3f2fd; color: #1976d2; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    <?php echo esc_html(ucfirst($product->product_type)); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <!-- Availability -->
                        <div class="availability" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-check-circle" style="color: #28a745;"></i>
                                <span style="font-weight: 600; color: #28a745;">
                                    <?php echo $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                </span>
                                <?php if ($product->stock_quantity > 0): ?>
                                    <span style="color: var(--text-light); font-size: 14px;">
                                        (<?php echo $product->stock_quantity; ?> available)
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons" style="margin-bottom: 25px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 15px;">
                                <button class="btn btn-primary" style="padding: 14px 20px; font-size: 16px; font-weight: 600; border: none; border-radius: 8px; background: var(--primary); color: white; cursor: pointer; transition: all 0.3s;">
                                    <i class="fa fa-shopping-cart" style="margin-right: 8px;"></i> Add to Cart
                                </button>
                                <button class="btn btn-success" style="padding: 14px 20px; font-size: 16px; font-weight: 600; border: none; border-radius: 8px; background: #28a745; color: white; cursor: pointer; transition: all 0.3s;">
                                    <i class="fa fa-bolt" style="margin-right: 8px;"></i> Buy Now
                                </button>
                            </div>
                            
                            <div style="display: flex; gap: 10px;">
                                <button class="btn btn-outline-secondary" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: white; cursor: pointer;">
                                    <i class="fa fa-heart" style="margin-right: 5px;"></i> Wishlist
                                </button>
                                <button class="btn btn-outline-secondary" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: white; cursor: pointer;">
                                    <i class="fa fa-share-alt" style="margin-right: 5px;"></i> Share
                                </button>
                            </div>
                        </div>

                        <!-- Product Meta -->
                        <div class="product-meta" style="border-top: 1px solid #eee; padding-top: 20px;">
                            <?php if ($product->weight): ?>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                    <span style="color: var(--text-light);">Weight:</span>
                                    <span style="font-weight: 600;"><?php echo $product->weight; ?> kg</span>
                                </div>
                            <?php endif; ?>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                <span style="color: var(--text-light);">Category:</span>
                                <span style="font-weight: 600;">Product</span>
                            </div>
                            
                            <div style="font-size: 12px; color: var(--text-light); margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <i class="fa fa-shield-alt" style="color: var(--primary); margin-right: 5px;"></i>
                                Secure transaction. 24/7 Support available. 
                                <strong style="color: var(--text-dark);">1800 120 8464</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description Tabs -->
            <div class="row" style="margin-top: 40px;">
                <div class="col-md-12">
                    <div class="product-tabs" style="background: #fff; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e8eef5;">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" style="border-bottom: 1px solid #e8eef5; padding: 0 25px; margin: 0;">
                            <li class="nav-item">
                                <a class="nav-link active" href="#description" data-toggle="tab" style="padding: 15px 20px; font-weight: 600; color: var(--secondary); border: none; border-bottom: 3px solid transparent;">
                                    Description
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#specifications" data-toggle="tab" style="padding: 15px 20px; font-weight: 600; color: var(--text-light); border: none; border-bottom: 3px solid transparent;">
                                    Specifications
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#reviews" data-toggle="tab" style="padding: 15px 20px; font-weight: 600; color: var(--text-light); border: none; border-bottom: 3px solid transparent;">
                                    Reviews (0)
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" style="padding: 25px;">
                            <div class="tab-pane fade show active" id="description">
                                <h3 style="font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 20px;">Product Description</h3>
                                <div style="color: var(--text-mid); line-height: 1.7; font-size: 15px;">
                                    <?php echo wp_kses_post($product->description ?: $product->short_description); ?>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="specifications">
                                <h3 style="font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 20px;">Product Specifications</h3>
                                <div class="specifications-table" style="width: 100%; border-collapse: collapse;">
                                    <table style="width: 100%; border: 1px solid #eee;">
                                        <tr>
                                            <td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: 600; width: 30%;">Product Type</td>
                                            <td style="padding: 12px; border: 1px solid #eee;"><?php echo esc_html(ucfirst($product->product_type)); ?></td>
                                        </tr>
                                        <?php if ($product->sku): ?>
                                        <tr>
                                            <td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: 600;">SKU</td>
                                            <td style="padding: 12px; border: 1px solid #eee;"><?php echo esc_html($product->sku); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if ($product->weight): ?>
                                        <tr>
                                            <td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: 600;">Weight</td>
                                            <td style="padding: 12px; border: 1px solid #eee;"><?php echo $product->weight; ?> kg</td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: 600;">Availability</td>
                                            <td style="padding: 12px; border: 1px solid #eee;"><?php echo $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock'; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="reviews">
                                <h3 style="font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 20px;">Customer Reviews</h3>
                                <div style="text-align: center; padding: 40px; color: var(--text-light);">
                                    <i class="fa fa-star" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                                    <p style="font-size: 16px; margin-bottom: 20px;">No reviews yet</p>
                                    <p style="font-size: 14px;">Be the first to review this product</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="row" style="margin-top: 60px;">
                <div class="col-md-12">
                    <h3 style="font-size: 24px; font-weight: 700; color: var(--secondary); margin-bottom: 25px; border-bottom: 2px solid #eee; padding-bottom: 10px; display: inline-block;">
                        Related Products
                    </h3>
                </div>
                
                <?php
                // Get related products (same location or random, latest first)
                $related_products = array();
                if ($product->location_id) {
                    $related_products = $wpdb->get_results($wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}pt_products 
                         WHERE location_id = %d 
                         AND id != %d 
                         AND is_active = 1 
                         ORDER BY created_at DESC 
                         LIMIT 4",
                        $product->location_id,
                        $product->id
                    ));
                }
                
                if (empty($related_products)) {
                    $related_products = $wpdb->get_results($wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}pt_products 
                         WHERE id != %d 
                         AND is_active = 1 
                         ORDER BY created_at DESC 
                         LIMIT 4",
                        $product->id
                    ));
                }
                
                foreach ($related_products as $related_product):
                    $related_product = $products_manager->format_record($related_product);
                    $related_location = '';
                    if ($related_product->location_id) {
                        $loc = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_locations WHERE id = %d", $related_product->location_id));
                        if ($loc) {
                            $related_location = $loc->title;
                        }
                    }
                ?>
                    <div class="col-md-3 col-sm-6" style="margin-bottom: 25px;">
                        <a href="<?php echo home_url('/product/' . $related_product->slug); ?>" style="display: block; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid #f1f1f1; text-decoration: none; color: inherit; transition: all 0.3s; height: 100%;">
                            <div style="height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-image" style="font-size: 40px; color: #ccc;"></i>
                            </div>
                            <div style="padding: 20px;">
                                <h4 style="font-size: 16px; font-weight: 700; color: var(--secondary); margin: 0 0 10px; line-height: 1.3; min-height: 42px;">
                                    <?php echo esc_html($related_product->title); ?>
                                </h4>
                                <?php if ($related_location): ?>
                                    <div style="font-size: 12px; color: var(--text-light); margin-bottom: 10px;">
                                        <i class="fa fa-map-marker-alt" style="margin-right: 5px;"></i>
                                        <?php echo esc_html($related_location); ?>
                                    </div>
                                <?php endif; ?>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="font-size: 18px; font-weight: 800; color: var(--primary);">
                                        <i class="fa fa-rupee-sign"></i> <?php echo number_format($related_product->price_regular, 2); ?>
                                    </div>
                                    <span style="background: #e3f2fd; color: #1976d2; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: 600;">
                                        <?php echo esc_html(ucfirst($related_product->product_type)); ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <!-- Product Not Found -->
            <div class="row">
                <div class="col-md-12 text-center" style="padding: 60px 20px;">
                    <i class="fa fa-search" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                    <h2 style="color: var(--secondary); margin-bottom: 15px;">Product Not Found</h2>
                    <p style="color: var(--text-light); margin-bottom: 30px; font-size: 16px;">
                        Sorry, we couldn't find the product you're looking for.
                    </p>
                    <a href="<?php echo home_url('/products'); ?>" class="btn btn-primary" style="padding: 12px 30px; background: var(--primary); color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        <i class="fa fa-arrow-left" style="margin-right: 8px;"></i> Back to Products
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add CSS for breadcrumb hover effects
    const style = document.createElement('style');
    style.textContent = `
        .breadcrumb-item a:hover {
            color: #0056b3 !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .breadcrumb {
            font-family: 'Poppins', sans-serif;
        }
        .breadcrumb-item a {
            position: relative;
        }
        .breadcrumb-item.active span {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .breadcrumb-item:not(.active):hover a {
            background: #f8f9fa !important;
        }
    `;
    document.head.appendChild(style);
    
    // Tab functionality
    const tabLinks = document.querySelectorAll('.nav-link');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active classes
            tabLinks.forEach(l => {
                l.classList.remove('active');
                l.style.color = 'var(--text-light)';
                l.style.borderBottom = '3px solid transparent';
            });
            tabPanes.forEach(p => p.classList.remove('show', 'active'));
            
            // Add active classes
            this.classList.add('active');
            this.style.color = 'var(--secondary)';
            this.style.borderBottom = '3px solid var(--primary)';
            
            const target = document.querySelector(this.getAttribute('href'));
            target.classList.add('show', 'active');
        });
    });
    
    // Add to cart functionality
    const addToCartBtn = document.querySelector('.btn-primary');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            this.innerHTML = '<i class="fa fa-check"></i> Added to Cart';
            this.style.background = '#28a745';
            setTimeout(() => {
                this.innerHTML = '<i class="fa fa-shopping-cart" style="margin-right: 8px;"></i> Add to Cart';
                this.style.background = 'var(--primary)';
            }, 2000);
        });
    }
});
</script>

<?php get_footer(); ?>