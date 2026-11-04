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
        header('Location: ' . url('products/'));
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
    $canonical = url('products/');
}

require_once __DIR__ . '/includes/header.php';

$products = get_catalog_products($categorySlug, $searchTerm);
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span><?= $currentCategory ? e($currentCategory['name']) : 'Commercial Print Catalog' ?></span>
      </div>
      <h1 class="page-intro-title">
        <?= $currentCategory ? e($currentCategory['name']) : 'Print Products &amp; Custom Transfers' ?>
      </h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        <?= $currentCategory && !empty($currentCategory['description']) 
            ? e($currentCategory['description']) 
            : 'Precision-manufactured DTF gang rolls, multi-surface UV decals, bio-washed apparel, and corporate merchandise engineered at our New Delhi facility.' ?>
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
          placeholder="Search products or specs..." 
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
              <button type="button" class="btn-wishlist" onclick="event.stopPropagation(); toggleWishlist(<?= $prod['id'] ?>, this)" title="Save to Wishlist" style="position:absolute; top:12px; right:12px; z-index:5;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </button>
              <img src="<?= url(e($prod['image_url'])) ?>" alt="<?= e($prod['title']) ?> - Industrial Commercial Printing" loading="lazy">
            </div>
            <div class="product-body">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
                <span class="product-category-label"><?= e($prod['category_name']) ?></span>
                <?php if (!empty($prod['sku'])): ?>
                  <span class="sku-badge">
                    SKU: <?= e($prod['sku']) ?>
                  </span>
                <?php endif; ?>
              </div>
              <h3 class="product-title">
                <a href="<?= product_url($prod['slug']) ?>" title="View details for <?= e($prod['title']) ?>">
                  <?= e($prod['title']) ?>
                </a>
              </h3>
              <p class="product-desc"><?= e($prod['short_desc']) ?></p>

              <?php if (!empty($prod['specifications'])): ?>
                <div class="product-specs-chip">
                  <?= e(mb_strimwidth($prod['specifications'], 0, 85, '...')) ?>
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

              <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.6rem; margin-bottom:0.6rem; padding-top:0.5rem; border-top:1px solid #28221b;">
                <label class="compare-checkbox-label">
                  <input type="checkbox" class="opx-compare-cb" data-product-id="<?= $prod['id'] ?>" onchange="toggleCompareProduct(<?= $prod['id'] ?>, this)">
                  Compare
                </label>
              </div>

              <div class="product-card-actions">
                <a href="<?= product_url($prod['slug']) ?>" class="btn btn-outline" aria-label="View details for <?= e($prod['title']) ?>">
                  View Details
                </a>
                <a href="<?= url('get-quote/?service_slug=' . urlencode($prod['service_slug'] ?? '')) ?>" class="btn btn-primary" aria-label="Request quote for <?= e($prod['title']) ?>">
                  Order / Quote
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
    <div class="section-label" style="justify-content:center; color:var(--beige);">B2B Wholesale Contracts</div>
    <h2 style="font-size:clamp(2rem, 3.2vw, 2.6rem); margin-bottom:1rem; color:#FFFFFF;">
      Need Continuous Gang Roll Dispatch for Your Print Shop?
    </h2>
    <p style="color:rgba(237,225,210,0.85); margin-bottom:2.25rem; font-size:1.05rem; line-height:1.7;">
      We partner with commercial screen printers, DTG studios, corporate merchandisers, and clothing labels across India for ongoing wholesale roll supply with same-day prepress turnaround.
    </p>
    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="<?= url('contact/') ?>" class="btn btn-primary btn-lg">
        <span>Inquire About Wholesale Rates</span> &rarr;
      </a>
      <a href="https://wa.me/919354028199?text=Hi%2C%20I%20would%20like%20to%20enquire%20about%20wholesale%20gang%20roll%20supply." target="_blank" rel="noopener" class="btn btn-outline-white btn-lg">
        Chat With Wholesale Desk
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
