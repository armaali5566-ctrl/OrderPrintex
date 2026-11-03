<?php
/**
 * OrderPrintex - Homepage
 * Premium International Printing Studio Redesign
 * Focused on Customer Conversions, Clean URLs, Dynamic Products & Blog Showcase
 */

$pageTitle = "PRINTEX — Industrial DTF & UV DTF Manufacturer & Bulk Supplier in India";
$pageDesc  = "PRINTEX is India's leading bulk manufacturer of DTF garment transfers, UV DTF 3D decals, custom screen-quality t-shirts, and corporate merchandise. Fast turnaround, prepress checks, and nationwide delivery.";

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

$heroHeading = get_setting('hero_heading', 'Precision Print Transfers for Garments & Hard Surfaces');
$heroDesc = get_setting('hero_description', 'Manufacturer & Bulk Supplier of DTF and UV DTF in India. We engineer press-ready transfers with vivid color gamut, durable washability, and rapid turnaround for apparel brands, promotional studios, and print businesses.');
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
          ★ India's Direct-to-Film &amp; UV DTF Specialists
        </div>
        
        <h1 class="hero-title">
          Precision Print Transfers for <span class="accent-serif">Garments</span> &amp; Hard Surfaces.
        </h1>
        
        <p class="hero-description">
          <?= e($heroDesc) ?>
        </p>

        <div class="hero-ctas">
          <a href="#upload-studio" class="btn btn-primary btn-lg">
            <span>Upload Your Design</span>
            <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
          </a>
          <a href="<?= url('products/') ?>" class="btn btn-navy btn-lg">
            <span>Explore Products</span>
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
              <div class="highlight-text">Pre-Cured &amp; ICC Calibrated</div>
              <small style="color:var(--slate-light);">300 DPI high-res output guarantee</small>
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
              <div class="highlight-text">Fast Pan-India Delivery</div>
              <small style="color:var(--slate-light);">1–3 business days express dispatch</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Visual Composition -->
      <div class="hero-visual reveal slide-left">
        <div class="hero-visual-card">
          <img src="<?= url('assets/images/dtf_fabric_print.jpg') ?>" alt="PRINTEX Delhi Studio DTF Production">
        </div>

        <!-- Floating Studio Quality Badge -->
        <div class="floating-badge">
          <h4>Commercial Grade</h4>
          <p>50+ Wash Cycles &bull; Hot/Cold Instant Peel &bull; Polyurethane TPU Adhesive</p>
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
    <div class="marquee-item">DTF Transfer Printing <span class="marquee-dot"></span></div>
    <div class="marquee-item">UV DTF Decals &amp; Stickers <span class="marquee-dot"></span></div>
    <div class="marquee-item">Custom T-Shirt Printing <span class="marquee-dot"></span></div>
    <div class="marquee-item">Corporate Promotional Merchandise <span class="marquee-dot"></span></div>
    <div class="marquee-item">24-Inch Continuous Gang Sheets <span class="marquee-dot"></span></div>
    <div class="marquee-item">Prepress Artwork Validation <span class="marquee-dot"></span></div>
    <div class="marquee-item">No Heat Press Needed For UV DTF <span class="marquee-dot"></span></div>
    <div class="marquee-item">Pan-India Express Dispatch <span class="marquee-dot"></span></div>
  </div>
  <div class="marquee-track">
    <div class="marquee-item">DTF Transfer Printing <span class="marquee-dot"></span></div>
    <div class="marquee-item">UV DTF Decals &amp; Stickers <span class="marquee-dot"></span></div>
    <div class="marquee-item">Custom T-Shirt Printing <span class="marquee-dot"></span></div>
    <div class="marquee-item">Corporate Promotional Merchandise <span class="marquee-dot"></span></div>
    <div class="marquee-item">24-Inch Continuous Gang Sheets <span class="marquee-dot"></span></div>
    <div class="marquee-item">Prepress Artwork Validation <span class="marquee-dot"></span></div>
    <div class="marquee-item">No Heat Press Needed For UV DTF <span class="marquee-dot"></span></div>
    <div class="marquee-item">Pan-India Express Dispatch <span class="marquee-dot"></span></div>
  </div>
</div>

