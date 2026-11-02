<?php
/**
 * OrderPrintex - Production Gallery & Showcase
 */

$pageTitle = "Production Gallery & Print Showcase | OrderPrintex Delhi";
$pageDesc  = "Browse actual production runs and commercial printing work completed at the OrderPrintex Delhi studio: DTF apparel prints, UV DTF hard-surface decals, and corporate gifts.";

require_once __DIR__ . '/includes/header.php';

$curatedGallery = [
  [
    'id' => 0,
    'title' => 'High-Detail DTF Apparel Print on Dark Garment',
    'category' => 'dtf',
    'category_name' => 'DTF Printing',
    'image_url' => 'assets/images/dtf_fabric_print.jpg',
    'description' => 'Direct-to-Film transfer showcasing ultra-fine color gradients, opaque white underbase, and soft stretch elasticity without hand feel stiffness.',
    'specs' => [
      'resolution' => '1440 × 2880 DPI Ultra-Fine',
      'ink_layer'  => 'Dual CMYK + Opaque White Underbase',
      'substrate'  => '100% Cotton, Poly-Blends, Canvas & Fleece',
      'durability' => '50+ Commercial Washes (Zero Crack / Fade)'
    ]
  ],
  [
    'id' => 1,
    'title' => 'UV DTF Decals on Glass & Tumbler Curved Surfaces',
    'category' => 'uv_dtf',
    'category_name' => 'UV DTF Decals',
    'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
    'description' => 'Industrial-adhesion 3D tactile UV DTF stickers applied seamlessly onto curved stainless steel bottles and glassware with spot gloss varnish.',
    'specs' => [
      'resolution' => '2880 DPI Micro-Drop Precision',
      'ink_layer'  => 'High-Build 3D Tactile Gloss Varnish',
      'substrate'  => 'Glassware, Metal, Tumblers, Acrylic & Wood',
      'durability' => 'Waterproof, Dishwasher-Safe & Scratch-Proof'
    ]
  ],
  [
    'id' => 2,
    'title' => 'Heavyweight 240 GSM Streetwear Graphic Tee',
    'category' => 'tshirt',
    'category_name' => 'Custom T-Shirts',
    'image_url' => 'assets/images/streetwear_tee.jpg',
    'description' => 'Vintage washed boxy drop-shoulder t-shirt with photographic DTF chest graphic, double-needle stitched seams, and tight retail ribbing.',
    'specs' => [
      'resolution' => 'High-Density Continuous Tone DTF',
      'ink_layer'  => 'Bio-Elastic TPU Hot-Melt Adhesive',
      'substrate'  => '240 GSM Super-Combed Cotton Single Jersey',
      'durability' => 'Pre-Shrunk, Zero Distortion Over Repeated Washes'
    ]
  ],
  [
    'id' => 3,
    'title' => 'Luxury Apparel Unboxing Mailer Packaging Box',
    'category' => 'packaging',
    'category_name' => 'Packaging & Boxes',
    'image_url' => 'assets/images/packaging_box.jpg',
    'description' => 'Custom matte black corrugated mailer box with hot-stamped gold foil logo, spot UV accents, and inner branded tissue wrap.',
    'specs' => [
      'resolution' => 'Vector Precision Metallic Hot-Foil Stamping',
      'ink_layer'  => 'Matte Soft-Touch Protective Laminate',
      'substrate'  => '3-Ply E-Flute Rigid Corrugated Flute Board',
      'durability' => 'High Edge Crush Test (ECT) for Global Shipping'
    ]
  ],
  [
    'id' => 4,
    'title' => 'Custom Die-Cut & Holographic Decal Collection',
    'category' => 'uv_dtf',
    'category_name' => 'Custom Stickers',
    'image_url' => 'assets/images/custom_stickers.jpg',
    'description' => 'Multi-surface peel-and-stick vinyl decals with embossed gloss spot varnish, iridescent light reflection, and permanent acrylic bond.',
    'specs' => [
      'resolution' => 'Multi-Pass High-Opacity Micro-Inkjet',
      'ink_layer'  => 'Rainbow Hologram Foil + 3D UV Spot Gloss',
      'substrate'  => 'Waterproof Automotive-Grade Vinyl Stock',
      'durability' => '2+ Years Outdoor UV & Weather Resistance'
    ]
  ],
  [
    'id' => 5,
    'title' => 'Executive Corporate Swag & Client Gifting Kit',
    'category' => 'corporate',
    'category_name' => 'Corporate Merchandise',
    'image_url' => 'assets/images/corporate_swag_kit.jpg',
    'description' => 'Turnkey corporate onboarding kit runs featuring customized insulated drinkware, embossed diary, and stylus pen in a bespoke gift hamper.',
    'specs' => [
      'resolution' => 'Rotary UV Print & Fiber Laser Engraving',
      'ink_layer'  => 'Deep-Etch Laser / Multi-Surface Decal',
      'substrate'  => 'SS 304 Food-Grade Metal, PU Leather & Metal',
      'durability' => 'Permanent Etching & Commercial Decal Adhesion'
    ]
  ],
  [
    'id' => 6,
    'title' => 'Retail Brand Collection & Crimson Studio Apparel',
    'category' => 'tshirt',
    'category_name' => 'Apparel & Fashion',
    'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
    'description' => 'Studio fashion apparel featuring rich crimson red garment dyes, true-match ICC color profiling, and breathable photographic chest prints.',
    'specs' => [
      'resolution' => '1200 × 1200 DPI Vivid Garment Transfer',
      'ink_layer'  => 'Eco-Friendly OEKO-TEX Standard 100 Pigments',
      'substrate'  => '200 GSM Bio-Washed Ring-Spun Cotton',
      'durability' => 'Silky Hand Feel, Stretches with Fabric Grain'
    ]
  ],
  [
    'id' => 7,
    'title' => 'Luxury Embossed Branding Collateral & Stationery',
    'category' => 'packaging',
    'category_name' => 'Branding Materials',
    'image_url' => 'assets/images/branding_stationery.jpg',
    'description' => 'Thick crimson red and warm beige business cards with edge gilding, debossed linen corporate journal, and executive presentation folders.',
    'specs' => [
      'resolution' => 'Precision Blind Deboss + Metallic Foil Stamp',
      'ink_layer'  => 'Pigment Foil & Hand-Gilded Beveled Edges',
      'substrate'  => '600 GSM Uncoated Heavyweight Cotton Pulp Board',
      'durability' => 'Archival Quality, Scratch-Resistant Foil Bond'
    ]
  ]
];
?>

