<?php
/**
 * OrderPrintex - Production Gallery & Showcase
 */

$pageTitle = "Production Gallery & Print Showcase | OrderPrintex Delhi";
$pageDesc  = "Browse actual production runs and commercial printing work completed at the OrderPrintex Delhi studio: DTF apparel prints, UV DTF hard-surface decals, and corporate gifts.";

require_once __DIR__ . '/includes/header.php';

$categoryNames = [
  'dtf'       => 'DTF Prints',
  'uv_dtf'    => 'UV DTF Decals',
  'tshirt'    => 'T-Shirts',
  'packaging' => 'Packaging',
  'stickers'  => 'Stickers',
  'corporate' => 'Corporate Gifts',
  'branding'  => 'Branding Material'
];

$defaultSpecs = [
  'dtf' => [
    'resolution' => '1440 DPI',
    'ink_layer'  => 'CMYK colors with a white base layer',
    'substrate'  => 'Cotton, polyester blends and fleece',
    'durability' => 'Built for 50+ washes with proper care'
  ],
  'uv_dtf' => [
    'resolution' => '2880 DPI Micro-Drop Precision',
    'ink_layer'  => 'High-Build 3D Tactile Gloss Varnish',
    'substrate'  => 'Glassware, Metal, Tumblers, Acrylic & Wood',
    'durability' => 'Waterproof, Dishwasher-Safe & Scratch-Proof'
  ],
  'tshirt' => [
    'resolution' => 'High-Density Continuous Tone DTF',
    'ink_layer'  => 'Bio-Elastic TPU Hot-Melt Adhesive',
    'substrate'  => '240 GSM Super-Combed Cotton Single Jersey',
    'durability' => 'Pre-Shrunk, Zero Distortion Over Repeated Washes'
  ],
  'corporate' => [
    'resolution' => 'Rotary UV Print & Fiber Laser Engraving',
    'ink_layer'  => 'Deep-Etch Laser / Multi-Surface Decal',
    'substrate'  => 'SS 304 Food-Grade Metal, PU Leather & Metal',
    'durability' => 'Permanent Etching & Commercial Decal Adhesion'
  ],
  'packaging' => [
    'resolution' => 'Vector Precision Metallic Hot-Foil Stamping',
    'ink_layer'  => 'Matte Soft-Touch Protective Laminate',
    'substrate'  => '3-Ply E-Flute Rigid Corrugated Flute Board',
    'durability' => 'High Edge Crush Test (ECT) for Global Shipping'
  ],
  'stickers' => [
    'resolution' => 'Multi-Pass High-Opacity Micro-Inkjet',
    'ink_layer'  => 'Rainbow Hologram Foil + 3D UV Spot Gloss',
    'substrate'  => 'Waterproof Automotive-Grade Vinyl Stock',
    'durability' => '2+ Years Outdoor UV & Weather Resistance'
  ],
  'branding' => [
    'resolution' => 'Precision Blind Deboss + Metallic Foil Stamp',
    'ink_layer'  => 'Pigment Foil & Hand-Gilded Beveled Edges',
    'substrate'  => '600 GSM Uncoated Heavyweight Cotton Pulp Board',
    'durability' => 'Archival Quality, Scratch-Resistant Foil Bond'
  ]
];

