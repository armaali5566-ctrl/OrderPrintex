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
$pageTitle = (!empty($product['meta_title']) ? $product['meta_title'] : ($product['title'] . " (SKU: " . $product['sku'] . ")")) . " | OrderPrintex";
$pageDesc  = !empty($product['meta_description']) ? $product['meta_description'] : $product['short_desc'];
$canonical = product_url($product['slug']);
$ogImage   = !empty($product['image_url']) ? url($product['image_url']) : url('assets/images/logo.png');
$ogType    = 'product';
$bodyClass = 'product-detail-page has-sticky-cart-page';

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
      "item": <?= json_encode(url('products')) ?>
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
<nav class="breadcrumb-bar" style="background-color:#121212; border-bottom:1px solid #40351E; padding:0.9rem 0;" aria-label="Breadcrumb">
  <div class="container" style="font-size:0.85rem; color:#B8B1A3; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
    <a href="<?= url('') ?>" style="color:#B8B1A3; font-weight:600; text-decoration:none;">Home</a>
    <span style="color:#665c47;">&rsaquo;</span>
    <a href="<?= url('products') ?>" style="color:#B8B1A3; font-weight:600; text-decoration:none;">Products</a>
    <span style="color:#665c47;">&rsaquo;</span>
    <a href="<?= category_url($product['category_slug'] ?? '') ?>" style="color:#B8B1A3; font-weight:600; text-decoration:none;">
      <?= e($product['category_name']) ?>
    </a>
    <span style="color:#665c47;">&rsaquo;</span>
    <span style="color:#FFD76A; font-weight:700;" aria-current="page"><?= e($product['title']) ?></span>
  </div>
</nav>

