<?php
/**
 * Template Name: Cart Page
 */
require_once get_template_directory() . '/includes/dynamic-management/products-manager.php';
global $wpdb;

$product_id   = isset($_GET['product_id'])   ? absint($_GET['product_id'])                : 0;
$product_slug = isset($_GET['product_slug']) ? sanitize_text_field($_GET['product_slug']) : '';

$product = null;
if ($product_id) {
    $product = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_products WHERE id = %d AND is_active = 1", $product_id));
} elseif ($product_slug) {
    $product = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}pt_products WHERE slug = %s AND is_active = 1", $product_slug));
}
if ($product) {
    $mgr = new PT_Products_Manager();
    $product = $mgr->format_record($product);
}

$location_name = '';
if ($product && $product->location_id) {
    $loc = $wpdb->get_row($wpdb->prepare("SELECT title FROM {$wpdb->prefix}pt_locations WHERE id = %d", $product->location_id));
    if ($loc) $location_name = $loc->title;
}

$product_img = '';
if ($product) {
    $product_img = !empty($product->featured_image) ? $product->featured_image : '';
    if (empty($product_img) && !empty($product->gallery_images)) {
        $g = explode(',', $product->gallery_images);
        $product_img = trim($g[0]);
    }
}

$unit_price = $product ? floatval($product->price_sale ?: $product->price_regular) : 0;
$stock_max  = $product ? (intval($product->stock_quantity) ?: 99) : 99;