$curatedGallery = [
  [
    'id' => 1,
    'title' => 'Detailed DTF Print on a Dark T-Shirt',
    'category' => 'dtf',
    'category_name' => 'DTF PRINTING',
    'image_url' => 'assets/images/dtf_fabric_print.jpg',
    'full_image_url' => url('assets/images/dtf_fabric_print.jpg'),
    'description' => 'A DTF transfer with smooth color blends, a bright white base and a soft, stretchy feel that doesn’t turn stiff.',
    'specs' => [
      'resolution' => '1440 DPI',
      'ink_layer'  => 'CMYK colors with a white base layer',
      'substrate'  => 'Cotton, polyester blends and fleece',
      'durability' => 'Built for 50+ washes with proper care'
    ]
  ],
  [
    'id' => 2,
    'title' => 'UV DTF Decals on Glass & Tumbler Curved Surfaces',
    'category' => 'uv_dtf',
    'category_name' => 'UV DTF Decals',
    'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
    'full_image_url' => url('assets/images/uv_dtf_drinkware.jpg'),
    'description' => 'Industrial-adhesion 3D tactile UV DTF stickers applied seamlessly onto curved stainless steel bottles and glassware with spot gloss varnish.',
    'specs' => [
      'resolution' => '2880 DPI Micro-Drop Precision',
      'ink_layer'  => 'High-Build 3D Tactile Gloss Varnish',
      'substrate'  => 'Glassware, Metal, Tumblers, Acrylic & Wood',
      'durability' => 'Waterproof, Dishwasher-Safe & Scratch-Proof'
    ]
  ],
  [
    'id' => 3,
    'title' => 'Heavyweight 240 GSM Streetwear Graphic Tee',
    'category' => 'tshirt',
    'category_name' => 'Custom T-Shirts',
    'image_url' => 'assets/images/streetwear_tee.jpg',
    'full_image_url' => url('assets/images/streetwear_tee.jpg'),
    'description' => 'Vintage washed boxy drop-shoulder t-shirt with photographic DTF chest graphic, double-needle stitched seams, and tight retail ribbing.',
    'specs' => [
      'resolution' => 'High-Density Continuous Tone DTF',
      'ink_layer'  => 'Bio-Elastic TPU Hot-Melt Adhesive',
      'substrate'  => '240 GSM Super-Combed Cotton Single Jersey',
      'durability' => 'Pre-Shrunk, Zero Distortion Over Repeated Washes'
    ]
  ],
  [
    'id' => 4,
    'title' => 'Luxury Apparel Unboxing Mailer Packaging Box',
    'category' => 'packaging',
    'category_name' => 'Packaging & Boxes',
    'image_url' => 'assets/images/packaging_box.jpg',
    'full_image_url' => url('assets/images/packaging_box.jpg'),
    'description' => 'Custom matte black corrugated mailer box with hot-stamped gold foil logo, spot UV accents, and inner branded tissue wrap.',
    'specs' => [
      'resolution' => 'Vector Precision Metallic Hot-Foil Stamping',
      'ink_layer'  => 'Matte Soft-Touch Protective Laminate',
      'substrate'  => '3-Ply E-Flute Rigid Corrugated Flute Board',
      'durability' => 'High Edge Crush Test (ECT) for Global Shipping'
    ]
  ],
  [
    'id' => 5,
    'title' => 'Custom Die-Cut & Holographic Decal Collection',
    'category' => 'stickers',
    'category_name' => 'Stickers',
    'image_url' => 'assets/images/custom_stickers.jpg',
    'full_image_url' => url('assets/images/custom_stickers.jpg'),
    'description' => 'Multi-surface peel-and-stick vinyl decals with embossed gloss spot varnish, iridescent light reflection, and permanent acrylic bond.',
    'specs' => [
      'resolution' => 'Multi-Pass High-Opacity Micro-Inkjet',
      'ink_layer'  => 'Rainbow Hologram Foil + 3D UV Spot Gloss',
      'substrate'  => 'Waterproof Automotive-Grade Vinyl Stock',
      'durability' => '2+ Years Outdoor UV & Weather Resistance'
    ]
  ],
  [
    'id' => 6,
    'title' => 'Executive Corporate Swag & Client Gifting Kit',
    'category' => 'corporate',
    'category_name' => 'Corporate Gifts',
    'image_url' => 'assets/images/corporate_swag_kit.jpg',
    'full_image_url' => url('assets/images/corporate_swag_kit.jpg'),
    'description' => 'Turnkey corporate onboarding kit runs featuring customized insulated drinkware, embossed diary, and stylus pen in a bespoke gift hamper.',
    'specs' => [
      'resolution' => 'Rotary UV Print & Fiber Laser Engraving',
      'ink_layer'  => 'Deep-Etch Laser / Multi-Surface Decal',
      'substrate'  => 'SS 304 Food-Grade Metal, PU Leather & Metal',
      'durability' => 'Permanent Etching & Commercial Decal Adhesion'
    ]
  ],
  [
    'id' => 8,
    'title' => 'Luxury Embossed Branding Collateral & Stationery',
    'category' => 'branding',
    'category_name' => 'Branding Material',
    'image_url' => 'assets/images/branding_stationery.jpg',
    'full_image_url' => url('assets/images/branding_stationery.jpg'),
    'description' => 'Thick crimson red and warm beige business cards with edge gilding, debossed linen corporate journal, and executive presentation folders.',
    'specs' => [
      'resolution' => 'Precision Blind Deboss + Metallic Foil Stamp',
      'ink_layer'  => 'Pigment Foil & Hand-Gilded Beveled Edges',
      'substrate'  => '600 GSM Uncoated Heavyweight Cotton Pulp Board',
      'durability' => 'Archival Quality, Scratch-Resistant Foil Bond'
    ]
  ],
  [
    'id' => 7,
    'title' => 'Retail Brand Collection & Crimson Studio Apparel',
    'category' => 'tshirt',
    'category_name' => 'T-Shirts',
    'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
    'full_image_url' => url('assets/images/custom_tshirt_apparel.jpg'),
    'description' => 'Studio fashion apparel featuring rich crimson red garment dyes, true-match ICC color profiling, and breathable photographic chest prints.',
    'specs' => [
      'resolution' => '1200 × 1200 DPI Vivid Garment Transfer',
      'ink_layer'  => 'Eco-Friendly OEKO-TEX Standard 100 Pigments',
      'substrate'  => '200 GSM Bio-Washed Ring-Spun Cotton',
      'durability' => 'Silky Hand Feel, Stretches with Fabric Grain'
    ]
  ]
];

