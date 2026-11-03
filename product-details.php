<?php
/**
 * OrderPrintex - Single Product Page & Dynamic Pricing Workbench
 * Features:
 *   - Unique Product SKU with Dynamic Variation updates
 *   - Object-fit contain full product imagery (never cropped)
 *   - Interactive Variation Selector (Size, Colour, Material) updating SKU, Price, Stock & Image
 *   - 6 Sequentially Scrolled Product Sections (No tabs or accordions):
 *       1. Product Overview & Dynamic Pricing Workbench
 *       2. Technical Specifications
 *       3. Artwork & File Guidelines
 *       4. Application & Heat Press Guide
 *       5. Customer Reviews (Interactive ratings & verified submissions)
 *       6. Turnaround & PAN-India Shipping
 *   - Dynamic Quantity Wholesale Discount Tiers
 *   - Schema.org Product and BreadcrumbList JSON-LD
 *   - Safe 404 handling
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/catalog_helper.php';

$slug = trim($_GET['slug'] ?? '');

if (empty($slug)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// 301 Canonical Redirect if accessed directly via old query parameter URL
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($requestUri, 'product-details.php') !== false) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . product_url($slug));
    exit;
}

// Fetch product from database (or catalog helper fallback)
$product = get_catalog_product_by_slug($slug);

if (!$product) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$productId = (int)($product['id'] ?? 1);

// Fetch variations for this product
$variations = db_fetch_all("
    SELECT * FROM product_variations
    WHERE product_id = :pid AND is_active = 1
    ORDER BY id ASC
", ['pid' => $productId]);

$productAttributes = [];
$variationData = [];

if (!empty($variations)) {
    // Fetch attributes attached to this product
    $attrs = db_fetch_all("
        SELECT a.id, a.name, a.slug
        FROM product_attributes pa
        JOIN attributes a ON pa.attribute_id = a.id
        WHERE pa.product_id = :pid
        ORDER BY pa.display_order ASC, a.id ASC
    ", ['pid' => $productId]);

    foreach ($attrs as $attr) {
        $values = db_fetch_all("
            SELECT DISTINCT av.id, av.value, av.color_code, av.display_order
            FROM attribute_values av
            JOIN product_variation_values pvv ON av.id = pvv.attribute_value_id
            JOIN product_variations pv ON pvv.variation_id = pv.id
            WHERE pv.product_id = :pid AND av.attribute_id = :aid AND pv.is_active = 1
            ORDER BY av.display_order ASC, av.id ASC
        ", ['pid' => $productId, 'aid' => $attr['id']]);

        if (!empty($values)) {
            $productAttributes[] = [
                'id' => (int)$attr['id'],
                'name' => $attr['name'],
                'slug' => $attr['slug'],
                'values' => $values
            ];
        }
    }

    foreach ($variations as $var) {
        $valRows = db_fetch_all("
            SELECT av.attribute_id, av.id as val_id
            FROM product_variation_values pvv
            JOIN attribute_values av ON pvv.attribute_value_id = av.id
            WHERE pvv.variation_id = :vid
        ", ['vid' => $var['id']]);

        $attrMap = [];
        foreach ($valRows as $vr) {
            $attrMap[$vr['attribute_id']] = (int)$vr['val_id'];
        }

        $variationData[] = [
            'id' => (int)$var['id'],
            'sku' => $var['sku'],
            'combination_key' => $var['combination_key'],
            'regular_price' => (float)$var['regular_price'],
            'sale_price' => $var['sale_price'] !== null ? (float)$var['sale_price'] : null,
            'stock_quantity' => (int)$var['stock_quantity'],
            'is_in_stock' => (int)$var['is_in_stock'],
            'image_url' => !empty($var['image_url']) ? url($var['image_url']) : null,
            'attributes' => $attrMap
        ];
    }
}

// Fetch product sizes from DB for dynamic size-based pricing (fallback / gang sheets)
$sizes = db_fetch_all("SELECT * FROM product_sizes WHERE product_id = :pid ORDER BY display_order ASC", [
    'pid' => $productId
]);

// Fetch quantity pricing tiers
$tiers = db_fetch_all("SELECT * FROM product_pricing_tiers WHERE product_id = :pid ORDER BY min_quantity ASC", [
    'pid' => $productId
]);

// Fetch product options (paper, materials, finishing)
$options = db_fetch_all("SELECT * FROM product_options WHERE product_id = :pid ORDER BY display_order ASC", [
    'pid' => $productId
]);

$groupedOptions = [];
foreach ($options as $opt) {
    $groupedOptions[$opt['option_type']][] = $opt;
}

// Fetch approved product reviews
$reviews = db_fetch_all("SELECT * FROM product_reviews WHERE product_id = :pid AND status = 'approved' ORDER BY created_at DESC", [
    'pid' => $productId
]);

$reviewCount = count($reviews);
$avgRating = 0.0;
$ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

if ($reviewCount > 0) {
    $totalStars = 0;
    foreach ($reviews as $rev) {
        $r = (int)$rev['rating'];
        if (isset($ratingCounts[$r])) {
            $ratingCounts[$r]++;
        }
        $totalStars += $r;
    }
    $avgRating = round($totalStars / $reviewCount, 1);
}

// SEO Metadata
$pageTitle = (!empty($product['meta_title']) ? $product['meta_title'] : (e($product['title']) . " (SKU: " . e($product['sku']) . ")")) . " | OrderPrintex";
$pageDesc  = !empty($product['meta_description']) ? e($product['meta_description']) : e($product['short_desc']);
$canonical = product_url($product['slug']);

require_once __DIR__ . '/includes/header.php';

// Fetch related products
$allProducts = get_catalog_products();
$relatedProducts = array_values(array_filter($allProducts, function ($p) use ($product) {
    return ($p['slug'] ?? '') !== $product['slug'];
}));
$relatedProducts = array_slice($relatedProducts, 0, 4);

$hasVariations = !empty($variationData) && !empty($productAttributes);
?>

<!-- Schema.org Product Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": <?= json_encode($product['title']) ?>,
  "image": [
    <?= json_encode(url($product['image_url'])) ?>
  ],
  "description": <?= json_encode($product['short_desc']) ?>,
  "sku": <?= json_encode($product['sku'] ?? ('OPX-' . str_pad($product['id'], 4, '0', STR_PAD_LEFT))) ?>,
  "brand": {
    "@type": "Brand",
    "name": "OrderPrintex"
  },
  "offers": {
    "@type": "Offer",
    "url": <?= json_encode($canonical) ?>,
    "priceCurrency": "INR",
    "price": <?= json_encode((float)preg_replace('/[^0-9.]/', '', $product['starting_price'] ?? '249')) ?>,
    "priceValidUntil": "2027-12-31",
    "availability": "https://schema.org/InStock",
    "itemCondition": "https://schema.org/NewCondition"
  }
  <?php if ($reviewCount > 0): ?>
  ,
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?= $avgRating ?>",
    "reviewCount": "<?= $reviewCount ?>"
  }
  <?php endif; ?>
}
</script>

<!-- Schema.org Breadcrumbs Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": <?= json_encode(url('')) ?>
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Products",
      "item": <?= json_encode(url('products/')) ?>
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": <?= json_encode($product['category_name']) ?>,
      "item": <?= json_encode(category_url($product['category_slug'] ?? '')) ?>
    },
    {
      "@type": "ListItem",
      "position": 4,
      "name": <?= json_encode($product['title']) ?>,
      "item": <?= json_encode($canonical) ?>
    }
  ]
}
</script>

<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-bar" style="background-color:var(--ivory); border-bottom:1px solid var(--border-light); padding:0.9rem 0;" aria-label="Breadcrumb">
  <div class="container" style="font-size:0.85rem; color:var(--slate); display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
    <a href="<?= url('') ?>" style="color:var(--navy); font-weight:600;">Home</a>
    <span style="color:var(--slate-subtle);">&rsaquo;</span>
    <a href="<?= url('products/') ?>" style="color:var(--navy); font-weight:600;">Products</a>
    <span style="color:var(--slate-subtle);">&rsaquo;</span>
    <a href="<?= category_url($product['category_slug'] ?? '') ?>" style="color:var(--navy); font-weight:600;">
      <?= e($product['category_name']) ?>
    </a>
    <span style="color:var(--slate-subtle);">&rsaquo;</span>
    <span style="color:var(--royal); font-weight:700;" aria-current="page"><?= e($product['title']) ?></span>
  </div>
</nav>

<!-- ======================================================================= -->
<!-- SECTION 1: PRODUCT OVERVIEW & DYNAMIC WORKBENCH -->
<!-- ======================================================================= -->
<section class="section section-white" id="product-overview-section" style="padding-top:3.5rem; padding-bottom:3.5rem;">
  <div class="container">
    <div class="product-detail-grid">
      
      <!-- Left Column: Product Imagery -->
      <div class="reveal slide-right">
        <div class="product-detail-media-card" style="background:var(--bg-surface); border-radius:var(--radius-lg); border:1px solid var(--border-color); padding:2rem; text-align:center; position:relative; min-height:480px; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 14px rgba(15, 25, 45, 0.09), 0 14px 34px rgba(15, 25, 45, 0.11); overflow:hidden;">
          <!-- Status badge -->
          <span class="product-badge-tag <?= e($product['badge_class'] ?? 'badge-gold') ?>" style="position:absolute; top:1.25rem; left:1.25rem; z-index:10;">
            <?= e($product['badge'] ?? 'Commercial Grade') ?>
          </span>

          <img id="mainProductImage" src="<?= url(e($product['image_url'])) ?>" alt="<?= e($product['title']) ?> - Full Product View" style="max-width:100%; max-height:420px; object-fit:contain; display:block; margin:0 auto; filter:drop-shadow(0 10px 20px rgba(0,0,0,0.08)); transition:opacity 0.25s ease;" loading="eager">
        </div>

        <!-- Technical Guarantees Bar -->
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:0.75rem; margin-top:1.25rem;">
          <div style="background:var(--bg-surface); border:1px solid var(--border-color); padding:0.85rem 0.65rem; border-radius:var(--radius-sm); text-align:center; box-shadow: 0 2px 8px rgba(15, 25, 45, 0.06);">
            <div style="color:var(--emerald); font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:var(--navy); line-height:1.3;">300 DPI Prepress Audit</div>
          </div>
          <div style="background:var(--bg-surface); border:1px solid var(--border-color); padding:0.85rem 0.65rem; border-radius:var(--radius-sm); text-align:center; box-shadow: 0 2px 8px rgba(15, 25, 45, 0.06);">
            <div style="color:var(--emerald); font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:var(--navy); line-height:1.3;">50+ Wash Longevity</div>
          </div>
          <div style="background:var(--bg-surface); border:1px solid var(--border-color); padding:0.85rem 0.65rem; border-radius:var(--radius-sm); text-align:center; box-shadow: 0 2px 8px rgba(15, 25, 45, 0.06);">
            <div style="color:var(--emerald); font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:var(--navy); line-height:1.3;">PAN-India Express</div>
          </div>
        </div>
      </div>

      <!-- Right Column: Product Title, Dynamic SKU, Variation & Pricing Workbench -->
      <div class="reveal slide-left">
        <!-- Header Meta Row: Category, SKU, and Rating -->
        <div class="product-heading-meta" style="display:flex; align-items:center; gap:0.65rem; flex-wrap:wrap; margin-bottom:0.85rem;">
          <span class="product-category-pill"><?= e($product['category_name']) ?></span>
          <span id="productSkuBadge" style="background:var(--navy); color:#FFFFFF; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:4px; letter-spacing:0.04em; transition:all 0.2s ease;">
            SKU: <span id="displaySku"><?= e($product['sku']) ?></span>
          </span>
          <?php if (!empty($product['min_order'])): ?>
            <span class="product-min-order" style="background:rgba(185,154,97,0.15); color:var(--navy); font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:4px;">
              <?= e($product['min_order']) ?>
            </span>
          <?php endif; ?>

          <!-- Rating Stars preview -->
          <div style="margin-left:auto; display:flex; align-items:center; gap:0.35rem; font-size:0.85rem;">
            <span style="color:#F59E0B; font-weight:700;">★ <?= $reviewCount > 0 ? $avgRating : '5.0' ?></span>
            <a href="#reviews-section" style="color:var(--text-muted); text-decoration:none;">
              (<?= $reviewCount ?> <?= $reviewCount === 1 ? 'review' : 'reviews' ?>)
            </a>
          </div>
        </div>

        <!-- Primary Product H1 -->
        <h1 class="product-page-title" style="font-size:clamp(1.75rem, 2.5vw, 2.3rem); color:var(--navy); margin-bottom:0.75rem; line-height:1.25; font-weight:800;">
          <?= e($product['title']) ?>
        </h1>
        
        <p style="font-size:1rem; color:var(--text-muted); margin-bottom:1.5rem; line-height:1.65;">
          <?= e($product['short_desc']) ?>
        </p>

        <!-- ============================================================== -->
        <!-- DYNAMIC PRICING & VARIATION WORKBENCH -->
        <!-- ============================================================== -->
        <div class="pricing-calculator-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: 0 4px 14px rgba(15, 25, 45, 0.09), 0 12px 30px rgba(15, 25, 45, 0.11); margin-bottom:1.75rem;">
          <div style="font-size:0.85rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:var(--navy); margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.75rem;">
            <span style="display:flex; align-items:center; gap:0.4rem;">
              <span style="color:var(--royal); font-size:1.1rem;">⚙</span>
              <?= $hasVariations ? 'Select Options &amp; Quantity' : 'Configure Size &amp; Quantity' ?>
            </span>
            <span id="stockStatusBadge" style="background:rgba(16,185,129,0.12); color:#059669; font-size:0.75rem; font-weight:700; padding:0.25rem 0.6rem; border-radius:4px;">
              ● In Stock
            </span>
          </div>

          <form id="priceCalculatorForm">
            <input type="hidden" id="calcProductId" value="<?= $productId ?>">
            <input type="hidden" id="selectedVariationId" value="">
            <input type="hidden" id="selectedVariationSku" value="<?= e($product['sku']) ?>">

            <?php if ($hasVariations): ?>
              <!-- DYNAMIC ATTRIBUTE VARIATION SELECTORS -->
              <div id="variationSelectors" style="display:flex; flex-direction:column; gap:1.25rem; margin-bottom:1.25rem;">
                <?php foreach ($productAttributes as $aIdx => $attr): ?>
                  <div>
                    <label style="display:flex; justify-content:space-between; align-items:baseline; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem;">
                      <span><?= ($aIdx + 1) ?>. Choose <?= e($attr['name']) ?>:</span>
                      <span id="selected_val_label_<?= $attr['id'] ?>" style="color:var(--royal); font-weight:600; font-size:0.8rem;"></span>
                    </label>

                    <?php 
                      $isColor = (stripos($attr['name'], 'colour') !== false || stripos($attr['name'], 'color') !== false);
                    ?>

                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem;" data-attr-id="<?= $attr['id'] ?>" class="attr-group-wrapper">
                      <?php foreach ($attr['values'] as $vIdx => $val): ?>
                        <label class="variation-choice-btn <?= $isColor ? 'color-swatch-label' : 'pill-swatch-label' ?>" 
                               style="display:inline-flex; align-items:center; gap:0.45rem; padding:<?= $isColor ? '0.45rem 0.85rem' : '0.5rem 0.95rem' ?>; border:1.5px solid var(--border-light); border-radius:6px; cursor:pointer; background:var(--bg-surface); font-size:0.85rem; font-weight:600; color:var(--navy); transition:all 0.2s ease;">
                          <input type="radio" 
                                 name="attr_<?= $attr['id'] ?>" 
                                 value="<?= $val['id'] ?>" 
                                 data-attr-id="<?= $attr['id'] ?>" 
                                 data-val-name="<?= e($val['value']) ?>" 
                                 <?= $vIdx === 0 ? 'checked' : '' ?> 
                                 style="display:none;" 
                                 onchange="handleVariationChange()">
                          
                          <?php if ($isColor && !empty($val['color_code'])): ?>
                            <span class="color-dot" style="width:18px; height:18px; border-radius:50%; background:<?= e($val['color_code']) ?>; border:1px solid rgba(0,0,0,0.2); box-shadow:0 1px 3px rgba(0,0,0,0.15); display:inline-block;"></span>
                          <?php endif; ?>

                          <span><?= e($val['value']) ?></span>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <!-- FALLBACK: SIZE & DIMENSIONS WORKBENCH (For Custom DTF Gang Rolls / Single Items) -->
              <?php if (!empty($sizes)): ?>
                <div style="margin-bottom:1.25rem;">
                  <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem;">
                    1. Select Target Size / Dimensions:
                  </label>
                  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:0.6rem;">
                    <?php foreach ($sizes as $sIdx => $s): ?>
                      <label class="size-choice-label" style="display:flex; flex-direction:column; padding:0.65rem 0.85rem; border:1.5px solid <?= $sIdx === 0 ? 'var(--royal)' : 'var(--border-light)' ?>; border-radius:6px; cursor:pointer; background:<?= $sIdx === 0 ? 'rgba(29,78,216,0.04)' : '#FFFFFF' ?>; transition:all 0.2s ease;">
                        <input type="radio" name="selected_size" value="<?= $s['id'] ?>" data-price="<?= $s['base_price'] ?>" <?= $sIdx === 0 ? 'checked' : '' ?> style="display:none;" onchange="updateLivePrice()">
                        <span style="font-size:0.85rem; font-weight:700; color:var(--navy);"><?= e($s['size_label']) ?></span>
                        <span style="font-size:0.75rem; color:var(--royal); font-weight:600; margin-top:2px;">₹<?= number_format($s['base_price'], 2) ?> base</span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Paper Stock Options -->
              <?php if (!empty($groupedOptions['paper'])): ?>
                <div style="margin-bottom:1.25rem;">
                  <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem;">
                    2. Select Paper / Cardstock Grade:
                  </label>
                  <div style="display:flex; flex-direction:column; gap:0.4rem;">
                    <?php foreach ($groupedOptions['paper'] as $pIdx => $pOpt): ?>
                      <label style="display:flex; align-items:center; justify-content:space-between; font-size:0.85rem; padding:0.5rem 0.75rem; border:1px solid var(--border-light); border-radius:6px; cursor:pointer;">
                        <span style="display:flex; align-items:center; gap:0.5rem; color:var(--navy);">
                          <input type="radio" name="opt_paper" value="<?= $pOpt['id'] ?>" data-price="<?= $pOpt['additional_price'] ?>" <?= $pIdx === 0 ? 'checked' : '' ?> onchange="updateLivePrice()">
                          <?= e($pOpt['option_name']) ?>
                        </span>
                        <span style="font-weight:700; color:var(--text-muted); font-size:0.8rem;">
                          <?= $pOpt['additional_price'] > 0 ? '+₹' . number_format($pOpt['additional_price'], 2) . '/pc' : 'Included' ?>
                        </span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            <?php endif; ?>

            <!-- Quantity Input with Stepper and Wholesale Tiers -->
            <div style="margin-bottom:1.25rem;">
              <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.5rem;">
                <label for="calcQuantity" style="font-size:0.825rem; font-weight:700; color:var(--navy);">
                  <?= $hasVariations ? (count($productAttributes) + 1) : (!empty($sizes) ? '2' : '1') ?>. Order Quantity:
                </label>
                <span id="tierBadge" style="font-size:0.75rem; font-weight:700; color:var(--royal); background:rgba(29,78,216,0.08); padding:0.2rem 0.5rem; border-radius:4px;">
                  Standard Tier
                </span>
              </div>
              <div style="display:flex; align-items:center; gap:0.5rem; max-width:220px;">
                <button type="button" onclick="adjustQty(-1)" class="btn btn-sm btn-outline" style="width:38px; height:38px; padding:0; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.1rem;">-</button>
                <input type="number" id="calcQuantity" value="<?= !empty($product['min_order']) && preg_match('/(\d+)/', $product['min_order'], $qm) ? $qm[1] : 1 ?>" min="1" max="99999" class="form-control" style="text-align:center; font-weight:700; font-size:1.1rem; height:38px;" oninput="updateLivePrice()">
                <button type="button" onclick="adjustQty(1)" class="btn btn-sm btn-outline" style="width:38px; height:38px; padding:0; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.1rem;">+</button>
              </div>
            </div>

            <!-- Finishing Options if present -->
            <?php if (!empty($groupedOptions['finishing'])): ?>
              <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem;">
                  Print Enhancements &amp; Protective Finish:
                </label>
                <div style="display:flex; flex-direction:column; gap:0.4rem;">
                  <?php foreach ($groupedOptions['finishing'] as $fIdx => $fOpt): ?>
                    <label style="display:flex; align-items:center; justify-content:space-between; font-size:0.85rem; padding:0.5rem 0.75rem; border:1px solid var(--border-light); border-radius:6px; cursor:pointer;">
                      <span style="display:flex; align-items:center; gap:0.5rem; color:var(--navy);">
                        <input type="checkbox" name="opt_finishing[]" value="<?= $fOpt['id'] ?>" data-price="<?= $fOpt['additional_price'] ?>" <?= $fIdx === 0 ? 'checked' : '' ?> onchange="updateLivePrice()">
                        <?= e($fOpt['option_name']) ?>
                      </span>
                      <span style="font-weight:700; color:var(--text-muted); font-size:0.8rem;">
                        <?= $fOpt['additional_price'] > 0 ? '+₹' . number_format($fOpt['additional_price'], 2) . '/pc' : 'Standard' ?>
                      </span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- Live Calculated Price Summary Display -->
            <div style="background:var(--ivory); border-radius:8px; border:1px solid var(--border-gold); padding:1.25rem; margin-top:1.25rem;">
              <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.4rem; font-size:0.85rem; color:var(--text-muted);">
                <span>Effective Unit Price:</span>
                <div style="display:flex; align-items:baseline; gap:0.5rem;">
                  <span id="displayOldPrice" style="text-decoration:line-through; font-size:0.85rem; color:var(--text-muted); display:none;"></span>
                  <span id="displayUnitPrice" style="font-weight:700; color:var(--navy); font-size:1.05rem;">₹0.00</span>
                  <span id="displayDiscountTag" style="background:#ECFDF5; color:#065F46; font-size:0.75rem; font-weight:700; padding:0.15rem 0.4rem; border-radius:4px; display:none;"></span>
                </div>
              </div>
              <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.6rem; font-size:0.85rem; color:var(--text-muted);">
                <span>Quantity Selected:</span>
                <span id="displayQuantity" style="font-weight:700; color:var(--navy);">1 unit</span>
              </div>
              <div style="display:flex; justify-content:space-between; align-items:baseline; padding-top:0.6rem; border-top:1px dashed var(--border-light); margin-top:0.4rem;">
                <span style="font-weight:800; color:var(--navy); font-size:1.05rem;">Estimated Total:</span>
                <div style="text-align:right;">
                  <span id="displayGrandTotal" style="font-weight:800; color:var(--royal); font-size:1.6rem; line-height:1;">₹0.00</span>
                  <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">(Inclusive of 18% GST)</div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- Order Action Buttons -->
        <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem;">
          <a href="#quote-form" class="btn btn-primary btn-lg" style="flex:1; min-width:240px;" onclick="syncQuoteForm()">
            <span>Upload Artwork &amp; Order Now</span> &darr;
          </a>
          <a href="https://wa.me/919354028199?text=<?= urlencode('Hi OrderPrintex, I am inquiring about ' . $product['title'] . ' (SKU: ' . $product['sku'] . '). Please provide a customized volume quote.') ?>" target="_blank" rel="noopener" class="btn btn-navy btn-lg">
            <span>WhatsApp Prepress Desk</span>
          </a>
        </div>

        <div style="font-size:0.85rem; color:var(--text-muted); display:flex; align-items:center; gap:0.5rem;">
          <span style="color:#10B981; font-weight:800;">✓</span>
          Direct Delhi factory manufacturer rates. Final binding invoice issued following digital proof sign-off.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================= -->
<!-- SEQUENTIAL SCROLL SECTIONS (Tabs/Accordions Completely Removed) -->
<!-- ======================================================================= -->
<div class="product-scroll-sections" style="background:var(--ivory); padding:3.5rem 0 5rem;">
  <div class="container container-readable">

    <!-- =================================================================== -->
    <!-- SECTION 2: TECHNICAL SPECIFICATIONS -->
    <!-- =================================================================== -->
    <?php if (!empty($product['specifications']) || !empty($product['description'])): ?>
    <section class="product-content-block reveal slide-up" id="specs-section" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 12px rgba(15, 25, 45, 0.08), 0 12px 28px rgba(15, 25, 45, 0.10); margin-bottom:2.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:rgba(20,35,60,0.06); color:var(--navy); width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">02</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:var(--royal);">Calibrated Parameters</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:var(--navy); margin:0; font-weight:800;">Technical Specifications</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <?php if (!empty($product['description'])): ?>
        <p style="margin-bottom:1.5rem; line-height:1.75; color:var(--slate); font-size:0.95rem;">
          <?= nl2br(e($product['description'])) ?>
        </p>
      <?php endif; ?>

      <?php if (!empty($product['specifications'])): ?>
        <div style="background:var(--ivory); border-left:4px solid var(--navy); padding:1.25rem 1.5rem; border-radius:var(--radius-sm); font-size:0.9rem; line-height:1.8; color:var(--navy);">
          <strong style="display:block; margin-bottom:0.5rem; color:var(--navy);">Engineered Standards &amp; Materials:</strong>
          <?= nl2br(e($product['specifications'])) ?>
        </div>
      <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- =================================================================== -->
    <!-- SECTION 3: ARTWORK & FILE GUIDELINES -->
    <!-- =================================================================== -->
    <?php if (!empty($product['artwork_notes'])): ?>
    <section class="product-content-block reveal slide-up" id="artwork-section" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 12px rgba(15, 25, 45, 0.08), 0 12px 28px rgba(15, 25, 45, 0.10); margin-bottom:2.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:rgba(20,35,60,0.06); color:var(--navy); width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">03</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:var(--royal);">Prepress Standards</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:var(--navy); margin:0; font-weight:800;">Artwork &amp; File Guidelines</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <div style="background:rgba(29,78,216,0.04); border:1px solid rgba(29,78,216,0.12); padding:1.25rem 1.5rem; border-radius:8px; margin-bottom:1.5rem;">
        <div style="font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Artwork Requirement for this Product:</div>
        <p style="margin:0; font-size:0.925rem; color:var(--slate); line-height:1.65;">
          <?= nl2br(e($product['artwork_notes'])) ?>
        </p>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem;">
        <div style="padding:1rem; border:1px solid var(--border-light); border-radius:8px; background:#FAFAFA;">
          <div style="font-weight:700; color:var(--navy); font-size:0.9rem; margin-bottom:0.25rem;">Resolution &amp; Scale</div>
          <div style="font-size:0.85rem; color:var(--slate); line-height:1.5;">Minimum 300 DPI at 100% finished reproduction scale. Never upscale low-res raster imagery.</div>
        </div>
        <div style="padding:1rem; border:1px solid var(--border-light); border-radius:8px; background:#FAFAFA;">
          <div style="font-weight:700; color:var(--navy); font-size:0.9rem; margin-bottom:0.25rem;">Accepted Formats</div>
          <div style="font-size:0.85rem; color:var(--slate); line-height:1.5;">PNG (transparent), Adobe Illustrator (.AI), PDF (vector curves), PSD, or SVG.</div>
        </div>
        <div style="padding:1rem; border:1px solid var(--border-light); border-radius:8px; background:#FAFAFA;">
          <div style="font-weight:700; color:var(--navy); font-size:0.9rem; margin-bottom:0.25rem;">Color Profile</div>
          <div style="font-size:0.85rem; color:var(--slate); line-height:1.5;">CMYK or RGB embedded with standard ICC profiles. Prepress RIP auto-maps to vivid gamut.</div>
        </div>
        <div style="padding:1rem; border:1px solid var(--border-light); border-radius:8px; background:#FAFAFA;">
          <div style="font-weight:700; color:var(--navy); font-size:0.9rem; margin-bottom:0.25rem;">Transparency Check</div>
          <div style="font-size:0.85rem; color:var(--slate); line-height:1.5;">Background must be 100% transparent. Feathered edges should maintain sharp anti-aliasing.</div>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- =================================================================== -->
    <!-- SECTION 4: APPLICATION & HEAT PRESS GUIDE -->
    <!-- =================================================================== -->
    <?php if (!empty($product['application_guide'])): ?>
    <section class="product-content-block reveal slide-up" id="application-section" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 12px rgba(15, 25, 45, 0.08), 0 12px 28px rgba(15, 25, 45, 0.10); margin-bottom:2.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:rgba(20,35,60,0.06); color:var(--navy); width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">04</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:var(--royal);">Substrate Engineering</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:var(--navy); margin:0; font-weight:800;">Application &amp; Heat Press Guide</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <div style="margin-bottom:1.5rem; font-size:0.95rem; color:var(--slate); line-height:1.75;">
        <?= nl2br(e($product['application_guide'])) ?>
      </div>

      <!-- Standard 4-Step Pressing Matrix -->
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
        <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); text-align:center;">
          <div style="font-weight:700; color:var(--navy); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.04em;">1. Temperature</div>
          <div style="font-size:1.35rem; font-weight:800; color:var(--royal); margin:0.35rem 0;">155°C – 165°C</div>
          <div style="font-size:0.8rem; color:var(--slate);">Optimal for polyurethane adhesive activation.</div>
        </div>
        <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); text-align:center;">
          <div style="font-weight:700; color:var(--navy); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.04em;">2. Pressure &amp; Time</div>
          <div style="font-size:1.35rem; font-weight:800; color:var(--royal); margin:0.35rem 0;">15 Seconds</div>
          <div style="font-size:0.8rem; color:var(--slate);">Medium-high pressure (40–50 PSI) for fiber lock.</div>
        </div>
        <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); text-align:center;">
          <div style="font-weight:700; color:var(--navy); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.04em;">3. Peel Method</div>
          <div style="font-size:1.35rem; font-weight:800; color:var(--royal); margin:0.35rem 0;">Cold Peel</div>
          <div style="font-size:0.8rem; color:var(--slate);">Allow sheet to reach room temperature before peel.</div>
        </div>
        <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); text-align:center;">
          <div style="font-weight:700; color:var(--navy); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.04em;">4. Post-Press</div>
          <div style="font-size:1.35rem; font-weight:800; color:var(--royal); margin:0.35rem 0;">5 Seconds</div>
          <div style="font-size:0.8rem; color:var(--slate);">Finish with silicone parchment for a soft hand feel.</div>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- =================================================================== -->
    <!-- SECTION 5: CUSTOMER REVIEWS -->
    <!-- =================================================================== -->
    <section class="product-content-block reveal slide-up" id="reviews-section" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 12px rgba(15, 25, 45, 0.08), 0 12px 28px rgba(15, 25, 45, 0.10); margin-bottom:2.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:rgba(20,35,60,0.06); color:var(--navy); width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">05</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:var(--royal);">Verified Feedback</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:var(--navy); margin:0; font-weight:800;">Customer Reviews</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <div style="display:grid; grid-template-columns:1fr 1.6fr; gap:2.5rem; align-items:flex-start; margin-bottom:2.5rem;">
        <!-- Review Summary Stats -->
        <div class="review-summary-card" style="background:var(--ivory); padding:1.75rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
          <div style="font-size:0.85rem; font-weight:800; text-transform:uppercase; letter-spacing:0.04em; color:var(--navy); margin-bottom:0.75rem;">Rating Breakdown</div>
          <div style="display:flex; align-items:baseline; gap:0.5rem; margin-bottom:0.5rem;">
            <span style="font-size:2.8rem; font-weight:800; color:var(--navy); line-height:1;"><?= $reviewCount > 0 ? $avgRating : '5.0' ?></span>
            <span style="font-size:1rem; color:var(--text-muted);">/ 5.0</span>
          </div>
          <div style="color:#F59E0B; font-size:1.2rem; margin-bottom:0.75rem;">
            <?php
            $filledStars = $reviewCount > 0 ? round($avgRating) : 5;
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $filledStars ? '★' : '☆';
            }
            ?>
          </div>
          <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.25rem;">
            Based on <?= $reviewCount ?> <?= $reviewCount === 1 ? 'verified review' : 'verified reviews' ?>
          </p>

          <!-- Rating distribution bars -->
          <div style="display:flex; flex-direction:column; gap:0.35rem; font-size:0.8rem;">
            <?php for ($star = 5; $star >= 1; $star--): 
              $count = $ratingCounts[$star] ?? 0;
              $pct = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : ($star === 5 ? 100 : 0);
            ?>
              <div style="display:flex; align-items:center; gap:0.5rem;">
                <span style="width:25px; color:var(--navy); font-weight:600;"><?= $star ?>★</span>
                <div style="flex:1; height:8px; background:rgba(0,0,0,0.06); border-radius:999px; overflow:hidden;">
                  <div style="width:<?= $pct ?>%; height:100%; background:#F59E0B; border-radius:999px;"></div>
                </div>
                <span style="width:30px; text-align:right; color:var(--text-muted);"><?= $count ?></span>
              </div>
            <?php endfor; ?>
          </div>
        </div>

        <!-- Review Submission Form -->
        <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: 0 2px 8px rgba(15, 25, 45, 0.05);">
          <h3 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">Write a Product Review</h3>
          <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.25rem;">
            Have you printed or sampled this substrate? Share your production notes with our community.
          </p>

          <form id="productReviewForm" onsubmit="submitReview(event)">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= $productId ?>">

            <!-- Interactive Star Rating Picker -->
            <div style="margin-bottom:1rem;">
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
                Your Rating <span style="color:var(--crimson);">*</span>
              </label>
              <div id="starRatingPicker" style="display:flex; gap:0.35rem; font-size:1.6rem; color:#D1D5DB; cursor:pointer;">
                <span onclick="setStarRating(1)" onmouseover="hoverStarRating(1)" onmouseout="resetStarRating()">★</span>
                <span onclick="setStarRating(2)" onmouseover="hoverStarRating(2)" onmouseout="resetStarRating()">★</span>
                <span onclick="setStarRating(3)" onmouseover="hoverStarRating(3)" onmouseout="resetStarRating()">★</span>
                <span onclick="setStarRating(4)" onmouseover="hoverStarRating(4)" onmouseout="resetStarRating()">★</span>
                <span onclick="setStarRating(5)" onmouseover="hoverStarRating(5)" onmouseout="resetStarRating()">★</span>
              </div>
              <input type="hidden" name="rating" id="selectedRatingInput" value="5">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
              <div>
                <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Your Name *</label>
                <input type="text" name="customer_name" required class="form-control" placeholder="e.g. Vikram Malhotra">
              </div>
              <div>
                <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Email Address</label>
                <input type="email" name="customer_email" class="form-control" placeholder="e.g. vikram@studio.com">
              </div>
            </div>

            <div style="margin-bottom:1rem;">
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Review Headline *</label>
              <input type="text" name="title" required class="form-control" placeholder="e.g. Flawless white opacity and vivid colors!">
            </div>

            <div style="margin-bottom:1.25rem;">
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Written Review *</label>
              <textarea name="review_text" rows="3" required class="form-control" placeholder="Describe the hand-feel, wash durability, or pressing performance..."></textarea>
            </div>

            <div id="reviewResponseAlert" style="display:none; padding:0.75rem; border-radius:6px; margin-bottom:1rem; font-size:0.85rem;"></div>

            <button type="submit" id="submitReviewBtn" class="btn btn-navy btn-sm">
              <span>Submit Verified Review</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Existing Reviews Feed -->
      <div style="border-top:1px solid var(--border-light); padding-top:2rem;">
        <h4 style="font-size:1.1rem; color:var(--navy); margin-bottom:1.25rem; font-weight:700;">Customer Feedback Feed</h4>
        
        <?php if (empty($reviews)): ?>
          <div style="text-align:center; padding:2.5rem 1.5rem; background:var(--ivory); border-radius:var(--radius-md); border:1px dashed var(--border-color);">
            <div style="font-size:2rem; margin-bottom:0.5rem;">⭐</div>
            <h4 style="color:var(--navy); margin-bottom:0.35rem; font-size:1rem;">Be the first to review this product!</h4>
            <p style="color:var(--text-muted); font-size:0.85rem; max-width:440px; margin:0 auto 1rem;">
              Share your production experience and print outcomes with fellow apparel creators.
            </p>
          </div>
        <?php else: ?>
          <div style="display:flex; flex-direction:column; gap:1.25rem;">
            <?php foreach ($reviews as $rev): ?>
              <div class="review-card" style="padding:1.4rem; background:var(--bg-surface); border:1px solid var(--border-color); border-radius:8px; box-shadow: 0 3px 10px rgba(15, 25, 45, 0.07); transition: transform 0.25s ease, box-shadow 0.25s ease;">
                <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.35rem;">
                  <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="font-weight:700; color:var(--navy);"><?= e($rev['customer_name']) ?></span>
                    <?php if (!empty($rev['is_verified_purchase'])): ?>
                      <span style="background:rgba(16,185,129,0.1); color:#059669; font-size:0.7rem; font-weight:700; padding:0.15rem 0.45rem; border-radius:4px;">
                        Verified Buyer
                      </span>
                    <?php endif; ?>
                  </div>
                  <span style="font-size:0.8rem; color:var(--text-muted);">
                    <?= date('M d, Y', strtotime($rev['created_at'])) ?>
                  </span>
                </div>

                <div style="color:#F59E0B; font-size:0.95rem; margin-bottom:0.4rem;">
                  <?php for ($s = 1; $s <= 5; $s++) { echo $s <= (int)$rev['rating'] ? '★' : '☆'; } ?>
                </div>

                <h5 style="font-size:0.95rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
                  <?= e($rev['title']) ?>
                </h5>
                <p style="font-size:0.88rem; color:var(--text-muted); line-height:1.6; margin:0;">
                  <?= nl2br(e($rev['review_text'])) ?>
                </p>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- =================================================================== -->
    <!-- SECTION 6: TURNAROUND & SHIPPING -->
    <!-- =================================================================== -->
    <?php if (!empty($product['shipping_info'])): ?>
    <section class="product-content-block reveal slide-up" id="shipping-section" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 12px rgba(15, 25, 45, 0.08), 0 12px 28px rgba(15, 25, 45, 0.10); margin-bottom:1.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:rgba(20,35,60,0.06); color:var(--navy); width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">06</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:var(--royal);">Logistics &amp; Delivery</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:var(--navy); margin:0; font-weight:800;">Turnaround &amp; PAN-India Shipping</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <div style="margin-bottom:1.5rem; font-size:0.95rem; color:var(--slate); line-height:1.75;">
        <?= nl2br(e($product['shipping_info'])) ?>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem;">
        <div style="padding:1rem; border:1px solid var(--border-light); border-radius:8px; background:var(--ivory);">
          <div style="font-weight:700; color:var(--navy); font-size:0.9rem; margin-bottom:0.25rem;">Digital Prepress Proof</div>
          <div style="font-size:0.85rem; color:var(--slate);">Confirmed within 2–4 hours of artwork submission.</div>
        </div>
        <div style="padding:1rem; border:1px solid var(--border-light); border-radius:8px; background:var(--ivory);">
          <div style="font-weight:700; color:var(--navy); font-size:0.9rem; margin-bottom:0.25rem;">Express Dispatch</div>
          <div style="font-size:0.85rem; color:var(--slate);">24–48 hours for gang roll transfers; 3–5 working days for finished apparel.</div>
        </div>
        <div style="padding:1rem; border:1px solid var(--border-light); border-radius:8px; background:var(--ivory);">
          <div style="font-weight:700; color:var(--navy); font-size:0.9rem; margin-bottom:0.25rem;">Damage-Free Packaging</div>
          <div style="font-size:0.85rem; color:var(--slate);">Moisture-proof hard core tubes and 5-ply corrugated shipper boxes.</div>
        </div>
      </div>
    </section>
    <?php endif; ?>

  </div>
</div>

<!-- Embedded Quotation / Upload Form for this Product -->
<section class="section section-ivory" id="quote-form">
  <div class="container container-readable">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label" style="justify-content:center; color:var(--royal);">Prepress Quotation Workbench</div>
      <h2>Request Custom Quotation for <?= e($product['title']) ?></h2>
      <p class="section-subtitle" style="margin:0 auto;">
        Upload your print-ready design or vector artwork to receive our prepress proof and wholesale price breakdown.
      </p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component" style="background:var(--bg-surface); border:1px solid var(--border-light); border-radius:var(--radius-lg); padding:clamp(1.5rem, 3vw, 2.5rem); box-shadow:var(--shadow-md);">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="product_id" value="<?= e($product['id'] ?? 1) ?>">
        <input type="hidden" name="product_title" value="<?= e($product['title']) ?>">
        <input type="hidden" name="service_name" value="<?= e($product['service_slug'] ?? 'custom-printing') ?>">

        <div class="upload-studio-grid">
          <div>
            <div class="dropzone-container" style="border:2px dashed var(--border-light); border-radius:var(--radius-md); padding:2rem; text-align:center; background:var(--ivory); cursor:pointer;">
              <input type="file" name="artwork_file" class="file-input" accept=".png,.jpg,.jpeg,.pdf,.ai,.psd,.svg" style="display:none;">
              <div class="dropzone-icon" style="color:var(--royal); margin-bottom:0.75rem;">
                <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
              </div>
              <div class="dropzone-title" style="font-weight:700; color:var(--navy); font-size:1.05rem;">
                Upload Artwork for <?= e($product['title']) ?>
              </div>
              <div class="dropzone-hint" style="color:var(--slate-light); font-size:0.825rem; margin-top:0.4rem;">
                Accepted: PNG (transparent), PDF, AI, PSD, SVG (Max 50 MB)
              </div>
              <button type="button" class="btn btn-sm btn-outline" style="margin-top:1rem;">Select Design File</button>
            </div>

            <div class="file-preview-area"></div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Required Quantity <span class="required">*</span></label>
                <input type="number" id="syncQuoteQty" name="quantity" class="form-control" value="1" min="1" required>
              </div>

              <div class="form-group">
                <label class="form-label">Dimensions / Target Sizing</label>
                <input type="text" id="syncQuoteSizing" name="material_options" class="form-control" placeholder="e.g. 2 x 6 inches, 22 x 40 inches, or A4 front">
              </div>

              <div class="form-group">
                <label class="form-label">Your Full Name <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control" placeholder="e.g. Vikram Malhotra" required>
              </div>

              <div class="form-group">
                <label class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" class="form-control" placeholder="e.g. vikram@studio.com" required>
              </div>

              <div class="form-group">
                <label class="form-label">Phone / WhatsApp <span class="required">*</span></label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="+91 93540 28199" required>
              </div>

              <div class="form-group">
                <label class="form-label">Company / Brand (Optional)</label>
                <input type="text" name="company_name" class="form-control" placeholder="e.g. Urban Streetwear Co.">
              </div>

              <div class="form-group full-width">
                <label class="form-label">Special Notes / Deadlines</label>
                <textarea name="customer_notes" class="form-control" rows="3" placeholder="Mention target date, shipping location, or specific finishing requirements..."></textarea>
              </div>
            </div>

            <div style="margin-top:1.75rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
              <small style="color:var(--text-muted); font-size:0.825rem;">
                ✓ Free Prepress Audit &bull; Fast Turnaround &bull; PAN-India Delivery
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Submit Quotation Request</span> &rarr;
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Related Products Section -->
<?php if (!empty($relatedProducts)): ?>
<section class="related-products-section">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="margin-bottom:2.5rem;">
      <div class="section-label" style="justify-content:center; color:var(--royal);">Complementary Print Solutions</div>
      <h2 style="font-size:clamp(1.75rem, 2.6vw, 2.2rem); color:var(--navy);">Related Products &amp; Transfers</h2>
      <p class="section-subtitle" style="margin:0 auto; font-size:0.95rem;">
        Explore additional printing mediums and merchandise produced with our calibrated industrial technology.
      </p>
    </div>

    <div class="products-grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
      <?php foreach ($relatedProducts as $idx => $relProd): ?>
        <article class="product-card reveal slide-up stagger-<?= ($idx % 4) + 1 ?>">
          <div class="product-media">
            <span class="product-badge-tag <?= e($relProd['badge_class'] ?? 'badge-gold') ?>">
              <?= e($relProd['badge'] ?? $relProd['category_name']) ?>
            </span>
            <img src="<?= url(e($relProd['image_url'])) ?>" alt="<?= e($relProd['title']) ?> - Commercial Specimen" loading="lazy">
          </div>
          <div class="product-body">
            <div class="product-category-label"><?= e($relProd['category_name']) ?></div>
            <h3 class="product-title">
              <a href="<?= product_url($relProd['slug']) ?>" title="View details for <?= e($relProd['title']) ?>">
                <?= e($relProd['title']) ?>
              </a>
            </h3>
            <p class="product-desc"><?= e($relProd['short_desc']) ?></p>
            
            <div class="product-pricing-row">
              <div class="product-price-box">
                <span class="price-label">From</span>
                <span class="price-val"><?= e($relProd['starting_price'] ?? '₹249') ?></span>
                <span class="price-unit"><?= e($relProd['price_unit'] ?? '') ?></span>
              </div>
              <?php if (!empty($relProd['min_order'])): ?>
                <span class="product-min-order"><?= e($relProd['min_order']) ?></span>
              <?php endif; ?>
            </div>

            <div class="product-card-actions">
              <a href="<?= product_url($relProd['slug']) ?>" class="btn btn-outline" aria-label="View details for <?= e($relProd['title']) ?>">
                View Details
              </a>
              <a href="<?= url('get-quote/?service_slug=' . urlencode($relProd['service_slug'] ?? '')) ?>" class="btn btn-primary" aria-label="Order or request quote for <?= e($relProd['title']) ?>">
                Order / Quote
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============================================================== -->
<!-- JAVASCRIPT: DYNAMIC VARIATIONS, PRICING & REVIEWS LOGIC -->
<!-- ============================================================== -->
<script>
// Data from Backend
const HAS_VARIATIONS = <?= json_encode($hasVariations) ?>;
const VARIATIONS_DATA = <?= json_encode($variationData) ?>;
const PRODUCT_ATTRIBUTES = <?= json_encode($productAttributes) ?>;
const PRICING_TIERS = <?= json_encode($tiers) ?>;
const BASE_PRODUCT_SKU = <?= json_encode($product['sku']) ?>;
const BASE_STARTING_PRICE = <?= (float)preg_replace('/[^0-9.]/', '', $product['starting_price'] ?? '249') ?>;

let currentMatchedVariation = null;

function adjustQty(delta) {
  const input = document.getElementById('calcQuantity');
  let val = parseInt(input.value, 10) || 1;
  val = Math.max(1, val + delta);
  input.value = val;
  updateLivePrice();
}

/**
 * Handle user changing any variation attribute radio
 */
