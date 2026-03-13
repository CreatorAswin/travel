<?php
/**
 * Template Name: Products Listing Page
 * Displays all products from the custom pt_products database table
 */

require_once get_template_directory() . '/includes/dynamic-management/products-manager.php';

global $wpdb;

// ── Filters from URL ──────────────────────────────────────────────────────────
$search       = isset($_GET['search'])       ? sanitize_text_field($_GET['search'])       : '';
$filter_type  = isset($_GET['product_type']) ? sanitize_text_field($_GET['product_type']) : '';
$sort         = isset($_GET['sort'])         ? sanitize_text_field($_GET['sort'])         : 'newest';
$paged        = isset($_GET['paged'])        ? absint($_GET['paged'])                     : 1;
$per_page     = 12;
$offset       = ($paged - 1) * $per_page;

// ── Build SQL ─────────────────────────────────────────────────────────────────
$where   = ['is_active = 1'];
$values  = [];

if ($search) {
    $where[]  = '(title LIKE %s OR short_description LIKE %s OR description LIKE %s)';
    $like     = '%' . $wpdb->esc_like($search) . '%';
    $values[] = $like;
    $values[] = $like;
    $values[] = $like;
}

if ($filter_type) {
    $where[]  = 'product_type = %s';
    $values[] = $filter_type;
}

$order_by = match ($sort) {
    'price_low'  => 'price_regular ASC',
    'price_high' => 'price_regular DESC',
    'name_az'    => 'title ASC',
    'name_za'    => 'title DESC',
    default      => 'created_at DESC',
};

$where_sql = 'WHERE ' . implode(' AND ', $where);
$table     = $wpdb->prefix . 'pt_products';

// Count total
$count_sql = "SELECT COUNT(*) FROM {$table} {$where_sql}";
if ($values) {
    $count_sql = $wpdb->prepare($count_sql, $values);
}
$total_products = (int) $wpdb->get_var($count_sql);
$total_pages    = ceil($total_products / $per_page);

// Fetch products
$data_sql = "SELECT * FROM {$table} {$where_sql} ORDER BY {$order_by} LIMIT %d OFFSET %d";
$values[] = $per_page;
$values[] = $offset;
$products = $wpdb->get_results($wpdb->prepare($data_sql, $values));

// Get all distinct product types for filter dropdown
$types = $wpdb->get_col("SELECT DISTINCT product_type FROM {$table} WHERE is_active = 1 AND product_type != '' ORDER BY product_type ASC");

get_header();
?>

<style>
/* ── Variables ─────────────────────────────────────────── */
:root {
    --products-primary: #1a56db;
    --products-primary-dark: #1140a0;
    --products-accent: #f97316;
    --products-bg: #f4f7fe;
    --products-card-bg: #ffffff;
    --products-text: #1e2a3b;
    --products-text-muted: #6b7892;
    --products-border: #e2e8f5;
    --products-shadow: 0 4px 20px rgba(26,86,219,0.08);
    --products-shadow-hover: 0 12px 40px rgba(26,86,219,0.18);
    --products-radius: 14px;
}

/* ── Page wrapper ───────────────────────────────────────── */
.products-page { background: var(--products-bg); padding: 0 0 60px; }