// Query published items from database, enriched with specs & category labels
$galleryList = [];
try {
  $dbGallery = db_fetch_all("SELECT * FROM gallery WHERE is_published = 1 ORDER BY display_order ASC, id ASC") ?: [];
  foreach ($dbGallery as $row) {
    $c = $row['category'] ?? 'dtf';
    $specs = $defaultSpecs[$c] ?? $defaultSpecs['dtf'];
    foreach ($curatedGallery as $cg) {
      if ($cg['title'] === $row['title'] && !empty($cg['specs'])) {
        $specs = $cg['specs'];
        break;
      }
    }
    $galleryList[] = [
      'id'             => (int) $row['id'],
      'title'          => $row['title'],
      'category'       => $c,
      'category_name'  => $categoryNames[$c] ?? ucfirst(str_replace('_', ' ', $c)),
      'image_url'      => $row['image_url'],
      'full_image_url' => url($row['image_url']),
      'description'    => $row['description'] ?? '',
      'specs'          => $specs
    ];
  }
} catch (Exception $e) {
  // Silent fallback to curated
}

if (empty($galleryList)) {
  $galleryList = $curatedGallery;
}

$firstItem = $galleryList[0];
?>

<!-- Hero Section with Standardized Page Intro -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>OUR WORK</span>
      </div>
      <h1 class="page-intro-title">Print Gallery</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        A look at the DTF transfers, UV decals, t-shirts and corporate gifts we’ve made for brands, agencies and businesses across India.
      </p>
      <p style="color:var(--text-secondary); max-width:680px; margin:0.75rem auto 0; font-size:0.95rem; line-height:1.6;">
        Browse sample work across DTF transfers, UV decals, t-shirts and corporate gifts. This is the kind of print quality we deliver for brands, agencies and businesses across India.
      </p>
    </div>
  </div>
</section>

