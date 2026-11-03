<?php
/**
 * OrderPrintex - Dedicated Get a Quote Page
 * Full-Width Studio Workbench Architecture
 */

$pageTitle = "Get an Itemized Printing Quotation | PRINTEX Studio";
$pageDesc  = "Request an itemized quotation for DTF transfers, UV DTF stickers, custom t-shirt production, or corporate promotional merchandise. Fast prepress review and transparent pricing.";
$bodyClass = 'page-get-quote';

require_once __DIR__ . '/includes/header.php';

$selectedServiceId = intval($_GET['service_id'] ?? 0);
$services = db_fetch_all("SELECT id, title, slug FROM services WHERE is_active = 1 ORDER BY display_order ASC");
if (empty($services)) {
    $services = [
        ['id' => 1, 'title' => 'DTF Printing', 'slug' => 'dtf-printing'],
        ['id' => 2, 'title' => 'UV DTF Printing', 'slug' => 'uv-dtf-printing'],
        ['id' => 3, 'title' => 'T-Shirt Printing', 'slug' => 't-shirt-printing'],
        ['id' => 4, 'title' => 'Corporate Printing', 'slug' => 'corporate-printing'],
    ];
}
$currentUser = auth_user();
?>

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Transparent B2B Pricing</span>
      </div>
      <h1 class="page-intro-title">Request an Itemized Quotation</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Submit your printing specifications and artwork file. Our prepress team evaluates printability, resolution, and gang optimization to provide a prompt, binding commercial quotation within 24 hours.
      </p>
    </div>
  </div>
</section>