function handleVariationChange() {
  if (!HAS_VARIATIONS || !VARIATIONS_DATA.length) return;

  const form = document.getElementById('priceCalculatorForm');
  if (!form) return;

  // Collect selected values per attribute
  const selectedAttrs = {};
  PRODUCT_ATTRIBUTES.forEach(attr => {
    const checked = form.querySelector(`input[name="attr_${attr.id}"]:checked`);
    if (checked) {
      selectedAttrs[attr.id] = parseInt(checked.value, 10);
      const labelSpan = document.getElementById(`selected_val_label_${attr.id}`);
      if (labelSpan) {
        labelSpan.textContent = checked.getAttribute('data-val-name') || '';
      }
    }
  });

  // Update styles for active radio labels
  form.querySelectorAll('.variation-choice-btn').forEach(lbl => {
    const radio = lbl.querySelector('input[type="radio"]');
    if (radio && radio.checked) {
      lbl.style.borderColor = 'var(--royal)';
      lbl.style.background = 'rgba(29, 78, 216, 0.08)';
      lbl.style.boxShadow = '0 0 0 1px var(--royal)';
    } else {
      lbl.style.borderColor = 'var(--border-light)';
      lbl.style.background = '#FFFFFF';
      lbl.style.boxShadow = 'none';
    }
  });

  // Find matching variation
  let matched = VARIATIONS_DATA.find(v => {
    return Object.keys(selectedAttrs).every(attrId => {
      return v.attributes[attrId] === selectedAttrs[attrId];
    });
  });

  if (!matched && VARIATIONS_DATA.length > 0) {
    matched = VARIATIONS_DATA[0];
  }

  currentMatchedVariation = matched;

  if (matched) {
    // 1. Update SKU Badge
    const displaySku = document.getElementById('displaySku');
    if (displaySku) displaySku.textContent = matched.sku;
    const selectedVarSku = document.getElementById('selectedVariationSku');
    if (selectedVarSku) selectedVarSku.value = matched.sku;
    const selectedVarId = document.getElementById('selectedVariationId');
    if (selectedVarId) selectedVarId.value = matched.id;

    // 2. Update Stock Badge
    const stockBadge = document.getElementById('stockStatusBadge');
    if (stockBadge) {
      if (!matched.is_in_stock || matched.stock_quantity <= 0) {
        stockBadge.innerHTML = '✕ Out of Stock';
        stockBadge.style.background = 'rgba(239,68,68,0.12)';
        stockBadge.style.color = '#DC2626';
      } else if (matched.stock_quantity <= 10) {
        stockBadge.innerHTML = `⚠ Low Stock (${matched.stock_quantity} left)`;
        stockBadge.style.background = 'rgba(245,158,11,0.15)';
        stockBadge.style.color = '#D97706';
      } else {
        stockBadge.innerHTML = `● In Stock (${matched.stock_quantity} available)`;
        stockBadge.style.background = 'rgba(16,185,129,0.12)';
        stockBadge.style.color = '#059669';
      }
    }

    // 3. Update Image if variation has custom image
    if (matched.image_url) {
      const img = document.getElementById('mainProductImage');
      if (img && img.src !== matched.image_url) {
        img.style.opacity = '0.4';
        setTimeout(() => {
          img.src = matched.image_url;
          img.style.opacity = '1';
        }, 120);
      }
    }
  }

  updateLivePrice();
}