<!-- Main Gallery Grid Section -->
<section class="section section-white" style="padding-top:3.5rem; padding-bottom:3.5rem;">
  <div class="container">
    <!-- Category Filter Bar -->
    <div class="gallery-filters reveal slide-up" id="galleryFilterBar">
      <button type="button" class="gallery-filter-btn active" data-filter="all">All Work</button>
      <button type="button" class="gallery-filter-btn" data-filter="dtf">DTF Prints</button>
      <button type="button" class="gallery-filter-btn" data-filter="uv_dtf">UV DTF Decals</button>
      <button type="button" class="gallery-filter-btn" data-filter="tshirt">T-Shirts</button>
      <button type="button" class="gallery-filter-btn" data-filter="packaging">Packaging</button>
      <button type="button" class="gallery-filter-btn" data-filter="stickers">Stickers</button>
      <button type="button" class="gallery-filter-btn" data-filter="corporate">Corporate Gifts</button>
      <button type="button" class="gallery-filter-btn" data-filter="branding">Branding Material</button>
    </div>

    <!-- Gallery Grid of Commercial Specimens -->
    <div class="gallery-grid" id="mainGalleryGrid">
      <?php foreach ($galleryList as $idx => $item): ?>
        <div class="gallery-item reveal scale-in stagger-<?= ($idx % 4) + 1 ?>" 
             data-category="<?= e($item['category']) ?>" 
             data-id="<?= $item['id'] ?>"
             onclick="selectSpotlight(<?= $item['id'] ?>, true)"
             title="Click to inspect this specimen in full preview below">
          <img src="<?= e($item['full_image_url']) ?>" alt="<?= e($item['title']) ?>" loading="lazy" onerror="this.onerror=null; this.src=PORTFOLIO_FALLBACK_SVG;">
          <div class="gallery-item-overlay">
            <span class="gallery-item-category"><?= e($item['category_name']) ?></span>
            <h4 class="gallery-item-title"><?= e($item['title']) ?></h4>
            <p style="font-size:0.835rem; color:rgba(255,255,255,0.85); margin:0 0 0.75rem; line-height:1.45;"><?= e($item['description']) ?></p>
            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
              <span style="color:var(--beige); font-size:0.8rem; font-weight:700; display:inline-flex; align-items:center; gap:4px;">
                Inspect Details &darr;
              </span>
              <span style="color:rgba(255,255,255,0.5); font-size:0.75rem;">|</span>
              <button type="button" onclick="event.stopPropagation(); openImageModal('<?= e($item['full_image_url']) ?>', '<?= e(addslashes($item['title'])) ?>', '<?= e(addslashes($item['description'])) ?>', '<?= e(addslashes($item['category_name'])) ?>');" 
                      style="background:transparent; border:none; color:var(--white); font-size:0.78rem; font-weight:700; text-decoration:underline; cursor:pointer; padding:0;">
                Zoom Lightbox &rarr;
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Clear Illustrative Disclaimer -->
    <div style="margin-top:2.5rem; text-align:center;">
      <small style="color:var(--slate-subtle); font-size:0.82rem; background:var(--ivory); padding:0.45rem 1rem; border-radius:var(--radius-full); border:1px solid var(--border-beige); display:inline-block;">
        Some images are sample prints. Every custom order is priced by artwork, size and quantity.
      </small>
    </div>
  </div>
</section>

<!-- =========================================================================
     FIXED & COMPLETE FULL PREVIEW SECTION (Artwork & Print Quality Inspector)
     ========================================================================= -->
