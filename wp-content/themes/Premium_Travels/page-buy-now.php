<?php
/**
 * Template Name: Buy Now Page
 */
require_once get_template_directory() . '/includes/dynamic-management/products-manager.php';
global $wpdb;

$product_id   = isset($_GET['product_id'])   ? absint($_GET['product_id'])                : 0;
$product_slug = isset($_GET['product_slug']) ? sanitize_text_field($_GET['product_slug']) : '';
$qty          = isset($_GET['qty'])          ? max(1, absint($_GET['qty']))                : 1;

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

$unit_price = 0;
if ($product) {
    $sale = floatval($product->price_sale);
    $reg  = floatval($product->price_regular);
    $unit_price = ($sale > 0 && $sale < $reg) ? $sale : $reg;
}
$total = $unit_price * $qty;

get_header();
?>
<style>
/* ── Breadcrumb reset ── */
.pt-bc { list-style: none !important; counter-reset: none !important; padding: 14px 22px !important; margin: 0 !important; display: flex !important; align-items: center !important; flex-wrap: wrap !important; background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); border: 1px solid #e2e8f5; }
.pt-bc li { display: flex !important; align-items: center !important; font-size: 13px; }
.pt-bc li::before { display: none !important; content: none !important; }
.pt-bc a { color: var(--primary); text-decoration: none; font-weight: 500; }
.pt-bc .sep { color: #ccc; margin: 0 6px; font-size: 15px; }
.pt-bc .cur { color: #666; font-weight: 600; }

/* ── Page wrapper ── */
.pt-bn-hero { background: linear-gradient(135deg, #0f2460 0%, #28a745 200%); color: #fff; padding: 36px 20px; text-align: center; clear: both !important; width: 100% !important; display: block !important; }
.pt-bn-hero h1 { font-size: 28px; font-weight: 800; margin: 0 0 4px; }
.pt-bn-hero p  { margin: 0; opacity: .8; font-size: 14px; }
.pt-bn-page { background: #f4f7fe; padding: 36px 0 70px; min-height: 60vh; clear: both !important; width: 100% !important; display: block !important; box-sizing: border-box; }

/* ── Two-column grid ── */
.pt-bn-container { max-width: 1000px !important; width: 100% !important; margin: 0 auto !important; padding: 0 20px !important; box-sizing: border-box !important; position: relative !important; float: none !important; left: 0 !important; right: 0 !important; }
.pt-bn-grid { display: grid !important; grid-template-columns: 1fr 320px !important; gap: 30px !important; align-items: start !important; width: 100% !important; box-sizing: border-box; }
@media(max-width: 991px) { .pt-bn-grid { grid-template-columns: 1fr !important; } }

/* ── Card ── */
.pt-bn-card { background: #fff; border-radius: 14px; box-shadow: 0 4px 20px rgba(26,86,219,.08); border: 1px solid #e2e8f5; padding: 24px; }
.pt-bn-card h2 { font-size: 16px; font-weight: 700; color: #1e2a3b; margin: 0 0 18px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f5; display: flex; align-items: center; gap: 8px; }

/* ── Form ── */
.pt-fg { margin-bottom: 20px !important; width: 100% !important; display: block !important; clear: both !important; box-sizing: border-box !important; }
.pt-fg label { display: block !important; font-size: 13px !important; font-weight: 700 !important; color: #334155 !important; margin-bottom: 8px !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; width: 100% !important; }
.pt-fg input, .pt-fg select, .pt-fg textarea {
    width: 100% !important; display: block !important; padding: 12px 14px !important; border: 1.5px solid #cbd5e1 !important; border-radius: 8px !important;
    font-size: 15px !important; color: #1e293b !important; background: #fff !important; transition: all .2s !important;
    box-sizing: border-box !important; font-family: inherit !important; height: auto !important;
}
.pt-fg input:focus, .pt-fg select:focus, .pt-fg textarea:focus { outline: none !important; border-color: #1a56db !important; box-shadow: 0 0 0 3px rgba(26,86,219,0.1) !important; }
.pt-fg-half { display: flex !important; gap: 20px !important; width: 100% !important; box-sizing: border-box !important; }
.pt-fg-half .pt-fg { flex: 1 !important; margin-bottom: 0 !important; }
@media(max-width: 600px) { .pt-fg-half { flex-direction: column !important; gap: 20px !important; } }

/* ── Order summary ── */
.pt-os-img { width: 100%; height: 150px; border-radius: 10px; overflow: hidden; background: #f0f4f8; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.pt-os-img img { width: 100%; height: 100%; object-fit: cover; }
.pt-os-row { display: flex; justify-content: space-between; font-size: 14px; color: #1e2a3b; margin-bottom: 8px; }
.pt-os-total { font-size: 18px; font-weight: 800; color: #1a56db; border-top: 2px solid #e2e8f5; padding-top: 12px; margin-top: 6px; }
.pt-place-btn { width: 100%; padding: 14px; font-size: 15px; font-weight: 700; background: #28a745; color: #fff; border: none; border-radius: 10px; cursor: pointer; margin-top: 16px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: .2s; }
.pt-place-btn:hover { background: #1e7e34; }
.pt-success { display: none; background: #f0fff4; border: 1px solid #c6f6d5; border-radius: 12px; padding: 28px; text-align: center; margin-top: 16px; }
.pt-success i { font-size: 44px; color: #28a745; margin-bottom: 10px; display: block; }
</style>

<div class="pt-bn-hero">
    <h1>⚡ Buy Now</h1>
    <p><?php echo $product ? 'Complete your purchase below' : 'No product selected'; ?></p>
</div>

<div class="pt-bn-page">
    <div class="pt-bn-container">

        <?php if ($product): ?>

        <!-- Breadcrumb -->
        <nav style="margin-bottom:22px;">
            <ul class="pt-bc">
                <li><a href="<?php echo home_url(); ?>"><i class="fa fa-home"></i> Home</a></li>
                <li><span class="sep">›</span><a href="<?php echo home_url('/products'); ?>">Products</a></li>
                <li><span class="sep">›</span><a href="<?php echo home_url('/product/' . $product->slug); ?>"><?php echo esc_html($product->title); ?></a></li>
                <li><span class="sep">›</span><span class="cur">Buy Now</span></li>
            </ul>
        </nav>

        <div class="pt-bn-grid">

            <!-- ─── LEFT: Customer Form ─── -->
            <div class="pt-bn-card">
                <h2><i class="fa fa-user" style="color:var(--primary);"></i> Your Details</h2>
                <form id="pt-bn-form" onsubmit="return false;">
                    <div class="pt-fg-half">
                        <div class="pt-fg">
                            <label>Full Name *</label>
                            <input type="text" name="name" placeholder="John Doe" required autocomplete="name" />
                        </div>
                        <div class="pt-fg">
                            <label>Phone *</label>
                            <input type="tel" name="phone" placeholder="+91 98765 43210" required autocomplete="tel" />
                        </div>
                    </div>
                    <div class="pt-fg">
                        <label>Email *</label>
                        <input type="email" name="email" placeholder="you@example.com" required autocomplete="email" />
                    </div>
                    <div class="pt-fg">
                        <label>Delivery Address *</label>
                        <textarea name="address" rows="3" placeholder="Flat No., Street, City, State, PIN" required></textarea>
                    </div>
                    <div class="pt-fg-half">
                        <div class="pt-fg">
                            <label>City *</label>
                            <input type="text" name="city" placeholder="Bhubaneswar" required />
                        </div>
                        <div class="pt-fg">
                            <label>PIN Code</label>
                            <input type="text" name="pin" placeholder="751001" maxlength="6" />
                        </div>
                    </div>
                    <div class="pt-fg">
                        <label>Payment Method *</label>
                        <select name="payment" required>
                            <option value="">— Select —</option>
                            <option value="upi">UPI / GPay / PhonePe</option>
                            <option value="card">Credit / Debit Card</option>
                            <option value="netbanking">Net Banking</option>
                            <option value="cod">Cash on Delivery</option>
                        </select>
                    </div>
                    <div class="pt-fg">
                        <label>Special Instructions <span style="font-weight:400;text-transform:none;">(optional)</span></label>
                        <textarea name="notes" rows="2" placeholder="Any special delivery instructions…"></textarea>
                    </div>
                </form>
            </div>

            <!-- ─── RIGHT: Order Summary ─── -->
            <div>
                <div class="pt-bn-card">
                    <h2><i class="fa fa-receipt" style="color:var(--primary);"></i> Order Summary</h2>

                    <div class="pt-os-img">
                        <?php if ($product_img): ?>
                            <img src="<?php echo esc_url($product_img); ?>" alt="<?php echo esc_attr($product->title); ?>" />
                        <?php else: ?>
                            <i class="fa fa-image" style="font-size:36px;color:#c8d5e8;"></i>
                        <?php endif; ?>
                    </div>

                    <div class="pt-os-row" style="font-weight:700;font-size:15px;">
                        <span><?php echo esc_html($product->title); ?></span>
                    </div>
                    <?php if ($location_name): ?>
                    <div class="pt-os-row" style="color:#6b7892;font-size:13px;margin-bottom:14px;">
                        <span><i class="fa fa-map-marker-alt" style="color:var(--primary);margin-right:4px;"></i><?php echo esc_html($location_name); ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="pt-os-row"><span>Unit Price</span><span>₹<?php echo number_format($unit_price, 2); ?></span></div>
                    <div class="pt-os-row"><span>Quantity</span><span><?php echo $qty; ?></span></div>
                    <?php if (!empty($product->price_sale) && $product->price_sale < $product->price_regular): ?>
                    <div class="pt-os-row" style="color:#e74c3c;"><span>Savings</span><span>− ₹<?php echo number_format(($product->price_regular - $product->price_sale) * $qty, 2); ?></span></div>
                    <?php endif; ?>
                    <div class="pt-os-row" style="color:#6b7892;font-size:13px;"><span>Taxes</span><span>Included</span></div>
                    <div class="pt-os-row pt-os-total"><span>Total</span><span>₹<?php echo number_format($total, 2); ?></span></div>

                    <button class="pt-place-btn" id="pt-place-btn" onclick="placeOrder()">
                        <i class="fa fa-check-circle"></i> Place Order
                    </button>

                    <p style="margin-top:14px;text-align:center;font-size:11px;color:#999;">
                        <i class="fa fa-shield-alt" style="color:#28a745;margin-right:3px;"></i>Secure &amp; Encrypted &nbsp;|&nbsp;
                        <i class="fa fa-headset" style="color:var(--primary);margin-right:3px;"></i>1800 120 8464
                    </p>
                </div>

                <div class="pt-success" id="pt-success">
                    <i class="fa fa-check-circle"></i>
                    <h3 style="font-size:18px;font-weight:700;color:#1e2a3b;margin-bottom:6px;">Order Placed!</h3>
                    <p style="color:#6b7892;font-size:13px;margin-bottom:14px;">Our team will contact you shortly to confirm.</p>
                    <a href="<?php echo home_url('/products'); ?>" style="display:inline-block;padding:10px 22px;background:#28a745;color:#fff;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;">
                        <i class="fa fa-store" style="margin-right:5px;"></i>Continue Shopping
                    </a>
                </div>
            </div>

        </div><!-- .pt-bn-grid -->

        <?php else: ?>
        <div style="text-align:center;padding:80px 20px;color:#6b7892;">
            <i class="fa fa-box-open" style="font-size:60px;color:#d0daf0;margin-bottom:18px;display:block;"></i>
            <h3 style="font-size:20px;color:#1e2a3b;margin-bottom:8px;">No Product Selected</h3>
            <a href="<?php echo home_url('/products'); ?>" style="display:inline-block;margin-top:14px;padding:12px 24px;background:#1a56db;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;">
                <i class="fa fa-store" style="margin-right:5px;"></i> Browse Products
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function placeOrder() {
    var form = document.getElementById('pt-bn-form');
    if (!form) return;
    var fields = form.querySelectorAll('[required]');
    var ok = true;
    fields.forEach(function(f) {
        if (!f.value.trim()) { f.style.borderColor = '#e74c3c'; ok = false; }
        else f.style.borderColor = '#e2e8f5';
    });
    if (!ok) { alert('Please fill in all required fields.'); return; }
    var btn = document.getElementById('pt-place-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Placing Order…';
    setTimeout(function() {
        btn.style.display = 'none';
        var s = document.getElementById('pt-success');
        s.style.display = 'block';
        s.scrollIntoView({ behavior: 'smooth' });
    }, 1800);
}
</script>

<?php get_footer(); ?>
