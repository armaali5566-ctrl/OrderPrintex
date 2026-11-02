<?php
/**
 * OrderPrintex - Dedicated Get a Quote Page
 * Full-Width Studio Workbench Architecture
 */

$pageTitle = "Get an Itemized Printing Quotation | PRINTEX Studio";
$pageDesc  = "Request an itemized quotation for DTF transfers, UV DTF stickers, custom t-shirt production, or corporate promotional merchandise. Fast prepress review and transparent pricing.";

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

<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem;">
  <div class="container text-center reveal slide-up">
    <div class="section-label" style="justify-content:center;">Transparent B2B Pricing</div>
    <h1 style="font-size:clamp(2.4rem, 4vw, 3.6rem); margin-bottom:1rem;">Request an Itemized Quotation</h1>
    <p class="section-subtitle" style="color:rgba(255,255,255,0.78); margin:0 auto;">
      Submit your printing specifications and artwork file. Our prepress team evaluates printability, resolution, and gang optimization to provide a prompt, binding commercial quotation within 24 hours.
    </p>
  </div>
</section>

<section class="section section-white">
  <div class="container">
    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="upload-studio-grid">
          
          <!-- Left: Drag & Drop Dropzone + File Preview -->
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
              <div class="dropzone-title">Drop your design file here, or <span class="text-gold" style="text-decoration:underline;">Browse</span></div>
              <div class="dropzone-hint">Accepted: PNG (transparent), PDF, AI, PSD, SVG (Max 25 MB). 300 DPI recommended for maximum print clarity.</div>
              <button type="button" class="btn btn-sm btn-outline-gold">Select Artwork File</button>
            </div>

            <div class="file-preview-area"></div>

            <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-light);">
              <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.85rem; font-weight:700; color:var(--navy);">Calculated Print Area:</span>
                <strong class="sq-inches-display text-navy" style="font-size:1.15rem; color:var(--blue);">—</strong>
              </div>
              <small style="color:var(--slate-light); display:block; margin-top:4px; font-size:0.78rem;">
                Based on specified width and height. Gang sheets are optimized for 24-inch continuous roll efficiency.
              </small>
            </div>

            <div style="margin-top:1.5rem; background:#FAF8F4; padding:1.25rem; border-radius:var(--radius-sm); border-left:3px solid var(--gold);">
              <h5 style="color:var(--navy); margin-bottom:0.35rem; font-size:0.95rem;">Prepress Quality Guarantee</h5>
              <p style="font-size:0.825rem; color:var(--slate); margin:0;">
                All uploads are inspected by Delhi studio technicians before printing. We will flag low-resolution artwork, transparent bleed issues, or font rasterization problems.
              </p>
            </div>
          </div>

          <!-- Right: Print Specs & Customer Details -->
          <div>
            <div class="form-grid">
              <!-- Service Selection -->
              <div class="form-group">
                <label class="form-label" for="quoteService">
                  Printing Service Required <span class="required">*</span>
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
                  Estimated Quantity / Sheets <span class="required">*</span>
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
                <label class="form-label" for="quoteName">Contact Name <span class="required">*</span></label>
                <input type="text" name="customer_name" id="quoteName" class="form-control" value="<?= e($currentUser['name'] ?? '') ?>" placeholder="e.g. Priyanshu Mehta" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="quoteEmail">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" id="quoteEmail" class="form-control" value="<?= e($currentUser['email'] ?? '') ?>" placeholder="client@example.com" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="quotePhone">Phone / WhatsApp Number <span class="required">*</span></label>
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

            <div style="margin-top:2.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.25rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ 24-hr Quote Turnaround &bull; No upfront card required &bull; Pan-India Dispatch
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Submit Print Enquiry</span>
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
