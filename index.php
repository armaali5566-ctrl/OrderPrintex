<?php
/**
 * OrderPrintex - Homepage
 * Premium International Printing Studio Redesign
 * Focused on Customer Conversions, Clean URLs, Dynamic Products & Blog Showcase
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "PRINTEX — Industrial DTF & UV DTF Manufacturer & Bulk Supplier in India";
$pageDesc  = "PRINTEX is India's leading bulk manufacturer of DTF garment transfers, UV DTF 3D decals, custom screen-quality t-shirts, and corporate merchandise. Fast turnaround, prepress checks, and nationwide delivery.";
$canonical = url('');
$pageSchemaKey = 'home';

require_once __DIR__ . '/includes/header.php';

// Fetch dynamic products from database (exactly 3 featured products for Commercial Showcase)
$featuredProducts = db_fetch_all("
    SELECT p.id, p.sku, p.title, p.slug, p.short_desc, p.image_url, p.badge, 
           p.starting_price, p.price_unit, p.min_order, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.is_active = 1 
    ORDER BY p.is_featured DESC, p.display_order ASC 
    LIMIT 3
");

// Fetch 3 latest published blog posts
$latestBlogPosts = db_fetch_all("
    SELECT p.id, p.title, p.slug, p.excerpt, p.featured_image, p.read_time, p.created_at, 
           c.name as category_name 
    FROM blog_posts p 
    LEFT JOIN blog_categories c ON p.category_id = c.id 
    WHERE p.status = 'published' 
    ORDER BY p.created_at DESC 
    LIMIT 3
");

$heroHeading = get_setting('hero_heading', 'Premium DTF & UV DTF Transfers, Made in Delhi for All of India');
$heroDesc = get_setting('hero_description', 'Printex prints press-ready DTF and UV DTF transfers in bulk, with vivid colors, a soft-hand finish and strong wash durability. Trusted by apparel brands, print shops and promotional studios across India.');
?>

<!-- ========================================================================
     SECTION 1: FULL-WIDTH EDITORIAL HERO SECTION
     ======================================================================== -->
<section class="hero-section">
  <div class="container">
    <div class="hero-grid">
      <!-- Left Content -->
      <div class="hero-content reveal slide-right">
        <div class="hero-badge-pill">
          <svg style="width:14px;height:14px;fill:var(--gold);" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          DELHI-BASED DTF &amp; UV DTF SPECIALISTS
        </div>
        
        <h1 class="hero-title">
          Premium DTF &amp; UV DTF Transfers, Made in <span class="accent-serif">Delhi</span> for All of India
        </h1>
        
        <p class="hero-description">
          <?= e($heroDesc) ?>
        </p>

        <div class="hero-ctas">
          <a href="#upload-studio" class="btn btn-primary btn-lg">
            <span>Get Bulk Quote</span>
            <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
          </a>
          <a href="<?= url('products/') ?>" class="btn btn-navy btn-lg">
            <span>View DTF &amp; UV DTF Products</span>
            <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
          </a>
        </div>

        <div class="hero-highlights">
          <div class="highlight-item">
            <div class="highlight-icon">
              <svg style="width:18px;height:18px;fill:currentColor;" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <div class="highlight-text">ICC-Calibrated Color</div>
              <small style="color:var(--slate-light);">Accurate, repeatable prints every batch</small>
            </div>
          </div>

          <div class="highlight-item">
            <div class="highlight-icon">
              <svg style="width:18px;height:18px;fill:currentColor;" viewBox="0 0 20 20">
                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
              </svg>
            </div>
            <div>
              <div class="highlight-text">Delivery Across India</div>
              <small style="color:var(--slate-light);">Dispatch in 1–3 business days after proof approval</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Visual Composition -->
      <div class="hero-visual reveal slide-left">
        <div class="hero-visual-card">
          <picture>
            <source srcset="<?= url('assets/images/dtf_fabric_print.webp') ?>" type="image/webp">
            <img src="<?= url('assets/images/dtf_fabric_print.jpg') ?>" alt="PRINTEX Delhi Studio DTF Production" width="1200" height="896" fetchpriority="high">
          </picture>
        </div>

        <!-- Floating Studio Quality Badge -->
        <div class="floating-badge">
          <h4>Made for Bulk Orders</h4>
          <p>Soft-hand finish &bull; Strong adhesion &bull; Wash-durable &bull; Consistent batches</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     SEAMLESS INFINITE MARQUEE TICKER
     ======================================================================== -->
<div class="marquee-container" aria-hidden="true">
  <div class="marquee-track">
    <div class="marquee-item">WASH-DURABLE DTF PRINTS <span class="marquee-dot"></span></div>
    <div class="marquee-item">ICC-CALIBRATED COLOR <span class="marquee-dot"></span></div>
    <div class="marquee-item">TIERED BULK PRICING <span class="marquee-dot"></span></div>
    <div class="marquee-item">1–3 DAY TURNAROUND <span class="marquee-dot"></span></div>
    <div class="marquee-item">TRACKED DELIVERY PAN-INDIA <span class="marquee-dot"></span></div>
    <div class="marquee-item">PREMIUM INKS &amp; FILMS <span class="marquee-dot"></span></div>
  </div>
  <div class="marquee-track">
    <div class="marquee-item">WASH-DURABLE DTF PRINTS <span class="marquee-dot"></span></div>
    <div class="marquee-item">ICC-CALIBRATED COLOR <span class="marquee-dot"></span></div>
    <div class="marquee-item">TIERED BULK PRICING <span class="marquee-dot"></span></div>
    <div class="marquee-item">1–3 DAY TURNAROUND <span class="marquee-dot"></span></div>
    <div class="marquee-item">TRACKED DELIVERY PAN-INDIA <span class="marquee-dot"></span></div>
    <div class="marquee-item">PREMIUM INKS &amp; FILMS <span class="marquee-dot"></span></div>
  </div>
</div>

<!-- ========================================================================
     SECTION 2: PROMINENT UPLOAD YOUR DESIGN WORKBENCH
     ======================================================================== -->
<section class="section section-white" id="upload-studio">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">FREE PREPRESS CHECK</div>
      <h2>Send Your Artwork, Get a Detailed Quote</h2>
      <p class="section-subtitle">
        Upload your design and our prepress team will check the resolution, transparency and sheet layout before quoting. No advance payment needed.
      </p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data" id="homepageUploadForm">
        <?= csrf_field() ?>

        <div class="upload-studio-grid">
          
          <!-- Left: Drag & Drop Dropzone + File Preview -->
          <div>
            <div class="dropzone-container" id="artworkDropzone">
              <input type="file" name="artwork_file" class="file-input" id="artworkFileInput" accept=".png,.jpg,.jpeg,.pdf,.ai,.psd,.svg" style="display:none;">
              <div class="dropzone-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
              </div>
              <div class="dropzone-title">Drag and drop your artwork here, or <span class="text-gold" style="text-decoration:underline; cursor:pointer;">Browse Files</span></div>
              <div class="dropzone-hint">PNG, JPG, PDF, AI, PSD, SVG (max 25 MB). 300 DPI with a transparent background.</div>
              <button type="button" class="btn btn-sm btn-outline-gold" onclick="document.getElementById('artworkFileInput').click();">Choose Artwork File</button>
            </div>

            <!-- Real-time File Preview Area -->
            <div class="file-preview-area" id="filePreviewArea"></div>

            <!-- Print Dimensions Footprint Meter -->
            <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-light);">
              <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.85rem; font-weight:700; color:var(--navy);">Estimated Print Area</span>
                <strong class="sq-inches-display text-navy" style="font-size:1.15rem; color:var(--blue);">—</strong>
              </div>
              <small style="color:var(--slate-light); display:block; margin-top:4px; font-size:0.78rem;">
                Enter width and height to see the sheet area. It helps us plan your layout and keep your cost low.
              </small>
            </div>
          </div>

          <!-- Right: Print Specs & Customer Details -->
          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label" for="serviceSelect">
                  Service Required <span class="required">*</span>
                </label>
                <select name="service_name" id="serviceSelect" class="form-control" required
                        data-enable-other="true"
                        data-other-name="custom_service_name"
                        data-other-label="Specify Custom Printing Service"
                        data-other-placeholder="e.g. Sublimation Mugs, Screen Printed Posters, Metallic Decals">
                  <option value="DTF Printing" selected>DTF Printing (Garment Transfers)</option>
                  <option value="UV DTF Printing">UV DTF Printing (Hard Surface Decals)</option>
                  <option value="T-Shirt Printing">T-Shirt Printing</option>
                  <option value="Corporate Printing">Corporate Gifts &amp; Merchandise</option>
                  <option value="other">Other (Specify Custom Service)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="qtyInput">
                  Quantity / Sheets <span class="required">*</span>
                </label>
                <input type="number" name="quantity" id="qtyInput" class="form-control" min="1" placeholder="e.g. 50" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="widthInput">Width (Inches)</label>
                <input type="number" step="0.1" name="width_inches" id="widthInput" class="form-control" placeholder="e.g. 22">
              </div>

              <div class="form-group">
                <label class="form-label" for="heightInput">Height / Length (Inches)</label>
                <input type="number" step="0.1" name="height_inches" id="heightInput" class="form-control" placeholder="e.g. 36">
              </div>

              <div class="form-group full-width">
                <label class="form-label" for="notesInput">Special Instructions</label>
                <textarea name="customer_notes" id="notesInput" class="form-control" placeholder="Fabric or surface type, color notes, deadline or any other detail"></textarea>
              </div>

              <div class="form-group">
                <label class="form-label" for="custName">Your Name / Brand <span class="required">*</span></label>
                <input type="text" name="customer_name" id="custName" class="form-control" placeholder="e.g. Rahul Sharma / Your Brand" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="custEmail">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" id="custEmail" class="form-control" placeholder="you@example.com" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label" for="custPhone">Phone / WhatsApp Number <span class="required">*</span></label>
                <input type="tel" name="customer_phone" id="custPhone" class="form-control" placeholder="+91 98XXX XXXXX" required>
              </div>
            </div>

            <div class="quote-submit-bar" style="margin-top:2rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.25rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ Free file check &bull; Quote within 24 hours &bull; Dispatch across India
              </small>
              <button type="submit" class="btn btn-primary btn-lg quote-submit-btn">
                <span>Submit Artwork &amp; Get Quote</span>
                <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </button>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 3: COMMERCIAL SHOWCASE / POPULAR PRINTING PRODUCTS (EXACTLY 3 PRODUCTS)
     ======================================================================== -->
<section class="section section-ivory" id="products-showcase">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label" style="justify-content:center;">OUR BESTSELLERS</div>
      <h2>Popular DTF, UV DTF and Custom Printing Products</h2>
      <p class="section-subtitle">
        Printed in our Delhi workshop with sharp detail, rich color and strong wash durability. Order a single sheet or go bulk.
      </p>
    </div>

    <?php
    $showcaseProducts = [
      [
        'badge' => 'BESTSELLER',
        'category_name' => 'DTF GANG SHEETS',
        'sku' => 'DTF-001',
        'title' => 'Custom DTF Gang Sheet',
        'short_desc' => 'Full-color DTF transfers on PET film with a strong white underbase. Soft-hand finish for repeated washes.',
        'starting_price' => '₹249',
        'price_unit' => '/ Foot',
        'min_order' => 'Min. 1 Meter',
        'alt' => 'Custom DTF transfer printed on a dark T-shirt',
        'image_url' => $featuredProducts[0]['image_url'] ?? 'assets/images/dtf_fabric_print.jpg',
        'slug' => $featuredProducts[0]['slug'] ?? 'custom-dtf-gang-sheet',
        'quote_service' => 'DTF Printing'
      ],
      [
        'badge' => 'TRENDING',
        'category_name' => 'UV DTF DECALS',
        'sku' => 'UV-001',
        'title' => 'High-Gloss UV DTF Decal Sheet',
        'short_desc' => 'Waterproof, scratch-resistant peel-and-stick decals. Stick to glass, metal, plastic and wood with no heat press.',
        'starting_price' => '₹349',
        'price_unit' => '/ Sheet',
        'min_order' => 'Min. 1 Sheet',
        'alt' => 'UV DTF decal applied on a branded bottle',
        'image_url' => $featuredProducts[1]['image_url'] ?? 'assets/images/uv_dtf_drinkware.jpg',
        'slug' => $featuredProducts[1]['slug'] ?? 'high-gloss-uv-dtf-decal-sheet',
        'quote_service' => 'UV DTF Printing'
      ],
      [
        'badge' => 'STREETWEAR',
        'category_name' => 'CUSTOM T-SHIRTS',
        'sku' => 'STR-001',
        'title' => 'Heavyweight 240 GSM Oversized T-Shirt',
        'short_desc' => 'Drop-shoulder streetwear tee in pre-shrunk, super-combed cotton, printed with high-density DTF graphics.',
        'starting_price' => '₹449',
        'price_unit' => '/ Piece',
        'min_order' => 'Min. 5 Pcs',
        'alt' => 'Model wearing a custom printed oversized T-shirt',
        'image_url' => $featuredProducts[2]['image_url'] ?? 'assets/images/streetwear_tee.jpg',
        'slug' => $featuredProducts[2]['slug'] ?? 'heavyweight-240-gsm-oversized-t-shirt',
        'quote_service' => 'T-Shirt Printing'
      ]
    ];
    ?>

    <div class="products-grid products-grid-3">
      <?php foreach ($showcaseProducts as $idx => $prod): ?>
        <article class="product-card reveal slide-up stagger-<?= ($idx % 3) + 1 ?>">
          <div class="product-media" style="background:#121212; border-bottom:1px solid var(--border-color); display:flex; align-items:center; justify-content:center; padding:1.25rem; height:280px;">
            <?php if (!empty($prod['badge'])): ?>
              <span class="product-badge-tag"><?= e($prod['badge']) ?></span>
            <?php endif; ?>
            <img src="<?= url($prod['image_url']) ?>" 
                 alt="<?= e($prod['alt']) ?>" 
                 loading="lazy" 
                 style="max-width:100%; max-height:100%; object-fit:contain;">
          </div>
          <div class="product-body">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
              <span class="product-category-label"><?= e($prod['category_name']) ?></span>
              <span class="sku-badge">
                SKU: <?= e($prod['sku']) ?>
              </span>
            </div>
            
            <h3 class="product-title" style="min-height:2.6rem;">
              <a href="<?= product_url($prod['slug']) ?>" title="View details for <?= e($prod['title']) ?>">
                <?= e($prod['title']) ?>
              </a>
            </h3>
            
            <p class="product-desc" style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
              <?= e($prod['short_desc']) ?>
            </p>

            <!-- Starting Price Row -->
            <div class="product-pricing-row">
              <div class="product-price-box">
                <span class="price-label">From</span>
                <span class="price-val"><?= e($prod['starting_price']) ?></span>
                <span class="price-unit"><?= e($prod['price_unit']) ?></span>
              </div>
              <span class="product-min-order"><?= e($prod['min_order']) ?></span>
            </div>

            <div class="product-card-actions">
              <a href="<?= product_url($prod['slug']) ?>" class="btn btn-outline" aria-label="View details for <?= e($prod['title']) ?>">
                View Details
              </a>
              <a href="<?= url('get-quote/?service_name=' . urlencode($prod['quote_service'])) ?>" class="btn btn-primary" aria-label="Get a Quote for <?= e($prod['title']) ?>">
                Get a Quote
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Prominent "View All Products" Button -->
    <div style="text-align:center; margin-top:3.5rem;" class="reveal slide-up">
      <a href="<?= url('products/') ?>" class="btn btn-outline btn-lg cta-wide-btn" style="border-color:var(--primary-gold); color:var(--bright-gold); background:var(--bg-surface);">
        <span>View All Products</span>
        <svg style="width:18px;height:18px;" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </a>
      <div style="margin-top:1.25rem;">
        <small style="color:var(--slate-subtle); font-size:0.82rem;">
          &bull; Prices shown are starting rates. Final quote depends on your artwork, size and quantity.
        </small>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 4: PRINTING SERVICES OVERVIEW
     ======================================================================== -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">WHAT WE DO</div>
      <h2>Our DTF, UV DTF and Custom Printing Services</h2>
      <p class="section-subtitle">
        From DTF transfers for apparel to UV DTF decals for hard surfaces, we handle printing, curing and quality checks under one roof in Delhi.
      </p>
    </div>

    <div class="services-grid">
      <!-- 1. DTF Transfers -->
      <div class="service-card reveal slide-up">
        <div class="service-card-media">
          <picture>
            <source srcset="<?= url('assets/images/dtf_fabric_print.webp') ?>" type="image/webp">
            <img src="<?= url('assets/images/dtf_fabric_print.jpg') ?>" alt="Custom DTF transfer printed on a dark T-shirt" width="1200" height="896" loading="lazy">
          </picture>
          <span class="service-card-badge">FLAGSHIP SERVICE</span>
        </div>
        <div class="service-card-body">
          <h3 class="service-card-title">DTF Printing</h3>
          <p class="service-card-desc">
            Vivid full-color DTF transfers for cotton, polyester and blended fabrics. Soft-hand feel, strong adhesion and wash-durable prints for apparel brands and print shops.
          </p>
          <a href="<?= service_url('dtf-printing') ?>" class="service-card-link">
            <span>Explore DTF Printing</span> &rarr;
          </a>
        </div>
      </div>

      <!-- 2. UV DTF Decals -->
      <div class="service-card reveal slide-up" style="transition-delay:0.1s;">
        <div class="service-card-media">
          <picture>
            <source srcset="<?= url('assets/images/uv_dtf_drinkware.webp') ?>" type="image/webp">
            <img src="<?= url('assets/images/uv_dtf_drinkware.jpg') ?>" alt="UV DTF decal applied on a bottle" width="1200" height="896" loading="lazy">
          </picture>
          <span class="service-card-badge">NO HEAT PRESS</span>
        </div>
        <div class="service-card-body">
          <h3 class="service-card-title">UV DTF Printing</h3>
          <p class="service-card-desc">
            Peel-and-stick UV DTF decals with a glossy 3D finish. They bond firmly to mugs, tumblers, bottles, glass and acrylic without any heat press.
          </p>
          <a href="<?= service_url('uv-dtf-printing') ?>" class="service-card-link">
            <span>Explore UV DTF Printing</span> &rarr;
          </a>
        </div>
      </div>

      <!-- 3. T-Shirt Printing -->
      <div class="service-card reveal slide-up" style="transition-delay:0.2s;">
        <div class="service-card-media">
          <picture>
            <source srcset="<?= url('assets/images/streetwear_tee.webp') ?>" type="image/webp">
            <img src="<?= url('assets/images/streetwear_tee.jpg') ?>" alt="Black custom printed graphic T-shirt" width="1200" height="896" loading="lazy">
          </picture>
          <span class="service-card-badge">COTTON TEES</span>
        </div>
        <div class="service-card-body">
          <h3 class="service-card-title">T-Shirt Printing</h3>
          <p class="service-card-desc">
            Custom-printed cotton T-shirts, oversized streetwear tees and company polos. Sharp, bright color on both dark and light fabrics, in single pieces or bulk.
          </p>
          <a href="<?= service_url('t-shirt-printing') ?>" class="service-card-link">
            <span>Explore T-Shirt Printing</span> &rarr;
          </a>
        </div>
      </div>

      <!-- 4. Corporate Printing -->
      <div class="service-card reveal slide-up" style="transition-delay:0.3s;">
        <div class="service-card-media">
          <picture>
            <source srcset="<?= url('assets/images/corporate_swag_kit.webp') ?>" type="image/webp">
            <img src="<?= url('assets/images/corporate_swag_kit.jpg') ?>" alt="Corporate welcome kit with printed mug and diary" width="1200" height="896" loading="lazy">
          </picture>
          <span class="service-card-badge">BULK MERCHANDISE</span>
        </div>
        <div class="service-card-body">
          <h3 class="service-card-title">Corporate Printing</h3>
          <p class="service-card-desc">
            Branded welcome kits, mugs, bottles, notebooks and gifts for teams, events and clients, with careful brand-color matching.
          </p>
          <a href="<?= service_url('corporate-printing') ?>" class="service-card-link">
            <span>Explore Corporate Printing</span> &rarr;
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 5: WHY ORDERPRINTEX? (CONCISE VALUE HIGHLIGHTS)
     ======================================================================== -->
<section class="section section-navy">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">WHY PRINTEX</div>
      <h2>Why Choose Printex for Bulk DTF &amp; UV DTF Printing?</h2>
      <p class="section-subtitle" style="color:rgba(255,255,255,0.78);">
        A dependable print partner for apparel brands, designers, print shops and corporate teams who need consistent quality at scale.
      </p>
    </div>

    <div class="home-features-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:2rem;">
      <div style="background:var(--navy-card); padding:2.25rem 1.75rem; border-radius:var(--radius-md); border:1px solid rgba(185,154,97,0.3); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:1rem;">🎯</div>
        <h3 style="color:var(--white); font-size:1.25rem; margin-bottom:0.75rem;">ICC-Calibrated Color</h3>
        <p style="color:rgba(255,255,255,0.75); font-size:0.9rem; line-height:1.6; margin:0;">
          Calibrated color profiles and premium inks give you sharp detail, smooth gradients and vivid color that stays consistent from batch to batch.
        </p>
      </div>

      <div style="background:var(--navy-card); padding:2.25rem 1.75rem; border-radius:var(--radius-md); border:1px solid rgba(185,154,97,0.3); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:1rem;">💰</div>
        <h3 style="color:var(--white); font-size:1.25rem; margin-bottom:0.75rem;">Bulk &amp; Gang Sheet Pricing</h3>
        <p style="color:rgba(255,255,255,0.75); font-size:0.9rem; line-height:1.6; margin:0;">
          Tiered pricing that gets better as your sheet length and quantity grow. Larger orders mean a lower cost per print.
        </p>
      </div>

      <div style="background:var(--navy-card); padding:2.25rem 1.75rem; border-radius:var(--radius-md); border:1px solid rgba(185,154,97,0.3); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:1rem;">🔍</div>
        <h3 style="color:var(--white); font-size:1.25rem; margin-bottom:0.75rem;">Free Prepress File Check</h3>
        <p style="color:rgba(255,255,255,0.75); font-size:0.9rem; line-height:1.6; margin:0;">
          We review your artwork for resolution, transparency and sheet layout before printing, so problems get fixed before they cost you a reprint.
        </p>
      </div>

      <div style="background:var(--navy-card); padding:2.25rem 1.75rem; border-radius:var(--radius-md); border:1px solid rgba(185,154,97,0.3); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:1rem;">⚡</div>
        <h3 style="color:var(--white); font-size:1.25rem; margin-bottom:0.75rem;">Fast Dispatch Across India</h3>
        <p style="color:rgba(255,255,255,0.75); font-size:0.9rem; line-height:1.6; margin:0;">
          Most orders ship within 1–3 business days after proof approval, with secure packing and tracked courier delivery to your doorstep.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 6: FEATURED PORTFOLIO SHOWCASE (4 PREVIEWS)
     ======================================================================== -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label" style="justify-content:center;">OUR WORK</div>
      <h2>Recent DTF, UV DTF and Merchandise Projects</h2>
      <p class="section-subtitle">
        A look at our DTF transfers, UV DTF decals and finished merchandise, printed and checked at our Delhi facility.
      </p>
    </div>

    <?php
    $homepagePortfolio = [
      [
        'title' => 'Multi-Color DTF Print on Dark Cotton',
        'category' => 'DTF PRINTING',
        'image' => 'assets/images/dtf_fabric_print.jpg',
        'desc' => 'Multi-color DTF transfer pressed on a dark cotton T-shirt'
      ],
      [
        'title' => 'UV DTF Decals on Drinkware',
        'category' => 'UV DTF DECALS',
        'image' => 'assets/images/uv_dtf_drinkware.jpg',
        'desc' => 'UV DTF decal applied on a steel tumbler and glass bottle'
      ],
      [
        'title' => 'Printed Streetwear Tees in Bulk',
        'category' => 'CUSTOM T-SHIRTS',
        'image' => 'assets/images/custom_tshirt_apparel.jpg',
        'desc' => 'Model wearing a custom printed red T-shirt'
      ],
      [
        'title' => 'Branded Corporate Welcome Kit',
        'category' => 'CORPORATE MERCHANDISE',
        'image' => 'assets/images/corporate_swag_kit.jpg',
        'desc' => 'Corporate welcome kit with printed mug, diary and keychain'
      ]
    ];
    ?>

    <div class="gallery-grid">
      <?php foreach ($homepagePortfolio as $idx => $item): ?>
        <div class="gallery-item reveal scale-in stagger-<?= $idx + 1 ?>" style="height:300px; border-radius:var(--radius-md);">
          <img src="<?= url($item['image']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
          <div class="gallery-overlay">
            <div class="gallery-category" style="color:var(--beige);"><?= e($item['category']) ?></div>
            <h4 class="gallery-title" style="font-size:1.05rem;"><?= e($item['title']) ?></h4>
            <p style="font-size:0.8rem; color:rgba(255,255,255,0.85); margin:0;"><?= e($item['desc']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Prominent "View Full Portfolio" Button -->
    <div style="text-align:center; margin-top:3.25rem;" class="reveal slide-up">
      <a href="<?= url('gallery/') ?>" class="btn btn-outline btn-lg cta-wide-btn">
        <span>View Full Portfolio</span>
        <svg style="width:18px;height:18px;" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </a>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 7: FORWARD WORKFLOW
     ======================================================================== -->
<section class="section section-workflow" id="workflow">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label" style="justify-content:center;">HOW IT WORKS</div>
      <h2>How to Order DTF &amp; UV DTF Prints in 3 Simple Steps</h2>
      <p class="section-subtitle">
        From your digital file to doorstep delivery, with a free proof before we print.
      </p>
    </div>

    <div class="workflow-grid">
      <!-- 01 -->
      <div class="workflow-card reveal slide-up stagger-1">
        <div class="workflow-header">
          <span class="workflow-num">01</span>
          <div class="workflow-line"></div>
        </div>
        <h3 class="workflow-title">01 &mdash; Upload Your Artwork</h3>
        <p class="workflow-desc">
          Upload an AI or PDF file, or a 300 DPI transparent PNG, on our site. You can also send your design and details on WhatsApp.
        </p>
      </div>

      <!-- 02 -->
      <div class="workflow-card reveal slide-up stagger-2">
        <div class="workflow-header">
          <span class="workflow-num">02</span>
          <div class="workflow-line"></div>
        </div>
        <h3 class="workflow-title">02 &mdash; Review Proof &amp; Quote</h3>
        <p class="workflow-desc">
          Our team checks resolution and sheet layout, then sends you an itemized quote and a proof to approve before printing starts.
        </p>
      </div>

      <!-- 03 -->
      <div class="workflow-card reveal slide-up stagger-3">
        <div class="workflow-header">
          <span class="workflow-num">03</span>
        </div>
        <h3 class="workflow-title">03 &mdash; Production &amp; Dispatch</h3>
        <p class="workflow-desc">
          We print, cure and quality-check your order, then ship it with tracked courier delivery anywhere in India.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 8: LATEST BLOG POSTS & PRINTING GUIDES
     ======================================================================== -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label" style="justify-content:center;">PRINTING GUIDES</div>
      <h2>DTF &amp; UV DTF Printing Guides and Tips</h2>
      <p class="section-subtitle">
        Practical tutorials and comparisons from our printing team, to help you choose the right transfer and get the best results.
      </p>
    </div>

    <?php
    $guidePosts = [
      [
        'category_name' => 'GARMENT & APPAREL',
        'title' => 'DTF Printing Explained: How Direct-to-Film Transfers Work',
        'excerpt' => 'Learn how DTF printing works, how it compares with vinyl and screen printing, and the pressing temperature and time that give the best wash durability.',
        'alt' => 'DTF transfer printed on a dark T-shirt',
        'featured_image' => $latestBlogPosts[0]['featured_image'] ?? 'assets/images/dtf_fabric_print.jpg',
        'created_at' => $latestBlogPosts[0]['created_at'] ?? date('Y-m-d'),
        'read_time' => $latestBlogPosts[0]['read_time'] ?? '5 min read',
        'slug' => $latestBlogPosts[0]['slug'] ?? 'dtf-printing-explained'
      ],
      [
        'category_name' => 'COMPARISONS',
        'title' => 'DTF vs UV DTF: Which Transfer Should You Choose?',
        'excerpt' => 'A simple breakdown of where each one works best: DTF for fabrics and apparel, UV DTF for mugs, bottles, glass and other hard surfaces.',
        'alt' => 'DTF and UV DTF transfers shown side by side',
        'featured_image' => $latestBlogPosts[1]['featured_image'] ?? 'assets/images/uv_dtf_drinkware.jpg',
        'created_at' => $latestBlogPosts[1]['created_at'] ?? date('Y-m-d'),
        'read_time' => $latestBlogPosts[1]['read_time'] ?? '4 min read',
        'slug' => $latestBlogPosts[1]['slug'] ?? 'dtf-vs-uv-dtf'
      ],
      [
        'category_name' => 'PRINTING GUIDES',
        'title' => 'How to Prepare Artwork for DTF Gang Sheets',
        'excerpt' => 'File formats, 300 DPI resolution, transparent backgrounds and spacing tips that save film and help you avoid reprints.',
        'alt' => 'Designer arranging artwork on a DTF gang sheet',
        'featured_image' => $latestBlogPosts[2]['featured_image'] ?? 'assets/images/custom_tshirt_apparel.jpg',
        'created_at' => $latestBlogPosts[2]['created_at'] ?? date('Y-m-d'),
        'read_time' => $latestBlogPosts[2]['read_time'] ?? '6 min read',
        'slug' => $latestBlogPosts[2]['slug'] ?? 'how-to-prepare-artwork-for-dtf-gang-sheets'
      ]
    ];
    ?>

    <div class="home-blog-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:2rem;">
      <?php foreach ($guidePosts as $idx => $post): ?>
        <article class="blog-card reveal slide-up stagger-<?= $idx + 1 ?>">
          <div style="position:relative; height:200px; overflow:hidden; background:#121212;">
            <img src="<?= url($post['featured_image']) ?>" alt="<?= e($post['alt']) ?>" style="width:100%; height:100%; object-fit:cover; transition:transform 0.4s ease;" loading="lazy">
            <span style="position:absolute; top:12px; left:12px; background:var(--bg-card); color:var(--primary-gold); border:1px solid rgba(212,175,55,0.4); font-size:0.75rem; font-weight:700; padding:4px 10px; border-radius:4px; text-transform:uppercase; letter-spacing:0.04em;">
              <?= e($post['category_name']) ?>
            </span>
          </div>
          
          <div style="padding:1.75rem; display:flex; flex-direction:column; flex:1;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:var(--slate-light); margin-bottom:0.75rem;">
              <span><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
              <span><?= e($post['read_time']) ?></span>
            </div>

            <h3 style="font-size:1.15rem; line-height:1.4; margin-bottom:0.75rem; font-family:var(--font-sans); font-weight:700;">
              <a href="<?= blog_url($post['slug']) ?>" style="color:var(--navy); text-decoration:none;">
                <?= e($post['title']) ?>
              </a>
            </h3>

            <p style="color:var(--slate); font-size:0.88rem; line-height:1.55; margin-bottom:1.5rem; flex:1; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
              <?= e($post['excerpt']) ?>
            </p>

            <div>
              <a href="<?= blog_url($post['slug']) ?>" style="color:var(--blue); font-weight:700; font-size:0.88rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                <span>Read Full Article</span> &rarr;
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:3.5rem;" class="reveal slide-up">
      <a href="<?= blog_url() ?>" class="btn btn-navy btn-lg cta-wide-btn">
        <span>View All Articles</span>
        <svg style="width:18px;height:18px;" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </a>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 9: CLOSING STUDIO CALL TO ACTION
     ======================================================================== -->
<section class="section section-navy" style="text-align:center; border-top:2px solid var(--border-gold);">
  <div class="container container-readable reveal slide-up">
    <div class="section-label" style="justify-content:center;">READY TO PRINT?</div>
    <h2 style="font-size:clamp(2.2rem, 3.8vw, 3.5rem); margin-bottom:1.25rem;">
      Turn Your Designs into Print-Ready DTF &amp; UV DTF Transfers
    </h2>
    <p style="font-size:1.2rem; color:rgba(255,255,255,0.85); max-width:680px; margin:0 auto 2.5rem; line-height:1.6;">
      Send us your artwork today. Our Delhi team will check your file for free and send you an itemized quote within 24 hours.
    </p>

    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="https://wa.me/919354028199" target="_blank" rel="noopener" class="btn btn-navy btn-lg" style="color:#25D366; border-color:#25D366;">
        <span>Chat on WhatsApp</span>
      </a>
      <a href="#upload-studio" class="btn btn-primary btn-lg">
        <span>Upload Your Design</span>
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
        </svg>
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>