/**
 * Recalculate price live
 */
function updateLivePrice() {
  const form = document.getElementById('priceCalculatorForm');
  if (!form) return;

  let basePrice = BASE_STARTING_PRICE;
  let regularPrice = null;
  let salePrice = null;

  if (HAS_VARIATIONS && currentMatchedVariation) {
    regularPrice = currentMatchedVariation.regular_price;
    salePrice = currentMatchedVariation.sale_price;
    basePrice = salePrice !== null && salePrice > 0 ? salePrice : regularPrice;
  } else {
    // Traditional size selection fallback
    const sizeRadio = form.querySelector('input[name="selected_size"]:checked');
    if (sizeRadio) {
      basePrice = parseFloat(sizeRadio.getAttribute('data-price')) || BASE_STARTING_PRICE;
    }
    // Highlight chosen size label
    form.querySelectorAll('.size-choice-label').forEach(lbl => {
      const radio = lbl.querySelector('input[type="radio"]');
      if (radio && radio.checked) {
        lbl.style.borderColor = 'var(--royal)';
        lbl.style.background = 'rgba(29,78,216,0.06)';
      } else {
        lbl.style.borderColor = 'var(--border-light)';
        lbl.style.background = '#FFFFFF';
      }
    });
  }

  const qty = Math.max(1, parseInt(document.getElementById('calcQuantity').value, 10) || 1);

  // Check tiers for discount
  let discountPct = 0;
  let tierLabel = 'Standard Tier';
  if (Array.isArray(PRICING_TIERS) && PRICING_TIERS.length > 0) {
    for (const t of PRICING_TIERS) {
      const min = parseInt(t.min_quantity, 10);
      const max = t.max_quantity ? parseInt(t.max_quantity, 10) : 999999;
      if (qty >= min && qty <= max) {
        discountPct = parseFloat(t.discount_percent) || 0;
        tierLabel = discountPct > 0 ? (discountPct + '% Wholesale Discount') : 'Standard Tier';
        break;
      }
    }
  }

  const tierBadge = document.getElementById('tierBadge');
  if (tierBadge) {
    tierBadge.textContent = tierLabel;
    tierBadge.style.color = discountPct > 0 ? '#059669' : 'var(--royal)';
    tierBadge.style.background = discountPct > 0 ? 'rgba(16,185,129,0.1)' : 'rgba(29,78,216,0.08)';
  }

  // Calculate options
  let optionsCharge = 0;
  const paperRadio = form.querySelector('input[name="opt_paper"]:checked');
  if (paperRadio) {
    optionsCharge += parseFloat(paperRadio.getAttribute('data-price')) || 0;
  }
  form.querySelectorAll('input[name="opt_finishing[]"]:checked').forEach(cb => {
    optionsCharge += parseFloat(cb.getAttribute('data-price')) || 0;
  });

  // Calculations
  const discountedUnit = basePrice * (1 - (discountPct / 100));
  const finalUnitPrice = discountedUnit + optionsCharge;
  const subtotal = finalUnitPrice * qty;
  const tax = subtotal * 0.18;
  const grandTotal = subtotal + tax;

  // Display old price & discount pill if on sale
  const displayOldPrice = document.getElementById('displayOldPrice');
  const displayDiscountTag = document.getElementById('displayDiscountTag');

  if (regularPrice && salePrice && regularPrice > salePrice) {
    const varDiscount = Math.round(((regularPrice - salePrice) / regularPrice) * 100);
    if (displayOldPrice) {
      displayOldPrice.textContent = '₹' + regularPrice.toFixed(2);
      displayOldPrice.style.display = 'inline';
    }
    if (displayDiscountTag) {
      displayDiscountTag.textContent = 'Save ' + varDiscount + '%';
      displayDiscountTag.style.display = 'inline-block';
    }
  } else {
    if (displayOldPrice) displayOldPrice.style.display = 'none';
    if (displayDiscountTag) displayDiscountTag.style.display = 'none';
  }

  // Update DOM
  document.getElementById('displayUnitPrice').textContent = '₹' + finalUnitPrice.toFixed(2);
  document.getElementById('displayQuantity').textContent = qty + (qty === 1 ? ' unit' : ' units');
  document.getElementById('displayGrandTotal').textContent = '₹' + grandTotal.toFixed(2);
}

