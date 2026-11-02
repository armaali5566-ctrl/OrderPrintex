<?php
/**
 * OrderPrintex - Single Product Page & Dynamic Pricing Workbench
 * Features:
 *   - Unique Product SKU
 *   - Object-fit contain full product imagery (never cropped)
 *   - Dynamic Size-Based Pricing with live client-side + backend validation
 *   - Quantity-based wholesale discount tiers
 *   - Material / paper stock and finishing option add-ons
 *   - Complete Product Reviews system (Star rating, breakdown, verified purchase badge)
 *   - Interactive specifications tabs
 *   - Schema.org Product and BreadcrumbList JSON-LD
 *   - Safe 404 handling (never redirects invalid slugs to homepage)
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

// Fetch product sizes from DB for dynamic size-based pricing
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

// Group options by option_type
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
$pageTitle = e($product['title']) . " (SKU: " . e($product['sku']) . ") | OrderPrintex";
$pageDesc  = e($product['short_desc']);
$canonical = product_url($product['slug']);

require_once __DIR__ . '/includes/header.php';

// Fetch related products
$allProducts = get_catalog_products();
$relatedProducts = array_values(array_filter($allProducts, function ($p) use ($product) {
    return ($p['slug'] ?? '') !== $product['slug'];
}));
$relatedProducts = array_slice($relatedProducts, 0, 4);
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
      "item": <?= json_encode(url('products/?category=' . urlencode($product['category_slug'] ?? ''))) ?>
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
    <a href="<?= url('products/?category=' . urlencode($product['category_slug'] ?? '')) ?>" style="color:var(--navy); font-weight:600;">
      <?= e($product['category_name']) ?>
    </a>
    <span style="color:var(--slate-subtle);">&rsaquo;</span>
    <span style="color:var(--royal); font-weight:700;" aria-current="page"><?= e($product['title']) ?></span>
  </div>
</nav>

<!-- Product Details Main Section -->
<section class="section section-white" style="padding-top:3.5rem; padding-bottom:4.5rem;">
  <div class="container">
    <div class="product-detail-grid">
      
      <!-- Left Column: Large Product Imagery (Fully Contained, Neutral Background, Zero Cropping) -->
      <div class="reveal slide-right">
        <div class="product-detail-media-card" style="background:#F8F5F0; border-radius:var(--radius-lg); border:1px solid var(--border-light); padding:2rem; text-align:center; position:relative; min-height:480px; display:flex; align-items:center; justify-content:center;">
          <!-- Permanent status badge that never disappears on hover -->
          <span class="product-badge-tag <?= e($product['badge_class'] ?? 'badge-gold') ?>" style="position:absolute; top:1.25rem; left:1.25rem; z-index:10;">
            <?= e($product['badge'] ?? 'Commercial Grade') ?>
          </span>

          <img src="<?= url(e($product['image_url'])) ?>" alt="<?= e($product['title']) ?> - Full Product View" style="max-width:100%; max-height:420px; object-fit:contain; display:block; margin:0 auto; filter:drop-shadow(0 10px 20px rgba(0,0,0,0.08));" loading="eager">
        </div>

        <!-- Technical Guarantees Bar -->
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:0.75rem; margin-top:1.25rem;">
          <div style="background:var(--ivory); border:1px solid var(--border-light); padding:0.85rem 0.65rem; border-radius:var(--radius-sm); text-align:center;">
            <div style="color:var(--royal); font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:var(--navy); line-height:1.3;">300 DPI Prepress Audit</div>
          </div>
          <div style="background:var(--ivory); border:1px solid var(--border-light); padding:0.85rem 0.65rem; border-radius:var(--radius-sm); text-align:center;">
            <div style="color:var(--royal); font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:var(--navy); line-height:1.3;">50+ Wash Longevity</div>
          </div>
          <div style="background:var(--ivory); border:1px solid var(--border-light); padding:0.85rem 0.65rem; border-radius:var(--radius-sm); text-align:center;">
            <div style="color:var(--royal); font-size:1.1rem; margin-bottom:0.25rem;">✓</div>
            <div style="font-size:0.75rem; font-weight:700; color:var(--navy); line-height:1.3;">PAN-India Express Shipping</div>
          </div>
        </div>
      </div>

      <!-- Right Column: Product Title, SKU, Dynamic Size-Based Pricing Workbench -->
      <div class="reveal slide-left">
        <!-- Header Meta Row: Category, SKU, and Rating -->
        <div class="product-heading-meta" style="display:flex; align-items:center; gap:0.65rem; flex-wrap:wrap; margin-bottom:0.85rem;">
          <span class="product-category-pill"><?= e($product['category_name']) ?></span>
          <span style="background:var(--navy); color:#FFFFFF; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:4px; letter-spacing:0.04em;">
            SKU: <?= e($product['sku']) ?>
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
        <!-- DYNAMIC SIZE-BASED PRICING WORKBENCH (Client + Server Validated) -->
        <!-- ============================================================== -->
        <div class="pricing-calculator-card" style="background:#FFFFFF; border:1.5px solid var(--border-light); border-radius:var(--radius-md); padding:1.5rem; box-shadow:var(--shadow-sm); margin-bottom:1.75rem;">
          <div style="font-size:0.85rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:var(--navy); margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between;">
            <span>Configure Size &amp; Quantity Pricing</span>
            <span style="color:var(--royal); font-weight:600; text-transform:none; font-size:0.8rem;">Live Price Calculator</span>
          </div>

          <form id="priceCalculatorForm">
            <input type="hidden" id="calcProductId" value="<?= $productId ?>">

            <!-- 1. Size Selection -->
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

            <!-- 2. Quantity Input with Stepper and Wholesale Tiers -->
            <div style="margin-bottom:1.25rem;">
              <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.5rem;">
                <label for="calcQuantity" style="font-size:0.825rem; font-weight:700; color:var(--navy);">
                  2. Order Quantity:
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

            <!-- 3. Material / Paper Stock Options (if applicable) -->
            <?php if (!empty($groupedOptions['paper'])): ?>
              <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem;">
                  3. Select Paper / Cardstock Grade:
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

            <!-- 4. Finishing Options -->
            <?php if (!empty($groupedOptions['finishing'])): ?>
              <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem;">
                  <?= !empty($groupedOptions['paper']) ? '4' : '3' ?>. Protective Finish &amp; Print Enhancements:
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
                <span id="displayUnitPrice" style="font-weight:700; color:var(--navy); font-size:1rem;">₹0.00</span>
              </div>
              <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.6rem; font-size:0.85rem; color:var(--text-muted);">
                <span>Quantity Selected:</span>
                <span id="displayQuantity" style="font-weight:700; color:var(--navy);">1</span>
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

    <!-- Product Specifications & Informational Tabs -->
    <div class="product-specs-tabs reveal slide-up" id="productSpecsTabs" style="margin-top:3.5rem;">
      <div class="specs-tab-header" role="tablist">
        <button type="button" class="specs-tab-btn active" onclick="switchSpecTab(0)" role="tab" aria-selected="true">
          Technical Specifications
        </button>
        <button type="button" class="specs-tab-btn" onclick="switchSpecTab(1)" role="tab" aria-selected="false">
          Artwork &amp; File Guidelines
        </button>
        <button type="button" class="specs-tab-btn" onclick="switchSpecTab(2)" role="tab" aria-selected="false">
          Application &amp; Heat Press Guide
        </button>
        <button type="button" class="specs-tab-btn" onclick="switchSpecTab(3)" role="tab" aria-selected="false">
          Customer Reviews (<?= $reviewCount ?>)
        </button>
        <button type="button" class="specs-tab-btn" onclick="switchSpecTab(4)" role="tab" aria-selected="false">
          Turnaround &amp; Shipping
        </button>
      </div>

      <!-- Tab 1: Specs -->
      <div class="specs-tab-content" id="tabContent-0" style="display:block;">
        <h3 style="font-size:1.15rem; color:var(--navy); margin-bottom:1rem;">Production Engineering Specifications</h3>
        <p style="margin-bottom:1.25rem; line-height:1.7;">
          <?= nl2br(e($product['description'])) ?>
        </p>
        <div style="background:var(--ivory); border-left:3px solid var(--gold); padding:1.25rem 1.5rem; border-radius:var(--radius-sm); font-size:0.9rem;">
          <strong>Calibrated Parameters:</strong><br>
          <?= nl2br(e($product['specifications'])) ?>
        </div>
      </div>

      <!-- Tab 2: Artwork Guide -->
      <div class="specs-tab-content" id="tabContent-1" style="display:none;">
        <h3 style="font-size:1.15rem; color:var(--navy); margin-bottom:1rem;">Artwork Preparation Standards</h3>
        <p style="margin-bottom:1rem;">
          <?= e($product['artwork_notes'] ?? 'Provide 300 DPI files with transparent background.') ?>
        </p>
        <ul style="list-style:disc; padding-left:1.5rem; line-height:1.8;">
          <li><strong>Resolution:</strong> 300 DPI at 100% finished print dimensions.</li>
          <li><strong>Supported Formats:</strong> PNG (transparent background), Adobe Illustrator (.AI), Photoshop (.PSD), PDF, or SVG.</li>
          <li><strong>Color Mode:</strong> CMYK or RGB (converted with our calibrated ICC printer profile for vibrant gamut).</li>
          <li><strong>Transparency:</strong> Ensure negative spaces are 100% transparent so no unwanted white base is deposited.</li>
        </ul>
      </div>

      <!-- Tab 3: Heat Press Guide -->
      <div class="specs-tab-content" id="tabContent-2" style="display:none;">
        <h3 style="font-size:1.15rem; color:var(--navy); margin-bottom:1rem;">Commercial Application &amp; Pressing Instructions</h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1.25rem; margin-top:1rem;">
          <div style="background:var(--ivory); padding:1rem; border-radius:var(--radius-sm); border:1px solid var(--border-light);">
            <div style="font-weight:700; color:var(--navy);">1. Temperature</div>
            <div style="font-size:1.25rem; font-weight:800; color:var(--royal); margin:0.35rem 0;">155°C – 165°C</div>
            <div style="font-size:0.8rem; color:var(--slate);">Optimal for polyurethane TPU adhesive activation.</div>
          </div>
          <div style="background:var(--ivory); padding:1rem; border-radius:var(--radius-sm); border:1px solid var(--border-light);">
            <div style="font-weight:700; color:var(--navy);">2. Pressure &amp; Time</div>
            <div style="font-size:1.25rem; font-weight:800; color:var(--royal); margin:0.35rem 0;">15 Seconds</div>
            <div style="font-size:0.8rem; color:var(--slate);">Medium-high pressure (40–50 PSI) for mechanical fiber bond.</div>
          </div>
          <div style="background:var(--ivory); padding:1rem; border-radius:var(--radius-sm); border:1px solid var(--border-light);">
            <div style="font-weight:700; color:var(--navy);">3. Peel Method</div>
            <div style="font-size:1.25rem; font-weight:800; color:var(--royal); margin:0.35rem 0;">Cold Peel</div>
            <div style="font-size:0.8rem; color:var(--slate);">Allow transfer to cool completely before smooth peeling.</div>
          </div>
          <div style="background:var(--ivory); padding:1rem; border-radius:var(--radius-sm); border:1px solid var(--border-light);">
            <div style="font-weight:700; color:var(--navy);">4. Post-Press</div>
            <div style="font-size:1.25rem; font-weight:800; color:var(--royal); margin:0.35rem 0;">5 Seconds</div>
            <div style="font-size:0.8rem; color:var(--slate);">Post-press with parchment paper for a soft-hand retail finish.</div>
          </div>
        </div>
      </div>

      <!-- Tab 4: Customer Reviews (Phase 3 Requirement) -->
      <div class="specs-tab-content" id="tabContent-3" style="display:none;">
        <div id="reviews-section">
          <div style="display:grid; grid-template-columns:1fr 1.6fr; gap:2.5rem; align-items:flex-start; margin-bottom:2.5rem;">
            <!-- Review Summary Stats -->
            <div style="background:var(--ivory); padding:1.5rem; border-radius:var(--radius-md); border:1px solid var(--border-light);">
              <h3 style="font-size:1.15rem; color:var(--navy); margin-bottom:1rem;">Customer Reviews</h3>
              <div style="display:flex; align-items:baseline; gap:0.5rem; margin-bottom:0.5rem;">
                <span style="font-size:2.8rem; font-weight:800; color:var(--navy); line-height:1;"><?= $reviewCount > 0 ? $avgRating : '0.0' ?></span>
                <span style="font-size:1rem; color:var(--text-muted);">/ 5.0</span>
              </div>
              <div style="color:#F59E0B; font-size:1.2rem; margin-bottom:0.75rem;">
                <?php
                $filledStars = round($avgRating);
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
                  $pct = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0;
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
            <div style="background:#FFFFFF; border:1px solid var(--border-light); border-radius:var(--radius-md); padding:1.5rem; box-shadow:var(--shadow-sm);">
              <h3 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem;">Write a Product Review</h3>
              <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.25rem;">
                Have you received or printed this product with OrderPrintex? Share your honest feedback with our community.
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
                    <input type="text" name="customer_name" required class="form-control" placeholder="e.g. Arun Sharma">
                  </div>
                  <div>
                    <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Email Address</label>
                    <input type="email" name="customer_email" class="form-control" placeholder="e.g. arun@brand.com">
                  </div>
                </div>

                <div style="margin-bottom:1rem;">
                  <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Review Headline *</label>
                  <input type="text" name="title" required class="form-control" placeholder="e.g. Exceptional color vibrancy and durability!">
                </div>

                <div style="margin-bottom:1.25rem;">
                  <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Written Review *</label>
                  <textarea name="review_text" rows="3" required class="form-control" placeholder="Describe the print quality, wash performance, hand-feel, or delivery speed..."></textarea>
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
            <h4 style="font-size:1.1rem; color:var(--navy); margin-bottom:1.25rem;">Customer Feedback Feed</h4>
            
            <?php if (empty($reviews)): ?>
              <div style="text-align:center; padding:3rem 1.5rem; background:var(--ivory); border-radius:var(--radius-md); border:1px dashed var(--border-light);">
                <div style="font-size:2rem; margin-bottom:0.75rem;">⭐</div>
                <h4 style="color:var(--navy); margin-bottom:0.35rem;">Be the first to review this product!</h4>
                <p style="color:var(--text-muted); font-size:0.88rem; max-width:440px; margin:0 auto 1rem;">
                  Help other apparel brands and creators evaluate this print substrate by submitting the first review above.
                </p>
              </div>
            <?php else: ?>
              <div style="display:flex; flex-direction:column; gap:1.25rem;">
                <?php foreach ($reviews as $rev): ?>
                  <div style="padding:1.25rem; background:#FFFFFF; border:1px solid var(--border-light); border-radius:8px;">
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
        </div>
      </div>

      <!-- Tab 5: Turnaround & Shipping -->
      <div class="specs-tab-content" id="tabContent-4" style="display:none;">
        <h3 style="font-size:1.15rem; color:var(--navy); margin-bottom:1rem;">Production Turnaround &amp; PAN-India Shipping</h3>
        <ul style="list-style:disc; padding-left:1.5rem; line-height:1.8;">
          <li><strong>Prepress Proof:</strong> Digital proof and layout confirmation sent within 2–4 hours of artwork upload.</li>
          <li><strong>Standard Dispatch:</strong> 24–48 hours for gang roll transfers; 3–5 working days for bulk finished garments.</li>
          <li><strong>Same-Day Dispatch:</strong> Available for rush gang roll print orders placed before 1:00 PM IST.</li>
          <li><strong>Packaging:</strong> Moisture-barrier protective tubes and heavy-duty corrugated cartons for zero damage in transit.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

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

    <div class="upload-card reveal scale-in artwork-upload-component" style="background:#FFFFFF; border:1px solid var(--border-light); border-radius:var(--radius-lg); padding:clamp(1.5rem, 3vw, 2.5rem); box-shadow:var(--shadow-md);">
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

<!-- Related Products Section (Linking with Clean /product/{slug}/ URLs) -->
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
<!-- DYNAMIC SIZE-BASED PRICING & REVIEW INTERACTION LOGIC -->
<!-- ============================================================== -->
<script>
// Pricing Tiers Data from PHP
const PRICING_TIERS = <?= json_encode($tiers) ?>;

function adjustQty(delta) {
  const input = document.getElementById('calcQuantity');
  let val = parseInt(input.value, 10) || 1;
  val = Math.max(1, val + delta);
  input.value = val;
  updateLivePrice();
}

function updateLivePrice() {
  const form = document.getElementById('priceCalculatorForm');
  if (!form) return;

  const sizeRadio = form.querySelector('input[name="selected_size"]:checked');
  const basePrice = sizeRadio ? parseFloat(sizeRadio.getAttribute('data-price')) : <?= (float)preg_replace('/[^0-9.]/', '', $product['starting_price'] ?? '249') ?>;
  
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

  const qty = Math.max(1, parseInt(document.getElementById('calcQuantity').value, 10) || 1);

  // Check tiers for discount
  let discountPct = 0;
  let tierLabel = 'Standard Tier';
  if (Array.isArray(PRICING_TIERS)) {
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
  // Paper
  const paperRadio = form.querySelector('input[name="opt_paper"]:checked');
  if (paperRadio) {
    optionsCharge += parseFloat(paperRadio.getAttribute('data-price')) || 0;
  }
  // Finishing
  form.querySelectorAll('input[name="opt_finishing[]"]:checked').forEach(cb => {
    optionsCharge += parseFloat(cb.getAttribute('data-price')) || 0;
  });

  // Calculations
  const discountedUnit = basePrice * (1 - (discountPct / 100));
  const finalUnitPrice = discountedUnit + optionsCharge;
  const subtotal = finalUnitPrice * qty;
  const tax = subtotal * 0.18;
  const grandTotal = subtotal + tax;

  // Update DOM
  document.getElementById('displayUnitPrice').textContent = '₹' + finalUnitPrice.toFixed(2);
  document.getElementById('displayQuantity').textContent = qty + (qty === 1 ? ' unit' : ' units');
  document.getElementById('displayGrandTotal').textContent = '₹' + grandTotal.toFixed(2);
}

function syncQuoteForm() {
  const qty = document.getElementById('calcQuantity').value;
  const quoteQty = document.getElementById('syncQuoteQty');
  if (quoteQty) quoteQty.value = qty;

  const sizeRadio = document.querySelector('input[name="selected_size"]:checked');
  if (sizeRadio) {
    const label = sizeRadio.closest('.size-choice-label').querySelector('span').textContent;
    const sizingInput = document.getElementById('syncQuoteSizing');
    if (sizingInput) sizingInput.value = label;
  }
}

// Spec Tabs Switching
function switchSpecTab(index) {
  const tabs = document.querySelectorAll('.specs-tab-btn');
  const contents = document.querySelectorAll('.specs-tab-content');

  tabs.forEach((tab, i) => {
    if (i === index) {
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
    } else {
      tab.classList.remove('active');
      tab.setAttribute('aria-selected', 'false');
    }
  });

  contents.forEach((content, i) => {
    content.style.display = (i === index) ? 'block' : 'none';
  });
}

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

// Initial price run
document.addEventListener('DOMContentLoaded', () => {
  updateLivePrice();
  setStarRating(5);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
