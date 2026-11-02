<?php
/**
 * OrderPrintex - DTF Printing Service Page
 */

$pageTitle = "DTF Printing in India — Direct-to-Film Transfers for Garments | OrderPrintex";
$pageDesc  = "OrderPrintex is India's bulk manufacturer of DTF garment transfers. Ultra soft-hand feel, 50+ wash cycles, 22-inch gang sheets, and fast nationwide dispatch.";

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'dtf-printing'");
?>

<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem;">
  <div class="container text-center reveal slide-up">
    <div class="section-label" style="justify-content:center;">Apparel &amp; Textile Innovation</div>
    <h1 style="font-size:3.2rem; margin-bottom:1rem;">Commercial DTF Printing (Direct-to-Film)</h1>
    <p class="section-subtitle" style="color:rgba(255,255,255,0.75); margin:0 auto;">
      High-density white underbase, vibrant textile pigments, and stretchable TPU adhesive for flawless pressing onto cotton, poly-blends, nylon, and performance wear.
    </p>
  </div>
</section>

<!-- Specifications & Overview -->
<section class="section section-white">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:3.5rem; align-items:center;">
      <div class="reveal slide-right">
        <div class="section-label">Garment Transfer Engineering</div>
        <h2 style="margin-bottom:1.25rem;">Why OrderPrintex DTF Outperforms Traditional Screen &amp; Vinyl</h2>
        <p>
          Direct-to-Film (DTF) transfer printing has revolutionized garment customization across India. Unlike screen printing which requires expensive mesh setup and color separation fees per shade, DTF prints photorealistic artwork, gradients, shadows, and fine line-art directly onto release film in full vibrant color.
        </p>
        <p>
          Every sheet produced at our Delhi facility is backed with uniform, high-grade thermoplastic polyurethane (TPU) powder that bonds mechanically to the fibers of cotton, polyester, spandex, leather, and canvas.
        </p>
        <p>
          No weeding, no vinyl cutting, and zero mess. Simply cut the transfer, heat-press at 160&deg;C for 15 seconds, cold peel, and post-press for a factory-finish feel.
        </p>

        <div style="margin-top:2rem; display:flex; gap:1rem;">
          <a href="#upload-dtf" class="btn btn-primary">Upload Design for DTF Quote</a>
          <a href="<?= url('products.php') ?>" class="btn btn-outline">View Gang Sheet Options</a>
        </div>
      </div>

      <div class="reveal slide-left">
        <div style="border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-lg); border:1px solid var(--border-gold);">
          <img src="<?= url('assets/images/dtf_fabric_print.jpg') ?>" alt="DTF Transfer Print on Dark Fabric" style="width:100%; height:440px; object-fit:cover;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Technical Specifications Table -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Technical Parameters</div>
      <h2>DTF Press &amp; Material Specifications</h2>
      <p class="section-subtitle">
        Calibrated parameters for professional apparel decorators and print shops.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.75rem;">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--gold); margin-bottom:0.5rem;">Press Temperature</h4>
        <div style="font-size:1.8rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">155&deg;C – 165&deg;C</div>
        <p style="font-size:0.85rem; color:var(--slate);">Optimal temperature for polyurethane adhesive activation.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--gold); margin-bottom:0.5rem;">Press Time &amp; Pressure</h4>
        <div style="font-size:1.8rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">15 Seconds &bull; Med-High</div>
        <p style="font-size:0.85rem; color:var(--slate);">Firm, even pressure (40–50 PSI) ensures deep fabric adhesion.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--gold); margin-bottom:0.5rem;">Peel Technique</h4>
        <div style="font-size:1.8rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Cold / Warm Release</div>
        <p style="font-size:0.85rem; color:var(--slate);">Allow film to cool for a clean, sharp, tear-free transfer edge.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--gold); margin-bottom:0.5rem;">Roll Width Options</h4>
        <div style="font-size:1.8rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">22 &amp; 24 Inches</div>
        <p style="font-size:0.85rem; color:var(--slate);">Continuous gang roll lengths up to 100 meters per order.</p>
      </div>
    </div>
  </div>
</section>

<!-- Artwork Guidelines & Upload -->
<section class="section section-white" id="upload-dtf">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Direct Upload Studio</div>
      <h2>Order DTF Transfers &amp; Gang Sheets</h2>
      <p class="section-subtitle">
        Upload your transparent PNG, PDF, or vector design below for a rapid turnaround commercial quotation.
      </p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="service_name" value="DTF Printing">

        <div class="upload-studio-grid">
          <div>
            <div class="dropzone-container">
              <input type="file" name="artwork_file" class="file-input" accept=".png,.jpg,.jpeg,.pdf,.ai,.psd,.svg" style="display:none;">
              <div class="dropzone-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
              </div>
              <div class="dropzone-title">Click to upload DTF artwork (or drag and drop)</div>
              <div class="dropzone-hint">Transparent PNG, 300 DPI, CMYK/RGB, Max 25 MB</div>
              <button type="button" class="btn btn-sm btn-outline-gold">Select File</button>
            </div>

            <div class="file-preview-area"></div>

            <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-light);">
              <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.85rem; font-weight:700; color:var(--navy);">Calculated Print Footprint:</span>
                <strong class="sq-inches-display text-navy" style="font-size:1.15rem; color:var(--blue);">—</strong>
              </div>
              <small style="color:var(--slate-light); display:block; margin-top:4px; font-size:0.78rem;">
                Standard roll width is 22 inches printable. Specify total height/length in inches.
              </small>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Gang Sheet Width (Inches)</label>
                <input type="number" step="0.1" name="width_inches" class="form-control" value="22" readonly>
              </div>
              <div class="form-group">
                <label class="form-label">Length / Height (Inches) <span class="required">*</span></label>
                <input type="number" step="0.1" name="height_inches" class="form-control" placeholder="e.g. 40 or 100" required>
              </div>
              <div class="form-group">
                <label class="form-label">Quantity (Rolls/Sheets) <span class="required">*</span></label>
                <input type="number" name="quantity" class="form-control" value="1" min="1" required>
              </div>
              <div class="form-group">
                <label class="form-label">Your Name <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control" placeholder="e.g. Rahul Sharma" required>
              </div>
              <div class="form-group">
                <label class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" class="form-control" placeholder="e.g. rahul@brand.com" required>
              </div>
              <div class="form-group">
                <label class="form-label">Phone / WhatsApp <span class="required">*</span></label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="e.g. +91 93540 28199" required>
              </div>
              <div class="form-group full-width">
                <label class="form-label">Instructions / Target Fabrics</label>
                <textarea name="customer_notes" class="form-control" placeholder="Target fabric (cotton, polyester, blend), fabric colors, or delivery timeline requirements..."></textarea>
              </div>
            </div>

            <div style="margin-top:2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ Free Prepress Inspection &bull; Express Dispatch &bull; 50+ Wash Durability
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Request DTF Quotation</span> &rarr;
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