<section class="portfolio-spotlight-section" id="fullPreviewSection">
  <div class="container">
    <div class="text-center reveal slide-up" style="margin-bottom:2.5rem;">
      <div class="section-label" style="justify-content:center; color:var(--crimson);">PRINT QUALITY UP CLOSE</div>
      <h2 style="font-size:clamp(1.8rem, 2.8vw, 2.4rem); color:var(--navy); margin-bottom:0.65rem;">
        Take a Closer Look at Our Prints
      </h2>
      <p style="color:var(--slate); max-width:680px; margin:0 auto; font-size:0.95rem; line-height:1.6;">
        Check the detail, color and finish of each print. Tap a thumbnail to switch the preview.
      </p>
    </div>

    <!-- Active Spotlight Card (2-Column Responsive Layout) -->
    <div class="spotlight-card reveal scale-in" id="spotlightCard">
      <!-- Left Column: Large Interactive Specimen Media -->
      <div class="spotlight-media" id="spotlightMediaBox" onclick="openActiveModal()" title="Click to view full-resolution lightbox">
        <span class="spotlight-media-badge" id="spotlightBadge"><?= e($firstItem['category_name']) ?></span>
        <img src="<?= e($firstItem['full_image_url']) ?>" id="spotlightImage" alt="<?= e($firstItem['title']) ?>" onerror="this.onerror=null; this.src=PORTFOLIO_FALLBACK_SVG;">
        <div style="position:absolute; bottom:1rem; right:1rem; background:rgba(10,14,22,0.88); color:#D4AF37; font-size:0.75rem; font-weight:700; padding:0.4rem 0.85rem; border-radius:9999px; backdrop-filter:blur(6px); border:1px solid rgba(212,175,55,0.3); pointer-events:none; display:flex; align-items:center; gap:6px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
          Tap to enlarge
        </div>
      </div>

      <!-- Right Column: Technical Specs & Gifting Action -->
      <div class="spotlight-content">
        <div class="spotlight-cat-tag" id="spotlightCategoryTag">DTF PRINTING • SAMPLE 1</div>
        <h3 class="spotlight-title" id="spotlightTitle"><?= e($firstItem['title']) ?></h3>
        <p class="spotlight-desc" id="spotlightDesc">
          <?= e($firstItem['description']) ?>
        </p>

        <!-- Dynamic Specs Grid (4 Cards) -->
        <div class="spotlight-specs-grid">
          <div class="spotlight-spec-item">
            <span class="spotlight-spec-label">Print detail</span>
            <span class="spotlight-spec-val" id="specResolution"><?= e($firstItem['specs']['resolution'] ?? '1440 DPI') ?></span>
          </div>
          <div class="spotlight-spec-item">
            <span class="spotlight-spec-label">Inks</span>
            <span class="spotlight-spec-val" id="specInk"><?= e($firstItem['specs']['ink_layer'] ?? 'CMYK colors with a white base layer') ?></span>
          </div>
          <div class="spotlight-spec-item">
            <span class="spotlight-spec-label">Works on</span>
            <span class="spotlight-spec-val" id="specSubstrate"><?= e($firstItem['specs']['substrate'] ?? 'Cotton, polyester blends and fleece') ?></span>
          </div>
          <div class="spotlight-spec-item">
            <span class="spotlight-spec-label">Durability</span>
            <span class="spotlight-spec-val" id="specDurability"><?= e($firstItem['specs']['durability'] ?? 'Built for 50+ washes with proper care') ?></span>
          </div>
        </div>

        <!-- Action CTAs -->
        <div class="spotlight-actions">
          <a href="<?= url('get-quote/') ?>?specimen=<?= urlencode($firstItem['title']) ?>" class="btn btn-primary" id="spotlightQuoteBtn">
            Get a Similar Print &rarr;
          </a>
          <button type="button" class="btn btn-outline" style="border-color:rgba(212,175,55,0.4); color:#D4AF37; background:#181818;" onclick="openActiveModal()">
            View Full Screen
          </button>
        </div>
      </div>
    </div>

    <!-- Thumbnails Switcher Strip -->
    <div style="margin-top:2rem;">
      <div style="font-size:0.82rem; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:var(--slate); margin-bottom:0.85rem; text-align:center;">
        CHOOSE A PRINT TO PREVIEW:
      </div>
      <div class="spotlight-strip" id="spotlightThumbnailStrip">
        <?php foreach ($galleryList as $idx => $item): ?>
          <button type="button" 
                  class="spotlight-thumb-btn <?= $idx === 0 ? 'active' : '' ?>" 
                  id="thumbBtn-<?= $item['id'] ?>"
                  onclick="selectSpotlight(<?= $item['id'] ?>, false)">
            <img src="<?= e($item['full_image_url']) ?>" alt="<?= e($item['title']) ?>" onerror="this.onerror=null; this.src=PORTFOLIO_FALLBACK_SVG;">
            <span class="spotlight-thumb-label">
              <?= e($item['category_name']) ?>
            </span>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- =========================================================================
     FULL-SIZE IMAGE LIGHTBOX MODAL (Properly Centered, Styled, and Dismissible)
     ========================================================================= -->
