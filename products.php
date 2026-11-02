<?php
/**
 * OrderPrintex - Commercial Products Catalog
 * Premium E-Commerce Style Showcase featuring industrial transfers, garments & merchandise.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/catalog_helper.php';

$pageTitle = "Commercial Print Products & Transfers Catalog | OrderPrintex Delhi";
$pageDesc  = "Explore OrderPrintex's commercial catalog: 22-inch DTF gang sheets, UV DTF decal transfers, bio-washed cotton tees, thermal drinkware, and luxury apparel packaging.";
$canonical = url('products.php');

require_once __DIR__ . '/includes/header.php';

// Filter parameters
$categorySlug = trim($_GET['category'] ?? '');
$searchTerm = trim($_GET['search'] ?? '');

$categories = get_curated_catalog_categories();
$products = get_catalog_products($categorySlug, $searchTerm);
?>

<!-- Hero Header -->
<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem; position:relative; overflow:hidden;">
  <div class="container text-center reveal slide-up" style="position:relative; z-index:2;">
    <div class="section-label" style="justify-content:center; color:var(--beige);">Commercial Print Catalog</div>
    <h1 style="font-size:clamp(2.4rem, 4.2vw, 3.4rem); margin-bottom:1rem; letter-spacing:-0.02em; color:#FFFFFF;">
      Print Products &amp; Custom Transfers
    </h1>
    <p class="section-subtitle" style="color:rgba(237, 225, 210, 0.9); margin:0 auto; max-width:760px; font-size:1.1rem; line-height:1.7;">
      Precision-manufactured DTF gang rolls, multi-surface UV decals, bio-washed apparel, and corporate merchandise engineered at our New Delhi facility.
    </p>
  </div>
</section>

<!-- Filter Toolbar -->
<section class="section section-white" style="padding-top:2.75rem; padding-bottom:1.5rem;">
  <div class="container">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; border-bottom:1px solid var(--border-light); padding-bottom:2rem;">
      <!-- Category Badges -->
      <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
        <?php foreach ($categories as $cat): ?>
          <a href="<?= empty($cat['slug']) ? url('products.php') : url('products.php?category=' . urlencode($cat['slug'])) ?>" 
             class="gallery-filter-btn <?= ($categorySlug === $cat['slug']) ? 'active' : '' ?>">
            <?= e($cat['name']) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Search Box -->
      <form action="<?= url('products.php') ?>" method="GET" style="display:flex; gap:0.5rem; align-items:center;">
        <?php if ($categorySlug): ?>
          <input type="hidden" name="category" value="<?= e($categorySlug) ?>">
        <?php endif; ?>
        <input 
          type="text" 
          name="search" 
          value="<?= e($searchTerm) ?>" 
          class="form-control" 
          placeholder="Search products or specs..." 
          style="width:240px; padding:0.6rem 0.95rem; border-radius:var(--radius-full); border:1.5px solid var(--border-light);"
        >
        <button type="submit" class="btn btn-sm btn-navy">Search</button>
        <?php if ($searchTerm || $categorySlug): ?>
          <a href="<?= url('products.php') ?>" class="btn btn-sm btn-outline">Reset</a>
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
        <h3 style="color:var(--navy); margin-bottom:0.75rem;">No products match your search</h3>
        <p style="color:var(--slate); max-width:480px; margin:0 auto 1.5rem;">
          Try clearing your search query or select another category to view available transfers and apparel.
        </p>
        <a href="<?= url('products.php') ?>" class="btn btn-navy">View All Products</a>
      </div>
    <?php else: ?>
      <div class="products-grid">
        <?php foreach ($products as $idx => $prod): 
          $badgeClass = $prod['badge_class'] ?? 'badge-gold';
          $badgeText = $prod['badge'] ?? $prod['category_name'];
        ?>
          <article class="product-card reveal slide-up stagger-<?= ($idx % 4) + 1 ?>">
            <div class="product-media">
              <span class="product-badge-tag <?= e($badgeClass) ?>">
                <?= e($badgeText) ?>
              </span>
              <img src="<?= url(e($prod['image_url'])) ?>" alt="<?= e($prod['title']) ?> - Industrial Commercial Printing" loading="lazy">
            </div>
            <div class="product-body">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
                <span class="product-category-label"><?= e($prod['category_name']) ?></span>
                <?php if (!empty($prod['sku'])): ?>
                  <span style="font-size:0.75rem; font-weight:700; color:var(--gold); background:rgba(185,154,97,0.12); padding:2px 8px; border-radius:4px; letter-spacing:0.04em;">
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

              <div class="product-card-actions">
                <a href="<?= product_url($prod['slug']) ?>" class="btn btn-outline" aria-label="View details for <?= e($prod['title']) ?>">
                  View Details
                </a>
                <a href="<?= url('get-quote.php?service_slug=' . urlencode($prod['service_slug'] ?? '')) ?>" class="btn btn-primary" aria-label="Request quote for <?= e($prod['title']) ?>">
                  Order / Quote
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Illustrative Demo Disclaimer -->
    <div style="margin-top:3.5rem; padding:1.25rem 1.65rem; background-color:var(--ivory); border-radius:var(--radius-sm); border-left:3px solid var(--crimson); font-size:0.86rem; color:var(--slate);">
      <strong>Commercial Notice:</strong> Displayed catalog items, custom packaging representations, and material specifications illustrate commercial capabilities at our New Delhi printing facility. Final volume quotations, minimum quantities, and turnarounds are confirmed upon artwork proof approval.
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
      <a href="<?= url('contact.php') ?>" class="btn btn-primary btn-lg">
        <span>Inquire About Wholesale Rates</span> &rarr;
      </a>
      <a href="https://wa.me/919354028199?text=Hi%2C%20I%20would%20like%20to%20enquire%20about%20wholesale%20gang%20roll%20supply." target="_blank" rel="noopener" class="btn btn-outline-white btn-lg">
        Chat With Wholesale Desk
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