<?php
$galleryImages = [url($product['image_url'])];
foreach ($variationData as $v) {
    if (!empty($v['image_url'])) {
        $vUrl = url($v['image_url']);
        if (!in_array($vUrl, $galleryImages)) {
            $galleryImages[] = $vUrl;
        }
    }
}
$supplementalImages = [
    url('assets/images/gallery_1.jpg'),
    url('assets/images/gallery_2.jpg'),
    url('assets/images/gallery_3.jpg')
];
foreach ($supplementalImages as $sImg) {
    if (count($galleryImages) >= 4) break;
    if (!in_array($sImg, $galleryImages)) {
        $galleryImages[] = $sImg;
    }
}
?>
<!-- ======================================================================= -->
<!-- SECTION 1: PRODUCT OVERVIEW & DYNAMIC WORKBENCH -->
<!-- ======================================================================= -->
<section class="section section-white" id="product-overview-section" style="padding-top:3.5rem; padding-bottom:3.5rem; background:#080808;">
  <div class="container">
    <div class="product-detail-grid">
      
      <!-- Left Column: Product Imagery with Interactive Gallery & Lightbox -->
      <div class="reveal slide-right">
        <div class="product-gallery-container">
          <div class="product-main-view" id="mainProductView" onclick="openLightbox(0)">
            <span class="product-badge-tag <?= e($product['badge_class'] ?? 'badge-gold') ?>" style="position:absolute; top:1.25rem; left:1.25rem; z-index:10;">
              <?= e($product['badge'] ?? 'Commercial Grade') ?>
            </span>

            <img id="mainProductImage" src="<?= $galleryImages[0] ?>" alt="<?= e($product['title']) ?> - Full Product View" style="max-width:100%; max-height:420px; object-fit:contain; display:block; margin:0 auto; transition:opacity 0.25s ease;" loading="eager">

            <div class="gallery-zoom-badge">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6M8 11h6"/>
              </svg>
              <span>Click to Zoom</span>
            </div>
          </div>

          <!-- Thumbnails Strip -->
          <div class="product-thumbnails-strip">
            <?php foreach ($galleryImages as $gIdx => $gImg): ?>
              <button type="button" class="gallery-thumb-btn <?= $gIdx === 0 ? 'is-active' : '' ?>" onclick="switchGalleryImage(<?= $gIdx ?>, '<?= $gImg ?>', this)" aria-label="View product angle <?= $gIdx + 1 ?>">
                <img src="<?= $gImg ?>" alt="Angle <?= $gIdx + 1 ?>" loading="lazy">
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Technical Guarantees Bar -->
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:0.75rem; margin-top:1.25rem;">
          <div style="background:#181818; border:1px solid #40351E; padding:0.85rem 0.65rem; border-radius:6px; text-align:center;">
            <div style="color:#D4AF37; font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:#F5F1E8; line-height:1.3;">300 DPI Prepress Audit</div>
          </div>
          <div style="background:#181818; border:1px solid #40351E; padding:0.85rem 0.65rem; border-radius:6px; text-align:center;">
            <div style="color:#D4AF37; font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:#F5F1E8; line-height:1.3;">50+ Wash Longevity</div>
          </div>
          <div style="background:#181818; border:1px solid #40351E; padding:0.85rem 0.65rem; border-radius:6px; text-align:center;">
            <div style="color:#D4AF37; font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:#F5F1E8; line-height:1.3;">PAN-India Express</div>
          </div>
        </div>
      </div>

      <!-- Right Column: Product Title, Dynamic SKU, Variation & Pricing Workbench -->
      <div class="reveal slide-left">
        <!-- Header Meta Row: Category, SKU, and Rating -->
        <div class="product-heading-meta" style="display:flex; align-items:center; gap:0.65rem; flex-wrap:wrap; margin-bottom:0.85rem;">
          <span class="product-category-pill"><?= e($product['category_name']) ?></span>
          <span id="productSkuBadge" class="sku-badge">
            SKU: <span id="displaySku"><?= e($product['sku']) ?></span>
          </span>
          <?php if (!empty($product['min_order'])): ?>
            <span class="product-min-order" style="background:rgba(212,175,55,0.15); color:#D4AF37; border:1px solid #40351E; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:4px;">
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
        <h1 class="product-page-title" style="font-size:clamp(1.75rem, 2.5vw, 2.3rem); color:#F5F1E8; margin-bottom:0.75rem; line-height:1.25; font-weight:800;">
          <?= e($product['title']) ?>
        </h1>
        
        <p style="font-size:1rem; color:var(--text-muted); margin-bottom:1.5rem; line-height:1.65;">
          <?= e($product['short_desc']) ?>
        </p>

        <!-- ============================================================== -->
        <!-- DYNAMIC PRICING & VARIATION WORKBENCH -->
        <!-- ============================================================== -->
        <div class="pricing-calculator-card" style="background:#181818; border:1px solid #40351E; border-radius:10px; padding:1.75rem; box-shadow: 0 8px 24px rgba(0,0,0,0.7); margin-bottom:1.75rem;">
          <div style="font-size:0.85rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:#D4AF37; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #40351E; padding-bottom:0.75rem;">
            <span style="display:flex; align-items:center; gap:0.4rem;">
              <span style="color:#D4AF37; font-size:1.1rem;">⚙</span>
              <?= $hasVariations ? 'Select Options &amp; Quantity' : 'Configure Size &amp; Quantity' ?>
            </span>
            <span id="stockStatusBadge" class="stock-status-badge in-stock">
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
                    <label style="display:flex; justify-content:space-between; align-items:baseline; font-size:0.825rem; font-weight:700; color:#F5F1E8; margin-bottom:0.5rem;">
                      <span><?= ($aIdx + 1) ?>. Choose <?= e($attr['name']) ?>:</span>
                      <span id="selected_val_label_<?= $attr['id'] ?>" style="color:#D4AF37; font-weight:600; font-size:0.8rem;"></span>
                    </label>

                    <?php 
                      $isColor = (stripos($attr['name'], 'colour') !== false || stripos($attr['name'], 'color') !== false);
                    ?>

                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem;" data-attr-id="<?= $attr['id'] ?>" class="attr-group-wrapper">
                      <?php foreach ($attr['values'] as $vIdx => $val): ?>
                        <label class="variation-choice-btn <?= $isColor ? 'color-swatch-label' : 'pill-swatch-label' ?> <?= $vIdx === 0 ? 'is-selected' : '' ?>">
                          <input type="radio" 
                                 name="attr_<?= $attr['id'] ?>" 
                                 value="<?= $val['id'] ?>" 
                                 data-attr-id="<?= $attr['id'] ?>" 
                                 data-val-name="<?= e($val['value']) ?>" 
                                 <?= $vIdx === 0 ? 'checked' : '' ?> 
                                 style="display:none;" 
                                 onchange="handleVariationChange()">
                          
                          <?php if ($isColor && !empty($val['color_code'])): ?>
                            <span class="color-dot" style="background:<?= e($val['color_code']) ?>;"></span>
                          <?php endif; ?>

                          <span><?= e($val['value']) ?></span>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <!-- FALLBACK: SIZE & DIMENSIONS WORKBENCH -->
              <?php if (!empty($sizes)): ?>
                <div style="margin-bottom:1.25rem;">
                  <label style="display:block; font-size:0.825rem; font-weight:700; color:#F5F1E8; margin-bottom:0.5rem;">
                    1. Select Target Size / Dimensions:
                  </label>
                  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:0.6rem;">
                    <?php foreach ($sizes as $sIdx => $s): ?>
                      <label class="size-choice-label <?= $sIdx === 0 ? 'is-selected' : '' ?>">
                        <input type="radio" name="selected_size" value="<?= $s['id'] ?>" data-price="<?= $s['base_price'] ?>" <?= $sIdx === 0 ? 'checked' : '' ?> style="display:none;" onchange="updateLivePrice()">
                        <span style="font-size:0.85rem; font-weight:700; color:#F5F1E8;"><?= e($s['size_label']) ?></span>
                        <span class="size-base-price" style="font-size:0.75rem; margin-top:2px;">₹<?= number_format($s['base_price'], 2) ?> base</span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Paper Stock Options -->
              <?php if (!empty($groupedOptions['paper'])): ?>
                <div style="margin-bottom:1.25rem;">
                  <label style="display:block; font-size:0.825rem; font-weight:700; color:#F5F1E8; margin-bottom:0.5rem;">
                    2. Select Paper / Cardstock Grade:
                  </label>
                  <div style="display:flex; flex-direction:column; gap:0.4rem;">
                    <?php foreach ($groupedOptions['paper'] as $pIdx => $pOpt): ?>
                      <label style="display:flex; align-items:center; justify-content:space-between; font-size:0.85rem; padding:0.5rem 0.75rem; border:1px solid #40351E; border-radius:6px; cursor:pointer; background:#111111; color:#F5F1E8;">
                        <span style="display:flex; align-items:center; gap:0.5rem;">
                          <input type="radio" name="opt_paper" value="<?= $pOpt['id'] ?>" data-price="<?= $pOpt['additional_price'] ?>" <?= $pIdx === 0 ? 'checked' : '' ?> onchange="updateLivePrice()" style="accent-color:#D4AF37;">
                          <?= e($pOpt['option_name']) ?>
                        </span>
                        <span style="font-weight:700; color:#D4AF37; font-size:0.8rem;">
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
                <label for="calcQuantity" style="font-size:0.825rem; font-weight:700; color:#F5F1E8;">
                  <?= $hasVariations ? (count($productAttributes) + 1) : (!empty($sizes) ? '2' : '1') ?>. Order Quantity:
                </label>
                <span id="tierBadge" style="font-size:0.75rem; font-weight:700; color:#FFD76A; background:rgba(212,175,55,0.15); border:1px solid #40351E; padding:0.2rem 0.5rem; border-radius:4px;">
                  Standard Tier
                </span>
              </div>
              <div class="qty-stepper-wrap">
                <button type="button" onclick="adjustQty(-1)" class="qty-btn" aria-label="Decrease quantity">-</button>
                <input type="number" id="calcQuantity" value="<?= !empty($product['min_order']) && preg_match('/(\d+)/', $product['min_order'], $qm) ? $qm[1] : 1 ?>" min="1" max="99999" class="qty-input-field" oninput="updateLivePrice()" aria-label="Order quantity">
                <button type="button" onclick="adjustQty(1)" class="qty-btn" aria-label="Increase quantity">+</button>
              </div>
            </div>

            <!-- Finishing Options if present -->
            <?php if (!empty($groupedOptions['finishing'])): ?>
              <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.825rem; font-weight:700; color:#F5F1E8; margin-bottom:0.5rem;">
                  Print Enhancements &amp; Protective Finish:
                </label>
                <div style="display:flex; flex-direction:column; gap:0.4rem;">
                  <?php foreach ($groupedOptions['finishing'] as $fIdx => $fOpt): ?>
                    <label style="display:flex; align-items:center; justify-content:space-between; font-size:0.85rem; padding:0.5rem 0.75rem; border:1px solid #40351E; border-radius:6px; cursor:pointer; background:#111111; color:#F5F1E8;">
                      <span style="display:flex; align-items:center; gap:0.5rem;">
                        <input type="checkbox" name="opt_finishing[]" value="<?= $fOpt['id'] ?>" data-price="<?= $fOpt['additional_price'] ?>" <?= $fIdx === 0 ? 'checked' : '' ?> onchange="updateLivePrice()" style="accent-color:#D4AF37;">
                        <?= e($fOpt['option_name']) ?>
                      </span>
                      <span style="font-weight:700; color:#D4AF37; font-size:0.8rem;">
                        <?= $fOpt['additional_price'] > 0 ? '+₹' . number_format($fOpt['additional_price'], 2) . '/pc' : 'Standard' ?>
                      </span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- Live Calculated Price Summary Display -->
            <div class="product-price-summary-box">
              <div class="summary-row">
                <span>Effective Unit Price:</span>
                <div style="display:flex; align-items:baseline; gap:0.5rem;">
                  <span id="displayOldPrice" style="text-decoration:line-through; font-size:0.85rem; color:#B8B1A3; display:none;"></span>
                  <span id="displayUnitPrice" style="font-weight:700; color:#F5F1E8; font-size:1.05rem;">₹0.00</span>
                  <span id="displayDiscountTag" style="background:rgba(34,197,94,0.18); color:#4ade80; border:1px solid rgba(34,197,94,0.3); font-size:0.75rem; font-weight:700; padding:0.15rem 0.4rem; border-radius:4px; display:none;"></span>
                </div>
              </div>
              <div class="summary-row">
                <span>Quantity Selected:</span>
                <span id="displayQuantity" class="summary-row-strong">1 unit</span>
              </div>
              <div class="summary-row" style="padding-top:0.6rem; border-top:1px dashed #40351E; margin-top:0.4rem;">
                <span style="font-weight:800; color:#F5F1E8; font-size:1.05rem;">Estimated Total:</span>
                <div style="text-align:right;">
                  <span id="displayGrandTotal" class="grand-price-text">₹0.00</span>
                  <div style="font-size:0.75rem; color:#B8B1A3; margin-top:2px;">(Inclusive of 18% GST)</div>
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
<div class="product-scroll-sections" style="background:#080808; padding:3.5rem 0 5rem;">
  <div class="container container-readable">

    <!-- =================================================================== -->
    <!-- SECTION 2: TECHNICAL SPECIFICATIONS -->
    <!-- =================================================================== -->
    <?php if (!empty($product['specifications']) || !empty($product['description'])): ?>
    <section class="product-content-block" id="specs-section" style="background:#141414; border:1px solid #40351E; border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 18px rgba(0, 0, 0, 0.65); margin-bottom:2.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:#202020; color:#E8C45A; border:1px solid #40351E; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">02</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:#D4AF37;">Calibrated Parameters</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:#F5F1E8; margin:0; font-weight:800;">Technical Specifications</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <?php if (!empty($product['description'])): ?>
        <p style="margin-bottom:1.5rem; line-height:1.75; color:var(--text-secondary); font-size:0.95rem;">
          <?= nl2br(e($product['description'])) ?>
        </p>
      <?php endif; ?>

      <?php if (!empty($product['specifications'])): ?>
        <div style="background:#111111; border-left:4px solid #D4AF37; border-top:1px solid #40351E; border-right:1px solid #40351E; border-bottom:1px solid #40351E; padding:1.25rem 1.5rem; border-radius:var(--radius-sm); font-size:0.9rem; line-height:1.8; color:#F5F1E8;">
          <strong style="display:block; margin-bottom:0.5rem; color:#E8C45A;">Engineered Standards &amp; Materials:</strong>
          <?= nl2br(e($product['specifications'])) ?>
        </div>
      <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- =================================================================== -->
    <!-- SECTION 3: ARTWORK & FILE GUIDELINES -->
    <!-- =================================================================== -->
    <?php if (!empty($product['artwork_notes'])): ?>
    <section class="product-content-block" id="artwork-section" style="background:#141414; border:1px solid #40351E; border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 18px rgba(0, 0, 0, 0.65); margin-bottom:2.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:#202020; color:#E8C45A; border:1px solid #40351E; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">03</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:#D4AF37;">Prepress Standards</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:#F5F1E8; margin:0; font-weight:800;">Artwork &amp; File Guidelines</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <div class="artwork-requirement-box" style="background:#111111; border:1px solid #40351E; padding:1.25rem 1.5rem; border-radius:8px; margin-bottom:1.5rem;">
        <div class="artwork-requirement-heading" style="font-weight:700; color:#E8C45A; margin-bottom:0.35rem;">Artwork Requirement for this Product:</div>
        <p class="artwork-requirement-body" style="margin:0; font-size:0.925rem; color:#F5F1E8; line-height:1.65;">
          <?= nl2br(e($product['artwork_notes'])) ?>
        </p>
      </div>

      <div class="guideline-cards-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem;">
        <div class="guideline-card" style="padding:1.25rem; border:1px solid #40351E; border-radius:8px; background:#181818; box-shadow:0 4px 14px rgba(0,0,0,0.45); display:flex; flex-direction:column; justify-content:flex-start; min-height:100%;">
          <div class="guideline-title" style="font-weight:700; color:#E8C45A; font-size:0.95rem; margin-bottom:0.35rem; line-height:1.3;">Resolution &amp; Scale</div>
          <div class="guideline-desc" style="font-size:0.85rem; color:#B8B1A3; line-height:1.55; margin:0;">Minimum 300 DPI at 100% finished reproduction scale. Never upscale low-res raster imagery.</div>
        </div>
        <div class="guideline-card" style="padding:1.25rem; border:1px solid #40351E; border-radius:8px; background:#181818; box-shadow:0 4px 14px rgba(0,0,0,0.45); display:flex; flex-direction:column; justify-content:flex-start; min-height:100%;">
          <div class="guideline-title" style="font-weight:700; color:#E8C45A; font-size:0.95rem; margin-bottom:0.35rem; line-height:1.3;">Accepted Formats</div>
          <div class="guideline-desc" style="font-size:0.85rem; color:#B8B1A3; line-height:1.55; margin:0;">PNG (transparent), Adobe Illustrator (.AI), PDF (vector curves), PSD, or SVG.</div>
        </div>
        <div class="guideline-card" style="padding:1.25rem; border:1px solid #40351E; border-radius:8px; background:#181818; box-shadow:0 4px 14px rgba(0,0,0,0.45); display:flex; flex-direction:column; justify-content:flex-start; min-height:100%;">
          <div class="guideline-title" style="font-weight:700; color:#E8C45A; font-size:0.95rem; margin-bottom:0.35rem; line-height:1.3;">Color Profile</div>
          <div class="guideline-desc" style="font-size:0.85rem; color:#B8B1A3; line-height:1.55; margin:0;">CMYK or RGB embedded with standard ICC profiles. Prepress RIP auto-maps to vivid gamut.</div>
        </div>
        <div class="guideline-card" style="padding:1.25rem; border:1px solid #40351E; border-radius:8px; background:#181818; box-shadow:0 4px 14px rgba(0,0,0,0.45); display:flex; flex-direction:column; justify-content:flex-start; min-height:100%;">
          <div class="guideline-title" style="font-weight:700; color:#E8C45A; font-size:0.95rem; margin-bottom:0.35rem; line-height:1.3;">Transparency Check</div>
          <div class="guideline-desc" style="font-size:0.85rem; color:#B8B1A3; line-height:1.55; margin:0;">Background must be 100% transparent. Feathered edges should maintain sharp anti-aliasing.</div>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- =================================================================== -->
    <!-- SECTION 4: APPLICATION & HEAT PRESS GUIDE -->
    <!-- =================================================================== -->
    <?php if (!empty($product['application_guide'])): ?>
    <section class="product-content-block" id="application-section" style="background:#141414; border:1px solid #40351E; border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 18px rgba(0, 0, 0, 0.65); margin-bottom:2.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:#202020; color:#E8C45A; border:1px solid #40351E; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">04</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:#D4AF37;">Substrate Engineering</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:#F5F1E8; margin:0; font-weight:800;">Application &amp; Heat Press Guide</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <div style="margin-bottom:1.5rem; font-size:0.95rem; color:var(--text-secondary); line-height:1.75;">
        <?= nl2br(e($product['application_guide'])) ?>
      </div>

      <!-- Standard 4-Step Pressing Matrix -->
      <div class="product-spec-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
        <div style="background:#111111; padding:1.25rem; border-radius:var(--radius-sm); border:1px solid #40351E; text-align:center;">
          <div style="font-weight:700; color:#E8C45A; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.04em;">1. Temperature</div>
          <div style="font-size:1.35rem; font-weight:800; color:#FFD76A; margin:0.35rem 0;">155°C – 165°C</div>
          <div style="font-size:0.8rem; color:#B8B1A3;">Optimal for polyurethane adhesive activation.</div>
        </div>
        <div style="background:#111111; padding:1.25rem; border-radius:var(--radius-sm); border:1px solid #40351E; text-align:center;">
          <div style="font-weight:700; color:#E8C45A; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.04em;">2. Pressure &amp; Time</div>
          <div style="font-size:1.35rem; font-weight:800; color:#FFD76A; margin:0.35rem 0;">15 Seconds</div>
          <div style="font-size:0.8rem; color:#B8B1A3;">Medium-high pressure (40–50 PSI) for fiber lock.</div>
        </div>
        <div style="background:#111111; padding:1.25rem; border-radius:var(--radius-sm); border:1px solid #40351E; text-align:center;">
          <div style="font-weight:700; color:#E8C45A; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.04em;">3. Peel Method</div>
          <div style="font-size:1.35rem; font-weight:800; color:#FFD76A; margin:0.35rem 0;">Cold Peel</div>
          <div style="font-size:0.8rem; color:#B8B1A3;">Allow sheet to reach room temperature before peel.</div>
        </div>
        <div style="background:#111111; padding:1.25rem; border-radius:var(--radius-sm); border:1px solid #40351E; text-align:center;">
          <div style="font-weight:700; color:#E8C45A; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.04em;">4. Post-Press</div>
          <div style="font-size:1.35rem; font-weight:800; color:#FFD76A; margin:0.35rem 0;">5 Seconds</div>
          <div style="font-size:0.8rem; color:#B8B1A3;">Finish with silicone parchment for a soft hand feel.</div>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- =================================================================== -->
    <!-- SECTION 5: CUSTOMER REVIEWS -->
    <!-- =================================================================== -->
    <section class="product-content-block" id="reviews-section" style="background:#141414; border:1px solid #40351E; border-radius:12px; padding:clamp(1.5rem, 3vw, 2.5rem); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.7); margin-bottom:2.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:#202020; color:#E8C45A; border:1px solid #40351E; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">05</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:#D4AF37;">Verified Feedback</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:#F5F1E8; margin:0; font-weight:800;">Customer Reviews</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <div class="review-workbench-grid" style="margin-bottom:2.5rem;">
        <!-- Review Summary Stats -->
        <div class="review-summary-card">
          <div class="review-card-title">Rating Breakdown</div>
          <div style="display:flex; align-items:baseline; gap:0.5rem; margin-bottom:0.5rem;">
            <span class="rating-large-num"><?= $reviewCount > 0 ? $avgRating : '5.0' ?></span>
            <span class="rating-scale-denom">/ 5.0</span>
          </div>
          <div class="rating-stars-gold">
            <?php
            $filledStars = $reviewCount > 0 ? round($avgRating) : 5;
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $filledStars ? '★' : '☆';
            }
            ?>
          </div>
          <p class="rating-count-text">
            Based on <?= $reviewCount ?> <?= $reviewCount === 1 ? 'verified review' : 'verified reviews' ?>
          </p>

          <!-- Rating distribution bars -->
          <div class="rating-bars-stack">
            <?php for ($star = 5; $star >= 1; $star--): 
              $count = $ratingCounts[$star] ?? 0;
              $pct = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : ($star === 5 ? 100 : 0);
            ?>
              <div class="rating-bar-row">
                <span class="rating-bar-label"><?= $star ?>★</span>
                <div class="rating-bar-track">
                  <div class="rating-bar-fill" style="width:<?= $pct ?>%;"></div>
                </div>
                <span class="rating-bar-count"><?= $count ?></span>
              </div>
            <?php endfor; ?>
          </div>
        </div>

        <!-- Review Submission Form -->
        <div class="review-form-card">
          <h3 class="review-card-title">Write a Product Review</h3>
          <p class="review-card-desc">
            Have you printed or sampled this substrate? Share your production notes with our community.
          </p>

          <form id="productReviewForm" onsubmit="submitReview(event)">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= $productId ?>">

            <!-- Interactive Star Rating Picker -->
            <div class="form-group review-star-group" style="margin-bottom:1rem;">
              <label class="form-label" style="display:block; font-size:0.85rem; font-weight:700; color:#E8C45A; margin-bottom:0.35rem;">
                Your Rating <span style="color:#EF4444;">*</span>
              </label>
              <div id="starRatingPicker" class="star-rating-picker">
                <span onclick="setStarRating(1)" onmouseover="hoverStarRating(1)" onmouseout="resetStarRating()">★</span>
                <span onclick="setStarRating(2)" onmouseover="hoverStarRating(2)" onmouseout="resetStarRating()">★</span>
                <span onclick="setStarRating(3)" onmouseover="hoverStarRating(3)" onmouseout="resetStarRating()">★</span>
                <span onclick="setStarRating(4)" onmouseover="hoverStarRating(4)" onmouseout="resetStarRating()">★</span>
                <span onclick="setStarRating(5)" onmouseover="hoverStarRating(5)" onmouseout="resetStarRating()">★</span>
              </div>
              <input type="hidden" name="rating" id="selectedRatingInput" value="5">
            </div>

            <div class="review-form-row-2">
              <div class="form-group">
                <label class="form-label" style="display:block; font-size:0.85rem; font-weight:700; color:#F5F1E8; margin-bottom:0.35rem;">Your Name <span style="color:#EF4444;">*</span></label>
                <input type="text" name="customer_name" required class="form-control" placeholder="e.g. Vikram Malhotra">
              </div>
              <div class="form-group">
                <label class="form-label" style="display:block; font-size:0.85rem; font-weight:700; color:#F5F1E8; margin-bottom:0.35rem;">Email Address</label>
                <input type="email" name="customer_email" class="form-control" placeholder="e.g. vikram@studio.com">
              </div>
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
              <label class="form-label" style="display:block; font-size:0.85rem; font-weight:700; color:#F5F1E8; margin-bottom:0.35rem;">Review Headline <span style="color:#EF4444;">*</span></label>
              <input type="text" name="title" required class="form-control" placeholder="e.g. Flawless white opacity and vivid colors!">
            </div>

            <div class="form-group" style="margin-bottom:1.25rem;">
              <label class="form-label" style="display:block; font-size:0.85rem; font-weight:700; color:#F5F1E8; margin-bottom:0.35rem;">Written Review <span style="color:#EF4444;">*</span></label>
              <textarea name="review_text" rows="3" required class="form-control" placeholder="Describe the hand-feel, wash durability, or pressing performance..."></textarea>
            </div>

            <div id="reviewResponseAlert" style="display:none; padding:0.75rem; border-radius:6px; margin-bottom:1rem; font-size:0.85rem;"></div>

            <button type="submit" id="submitReviewBtn" class="btn btn-primary submit-review-btn">
              <span>Submit Verified Review</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Existing Reviews Feed -->
      <div style="border-top:1px solid #40351E; padding-top:2rem;">
        <h4 style="font-size:1.1rem; color:#E8C45A; margin-bottom:1.25rem; font-weight:700;">Customer Feedback Feed</h4>
        
        <?php if (empty($reviews)): ?>
          <div style="text-align:center; padding:2.5rem 1.5rem; background:#181818; border-radius:10px; border:1px dashed #40351E;">
            <div style="font-size:2rem; margin-bottom:0.5rem; color:#E8C45A;">★</div>
            <h4 style="color:#E8C45A; margin-bottom:0.35rem; font-size:1rem;">Be the first to review this product!</h4>
            <p style="color:#B8B1A3; font-size:0.85rem; max-width:440px; margin:0 auto 1rem;">
              Share your production experience and print outcomes with fellow apparel creators.
            </p>
          </div>
        <?php else: ?>
          <div style="display:flex; flex-direction:column; gap:1.25rem;">
            <?php foreach ($reviews as $rev): ?>
              <div class="review-card" style="padding:1.4rem; background:#181818; border:1px solid #40351E; border-radius:8px; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.5); transition: transform 0.25s ease, box-shadow 0.25s ease;">
                <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.35rem;">
                  <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="font-weight:700; color:#F5F1E8;"><?= e($rev['customer_name']) ?></span>
                    <?php if (!empty($rev['is_verified_purchase'])): ?>
                      <span style="background:rgba(212,175,55,0.15); color:#FFD76A; border:1px solid rgba(212,175,55,0.3); font-size:0.7rem; font-weight:700; padding:0.15rem 0.45rem; border-radius:4px;">
                        Verified Buyer
                      </span>
                    <?php endif; ?>
                  </div>
                  <span style="font-size:0.8rem; color:#B8B1A3;">
                    <?= date('M d, Y', strtotime($rev['created_at'])) ?>
                  </span>
                </div>

                <div style="color:#F59E0B; font-size:0.95rem; margin-bottom:0.4rem; letter-spacing:1px;">
                  <?php for ($s = 1; $s <= 5; $s++) { echo $s <= (int)$rev['rating'] ? '★' : '☆'; } ?>
                </div>

                <h5 style="font-size:0.95rem; font-weight:700; color:#E8C45A; margin-bottom:0.35rem;">
                  <?= e($rev['title']) ?>
                </h5>
                <p style="font-size:0.88rem; color:#B8B1A3; line-height:1.6; margin:0;">
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
    <section class="product-content-block" id="shipping-section" style="background:#141414; border:1px solid #40351E; border-radius:var(--radius-md); padding:clamp(1.75rem, 3vw, 2.5rem); box-shadow: 0 4px 18px rgba(0, 0, 0, 0.65); margin-bottom:1.5rem;">
      <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
        <span style="background:#202020; color:#E8C45A; border:1px solid #40351E; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem;">06</span>
        <div>
          <div style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:#D4AF37;">Logistics &amp; Delivery</div>
          <h2 style="font-size:clamp(1.25rem, 2vw, 1.6rem); color:#F5F1E8; margin:0; font-weight:800;">Turnaround &amp; PAN-India Shipping</h2>
        </div>
      </div>
      <div style="width:40px; height:3px; background:var(--gold); border-radius:2px; margin-bottom:1.5rem;"></div>

      <div style="margin-bottom:1.5rem; font-size:0.95rem; color:var(--text-secondary); line-height:1.75;">
        <?= nl2br(e($product['shipping_info'])) ?>
      </div>

      <div class="product-guarantee-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem;">
        <div style="padding:1.15rem; border:1px solid #40351E; border-radius:8px; background:#111111; box-shadow:0 4px 12px rgba(0,0,0,0.35);">
          <div style="font-weight:700; color:#E8C45A; font-size:0.9rem; margin-bottom:0.25rem;">Digital Prepress Proof</div>
          <div style="font-size:0.85rem; color:#B8B1A3; line-height:1.5;">Confirmed within 2–4 hours of artwork submission.</div>
        </div>
        <div style="padding:1.15rem; border:1px solid #40351E; border-radius:8px; background:#111111; box-shadow:0 4px 12px rgba(0,0,0,0.35);">
          <div style="font-weight:700; color:#E8C45A; font-size:0.9rem; margin-bottom:0.25rem;">Express Dispatch</div>
          <div style="font-size:0.85rem; color:#B8B1A3; line-height:1.5;">24–48 hours for gang roll transfers; 3–5 working days for finished apparel.</div>
        </div>
        <div style="padding:1.15rem; border:1px solid #40351E; border-radius:8px; background:#111111; box-shadow:0 4px 12px rgba(0,0,0,0.35);">
          <div style="font-weight:700; color:#E8C45A; font-size:0.9rem; margin-bottom:0.25rem;">Damage-Free Packaging</div>
          <div style="font-size:0.85rem; color:#B8B1A3; line-height:1.5;">Moisture-proof hard core tubes and 5-ply corrugated shipper boxes.</div>
        </div>
      </div>
    </section>
    <?php endif; ?>

  </div>