<section class="section section-quote-workbench" style="background:#080808; padding:clamp(2rem, 3.5vw, 3.5rem) 0 clamp(4rem, 6vw, 6rem);">
  <div class="container quotation-container">
    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="upload-studio-grid">
          
          <!-- Left: Drag & Drop Dropzone + File Preview + Estimation Card -->
          <div class="upload-left-col">
            <div class="dropzone-container" id="quoteDropzone">
              <input type="file" name="artwork_file" class="file-input" id="artworkFileInput" accept=".png,.jpg,.jpeg,.pdf,.ai,.psd,.svg" style="display:none;">
              <div class="dropzone-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
              </div>
              <div class="dropzone-title">Drop your design file here, or <span class="browse-link" style="color:#FFD76A; text-decoration:underline; cursor:pointer;" onclick="document.getElementById('artworkFileInput').click();">Browse</span></div>
              <div class="dropzone-hint">Accepted: PNG (transparent), PDF, AI, PSD, SVG (Max 25 MB). 300 DPI recommended for maximum print clarity.</div>
              <button type="button" class="btn btn-primary btn-md select-artwork-btn" onclick="document.getElementById('artworkFileInput').click();" style="padding:0.65rem 1.6rem; font-weight:700;">
                Select Artwork File
              </button>
            </div>

            <!-- Real-time File Preview Area -->
            <div class="file-preview-area" id="filePreviewArea"></div>

            <!-- Dynamic Estimated Price Range & Footprint Card -->
            <div class="quote-estimation-card" id="quoteEstimationCard" style="background:#202020; padding:1.4rem 1.5rem; border-radius:10px; margin-top:1.5rem; border:1px solid #40351E; box-shadow:0 4px 18px rgba(0,0,0,0.5);">
              <div style="font-size:0.85rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:#E8C45A; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#D4AF37;">
                  <path d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zm12 6v12H5V8h14zm-7 2h2v4h-2v-4zm-4 0h2v4H8v-4zm8 0h2v4h-2v-4z"/>
                </svg>
                <span>Estimated Price Range &amp; Footprint</span>
              </div>

              <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid #332a18;">
                <span style="font-size:0.9rem; font-weight:600; color:#F5F1E8;">Calculated Print Area:</span>
                <strong class="sq-inches-display" style="font-size:1.05rem; color:#FFD76A; font-weight:700;">—</strong>
              </div>

              <div style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 0 0.35rem;">
                <span style="font-size:0.9rem; font-weight:600; color:#F5F1E8;">Estimated Price Range:</span>
                <strong class="price-range-display" id="estimatedPriceDisplay" style="font-size:1.2rem; color:#FFD76A; font-weight:800;">₹249 – ₹349</strong>
              </div>

              <small style="color:#B8B1A3; display:block; margin-top:0.65rem; font-size:0.8rem; line-height:1.55;">
                Instant automated calculation based on selected service, quantity, and dimensions. Delhi prepress engineers perform a free resolution audit before issuing final invoice.
              </small>
            </div>

            <!-- Prepress Quality Guarantee Box -->
            <div class="prepress-guarantee-card" style="margin-top:1.25rem; background:#181818; padding:1.25rem 1.4rem; border-radius:10px; border:1px solid #40351E; border-left:3px solid #D4AF37; box-shadow:0 4px 14px rgba(0,0,0,0.45);">
              <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.4rem;">
                <span style="color:#D4AF37; font-size:1.1rem; line-height:1;">🛡️</span>
                <h5 style="color:#E8C45A; margin:0; font-size:0.95rem; font-weight:700;">Prepress Quality Guarantee</h5>
              </div>
              <p style="font-size:0.835rem; color:#B8B1A3; margin:0; line-height:1.6;">
                All uploads are inspected by Delhi studio technicians before printing. We will flag low-resolution artwork, transparent bleed issues, or font rasterization problems.
              </p>
            </div>
          </div>

          <!-- Right: Print Specs & Customer Details -->
          <div class="upload-right-col">
            <div class="form-grid">
              <!-- Service Selection -->
              <div class="form-group">
                <label class="form-label" for="quoteService">
                  <span>Printing Service Required <span class="required" aria-hidden="true">*</span></span>
                </label>
                <select name="service_name" id="quoteService" class="form-control" required>
                  <option value="">Choose a Service</option>
                  <?php foreach ($services as $svc): ?>
                    <option value="<?= e($svc['title']) ?>" <?= ($selectedServiceId === (int)$svc['id']) ? 'selected' : '' ?>>
                      <?= e($svc['title']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Quantity -->
              <div class="form-group">
                <label class="form-label" for="quoteQty">
                  <span>Estimated Quantity / Sheets <span class="required" aria-hidden="true">*</span></span>
                </label>
                <input type="number" name="quantity" id="quoteQty" class="form-control" value="1" min="1" required>
              </div>

              <!-- Dimensions -->
              <div class="form-group">
                <label class="form-label" for="quoteWidth">Width (Inches)</label>
                <input type="number" step="0.1" name="width_inches" id="quoteWidth" class="form-control" placeholder="e.g. 22">
              </div>

              <div class="form-group">
                <label class="form-label" for="quoteHeight">Height / Length (Inches)</label>
                <input type="number" step="0.1" name="height_inches" id="quoteHeight" class="form-control" placeholder="e.g. 36">
              </div>

              <!-- Material / Substrate Options -->
              <div class="form-group full-width">
                <label class="form-label" for="quoteMaterial">Substrate / Garment / Finish Details</label>
                <input type="text" name="material_options" id="quoteMaterial" class="form-control" placeholder="e.g. 100% Black Combed Cotton, Stainless Tumbler, Acrylic Sheet, Matte Gang Film">
              </div>

              <!-- Contact Details -->
              <div class="form-group">
                <label class="form-label" for="quoteName">
                  <span>Contact Name <span class="required" aria-hidden="true">*</span></span>
                </label>
                <input type="text" name="customer_name" id="quoteName" class="form-control" value="<?= e($currentUser['name'] ?? '') ?>" placeholder="e.g. Priyanshu Mehta" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="quoteEmail">
                  <span>Email Address <span class="required" aria-hidden="true">*</span></span>
                </label>
                <input type="email" name="customer_email" id="quoteEmail" class="form-control" value="<?= e($currentUser['email'] ?? '') ?>" placeholder="client@example.com" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="quotePhone">
                  <span>Phone / WhatsApp Number <span class="required" aria-hidden="true">*</span></span>
                </label>
                <input type="tel" name="customer_phone" id="quotePhone" class="form-control" value="<?= e($currentUser['phone'] ?? '') ?>" placeholder="+91 98765 43210" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="quoteCompany">Company / Brand Name</label>
                <input type="text" name="company_name" id="quoteCompany" class="form-control" value="<?= e($currentUser['company_name'] ?? '') ?>" placeholder="e.g. Apex Apparel Studio">
              </div>

              <div class="form-group full-width">
                <label class="form-label" for="quoteNotes">Special Instructions or Custom Requirements</label>
                <textarea name="customer_notes" id="quoteNotes" class="form-control" placeholder="Mention turnaround deadline, specific PANTONE color codes, peeling preferences (cold vs warm peel), or packaging requests..."></textarea>
              </div>
            </div>

            <!-- Submit Action Bar -->
            <div class="quote-submit-bar" style="margin-top:2.25rem; padding-top:1.5rem; border-top:1px solid #40351E; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.25rem;">
              <div style="color:#B8B1A3; font-size:0.85rem; display:flex; align-items:center; gap:0.5rem;">
                <span style="color:#10B981; font-weight:800; font-size:1.05rem;">✓</span>
                <span>24-hr Quote Turnaround &bull; No upfront card required &bull; Pan-India Dispatch</span>
              </div>
              <button type="submit" class="btn btn-primary btn-lg" style="padding:0.85rem 2rem; font-size:1rem; font-weight:700;">
                <span>Submit Print Enquiry</span>
                <svg style="width:16px;height:16px;fill:currentColor;margin-left:0.5rem;" viewBox="0 0 20 20">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