<div class="modal-backdrop" id="galleryModal" role="dialog" aria-modal="true" aria-labelledby="modalImageTitle" onclick="handleBackdropClick(event)">
  <div class="modal-dialog">
    <div class="modal-header">
      <div>
        <span id="modalImageCategory" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.12em; font-weight:700; color:#D4AF37; display:block; margin-bottom:0.25rem;"></span>
        <h3 class="modal-title" id="modalImageTitle" style="color:#F5F1E8; font-size:1.25rem; margin:0; font-weight:800; line-height:1.3;"></h3>
      </div>
      <button type="button" class="modal-close" onclick="closeImageModal()" aria-label="Close modal">&times;</button>
    </div>
    <div class="modal-body">
      <div class="modal-img-container">
        <img src="" id="modalImageSrc" alt="Full Preview" onerror="this.onerror=null; this.src=PORTFOLIO_FALLBACK_SVG;">
      </div>
      <p id="modalImageDesc" style="margin-top:1.25rem; color:#CBD5E1; font-size:0.925rem; max-width:720px; margin-left:auto; margin-right:auto; line-height:1.6;"></p>
    </div>
    <div class="modal-footer">
      <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-sm" id="modalQuoteBtn">
        Request Similar Custom Print &rarr;
      </a>
      <button type="button" class="btn btn-outline btn-sm" style="border-color:rgba(212,175,55,0.4); color:#F5F1E8; background:#181818;" onclick="closeImageModal()">
        Close Lightbox
      </button>
    </div>
  </div>
</div>

<!-- Raw Gallery Data & Interactive Engine -->
<script>
const BASE_URL = <?= json_encode(url('')) ?>;
const PORTFOLIO_FALLBACK_SVG = "data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22800%22%20height%3D%22600%22%20viewBox%3D%220%200%20800%20600%22%3E%3Crect%20fill%3D%22%2312151C%22%20width%3D%22800%22%20height%3D%22600%22%2F%3E%3Crect%20x%3D%2220%22%20y%3D%2220%22%20width%3D%22760%22%20height%3D%22560%22%20fill%3D%22none%22%20stroke%3D%22%23D4AF37%22%20stroke-width%3D%222%22%20stroke-opacity%3D%220.35%22%20stroke-dasharray%3D%228%206%22%20rx%3D%2212%22%2F%3E%3Ccircle%20cx%3D%22400%22%20cy%3D%22260%22%20r%3D%2248%22%20fill%3D%22none%22%20stroke%3D%22%23D4AF37%22%20stroke-width%3D%223%22%2F%3E%3Cpath%20d%3D%22M380%20260%20h40M400%20240%20v40%22%20stroke%3D%22%23D4AF37%22%20stroke-width%3D%223%22%20stroke-linecap%3D%22round%22%2F%3E%3Ctext%20x%3D%22400%22%20y%3D%22360%22%20fill%3D%22%23F5F1E8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2222%22%20font-weight%3D%22700%22%20text-anchor%3D%22middle%22%3EOrderPrintex%20Portfolio%20Specimen%3C%2Ftext%3E%3Ctext%20x%3D%22400%22%20y%3D%22396%22%20fill%3D%22%239CA3AF%22%20font-family%3D%22sans-serif%22%20font-size%3D%2215%22%20text-anchor%3D%22middle%22%3ECommercial%20Print%20Showcase%20Run%3C%2Ftext%3E%3C%2Fsvg%3E";

function resolveImageUrl(src) {
  if (!src) return PORTFOLIO_FALLBACK_SVG;
  if (src.startsWith('http://') || src.startsWith('https://') || src.startsWith('//') || src.startsWith('data:')) {
    return src;
  }
  const base = BASE_URL.endsWith('/') ? BASE_URL : (BASE_URL + '/');
  const clean = src.startsWith('/') ? src.substring(1) : src;
  return base + clean;
}