function syncQuoteForm() {
  const qty = document.getElementById('calcQuantity').value;
  const quoteQty = document.getElementById('syncQuoteQty');
  if (quoteQty) quoteQty.value = qty;

  const sizingInput = document.getElementById('syncQuoteSizing');
  if (sizingInput) {
    if (HAS_VARIATIONS && currentMatchedVariation) {
      sizingInput.value = `SKU: ${currentMatchedVariation.sku}`;
    } else {
      const sizeRadio = document.querySelector('input[name="selected_size"]:checked');
      if (sizeRadio) {
        const label = sizeRadio.closest('.size-choice-label').querySelector('span').textContent;
        sizingInput.value = label;
      }
    }
  }
}

// Keep switchSpecTab for backward safety
function switchSpecTab(index) {}

// Star Rating Picker Logic
let currentStarRating = 5;

function setStarRating(stars) {
  currentStarRating = stars;
  document.getElementById('selectedRatingInput').value = stars;
  renderStarPicker(stars);
}

function hoverStarRating(stars) {
  renderStarPicker(stars);
}

function resetStarRating() {
  renderStarPicker(currentStarRating);
}

function renderStarPicker(activeCount) {
  const spans = document.querySelectorAll('#starRatingPicker span');
  spans.forEach((span, idx) => {
    span.style.color = (idx < activeCount) ? '#F59E0B' : '#D1D5DB';
  });
}