</div>

<!-- Embedded Quotation / Upload Form for this Product -->
<section class="section" id="quote-form" style="background:#0c0c0c; border-top:1px solid #40351E;">
  <div class="container container-readable">
    <div class="section-header text-center" style="margin-bottom:2rem;">
      <div class="section-label" style="justify-content:center; color:#E8C45A;">Prepress Quotation Workbench</div>
      <h2 style="font-size:clamp(1.75rem, 2.5vw, 2.2rem); color:#F5F1E8; margin-bottom:0.75rem; font-weight:800;">Request Custom Quotation for <?= e($product['title']) ?></h2>
      <p class="section-subtitle" style="margin:0 auto; color:#B8B1A3;">
        Upload your print-ready design or vector artwork to receive our prepress proof and wholesale price breakdown.
      </p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card artwork-upload-component" style="background:#141414; border:1px solid #40351E; border-radius:12px; padding:clamp(1.5rem, 3vw, 2.5rem); box-shadow:0 8px 30px rgba(0,0,0,0.7);">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="product_id" value="<?= e($product['id'] ?? 1) ?>">
        <input type="hidden" name="product_title" value="<?= e($product['title']) ?>">
        <input type="hidden" name="service_name" value="<?= e($product['service_slug'] ?? 'custom-printing') ?>">

        <div class="upload-studio-grid">
          <div>
            <div class="dropzone-container" style="border:2px dashed #40351E; border-radius:10px; padding:2rem; text-align:center; background:#111111; cursor:pointer;">
              <input type="file" name="artwork_file" class="file-input" accept=".png,.jpg,.jpeg,.pdf,.ai,.psd,.svg" style="display:none;">
              <div class="dropzone-icon" style="color:#E8C45A; margin-bottom:0.75rem;">
                <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
              </div>
              <div class="dropzone-title" style="font-weight:700; color:#F5F1E8; font-size:1.05rem;">
                Upload Artwork for <?= e($product['title']) ?>
              </div>
              <div class="dropzone-hint" style="color:#B8B1A3; font-size:0.825rem; margin-top:0.4rem;">
                Accepted: PNG (transparent), PDF, AI, PSD, SVG (Max 50 MB)
              </div>
              <button type="button" class="btn btn-sm" style="margin-top:1rem; border:1px solid #40351E; background:#202020; color:#E8C45A;">Select Design File</button>
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
<section class="related-products-section" style="background:#080808; border-top:1px solid #40351E; padding:4rem 0;">
  <div class="container">
    <div class="section-header text-center" style="margin-bottom:2.5rem;">
      <div class="section-label" style="justify-content:center; color:#E8C45A;">Complementary Print Solutions</div>
      <h2 style="font-size:clamp(1.75rem, 2.6vw, 2.2rem); color:#F5F1E8; font-weight:800;">Related Products &amp; Transfers</h2>
      <p class="section-subtitle" style="margin:0 auto; font-size:0.95rem; color:#B8B1A3;">
        Explore additional printing mediums and merchandise produced with our calibrated industrial technology.
      </p>
    </div>
        Explore additional printing mediums and merchandise produced with our calibrated industrial technology.
      </p>
    </div>

    <div class="products-grid">
      <?php foreach ($relatedProducts as $idx => $relProd): ?>
        <article class="product-card reveal slide-up stagger-<?= ($idx % 4) + 1 ?>">
          <div class="product-media" style="position:relative;">
            <span class="product-badge-tag <?= e($relProd['badge_class'] ?? 'badge-gold') ?>">
              <?= e($relProd['badge'] ?? $relProd['category_name']) ?>
            </span>
            <img src="<?= url(e($relProd['image_url'])) ?>" alt="<?= e($relProd['title']) ?> - Commercial Specimen" loading="lazy">
          </div>
          <div class="product-body">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
              <span class="product-category-label"><?= e($relProd['category_name']) ?></span>
              <?php if (!empty($relProd['sku'])): ?>
                <span class="sku-badge">SKU: <?= e($relProd['sku']) ?></span>
              <?php endif; ?>
            </div>
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

            <div class="product-card-actions" style="margin-top:0.75rem;">
              <a href="<?= product_url($relProd['slug']) ?>" class="btn btn-outline" aria-label="View details for <?= e($relProd['title']) ?>">
                View Details
              </a>
              <a href="<?= url('get-quote/?service_slug=' . urlencode($relProd['service_slug'] ?? '')) ?>" class="btn btn-primary" aria-label="Order or request quote for <?= e($relProd['title']) ?>">
                Request Quote
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Recently Viewed Products Section -->
<section class="recently-viewed-section" id="recentlyViewedSection" style="display:none; background:#080808; padding:3.5rem 0;">
  <div class="container">
    <div class="section-header" style="margin-bottom:1.5rem;">
      <div class="section-label" style="color:#D4AF37;">Browsing History</div>
      <h2 style="font-size:clamp(1.5rem, 2.2vw, 1.9rem); color:#F5F1E8; margin:0;">Recently Viewed Products</h2>
    </div>
    <div class="recent-products-grid" id="recentlyViewedGrid"></div>
  </div>
