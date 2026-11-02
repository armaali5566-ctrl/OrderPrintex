<?php
/**
 * OrderPrintex - T-Shirt Printing Service Page
 */

$pageTitle = "Custom T-Shirt Printing in Delhi, India — Cotton & Poly Blends | OrderPrintex";
$pageDesc  = "High-definition custom t-shirt printing by OrderPrintex Delhi. Bio-washed 100% combed cotton, oversized streetwear tees, couple tees, wedding packages, and bulk corporate polo printing.";

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 't-shirt-printing'");
?>

<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem;">
  <div class="container text-center reveal slide-up">
    <div class="section-label" style="justify-content:center;">Apparel Production &amp; Merchandise</div>
    <h1 style="font-size:3.2rem; margin-bottom:1rem;">Custom T-Shirt Printing Studio</h1>
    <p class="section-subtitle" style="color:rgba(255,255,255,0.75); margin:0 auto;">
      From prototype brand samples to bulk corporate runs, we print high-density graphics onto 180–240 GSM bio-washed combed cotton, oversized drop-shoulder tees, and athletic polyester.
    </p>
  </div>
</section>

<!-- Overview -->
<section class="section section-white">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:3.5rem; align-items:center;">
      <div class="reveal slide-right">
        <div class="section-label">Garment Quality Standards</div>
        <h2 style="margin-bottom:1.25rem;">Retail-Grade Printing on Premium Bio-Washed Fabrics</h2>
        <p>
          At <strong>OrderPrintex</strong>, we bridge the gap between small creator runs and heavy industrial garment manufacturing. By pairing advanced digital DTF machinery with ethically sourced, pre-shrunk combed cotton fabrics, we produce ready-to-wear garments that rival international retail brands.
        </p>
        <p>
          Our prints are soft to the touch, highly breathable, and engineered with grade 4+ color wash fastness. They stretch with the fabric and will not crack or peel after repeated home machine laundering.
        </p>
        <p>
          Whether you need 10 t-shirts for a wedding party or 5,000 corporate dry-fit jerseys for a nationwide marathon, our Delhi facility ensures strict color matching and on-time dispatch.
        </p>

        <div style="margin-top:2rem; display:flex; gap:1rem;">
          <a href="#upload-tshirt" class="btn btn-primary">Request T-Shirt Quote</a>
          <a href="<?= url('products.php') ?>" class="btn btn-outline">Explore Garment Styles</a>
        </div>
      </div>

      <div class="reveal slide-left">
        <div style="border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-lg); border:1px solid var(--border-gold);">
          <img src="<?= url('assets/images/custom_tshirt_apparel.jpg') ?>" alt="Finished Custom Printed T-Shirts" style="width:100%; height:440px; object-fit:cover;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Package Types Supported (From verified site content) -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Supported Solutions</div>
      <h2>Tailored Packages for Every Occasion</h2>
      <p class="section-subtitle">
        Flexible order quantities, premium packaging, and multiple garment silhouettes.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.75rem;">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Brand Merchandise</h4>
        <p style="font-size:0.85rem; color:var(--slate);">240 GSM heavy oversized streetwear tees with neck label tags and individual barcode poly-bags.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Couple &amp; Family Tees</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Matching his &amp; hers tees, anniversary sets, and custom typography prints for celebrations.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Wedding &amp; Event Packages</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Coordinated wedding squad tees, college fest batches, reunion apparel, and sports team kits.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Corporate Staff Polos</h4>
        <p style="font-size:0.85rem; color:var(--slate);">High-GSM pique cotton polo shirts with embroidered or DTF chest logos for corporate teams.</p>
      </div>
    </div>
  </div>
</section>

<!-- Direct Upload / Order Form -->
<section class="section section-white" id="upload-tshirt">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">T-Shirt Printing Quote</div>
      <h2>Configure Your Custom T-Shirt Order</h2>
      <p class="section-subtitle">
        Upload your front/back graphic and choose your quantities and sizes for a direct factory quote.
      </p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="service_name" value="T-Shirt Printing">

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
              <div class="dropzone-title">Upload T-Shirt Print Graphic</div>
              <div class="dropzone-hint">Transparent PNG preferred, 300 DPI (Max 25 MB)</div>
              <button type="button" class="btn btn-sm btn-outline-gold">Choose File</button>
            </div>

            <div class="file-preview-area"></div>

            <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-light);">
              <h5 style="color:var(--navy); margin-bottom:0.35rem; font-size:0.95rem;">Fabric &amp; Print Assurance</h5>
              <p style="font-size:0.825rem; color:var(--slate); margin:0;">
                All t-shirts use 100% super-combed bio-washed ringspun cotton with double-needle stitched neck and sleeves.
              </p>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Garment Silhouette / Fabric <span class="required">*</span></label>
                <select name="material_options" class="form-control">
                  <option value="180 GSM Round Neck Cotton">180 GSM Round Neck (100% Bio-Washed Cotton)</option>
                  <option value="240 GSM Oversized Streetwear">240 GSM Heavyweight Oversized Tee</option>
                  <option value="Polo Collar Cotton Pique">Polo Collar Shirt (Cotton Pique)</option>
                  <option value="Dry-Fit Sports Polyester">Dry-Fit Athletic Polyester</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Total Quantity <span class="required">*</span></label>
                <input type="number" name="quantity" class="form-control" value="25" min="1" required>
              </div>

              <div class="form-group">
                <label class="form-label">Print Placement</label>
                <select class="form-control">
                  <option value="Front Chest (A4)">Front Chest (A4)</option>
                  <option value="Front + Back Large">Front Chest + Full Back (A3)</option>
                  <option value="Left Chest Pocket Only">Left Chest Pocket Only</option>
                  <option value="Custom Placements">Custom Placements</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Your Name <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control" placeholder="Your Name" required>
              </div>

              <div class="form-group">
                <label class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" class="form-control" placeholder="Your Email" required>
              </div>

              <div class="form-group">
                <label class="form-label">Phone / WhatsApp <span class="required">*</span></label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="+91 93540 28199" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label">Size Breakdown &amp; Color Preferences</label>
                <textarea name="customer_notes" class="form-control" placeholder="e.g. 10 Medium Black, 15 Large White, 5 XL Maroon..."></textarea>
              </div>
            </div>

            <div style="margin-top:2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ Pre-shrunk cotton &bull; High opacity DTF &bull; Bulk packing
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Request T-Shirt Quotation</span> &rarr;
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