get_header();
?>
<style>
/* ── Reset numbered list for breadcrumb ── */
.pt-bc { list-style: none !important; counter-reset: none !important; padding: 14px 22px !important; margin: 0 !important; display: flex !important; align-items: center !important; flex-wrap: wrap !important; gap: 0 !important; background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); border: 1px solid #e2e8f5; }
.pt-bc li { display: flex !important; align-items: center !important; font-size: 13px; }
.pt-bc li::before { display: none !important; content: none !important; }
.pt-bc a { color: var(--primary); text-decoration: none; font-weight: 500; }
.pt-bc a:hover { text-decoration: underline; }
.pt-bc .sep { color: #ccc; margin: 0 6px; font-size: 15px; }
.pt-bc .cur { color: #666; font-weight: 600; }

/* ── Cart layout ── */
.pt-cart-wrap { background: #f4f7fe; padding: 36px 0 70px; min-height: 60vh; }
.pt-cart-hero { background: linear-gradient(135deg, #0f2460 0%, #1a56db 100%); color: #fff; padding: 36px 20px; text-align: center; }
.pt-cart-hero h1 { font-size: 28px; font-weight: 800; margin: 0 0 4px; }
.pt-cart-hero p  { margin: 0; opacity: .8; font-size: 14px; }
.pt-cart-box { background: #fff; border-radius: 14px; box-shadow: 0 4px 20px rgba(26,86,219,.08); border: 1px solid #e2e8f5; overflow: hidden; }

/* product row */
.pt-cart-pr { display: flex; gap: 20px; padding: 24px; align-items: flex-start; }
.pt-cart-thumb { width: 120px; height: 90px; flex-shrink: 0; border-radius: 10px; overflow: hidden; background: #eef2fb; display: flex; align-items: center; justify-content: center; }
.pt-cart-thumb img { width: 100%; height: 100%; object-fit: cover; }
.pt-cart-info h3 { font-size: 18px; font-weight: 700; color: #1e2a3b; margin: 0 0 6px; }
.pt-cart-loc  { font-size: 13px; color: #6b7892; margin-bottom: 5px; }
.pt-cart-price { font-size: 22px; font-weight: 800; color: #1a56db; margin-bottom: 14px; }

/* qty */
.pt-qty { display: inline-flex; align-items: center; gap: 0; border: 1.5px solid #d6dff5; border-radius: 8px; overflow: hidden; }
.pt-qty-btn { width: 34px; height: 34px; background: #f0f4ff; border: none; font-size: 18px; font-weight: 700; color: #1a56db; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.pt-qty-btn:hover { background: #1a56db; color: #fff; }
.pt-qty-input { width: 44px; height: 34px; text-align: center; font-size: 15px; font-weight: 700; border: none; border-left: 1.5px solid #d6dff5; border-right: 1.5px solid #d6dff5; outline: none; }

/* summary */
.pt-cart-sum { padding: 18px 24px; border-top: 1px solid #e8eef5; background: #f8faff; }
.pt-sum-row  { display: flex; justify-content: space-between; font-size: 14px; color: #1e2a3b; margin-bottom: 8px; }
.pt-sum-total { font-size: 19px; font-weight: 800; color: #1a56db; border-top: 2px solid #e2e8f5; padding-top: 12px; margin-top: 4px; }

/* buttons */
.pt-cart-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 18px 24px; }
.pt-cbtn { display: block; padding: 13px; text-align: center; font-size: 14px; font-weight: 700; border-radius: 10px; text-decoration: none; transition: .2s; }
.pt-cbtn-outline { background: #fff; color: #1a56db; border: 2px solid #1a56db; }
.pt-cbtn-outline:hover { background: #f0f4ff; color: #1a56db; }
.pt-cbtn-solid  { background: #1a56db; color: #fff; border: 2px solid #1a56db; }
.pt-cbtn-solid:hover { background: #1340a0; color: #fff; }

/* trust */
.pt-trust { display: flex; justify-content: center; gap: 28px; flex-wrap: wrap; margin-top: 20px; font-size: 12px; color: #6b7892; }
</style>

<div class="pt-cart-hero">
    <h1>🛒 Shopping Cart</h1>
    <p><?php echo $product ? 'Review your item before checkout' : 'Your cart is empty'; ?></p>
</div>

<div class="pt-cart-wrap">
    <div class="container" style="max-width:780px; padding:0 16px;">

        <?php if ($product): ?>

        <!-- Breadcrumb -->
        <nav style="margin-bottom:22px;">
            <ul class="pt-bc">
                <li><a href="<?php echo home_url(); ?>"><i class="fa fa-home"></i> Home</a></li>
                <li><span class="sep">›</span><a href="<?php echo home_url('/products'); ?>">Products</a></li>
                <li><span class="sep">›</span><a href="<?php echo home_url('/product/' . $product->slug); ?>"><?php echo esc_html($product->title); ?></a></li>
                <li><span class="sep">›</span><span class="cur">Cart</span></li>
            </ul>
        </nav>

        <div class="pt-cart-box">
            <!-- Product Row -->
            <div class="pt-cart-pr">
                <div class="pt-cart-thumb">
                    <?php if ($product_img): ?>
                        <img src="<?php echo esc_url($product_img); ?>" alt="<?php echo esc_attr($product->title); ?>" />
                    <?php else: ?>
                        <i class="fa fa-image" style="font-size:32px;color:#c8d5e8;"></i>
                    <?php endif; ?>
                </div>
                <div class="pt-cart-info">
                    <h3><?php echo esc_html($product->title); ?></h3>
                    <?php if ($location_name): ?>
                        <div class="pt-cart-loc"><i class="fa fa-map-marker-alt" style="color:var(--primary);margin-right:4px;"></i><?php echo esc_html($location_name); ?></div>
                    <?php endif; ?>
                    <?php if ($product->sku): ?>
                        <div style="font-size:12px;color:#999;margin-bottom:6px;">SKU: <?php echo esc_html($product->sku); ?></div>
                    <?php endif; ?>
                    <div class="pt-cart-price">₹<?php echo number_format($unit_price, 2); ?></div>

                    <!-- Qty selector -->
                    <div style="display:flex;align-items:center;gap:12px;">
                        <span style="font-size:13px;color:#6b7892;font-weight:600;">Qty:</span>
                        <div class="pt-qty">
                            <button class="pt-qty-btn" onclick="changeQty(-1)">−</button>
                            <input class="pt-qty-input" type="number" id="pt-qty" value="1" min="1" max="<?php echo $stock_max; ?>" readonly />
                            <button class="pt-qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="pt-cart-sum">
                <div class="pt-sum-row">
                    <span>Unit Price</span>
                    <span id="pt-unit" data-price="<?php echo $unit_price; ?>">₹<?php echo number_format($unit_price, 2); ?></span>
                </div>
                <?php if (!empty($product->price_sale) && $product->price_sale < $product->price_regular): ?>
                <div class="pt-sum-row" style="color:#e74c3c;">
                    <span>Discount</span>
                    <span>− ₹<?php echo number_format($product->price_regular - $product->price_sale, 2); ?></span>
                </div>
                <?php endif; ?>
                <div class="pt-sum-row" style="color:#6b7892;font-size:13px;"><span>Taxes</span><span>Included</span></div>
                <div class="pt-sum-row pt-sum-total">
                    <span>Total</span>
                    <span id="pt-total">₹<?php echo number_format($unit_price, 2); ?></span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-cart-btns">
                <a href="<?php echo home_url('/product/' . $product->slug); ?>" class="pt-cbtn pt-cbtn-outline">
                    <i class="fa fa-arrow-left" style="margin-right:6px;"></i> Back
                </a>
                <a href="<?php echo esc_url(home_url('/buy-now?product_id=' . $product->id . '&product_slug=' . $product->slug)); ?>" class="pt-cbtn pt-cbtn-solid" id="pt-buy-link">
                    <i class="fa fa-bolt" style="margin-right:6px;"></i> Proceed to Buy
                </a>
            </div>
        </div>

        <!-- Trust badges -->
        <div class="pt-trust">
            <span><i class="fa fa-shield-alt" style="color:#28a745;margin-right:4px;"></i>Secure Checkout</span>
            <span><i class="fa fa-undo" style="color:#1a56db;margin-right:4px;"></i>Easy Returns</span>
            <span><i class="fa fa-headset" style="color:#f97316;margin-right:4px;"></i>Support: 1800 120 8464</span>
        </div>

        <?php else: ?>
        <div style="text-align:center;padding:80px 20px;color:#6b7892;">
            <i class="fa fa-shopping-cart" style="font-size:60px;color:#d0daf0;margin-bottom:18px;display:block;"></i>
            <h3 style="font-size:22px;color:#1e2a3b;margin-bottom:8px;">Your cart is empty</h3>
            <p>Browse our products and add something you love!</p>
            <a href="<?php echo home_url('/products'); ?>" style="display:inline-block;margin-top:18px;padding:12px 26px;background:#1a56db;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;">
                <i class="fa fa-store" style="margin-right:6px;"></i> Browse Products
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function changeQty(d) {
    var el = document.getElementById('pt-qty');
    var v  = Math.min(Math.max(parseInt(el.value) + d, 1), parseInt(el.max) || 99);
    el.value = v;
    var price = parseFloat(document.getElementById('pt-unit').getAttribute('data-price'));
    var fmt   = (price * v).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
    document.getElementById('pt-total').textContent = '₹' + fmt;
    var link = document.getElementById('pt-buy-link');
    if (link) {
        var url = new URL(link.href, window.location.origin);
        url.searchParams.set('qty', v);
        link.href = url.toString();
    }
}
</script>

<?php get_footer(); ?>