</section>

<!-- Sticky Add to Cart Bar (Desktop & Mobile) -->
<div class="sticky-cart-bar" id="stickyCartBar">
  <div class="sticky-cart-info">
    <img src="<?= $galleryImages[0] ?>" alt="<?= e($product['title']) ?>" class="sticky-cart-thumb">
    <div class="sticky-cart-details">
      <div class="sticky-cart-title"><?= e($product['title']) ?></div>
      <div class="sticky-cart-meta">
        <span class="sku-badge">SKU: <span id="stickySku"><?= e($product['sku']) ?></span></span>
        <span id="stickyVariantLabel" style="color:#B8B1A3;">Standard</span>
      </div>
    </div>
  </div>
  <div class="sticky-cart-actions">
    <div class="sticky-cart-price" id="stickyPrice"><?= e($product['starting_price'] ?? '₹249') ?></div>
    <button type="button" class="btn btn-primary sticky-order-btn" onclick="submitStickyPurchase()">
      Request Quote &darr;
    </button>
  </div>
</div>

<!-- Fullscreen Lightbox Modal -->
<div class="opx-lightbox-modal" id="opxLightboxModal" onclick="closeLightbox(event)">
  <button type="button" class="lightbox-btn lightbox-close-btn" onclick="closeLightbox(event)" aria-label="Close fullscreen view">
    ✕
  </button>
  <button type="button" class="lightbox-btn lightbox-prev-btn" onclick="prevLightboxImage(event)" aria-label="Previous image">
    ‹
  </button>
  <div class="lightbox-content" onclick="event.stopPropagation()">
    <img id="lightboxTargetImage" src="" alt="Fullscreen zoom view" class="lightbox-image-target" onclick="toggleLightboxZoom(this)">
  </div>
  <button type="button" class="lightbox-btn lightbox-next-btn" onclick="nextLightboxImage(event)" aria-label="Next image">
    ›
  </button>