// AJAX Review Submission
async function submitReview(e) {
  e.preventDefault();
  const form = document.getElementById('productReviewForm');
  const btn = document.getElementById('submitReviewBtn');
  const alertBox = document.getElementById('reviewResponseAlert');
  
  btn.disabled = true;
  btn.textContent = 'Submitting Review...';
  alertBox.style.display = 'none';

  const formData = new FormData(form);

  try {
    const res = await fetch('<?= url('api/submit_review.php') ?>', {
      method: 'POST',
      body: formData
    });
    const data = await res.json();

    alertBox.style.display = 'block';
    if (data.success) {
      alertBox.style.background = '#ECFDF5';
      alertBox.style.color = '#065F46';
      alertBox.style.border = '1px solid #A7F3D0';
      alertBox.textContent = data.message || 'Thank you! Your review was successfully submitted.';
      form.reset();
      setStarRating(5);
      setTimeout(() => location.reload(), 1500);
    } else {
      alertBox.style.background = '#FEF2F2';
      alertBox.style.color = '#991B1B';
      alertBox.style.border = '1px solid #FECACA';
      alertBox.textContent = data.error || 'Failed to submit review. Please check all required fields.';
      btn.disabled = false;
      btn.textContent = 'Submit Verified Review';
    }
  } catch (err) {
    alertBox.style.display = 'block';
    alertBox.style.background = '#FEF2F2';
    alertBox.style.color = '#991B1B';
    alertBox.textContent = 'A network error occurred. Please try again.';
    btn.disabled = false;
    btn.textContent = 'Submit Verified Review';
  }
}

// Initial Run
document.addEventListener('DOMContentLoaded', () => {
  if (HAS_VARIATIONS) {
    handleVariationChange();
  } else {
    updateLivePrice();
  }
  setStarRating(5);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