<!-- Hero Section with Red & Beige Palette -->
<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem; border-bottom:1px solid rgba(237,225,210,0.15);">
  <div class="container text-center reveal slide-up">
    <div class="section-label" style="justify-content:center; color:var(--beige);">Factory Portfolio &amp; Specimen Showcase</div>
    <h1 style="font-size:clamp(2.1rem, 3.8vw, 3.2rem); margin-bottom:1rem; color:#FFFFFF;">Commercial Print Showcase &amp; Production Gallery</h1>
    <p class="section-subtitle" style="color:rgba(237, 225, 210, 0.88); margin:0 auto; max-width:760px;">
      Explore real factory runs and illustrative demonstration specimens manufactured for apparel brands, promotional agencies, and corporate enterprises across India.
    </p>
  </div>
</section>

<!-- Main Gallery Grid Section -->
<section class="section section-white" style="padding-top:3.5rem; padding-bottom:3.5rem;">
  <div class="container">
    <!-- Category Filter Bar -->
    <div class="gallery-filters reveal slide-up" id="galleryFilterBar">
      <button type="button" class="gallery-filter-btn active" data-filter="all">All Showcase (<?= count($curatedGallery) ?>)</button>
      <button type="button" class="gallery-filter-btn" data-filter="dtf">DTF Transfers</button>
      <button type="button" class="gallery-filter-btn" data-filter="uv_dtf">UV DTF Decals</button>
      <button type="button" class="gallery-filter-btn" data-filter="tshirt">Custom T-Shirts</button>
      <button type="button" class="gallery-filter-btn" data-filter="corporate">Corporate Gifts</button>
      <button type="button" class="gallery-filter-btn" data-filter="packaging">Packaging &amp; Branding</button>
    </div>

    <!-- Gallery Grid of 8 Commercial Specimens -->
    <div class="gallery-grid" id="mainGalleryGrid">
      <?php foreach ($curatedGallery as $idx => $item): ?>
        <div class="gallery-item reveal scale-in stagger-<?= ($idx % 4) + 1 ?>" 
             data-category="<?= e($item['category']) ?>" 
             data-id="<?= $item['id'] ?>"
             onclick="selectSpotlight(<?= $item['id'] ?>, true)"
             title="Click to inspect this specimen in full preview below">
          <img src="<?= url(e($item['image_url'])) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
          <div class="gallery-item-overlay">
            <span class="gallery-item-category"><?= e($item['category_name']) ?></span>
            <h4 class="gallery-item-title"><?= e($item['title']) ?></h4>
            <p style="font-size:0.835rem; color:rgba(255,255,255,0.85); margin:0 0 0.75rem; line-height:1.45;"><?= e($item['description']) ?></p>
            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
              <span style="color:var(--beige); font-size:0.8rem; font-weight:700; display:inline-flex; align-items:center; gap:4px;">
                Inspect Details &darr;
              </span>
              <span style="color:rgba(255,255,255,0.5); font-size:0.75rem;">|</span>
              <button type="button" onclick="event.stopPropagation(); openImageModal('<?= url(e($item['image_url'])) ?>', '<?= e(addslashes($item['title'])) ?>', '<?= e(addslashes($item['description'])) ?>', '<?= e(addslashes($item['category_name'])) ?>');" 
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
        &bull; Portfolio contains verified factory runs and illustrative demonstration specimens. Custom orders quoted based on vector artwork, dimensions, and quantity.
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
      <div class="section-label" style="justify-content:center; color:var(--crimson);">Detailed Quality Inspector</div>
      <h2 style="font-size:clamp(1.8rem, 2.8vw, 2.4rem); color:var(--navy); margin-bottom:0.65rem;">
        Full Preview &amp; Print Specimen Inspector
      </h2>
      <p style="color:var(--slate); max-width:680px; margin:0 auto; font-size:0.95rem; line-height:1.6;">
        Inspect high-resolution finish, ink opacity, micro-line detail, and substrate compatibility. Click any thumbnail below to update the live preview.
      </p>
    </div>

    <!-- Active Spotlight Card (2-Column Responsive Layout) -->
    <div class="spotlight-card reveal scale-in" id="spotlightCard">
      <!-- Left Column: Large Interactive Specimen Media -->
      <div class="spotlight-media" id="spotlightMediaBox" onclick="openActiveModal()" style="cursor:zoom-in;" title="Click to view full-resolution lightbox">
        <span class="spotlight-media-badge" id="spotlightBadge">DTF Printing</span>
        <img src="<?= url($curatedGallery[0]['image_url']) ?>" id="spotlightImage" alt="<?= e($curatedGallery[0]['title']) ?>">
        <div style="position:absolute; bottom:1rem; right:1rem; background:rgba(16,27,48,0.85); color:#fff; font-size:0.75rem; font-weight:700; padding:0.4rem 0.85rem; border-radius:var(--radius-full); backdrop-filter:blur(6px); border:1px solid rgba(255,255,255,0.2); pointer-events:none; display:flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
          Click to Enlarge Lightbox
        </div>
      </div>

      <!-- Right Column: Technical Specs & Gifting Action -->
      <div class="spotlight-content">
        <div class="spotlight-cat-tag" id="spotlightCategoryTag">DTF Printing &bull; Specimen #1</div>
        <h3 class="spotlight-title" id="spotlightTitle">High-Detail DTF Apparel Print on Dark Garment</h3>
        <p class="spotlight-desc" id="spotlightDesc">
          Direct-to-Film transfer showcasing ultra-fine color gradients, opaque white underbase, and soft stretch elasticity without hand feel stiffness.
        </p>

        <!-- Dynamic Specs Grid (4 Cards) -->
        <div class="spotlight-specs-grid">
          <div class="spotlight-spec-item">
            <span class="spotlight-spec-label">Print Resolution</span>
            <span class="spotlight-spec-val" id="specResolution">1440 × 2880 DPI Ultra-Fine</span>
          </div>
          <div class="spotlight-spec-item">
            <span class="spotlight-spec-label">Ink &amp; Base Layer</span>
            <span class="spotlight-spec-val" id="specInk">Dual CMYK + Opaque White Underbase</span>
          </div>
          <div class="spotlight-spec-item">
            <span class="spotlight-spec-label">Substrate Compatibility</span>
            <span class="spotlight-spec-val" id="specSubstrate">100% Cotton, Poly-Blends, Canvas &amp; Fleece</span>
          </div>
          <div class="spotlight-spec-item">
            <span class="spotlight-spec-label">Durability Rating</span>
            <span class="spotlight-spec-val" id="specDurability">50+ Commercial Washes (Zero Crack / Fade)</span>
          </div>
        </div>

        <!-- Action CTAs -->
        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
          <a href="<?= url('get-quote.php') ?>" class="btn btn-primary" id="spotlightQuoteBtn">
            Request Similar Custom Print &rarr;
          </a>
          <button type="button" class="btn btn-outline" style="border-color:var(--border-beige); color:var(--navy); background:var(--white);" onclick="openActiveModal()">
            Inspect Fullscreen Lightbox ⛶
          </button>
        </div>
      </div>
    </div>

    <!-- Thumbnails Switcher Strip -->
    <div style="margin-top:2rem;">
      <div style="font-size:0.82rem; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:var(--slate); margin-bottom:0.85rem; text-align:center;">
        Select Specimen to Inspect in Full Preview:
      </div>
      <div class="spotlight-strip" id="spotlightThumbnailStrip">
        <?php foreach ($curatedGallery as $idx => $item): ?>
          <button type="button" 
                  class="spotlight-thumb-btn <?= $idx === 0 ? 'active' : '' ?>" 
                  id="thumbBtn-<?= $item['id'] ?>"
                  onclick="selectSpotlight(<?= $item['id'] ?>, false)">
            <img src="<?= url(e($item['image_url'])) ?>" alt="<?= e($item['title']) ?>">
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
  <div class="modal-dialog" style="max-width:860px; background-color:var(--navy-dark); color:#fff; border-color:rgba(237,225,210,0.3); box-shadow:0 25px 60px rgba(0,0,0,0.7);">
    <div class="modal-header" style="border-bottom-color:rgba(255,255,255,0.12); padding:1.25rem 1.75rem;">
      <div>
        <span id="modalImageCategory" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.12em; font-weight:700; color:var(--beige); display:block; margin-bottom:0.25rem;"></span>
        <h3 class="modal-title" id="modalImageTitle" style="color:#FFFFFF; font-size:1.25rem; margin:0; font-weight:800;"></h3>
      </div>
      <button type="button" class="modal-close" onclick="closeImageModal()" aria-label="Close modal">&times;</button>
    </div>
    <div class="modal-body" style="padding:1.5rem; text-align:center;">
      <div style="max-height:550px; overflow:hidden; border-radius:var(--radius-sm); border:1px solid rgba(255,255,255,0.1); background:#070d18; display:flex; align-items:center; justify-content:center;">
        <img src="" id="modalImageSrc" alt="Full Preview" style="max-height:520px; width:auto; max-width:100%; margin:0 auto; object-fit:contain; display:block;">
      </div>
      <p id="modalImageDesc" style="margin-top:1.25rem; color:rgba(237,225,210,0.9); font-size:0.925rem; max-width:700px; margin-left:auto; margin-right:auto; line-height:1.6;"></p>
    </div>
    <div class="modal-footer" style="background-color:rgba(0,0,0,0.4); border-top-color:rgba(255,255,255,0.1); padding:1rem 1.75rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
      <a href="<?= url('get-quote.php') ?>" class="btn btn-primary btn-sm">Request Similar Custom Print</a>
      <button type="button" class="btn btn-outline" style="border-color:rgba(255,255,255,0.25); color:#fff; font-size:0.85rem; padding:0.55rem 1.25rem;" onclick="closeImageModal()">Close Lightbox</button>
    </div>
  </div>
