<?php
/**
 * OrderPrintex - Commercial Products Catalog & Category Workbench
 * Premium E-Commerce Style Showcase featuring industrial transfers, garments & merchandise.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/catalog_helper.php';

// Filter parameters
$categorySlug = trim($_GET['category'] ?? '');
$searchTerm = trim($_GET['search'] ?? '');

// 301 Redirect legacy query strings to clean URLs
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($requestUri, 'products.php') !== false) {
    if (!empty($categorySlug)) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . category_url($categorySlug));
        exit;
    } elseif (empty($searchTerm)) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . url('products'));
        exit;
    }
}

$categories = get_curated_catalog_categories();
$currentCategory = null;

// Validate category slug if provided
if (!empty($categorySlug)) {
    // 1. Check in curated categories
    foreach ($categories as $cat) {
        if (($cat['slug'] ?? '') === $categorySlug) {
            $currentCategory = $cat;
            break;
        }
    }

    // 2. Check in database categories
    if (!$currentCategory) {
        $dbCat = db_fetch_one("SELECT * FROM categories WHERE slug = :s", ['s' => $categorySlug]);
        if ($dbCat) {
            $currentCategory = $dbCat;
        }
    }

    // If still not found, return HTTP 404
    if (!$currentCategory) {
        http_response_code(404);
        require __DIR__ . '/404.php';
        exit;
    }
}

// SEO Metadata
if ($currentCategory) {
    $pageTitle = e($currentCategory['name']) . " | Industrial Transfers & Printing Catalog | OrderPrintex";
    $pageDesc  = !empty($currentCategory['description']) 
        ? e($currentCategory['description']) 
        : ("Explore high-precision " . e($currentCategory['name']) . " manufactured in New Delhi with express PAN-India delivery.");
    $canonical = category_url($categorySlug);
} else {
    $pageTitle = "Commercial Print Products & Transfers Catalog | OrderPrintex Delhi";
    $pageDesc  = "Explore OrderPrintex's commercial catalog: 22-inch DTF gang sheets, UV DTF decal transfers, bio-washed cotton tees, thermal drinkware, and luxury apparel packaging.";
    $canonical = url('products');
}

$pageSchemaKey = 'products';
require_once __DIR__ . '/includes/header.php';

$products = get_catalog_products($categorySlug, $searchTerm);
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span><?= $currentCategory ? e($currentCategory['name']) : 'OUR PRODUCTS' ?></span>
      </div>
      <h1 class="page-intro-title">
        <?= $currentCategory ? e($currentCategory['name']) : 'DTF Transfers, UV Decals &amp; Custom Merchandise' ?>
      </h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        <?= $currentCategory && !empty($currentCategory['description']) 
            ? e($currentCategory['description']) 
            : 'Browse ready-to-order prints, tees and branded gifts made in our Delhi workshop. Pick a product, or ask us for a custom quote.' ?>
      </p>
    </div>
  </div>
</section>

<!-- Filter Toolbar -->
<section class="section section-white" style="padding-top:2.75rem; padding-bottom:1.5rem;">
  <div class="container">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; border-bottom:1px solid var(--border-light); padding-bottom:2rem;">
      <!-- Category Badges -->
      <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
        <a href="<?= url('products/') ?>" 
           class="gallery-filter-btn <?= empty($categorySlug) ? 'active' : '' ?>">
          All Products
        </a>
        <?php foreach ($categories as $cat): ?>
          <?php if (!empty($cat['slug'])): ?>
            <a href="<?= category_url($cat['slug']) ?>" 
               class="gallery-filter-btn <?= ($categorySlug === $cat['slug']) ? 'active' : '' ?>">
              <?= e($cat['name']) ?>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <!-- Search Box -->
      <form action="<?= url('products/') ?>" method="GET" class="product-filter-search-form" style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap; max-width:100%;">
        <?php if ($categorySlug): ?>
          <input type="hidden" name="category" value="<?= e($categorySlug) ?>">
        <?php endif; ?>
        <input 
          type="text" 
          name="search" 
          value="<?= e($searchTerm) ?>" 
          class="form-control product-filter-search-input" 
          placeholder="Search products..." 
          style="width:240px; max-width:100%; flex:1 1 180px; padding:0.6rem 0.95rem; border-radius:var(--radius-full); border:1.5px solid var(--border-light);"
        >
        <button type="submit" class="btn btn-sm btn-navy">Search</button>
        <?php if ($searchTerm || $categorySlug): ?>
          <a href="<?= url('products/') ?>" class="btn btn-sm btn-outline">Reset</a>
        <?php endif; ?>
      </form>
    </div>
  </div>
</section>

<!-- Products Grid Section -->
<section class="section section-white" style="padding-top:1.5rem; padding-bottom:5rem;">
  <div class="container">
    <?php if (empty($products)): ?>
      <div class="text-center" style="padding:4.5rem 1.5rem; background-color:var(--ivory); border-radius:var(--radius-lg); border:1.5px dashed var(--border-gold);">
        <h3 style="color:var(--navy); margin-bottom:0.75rem;">No products match your criteria</h3>
        <p style="color:var(--slate); max-width:480px; margin:0 auto 1.5rem;">
          Try clearing your search query or select another category to view available transfers and apparel.
        </p>
        <a href="<?= url('products/') ?>" class="btn btn-navy">View All Products</a>
      </div>
    <?php else: ?>
      <div class="products-grid">
        <?php foreach ($products as $idx => $prod): 
          $badgeClass = $prod['badge_class'] ?? 'badge-gold';
          $badgeText = $prod['badge'] ?? $prod['category_name'];
        ?>
          <article class="product-card reveal slide-up stagger-<?= ($idx % 4) + 1 ?>">
            <div class="product-media" style="position:relative;">
              <span class="product-badge-tag <?= e($badgeClass) ?>">
                <?= e($badgeText) ?>
              </span>
              <img src="<?= url(e($prod['image_url'])) ?>" alt="<?= e($prod['title']) ?> - Industrial Commercial Printing" loading="lazy">
            </div>
            <div class="product-body">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
                <span class="product-category-label"><?= e($prod['category_name']) ?></span>
              </div>
              <h3 class="product-title">
                <a href="<?= product_url($prod['slug']) ?>" title="View details for <?= e($prod['title']) ?>">
                  <?= e($prod['title']) ?>
                </a>
              </h3>
              <p class="product-desc"><?= e($prod['short_desc']) ?></p>

              <?php if (!empty($prod['specifications']) && ($prod['slug'] === 'bookmark-03' || strpos(strtolower($prod['title']), 'bookmark') !== false)): ?>
                <div class="product-specs-chip">
                  <?= e($prod['specifications']) ?>
                </div>
              <?php endif; ?>

              <!-- Pricing Row -->
              <div class="product-pricing-row">
                <div class="product-price-box">
                  <span class="price-label">From</span>
                  <span class="price-val"><?= e($prod['starting_price'] ?? '₹249') ?></span>
                  <span class="price-unit"><?= e($prod['price_unit'] ?? '') ?></span>
                </div>
                <?php if (!empty($prod['min_order'])): ?>
                  <span class="product-min-order"><?= e($prod['min_order']) ?></span>
                <?php endif; ?>
              </div>

              <div class="product-card-actions" style="margin-top:0.75rem;">
                <a href="<?= product_url($prod['slug']) ?>" class="btn btn-outline" aria-label="View details for <?= e($prod['title']) ?>">
                  View Details
                </a>
                <a href="<?= url('get-quote/?service_slug=' . urlencode($prod['service_slug'] ?? '')) ?>" class="btn btn-primary" aria-label="Request quote for <?= e($prod['title']) ?>">
                  Get Quote
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Commercial Notice -->
    <div style="margin-top:3.5rem; padding:1.25rem 1.65rem; background-color:#181818; border-radius:8px; border:1px solid #40351E; border-left:3px solid #D4AF37; font-size:0.86rem; color:#B8B1A3;">
      <strong style="color:#FFD76A;">Commercial Notice:</strong> Displayed catalog items, custom packaging representations, and material specifications illustrate commercial capabilities at our New Delhi printing facility. Final volume quotations, minimum quantities, and turnarounds are confirmed upon artwork proof approval.
    </div>
  </div>
</section>

<!-- Wholesale Gang Roll Banner -->
<section class="section section-navy text-center" style="padding-top:4.5rem; padding-bottom:4.5rem; border-top:2px solid var(--border-gold);">
  <div class="container container-narrow reveal scale-in">
    <div class="section-label" style="justify-content:center; color:var(--beige);">FOR PRINT SHOPS &amp; RESELLERS</div>
    <h2 style="font-size:clamp(2rem, 3.2vw, 2.6rem); margin-bottom:1rem; color:#FFFFFF;">
      Need Regular Gang Rolls for Your Print Shop?
    </h2>
    <p style="color:rgba(237,225,210,0.85); margin-bottom:2.25rem; font-size:1.05rem; line-height:1.7;">
      We supply screen printers, DTG studios, merchandisers and clothing labels across India with ongoing roll orders. Share your artwork and we’ll get it print-ready fast.
    </p>
    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="<?= url('contact/') ?>" class="btn btn-primary btn-lg">
        <span>Ask About Wholesale Rates</span> &rarr;
      </a>
      <a href="https://wa.me/919354028199?text=Hi%2C%20I%20would%20like%20to%20enquire%20about%20wholesale%20gang%20roll%20supply." target="_blank" rel="noopener" class="btn btn-outline-white btn-lg">
        Chat on WhatsApp
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