const GALLERY_ITEMS = <?= json_encode($galleryList, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
let activeSpotlightId = <?= (int) ($firstItem['id'] ?? 1) ?>;

function selectSpotlight(id, shouldScroll) {
  const item = GALLERY_ITEMS.find(it => it.id === id);
  if (!item) return;

  activeSpotlightId = id;

  const imgEl = document.getElementById('spotlightImage');
  imgEl.style.opacity = '0.3';
  imgEl.style.transform = 'scale(0.98)';
  
  setTimeout(() => {
    imgEl.src = resolveImageUrl(item.full_image_url || item.image_url);
    imgEl.alt = item.title;
    imgEl.style.opacity = '1';
    imgEl.style.transform = 'scale(1)';
  }, 150);

  document.getElementById('spotlightBadge').textContent = item.category_name;
  document.getElementById('spotlightCategoryTag').textContent = item.category_name + ' • Specimen #' + item.id;
  document.getElementById('spotlightTitle').textContent = item.title;
  document.getElementById('spotlightDesc').textContent = item.description;

  if (item.specs) {
    document.getElementById('specResolution').textContent = item.specs.resolution || '1440 × 2880 DPI Ultra-Fine';
    document.getElementById('specInk').textContent = item.specs.ink_layer || 'Dual CMYK + Opaque White Underbase';
    document.getElementById('specSubstrate').textContent = item.specs.substrate || 'Commercial Multi-Surface';
    document.getElementById('specDurability').textContent = item.specs.durability || '50+ Commercial Washes';
  }

  document.querySelectorAll('.spotlight-thumb-btn').forEach(btn => btn.classList.remove('active'));
  const activeBtn = document.getElementById('thumbBtn-' + id);
  if (activeBtn) activeBtn.classList.add('active');

  const quoteBtn = document.getElementById('spotlightQuoteBtn');
  if (quoteBtn) {
    quoteBtn.href = <?= json_encode(url('get-quote/')) ?> + '?specimen=' + encodeURIComponent(item.title);
  }

  if (shouldScroll) {
    const inspectorEl = document.getElementById('fullPreviewSection');
    if (inspectorEl) {
      inspectorEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
}

function openActiveModal() {
  const item = GALLERY_ITEMS.find(it => it.id === activeSpotlightId) || GALLERY_ITEMS[0];
  if (item) {
    openImageModal(item.full_image_url || item.image_url, item.title, item.description, item.category_name);
  }
}

function openImageModal(src, title, desc, category) {
  const finalSrc = resolveImageUrl(src);
  const modalImg = document.getElementById('modalImageSrc');
  modalImg.src = finalSrc;
  modalImg.alt = title;
  document.getElementById('modalImageTitle').textContent = title;
  document.getElementById('modalImageDesc').textContent = desc;
  document.getElementById('modalImageCategory').textContent = category || 'Production Specimen';

  const modalQuote = document.getElementById('modalQuoteBtn');
  if (modalQuote) {
    modalQuote.href = <?= json_encode(url('get-quote/')) ?> + '?specimen=' + encodeURIComponent(title);
  }

  document.getElementById('galleryModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeImageModal() {
  document.getElementById('galleryModal').classList.remove('open');
  document.body.style.overflow = '';
}

function handleBackdropClick(event) {
  if (event.target.id === 'galleryModal') {
    closeImageModal();
  }
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeImageModal();
  }
});

// Category Filter Handling
document.addEventListener('DOMContentLoaded', function() {
  const filterBtns = document.querySelectorAll('#galleryFilterBar .gallery-filter-btn');
  const items = document.querySelectorAll('#mainGalleryGrid .gallery-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      filterBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      const filter = this.getAttribute('data-filter');

      items.forEach(item => {
        if (filter === 'all' || item.getAttribute('data-category') === filter) {
          item.style.display = 'block';
          item.style.animation = 'scaleIn 0.35s ease forwards';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