<!-- ========================================================================
     SECTION 2: PROMINENT UPLOAD YOUR DESIGN WORKBENCH
     ======================================================================== -->
<section class="section section-white" id="upload-studio">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Prepress Production Studio</div>
      <h2>Upload Your Artwork for an Itemized Quotation</h2>
      <p class="section-subtitle">
        Our prepress technicians inspect transparency, resolution (300 DPI), and sheet density before formulating your itemized commercial proposal. No upfront payment required.
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
              <div class="dropzone-title">Drag &amp; drop artwork here, or <span class="text-gold" style="text-decoration:underline; cursor:pointer;">Browse Files</span></div>
              <div class="dropzone-hint">Supported: PNG, JPG, PDF, AI, PSD, SVG (Max 25 MB). 300 DPI transparent background recommended.</div>
              <button type="button" class="btn btn-sm btn-outline-gold" onclick="document.getElementById('artworkFileInput').click();">Select Artwork File</button>
            </div>

            <!-- Real-time File Preview Area -->
            <div class="file-preview-area" id="filePreviewArea"></div>

            <!-- Print Dimensions Footprint Meter -->
            <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-light);">
              <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.85rem; font-weight:700; color:var(--navy);">Calculated Print Footprint:</span>
                <strong class="sq-inches-display text-navy" style="font-size:1.15rem; color:var(--blue);">—</strong>
              </div>
              <small style="color:var(--slate-light); display:block; margin-top:4px; font-size:0.78rem;">
                Width &times; Height calculates sheet area to maximize roll economy on 24-inch continuous film.
              </small>
            </div>
          </div>

          <!-- Right: Print Specs & Customer Details -->
          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label" for="serviceSelect">
                  Printing Service <span class="required">*</span>
                </label>
                <select name="service_name" id="serviceSelect" class="form-control" required>
                  <option value="DTF Printing" selected>DTF Printing (Garment Transfers)</option>
                  <option value="UV DTF Printing">UV DTF Printing (Hard Surface Decals)</option>
                  <option value="T-Shirt Printing">Custom T-Shirt Printing (Cotton)</option>
                  <option value="Corporate Printing">Corporate Gifts &amp; Branded Merch</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="qtyInput">
                  Quantity / Sheets <span class="required">*</span>
                </label>
                <input type="number" name="quantity" id="qtyInput" class="form-control" min="1" value="1" required>
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
                <label class="form-label" for="notesInput">Special Instructions / Fabric or Surface Details</label>
                <textarea name="customer_notes" id="notesInput" class="form-control" placeholder="Mention target fabric colors (e.g. black cotton tees), surface type (e.g. stainless mugs), or specific gang sheet layout requirements..."></textarea>
              </div>

              <div class="form-group">
                <label class="form-label" for="custName">Your Name / Brand <span class="required">*</span></label>
                <input type="text" name="customer_name" id="custName" class="form-control" placeholder="e.g. Rahul Sharma" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="custEmail">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" id="custEmail" class="form-control" placeholder="client@example.com" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label" for="custPhone">Phone / WhatsApp Number <span class="required">*</span></label>
                <input type="tel" name="customer_phone" id="custPhone" class="form-control" placeholder="e.g. +91 93540 28199" required>
              </div>
            </div>

            <div style="margin-top:2rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.25rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ Free Prepress Audit &bull; 24-hr Quote Response &bull; Pan-India Dispatch
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Submit Artwork &amp; Get Quotation</span>
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
      <div class="section-label" style="justify-content:center;">Commercial Showcase</div>
      <h2>Popular Printing Products</h2>
      <p class="section-subtitle">
        Manufactured in our New Delhi workshop with Japanese micro-piezo heads, vivid color saturation, and durable washability.
      </p>
    </div>

    <div class="products-grid products-grid-3">
      <?php foreach ($featuredProducts as $idx => $prod): ?>
        <article class="product-card reveal slide-up stagger-<?= ($idx % 3) + 1 ?>">
          <div class="product-media" style="background:#121212; border-bottom:1px solid var(--border-color); display:flex; align-items:center; justify-content:center; padding:1.25rem; height:280px;">
            <?php if (!empty($prod['badge'])): ?>
              <span class="product-badge-tag"><?= e($prod['badge']) ?></span>
            <?php endif; ?>
            <button type="button" class="btn-wishlist" onclick="event.stopPropagation(); toggleWishlist(<?= $prod['id'] ?>, this)" title="Save to Wishlist" style="position:absolute; top:12px; right:12px; z-index:5;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
              </svg>
            </button>
            <img src="<?= url($prod['image_url'] ?: 'assets/images/placeholder.jpg') ?>" 
                 alt="<?= e($prod['title']) ?> - OrderPrintex" 
                 loading="lazy" 
                 style="max-width:100%; max-height:100%; object-fit:contain;">
          </div>
          <div class="product-body">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
              <span class="product-category-label"><?= e($prod['category_name'] ?? 'Printing Service') ?></span>
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
              <a href="<?= url('get-quote/?service_name=' . urlencode($prod['category_name'] ?? 'DTF Printing')) ?>" class="btn btn-primary" aria-label="Order or request quote for <?= e($prod['title']) ?>">
                Order / Quote
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Prominent "View All Products" Button -->
    <div style="text-align:center; margin-top:3.5rem;" class="reveal slide-up">
      <a href="<?= url('products/') ?>" class="btn btn-outline btn-lg" style="display:inline-flex; align-items:center; gap:0.75rem; padding:1.15rem 2.75rem; font-size:1.05rem; border-color:var(--primary-gold); color:var(--bright-gold); background:var(--bg-surface);">
        <span>View All Products in Commercial Catalog</span>
        <svg style="width:18px;height:18px;" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </a>
      <div style="margin-top:1.25rem;">
        <small style="color:var(--slate-subtle); font-size:0.82rem;">
          &bull; Illustrative commercial specimen catalog. Bespoke orders quoted based on your custom artwork and quantity tiers.
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
      <div class="section-label">Industrial Solutions</div>
      <h2>Printing Services Overview</h2>
      <p class="section-subtitle">
        Engineered with Japanese micro-piezo printheads, white-ink recirculation systems, and precision powder-shaker ovens.
      </p>
    </div>

    <div class="services-grid">
      <!-- 1. DTF Transfers -->
      <div class="service-card reveal slide-up">
        <div class="service-card-media">
          <img src="<?= url('assets/images/dtf_fabric_print.jpg') ?>" alt="Direct-to-Film Transfers">
          <span class="service-card-badge">Flagship Service</span>
        </div>
        <div class="service-card-body">
          <h3 class="service-card-title">DTF Printing</h3>
          <p class="service-card-desc">
            Vivid full-color textile film transfers engineered for cotton, polyester, and performance blends. 50+ wash durability with soft-hand feel.
          </p>
          <a href="<?= service_url('dtf-printing') ?>" class="service-card-link">
            <span>Explore Specifications</span> &rarr;
          </a>
        </div>
      </div>

      <!-- 2. UV DTF Decals -->
      <div class="service-card reveal slide-up" style="transition-delay:0.1s;">
        <div class="service-card-media">
          <img src="<?= url('assets/images/uv_dtf_drinkware.jpg') ?>" alt="UV DTF 3D Decals">
          <span class="service-card-badge">No Heat Press</span>
        </div>
        <div class="service-card-body">
          <h3 class="service-card-title">UV DTF Printing</h3>
          <p class="service-card-desc">
            Industrial-grade peel-and-stick decals with tactile 3D embossed gloss varnish. Permanent bonding on mugs, tumblers, glass, and acrylic.
          </p>
          <a href="<?= service_url('uv-dtf-printing') ?>" class="service-card-link">
            <span>Explore Specifications</span> &rarr;
          </a>
        </div>
      </div>

      <!-- 3. T-Shirt Printing -->
      <div class="service-card reveal slide-up" style="transition-delay:0.2s;">
        <div class="service-card-media">
          <img src="<?= url('assets/images/streetwear_tee.jpg') ?>" alt="Custom Apparel Printing">
          <span class="service-card-badge">100% Combed Cotton</span>
        </div>
        <div class="service-card-body">
          <h3 class="service-card-title">T-Shirt Printing</h3>
          <p class="service-card-desc">
            Custom-printed screen-grade cotton t-shirts, oversized streetwear, and company polos. High-density underbase on dark and light fabrics.
          </p>
          <a href="<?= service_url('t-shirt-printing') ?>" class="service-card-link">
            <span>Explore Specifications</span> &rarr;
          </a>
        </div>
      </div>

      <!-- 4. Corporate Printing -->
      <div class="service-card reveal slide-up" style="transition-delay:0.3s;">
        <div class="service-card-media">
          <img src="<?= url('assets/images/corporate_swag_kit.jpg') ?>" alt="Corporate Printing">
          <span class="service-card-badge">Bulk Merchandise</span>
        </div>
        <div class="service-card-body">
          <h3 class="service-card-title">Corporate Printing</h3>
          <p class="service-card-desc">
            Complete executive welcome kits, promotional packages, branded water bottles, notebooks, and summit gifts with precise brand matching.
          </p>
          <a href="<?= service_url('corporate-printing') ?>" class="service-card-link">
            <span>Explore Specifications</span> &rarr;
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
      <div class="section-label">Commercial Reliability</div>
      <h2>Why OrderPrintex?</h2>
      <p class="section-subtitle" style="color:rgba(255,255,255,0.78);">
        A dependable print partner helping apparel brands, designers, and corporate teams scale production seamlessly.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:2rem;">
      <div style="background:var(--navy-card); padding:2.25rem 1.75rem; border-radius:var(--radius-md); border:1px solid rgba(185,154,97,0.3); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:1rem;">🎯</div>
        <h3 style="color:var(--white); font-size:1.25rem; margin-bottom:0.75rem;">300+ DPI Precision</h3>
        <p style="color:rgba(255,255,255,0.75); font-size:0.9rem; line-height:1.6; margin:0;">
          Micro-piezo heads with calibrated high-opacity white ink underbases ensure fine gradients, sharp typefaces, and vivid saturation.
        </p>
      </div>

      <div style="background:var(--navy-card); padding:2.25rem 1.75rem; border-radius:var(--radius-md); border:1px solid rgba(185,154,97,0.3); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:1rem;">💰</div>
        <h3 style="color:var(--white); font-size:1.25rem; margin-bottom:0.75rem;">Wholesale Tier Savings</h3>
        <p style="color:rgba(255,255,255,0.75); font-size:0.9rem; line-height:1.6; margin:0;">
          Tiered pricing scales automatically with sheet length and bulk piece counts, passing manufacturer efficiency directly to your bottom line.
        </p>
      </div>

      <div style="background:var(--navy-card); padding:2.25rem 1.75rem; border-radius:var(--radius-md); border:1px solid rgba(185,154,97,0.3); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:1rem;">🔍</div>
        <h3 style="color:var(--white); font-size:1.25rem; margin-bottom:0.75rem;">Prepress File Validation</h3>
        <p style="color:rgba(255,255,255,0.75); font-size:0.9rem; line-height:1.6; margin:0;">
          Technicians inspect your artwork for transparent margins, resolution, and gang sheet density before printing to guarantee flawless output.
        </p>
      </div>

      <div style="background:var(--navy-card); padding:2.25rem 1.75rem; border-radius:var(--radius-md); border:1px solid rgba(185,154,97,0.3); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:1rem;">⚡</div>
        <h3 style="color:var(--white); font-size:1.25rem; margin-bottom:0.75rem;">Express Dispatch</h3>
        <p style="color:rgba(255,255,255,0.75); font-size:0.9rem; line-height:1.6; margin:0;">
          Continuous roll-to-roll printing and automated shaker ovens enable rapid 24-48hr turnaround and express courier delivery across India.
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
      <div class="section-label" style="justify-content:center;">Portfolio Preview</div>
      <h2>Selected Production Specimens</h2>
      <p class="section-subtitle">
        A glimpse of precision transfer runs, 3D UV decals, and finished merchandise produced inside our New Delhi facility.
      </p>
    </div>

    <?php
    $homepagePortfolio = [
      [
        'title' => 'DTF Apparel Print on Dark Cotton Garment',
        'category' => 'DTF Printing',
        'image' => 'assets/images/dtf_fabric_print.jpg',
        'desc' => 'Multi-color DTF print with high-opacity white underbase and soft-hand elasticity.'
      ],
      [
        'title' => 'UV DTF 3D Tactile Decals on Drinkware',
        'category' => 'UV DTF Decals',
        'image' => 'assets/images/uv_dtf_drinkware.jpg',
        'desc' => 'Peel-and-stick 3D gloss varnish decals with permanent multi-surface adhesion.'
      ],
      [
        'title' => 'Streetwear Graphic Apparel Production',
        'category' => 'Custom T-Shirts',
        'image' => 'assets/images/custom_tshirt_apparel.jpg',
        'desc' => 'Retail-grade bio-washed cotton streetwear drop with sharp edge sharpness.'
      ],
      [
        'title' => 'Executive Corporate Merchandise Kit',
        'category' => 'Corporate Merchandise',
        'image' => 'assets/images/corporate_swag_kit.jpg',
        'desc' => 'Turnkey branded presentation box with personalized mug, diary, and metal pen.'
      ]
    ];
    ?>

    <div class="gallery-grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
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
      <a href="<?= url('gallery/') ?>" class="btn btn-outline btn-lg" style="display:inline-flex; align-items:center; gap:0.75rem; padding:1.15rem 2.75rem; font-size:1.05rem;">
        <span>View Full Portfolio &amp; Specimen Gallery</span>
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
      <div class="section-label" style="justify-content:center;">Process &amp; Fulfillment</div>
      <h2>Forward Workflow</h2>
      <p class="section-subtitle">
        Forward workflow from digital file to door-to-door delivery.
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
          Drop your vector (AI, PDF) or 300 DPI transparent PNG into our online studio, or share details over WhatsApp.
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
          Technicians inspect resolution and density, then send an itemized quote with GST and gang sheet proofs.
        </p>
      </div>

      <!-- 03 -->
      <div class="workflow-card reveal slide-up stagger-3">
        <div class="workflow-header">
          <span class="workflow-num">03</span>
        </div>
        <h3 class="workflow-title">03 &mdash; Production &amp; Dispatch</h3>
        <p class="workflow-desc">
          High-speed printing, curing, quality checks, and insured courier delivery to your doorstep anywhere in India.
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
      <div class="section-label" style="justify-content:center;">Editorial Knowledge Hub</div>
      <h2>Latest Printing Guides &amp; Insights</h2>
      <p class="section-subtitle">
        Expert tutorials, material comparisons, and production guides from our technical printing team.
      </p>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:2rem;">
      <?php foreach ($latestBlogPosts as $idx => $post): ?>
        <article class="blog-card reveal slide-up stagger-<?= $idx + 1 ?>">
          <div style="position:relative; height:200px; overflow:hidden; background:#121212;">
            <img src="<?= url($post['featured_image']) ?>" alt="<?= e($post['title']) ?>" style="width:100%; height:100%; object-fit:cover; transition:transform 0.4s ease;" loading="lazy">
            <span style="position:absolute; top:12px; left:12px; background:var(--bg-card); color:var(--primary-gold); border:1px solid rgba(212,175,55,0.4); font-size:0.75rem; font-weight:700; padding:4px 10px; border-radius:4px; text-transform:uppercase; letter-spacing:0.04em;">
              <?= e($post['category_name'] ?? 'Guide') ?>
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
      <a href="<?= blog_url() ?>" class="btn btn-navy btn-lg" style="display:inline-flex; align-items:center; gap:0.75rem; padding:1.15rem 2.75rem; font-size:1.05rem;">
        <span>View All Industry Guides &amp; Articles</span>
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
    <div class="section-label" style="justify-content:center;">Ready to Print?</div>
    <h2 style="font-size:clamp(2.2rem, 3.8vw, 3.5rem); margin-bottom:1.25rem;">
      Transform Your Designs into Industrial-Grade Reality.
    </h2>
    <p style="font-size:1.2rem; color:rgba(255,255,255,0.85); max-width:680px; margin:0 auto 2.5rem; line-height:1.6;">
      Submit your artwork today. Our Delhi technicians will perform a complimentary prepress audit and provide a transparent, itemized quotation within 24 hours.
    </p>

    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="#upload-studio" class="btn btn-primary btn-lg">
        <span>Upload Your Design</span>
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
        </svg>
      </a>
      <a href="https://wa.me/919354028199" target="_blank" rel="noopener" class="btn btn-navy btn-lg" style="color:#25D366; border-color:#25D366;">
        <span>💬 Direct WhatsApp Studio</span>
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