</div>

<!-- Raw Gallery Data & Interactive Engine -->
<script>
const GALLERY_ITEMS = <?= json_encode($curatedGallery, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
let activeSpotlightId = 0;

function selectSpotlight(id, shouldScroll) {
  const item = GALLERY_ITEMS.find(it => it.id === id);
  if (!item) return;

  activeSpotlightId = id;

  // Animate image fade
  const imgEl = document.getElementById('spotlightImage');
  imgEl.style.opacity = '0.3';
  imgEl.style.transform = 'scale(0.98)';
  
  setTimeout(() => {
    imgEl.src = item.image_url;
    imgEl.alt = item.title;
    imgEl.style.opacity = '1';
    imgEl.style.transform = 'scale(1)';
  }, 150);

  // Update text & badges
  document.getElementById('spotlightBadge').textContent = item.category_name;
  document.getElementById('spotlightCategoryTag').textContent = item.category_name + ' • Specimen #' + (item.id + 1);
  document.getElementById('spotlightTitle').textContent = item.title;
  document.getElementById('spotlightDesc').textContent = item.description;

  // Update specs
  if (item.specs) {
    document.getElementById('specResolution').textContent = item.specs.resolution || 'High-Resolution 300+ DPI';
    document.getElementById('specInk').textContent = item.specs.ink_layer || 'Industrial Inks';
    document.getElementById('specSubstrate').textContent = item.specs.substrate || 'Commercial Multi-Surface';
    document.getElementById('specDurability').textContent = item.specs.durability || 'Commercial Durability Tested';
  }

  // Update active thumbnail
  document.querySelectorAll('.spotlight-thumb-btn').forEach(btn => btn.classList.remove('active'));
  const activeBtn = document.getElementById('thumbBtn-' + id);
  if (activeBtn) activeBtn.classList.add('active');

  // Scroll smoothly to inspector if initiated from grid
  if (shouldScroll) {
    const inspectorEl = document.getElementById('fullPreviewSection');
    if (inspectorEl) {
      inspectorEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
}

function openActiveModal() {
  const item = GALLERY_ITEMS.find(it => it.id === activeSpotlightId);
  if (item) {
    openImageModal(item.image_url, item.title, item.description, item.category_name);
  }
}

function openImageModal(src, title, desc, category) {
  document.getElementById('modalImageSrc').src = src;
  document.getElementById('modalImageTitle').textContent = title;
  document.getElementById('modalImageDesc').textContent = desc;
  document.getElementById('modalImageCategory').textContent = category || 'Production Specimen';
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

// Close on Escape key
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