/* ── Hero banner ────────────────────────────────────────── */
.products-hero {
    background: linear-gradient(135deg, #0f2460 0%, #1a56db 60%, #3b82f6 100%);
    color: #fff;
    padding: 60px 20px 50px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.products-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.products-hero h1 { font-size: 42px; font-weight: 800; margin: 0 0 12px; letter-spacing: -0.5px; position: relative; }
.products-hero p  { font-size: 17px; opacity: .85; margin: 0; position: relative; }

/* ── Controls bar ───────────────────────────────────────── */
.products-controls {
    background: #fff;
    border-bottom: 1px solid var(--products-border);
    padding: 18px 0;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.products-controls .container { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
.products-search-form { display: flex; flex: 1; min-width: 220px; }
.products-search-form input {
    flex: 1;
    padding: 10px 16px;
    border: 1.5px solid var(--products-border);
    border-right: none;
    border-radius: 8px 0 0 8px;
    font-size: 14px;
    outline: none;
    transition: border-color .2s;
}
.products-search-form input:focus { border-color: var(--products-primary); }
.products-search-form button {
    padding: 10px 18px;
    background: var(--products-primary);
    color: #fff;
    border: none;
    border-radius: 0 8px 8px 0;
    cursor: pointer;
    font-size: 15px;
    transition: background .2s;
}
.products-search-form button:hover { background: var(--products-primary-dark); }
.products-filter-select {
    padding: 10px 14px;
    border: 1.5px solid var(--products-border);
    border-radius: 8px;
    font-size: 14px;
    color: var(--products-text);
    background: #fff;
    cursor: pointer;
    outline: none;
    transition: border-color .2s;
}
.products-filter-select:focus { border-color: var(--products-primary); }
.products-count { margin-left: auto; font-size: 13px; color: var(--products-text-muted); white-space: nowrap; }

/* ── Grid ───────────────────────────────────────────────── */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-top: 36px;
}

/* ── Card ───────────────────────────────────────────────── */
.product-card {
    background: var(--products-card-bg);
    border-radius: var(--products-radius);
    overflow: hidden;
    box-shadow: var(--products-shadow);
    border: 1px solid var(--products-border);
    transition: transform .28s ease, box-shadow .28s ease;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
}
.product-card:hover { transform: translateY(-6px); box-shadow: var(--products-shadow-hover); text-decoration: none; color: inherit; }

.product-card-img {
    height: 200px;
    background: linear-gradient(135deg, #e8eef8 0%, #d4dff5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}
.product-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
.product-card:hover .product-card-img img { transform: scale(1.06); }
.product-card-img .no-img { color: #b5c3dd; font-size: 48px; }

.product-card-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: var(--products-primary);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.product-card-badge.sale { background: #e74c3c; }

.product-card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
.product-card-title {
    font-size: 17px;
    font-weight: 700;
    color: var(--products-text);
    margin: 0 0 8px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.product-card-desc {
    font-size: 13px;
    color: var(--products-text-muted);
    margin: 0 0 14px;
    line-height: 1.55;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}
.product-card-meta { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
.product-card-price { font-size: 20px; font-weight: 800; color: var(--products-primary); }
.product-card-price small { font-size: 12px; font-weight: 500; color: var(--products-text-muted); text-decoration: line-through; margin-left: 4px; }
.product-card-btn {
    background: var(--products-primary);
    color: #fff;
    border: none;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
    text-decoration: none;
}
.product-card-btn:hover { background: var(--products-primary-dark); color: #fff; }

/* ── Empty state ────────────────────────────────────────── */
.products-empty {
    text-align: center;
    padding: 80px 20px;
    color: var(--products-text-muted);
}
.products-empty i { font-size: 64px; color: #d0daf0; margin-bottom: 20px; display: block; }
.products-empty h3 { font-size: 22px; color: var(--products-text); margin-bottom: 8px; }

/* ── Pagination ─────────────────────────────────────────── */
.products-pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 48px; flex-wrap: wrap; }
.products-pagination a, .products-pagination span {
    display: inline-flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 8px;
    font-size: 14px; font-weight: 600;
    border: 1.5px solid var(--products-border);
    text-decoration: none; color: var(--products-primary);
    transition: all .2s;
    background: #fff;
}
.products-pagination a:hover { background: var(--products-primary); color: #fff; border-color: var(--products-primary); }
.products-pagination span.current { background: var(--products-primary); color: #fff; border-color: var(--products-primary); }

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 768px) {
    .products-hero h1 { font-size: 28px; }
    .products-controls .container { flex-direction: column; align-items: stretch; }
    .products-count { margin-left: 0; }
    .products-grid { grid-template-columns: 1fr 1fr; gap: 16px; }
}
@media (max-width: 480px) {
    .products-grid { grid-template-columns: 1fr; }
}
</style>

<div class="products-page">

    <!-- ── Hero ────────────────────────────────────────────────── -->
    <div class="products-hero">
        <h1>🛍️ Our Products</h1>
        <p>Browse our full range of <?php echo $total_products; ?> product<?php echo $total_products !== 1 ? 's' : ''; ?></p>
    </div>

    <!-- ── Controls ────────────────────────────────────────────── -->
    <div class="products-controls">
        <div class="container">
            <form class="products-search-form" method="get">
                <input type="text" name="search" value="<?php echo esc_attr($search); ?>" placeholder="Search products…" />
                <?php if ($filter_type): ?>
                    <input type="hidden" name="product_type" value="<?php echo esc_attr($filter_type); ?>" />
                <?php endif; ?>
                <?php if ($sort): ?>
                    <input type="hidden" name="sort" value="<?php echo esc_attr($sort); ?>" />
                <?php endif; ?>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

            <form method="get" id="products-filter-form">
                <?php if ($search): ?>
                    <input type="hidden" name="search" value="<?php echo esc_attr($search); ?>" />
                <?php endif; ?>
                <select class="products-filter-select" name="product_type" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <?php foreach ($types as $t): ?>
                        <option value="<?php echo esc_attr($t); ?>" <?php selected($filter_type, $t); ?>>
                            <?php echo esc_html(ucfirst(str_replace('_', ' ', $t))); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select class="products-filter-select" name="sort" onchange="this.form.submit()">
                    <option value="newest"     <?php selected($sort, 'newest'); ?>>Newest First</option>
                    <option value="price_low"  <?php selected($sort, 'price_low'); ?>>Price: Low → High</option>
                    <option value="price_high" <?php selected($sort, 'price_high'); ?>>Price: High → Low</option>
                    <option value="name_az"    <?php selected($sort, 'name_az'); ?>>Name: A → Z</option>
                    <option value="name_za"    <?php selected($sort, 'name_za'); ?>>Name: Z → A</option>
                </select>
            </form>

            <div class="products-count">
                Showing <?php echo min($offset + 1, $total_products); ?>–<?php echo min($offset + $per_page, $total_products); ?> of <?php echo $total_products; ?> products
            </div>
        </div>
    </div>

    <!-- ── Main Content ────────────────────────────────────────── -->
    <div class="container" style="padding: 0 20px;">

        <?php if ($products): ?>
            <div class="products-grid">
                <?php foreach ($products as $prod): ?>
                    <?php
                    // Image
                    $img = $prod->featured_image ?? '';
                    if (empty($img) && !empty($prod->gallery_images)) {
                        $first = explode(',', $prod->gallery_images);
                        $img   = trim($first[0]);
                    }

                    // Price display
                    $has_sale = !empty($prod->price_sale) && $prod->price_sale > 0 && $prod->price_sale < $prod->price_regular;
                    $display_price = $has_sale ? $prod->price_sale : $prod->price_regular;

                    // Link
                    $link = home_url('/product/' . $prod->slug);
                    ?>
                    <a href="<?php echo esc_url($link); ?>" class="product-card">
                        <div class="product-card-img">
                            <?php if ($img): ?>
                                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($prod->title); ?>" loading="lazy" />
                            <?php else: ?>
                                <i class="fa fa-image no-img"></i>
                            <?php endif; ?>

                            <?php if (!empty($prod->product_type)): ?>
                                <span class="product-card-badge <?php echo $has_sale ? 'sale' : ''; ?>">
                                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $prod->product_type))); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="product-card-body">
                            <h3 class="product-card-title"><?php echo esc_html($prod->title); ?></h3>
                            <?php if (!empty($prod->short_description)): ?>
                                <p class="product-card-desc"><?php echo esc_html($prod->short_description); ?></p>
                            <?php endif; ?>

                            <div class="product-card-meta">
                                <div class="product-card-price">
                                    <i class="fa fa-rupee-sign" style="font-size:15px;"></i><?php echo number_format($display_price, 2); ?>
                                    <?php if ($has_sale): ?>
                                        <small>₹<?php echo number_format($prod->price_regular, 2); ?></small>
                                    <?php endif; ?>
                                </div>
                                <span class="product-card-btn">View Details</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="products-pagination">
                    <?php
                    $base_url = strtok($_SERVER['REQUEST_URI'], '?');
                    $params   = $_GET;
                    unset($params['paged']);

                    // Previous
                    if ($paged > 1):
                        $params['paged'] = $paged - 1;
                    ?>
                        <a href="<?php echo esc_url($base_url . '?' . http_build_query($params)); ?>"><i class="fa fa-chevron-left"></i></a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $paged - 2); $i <= min($total_pages, $paged + 2); $i++): ?>
                        <?php if ($i === $paged): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <?php $params['paged'] = $i; ?>
                            <a href="<?php echo esc_url($base_url . '?' . http_build_query($params)); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php
                    // Next
                    if ($paged < $total_pages):
                        $params['paged'] = $paged + 1;
                    ?>
                        <a href="<?php echo esc_url($base_url . '?' . http_build_query($params)); ?>"><i class="fa fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="products-empty">
                <i class="fa fa-search"></i>
                <h3><?php echo $search || $filter_type ? 'No products match your search' : 'No products found'; ?></h3>
                <p><?php echo $search || $filter_type ? 'Try adjusting your filters or search term.' : 'Products will appear here once they are added.'; ?></p>
                <?php if ($search || $filter_type): ?>
                    <a href="<?php echo esc_url(home_url('/products')); ?>" style="display:inline-block;margin-top:16px;padding:10px 24px;background:var(--products-primary);color:#fff;border-radius:8px;text-decoration:none;font-weight:600;">
                        Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