</div>

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

  // Update styles for active radio labels (Black & Gold Theme)
  form.querySelectorAll('.variation-choice-btn').forEach(lbl => {
    const radio = lbl.querySelector('input[type="radio"]');
    if (radio && radio.checked) {
      lbl.classList.add('is-selected');
      lbl.style.borderColor = '#D4AF37';
      lbl.style.backgroundColor = 'rgba(212, 175, 55, 0.16)';
      lbl.style.color = '#FFD76A';
      lbl.style.boxShadow = '0 0 0 1px #D4AF37';
    } else {
      lbl.classList.remove('is-selected');
      lbl.style.borderColor = '#40351E';
      lbl.style.backgroundColor = '#181818';
      lbl.style.color = '#F5F1E8';
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
    const stickySku = document.getElementById('stickySku');
    if (stickySku) stickySku.textContent = matched.sku;
    const stickyVarLabel = document.getElementById('stickyVariantLabel');
    if (stickyVarLabel) stickyVarLabel.textContent = matched.combination_summary || matched.combination_key || 'Custom';
    const selectedVarSku = document.getElementById('selectedVariationSku');
    if (selectedVarSku) selectedVarSku.value = matched.sku;
    const selectedVarId = document.getElementById('selectedVariationId');
    if (selectedVarId) selectedVarId.value = matched.id;

    // 2. Update Stock Badge
    const stockBadge = document.getElementById('stockStatusBadge');
    if (stockBadge) {
      if (!matched.is_in_stock || matched.stock_quantity <= 0) {
        stockBadge.innerHTML = '✕ Out of Stock';
        stockBadge.className = 'stock-status-badge out-of-stock';
        stockBadge.style.background = 'rgba(239,68,68,0.18)';
        stockBadge.style.color = '#f87171';
        stockBadge.style.border = '1px solid rgba(239,68,68,0.4)';
      } else if (matched.stock_quantity <= 10) {
        stockBadge.innerHTML = `⚠ Low Stock (${matched.stock_quantity} left)`;
        stockBadge.className = 'stock-status-badge low-stock';
        stockBadge.style.background = 'rgba(245,158,11,0.18)';
        stockBadge.style.color = '#fbbf24';
        stockBadge.style.border = '1px solid rgba(245,158,11,0.4)';
      } else {
        stockBadge.innerHTML = `● In Stock (${matched.stock_quantity} available)`;
        stockBadge.className = 'stock-status-badge in-stock';
        stockBadge.style.background = 'rgba(34,197,94,0.15)';
        stockBadge.style.color = '#4ade80';
        stockBadge.style.border = '1px solid rgba(34,197,94,0.35)';
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
    // Highlight chosen size label (Black & Gold)
    form.querySelectorAll('.size-choice-label').forEach(lbl => {
      const radio = lbl.querySelector('input[type="radio"]');
      if (radio && radio.checked) {
        lbl.classList.add('is-selected');
        lbl.style.borderColor = '#D4AF37';
        lbl.style.backgroundColor = 'rgba(212, 175, 55, 0.16)';
        lbl.style.color = '#FFD76A';
      } else {
        lbl.classList.remove('is-selected');
        lbl.style.borderColor = '#40351E';
        lbl.style.backgroundColor = '#181818';
        lbl.style.color = '#F5F1E8';
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
    tierBadge.style.color = discountPct > 0 ? '#10B981' : '#D4AF37';
    tierBadge.style.background = discountPct > 0 ? 'rgba(16,185,129,0.1)' : 'rgba(212,175,55,0.12)';
    tierBadge.style.border = discountPct > 0 ? '1px solid rgba(16,185,129,0.3)' : '1px solid #40351E';
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
  const stickyPrice = document.getElementById('stickyPrice');
  if (stickyPrice) stickyPrice.textContent = '₹' + grandTotal.toFixed(2);
}

// ==============================================================
// ADVANCED PRODUCT GALLERY & LIGHTBOX (Phase 3)
// ==============================================================
const GALLERY_IMAGES = <?= json_encode($galleryImages) ?>;
let currentGalleryIdx = 0;

function switchGalleryImage(idx, src, btn) {
  currentGalleryIdx = idx;
  const img = document.getElementById('mainProductImage');
  if (img) {
    img.style.opacity = '0.3';
    setTimeout(() => {
      img.src = src;
      img.style.opacity = '1';
    }, 150);
  }
  document.querySelectorAll('.gallery-thumb-btn').forEach(b => b.classList.remove('is-active'));
  if (btn) btn.classList.add('is-active');
}

function openLightbox(idx) {
  currentGalleryIdx = typeof idx === 'number' ? idx : currentGalleryIdx;
  const modal = document.getElementById('opxLightboxModal');
  const targetImg = document.getElementById('lightboxTargetImage');
  if (modal && targetImg) {
    targetImg.src = GALLERY_IMAGES[currentGalleryIdx] || GALLERY_IMAGES[0];
    targetImg.classList.remove('zoomed');
    modal.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  }
}

function closeLightbox(e) {
  const modal = document.getElementById('opxLightboxModal');
  if (modal) {
    modal.classList.remove('is-active');
    document.body.style.overflow = '';
  }
}

function nextLightboxImage(e) {
  if (e) e.stopPropagation();
  currentGalleryIdx = (currentGalleryIdx + 1) % GALLERY_IMAGES.length;
  const targetImg = document.getElementById('lightboxTargetImage');
  if (targetImg) {
    targetImg.classList.remove('zoomed');
    targetImg.src = GALLERY_IMAGES[currentGalleryIdx];
  }
}

function prevLightboxImage(e) {
  if (e) e.stopPropagation();
  currentGalleryIdx = (currentGalleryIdx - 1 + GALLERY_IMAGES.length) % GALLERY_IMAGES.length;
  const targetImg = document.getElementById('lightboxTargetImage');
  if (targetImg) {
    targetImg.classList.remove('zoomed');
    targetImg.src = GALLERY_IMAGES[currentGalleryIdx];
  }
}

function toggleLightboxZoom(img) {
  img.classList.toggle('zoomed');
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeLightbox();
  if (e.key === 'ArrowRight') nextLightboxImage();
  if (e.key === 'ArrowLeft') prevLightboxImage();
});

// ==============================================================
// STICKY ADD TO CART OBSERVER (Phase 4)
// ==============================================================
function submitStickyPurchase() {
  const formSection = document.getElementById('quote-form') || document.getElementById('priceCalculatorForm');
  if (formSection) {
    formSection.scrollIntoView({ behavior: 'smooth' });
    syncQuoteForm();
  }
}

// Initial Run & Observers
document.addEventListener('DOMContentLoaded', () => {
  if (HAS_VARIATIONS) {
    handleVariationChange();
  } else {
    updateLivePrice();
  }
  setStarRating(5);

  // Sticky Bar Scroll Trigger
  const buyForm = document.getElementById('priceCalculatorForm');
  const stickyBar = document.getElementById('stickyCartBar');
  const reviewForm = document.getElementById('productReviewForm');

  if (buyForm && stickyBar && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting && !stickyBar.classList.contains('is-hidden-by-form')) {
          stickyBar.classList.add('is-visible');
          document.body.classList.add('has-sticky-bar-active');
        } else if (entry.isIntersecting) {
          stickyBar.classList.remove('is-visible');
          document.body.classList.remove('has-sticky-bar-active');
        }
      });
    }, { threshold: 0.1 });
    observer.observe(buyForm);
  }

  // Hide sticky purchase bar whenever the review form is focused or being filled out
  if (reviewForm && stickyBar) {
    reviewForm.addEventListener('focusin', function () {
      stickyBar.classList.add('is-hidden-by-form');
      stickyBar.classList.remove('is-visible');
      document.body.classList.remove('has-sticky-bar-active');
    });

    reviewForm.addEventListener('focusout', function () {
      setTimeout(function () {
        if (!reviewForm.contains(document.activeElement)) {
          stickyBar.classList.remove('is-hidden-by-form');
          if (buyForm) {
            const rect = buyForm.getBoundingClientRect();
            const isInView = rect.top >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight);
            if (!isInView && rect.bottom < 0) {
              stickyBar.classList.add('is-visible');
              document.body.classList.add('has-sticky-bar-active');
            }
          }
        }
      }, 200);
    });
  }

  // ==============================================================
  // RECENTLY VIEWED PRODUCTS TRACKER (Phase 7)
  // ==============================================================
  try {
    const curProduct = {
      id: <?= (int)$product['id'] ?>,
      sku: <?= json_encode($product['sku']) ?>,
      title: <?= json_encode($product['title']) ?>,
      slug: <?= json_encode($product['slug']) ?>,
      url: <?= json_encode(product_url($product['slug'])) ?>,
      image_url: <?= json_encode(url(e($product['image_url']))) ?>,
      price: <?= json_encode($product['starting_price'] ?? '₹249') ?>,
      category: <?= json_encode($product['category_name']) ?>
    };

    let recentList = JSON.parse(localStorage.getItem('orderprintex_recent_views') || '[]');
    recentList = recentList.filter(item => item.id !== curProduct.id);
    recentList.unshift(curProduct);
    if (recentList.length > 6) recentList = recentList.slice(0, 6);
    localStorage.setItem('orderprintex_recent_views', JSON.stringify(recentList));

    const otherViews = recentList.filter(item => item.id !== curProduct.id);
    if (otherViews.length > 0) {
      const rSection = document.getElementById('recentlyViewedSection');
      const rGrid = document.getElementById('recentlyViewedGrid');
      if (rSection && rGrid) {
        rGrid.innerHTML = '';
        otherViews.slice(0, 4).forEach(item => {
          const card = document.createElement('article');
          card.className = 'product-card';
          card.style.cssText = 'background:#181818; border:1px solid #40351E; border-radius:8px; overflow:hidden; display:flex; flex-direction:column;';
          card.innerHTML = `
            <div class="product-media" style="position:relative; background:#111111; height:200px; display:flex; align-items:center; justify-content:center; padding:1rem;">
              <img src="${item.image_url}" alt="${item.title}" style="max-height:100%; max-width:100%; object-fit:contain;">
            </div>
            <div class="product-body" style="padding:1rem; display:flex; flex-direction:column; flex:1;">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.4rem;">
                <span class="product-category-label">${item.category}</span>
                <span class="sku-badge">SKU: ${item.sku}</span>
              </div>
              <h4 style="font-size:0.95rem; margin-bottom:0.4rem; font-weight:700;">
                <a href="${item.url}" style="color:#F5F1E8; text-decoration:none;">${item.title}</a>
              </h4>
              <div style="margin-top:auto; display:flex; justify-content:space-between; align-items:baseline; padding-top:0.5rem; border-top:1px solid #28221b;">
                <span style="color:#FFD76A; font-weight:800; font-size:1.05rem;">From ${item.price}</span>
                <a href="${item.url}" class="btn btn-outline btn-xs" style="border-color:#40351E; color:#D4AF37;">View</a>
              </div>
            </div>
          `;
          rGrid.appendChild(card);
        });
        rSection.style.display = 'block';
      }
    }
  } catch(e) {}
});

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
