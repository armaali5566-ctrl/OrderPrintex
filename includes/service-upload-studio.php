<?php
/**
 * Reusable upload-and-quote studio for service landing pages.
 * Expects $serviceUploadConfig from the including template.
 */
$serviceUploadConfig = $serviceUploadConfig ?? [];
$uploadServiceName = $serviceUploadConfig['service_name'] ?? 'OrderPrintex Service';
$uploadTitle = $serviceUploadConfig['title'] ?? 'Request a Custom Quote';
$uploadDescription = $serviceUploadConfig['description'] ?? 'Upload your artwork and share your production requirements.';
$uploadFileTitle = $serviceUploadConfig['file_title'] ?? 'Upload artwork or reference files';
$uploadFileHint = $serviceUploadConfig['file_hint'] ?? 'PNG, JPG, PDF, AI, PSD or SVG (max 25 MB)';
$uploadMaterialLabel = $serviceUploadConfig['material_label'] ?? 'Material / Product Type';
$uploadMaterialOptions = $serviceUploadConfig['material_options'] ?? [];
$uploadNotesLabel = $serviceUploadConfig['notes_label'] ?? 'Artwork & Production Notes';
$uploadNotesPlaceholder = $serviceUploadConfig['notes_placeholder'] ?? 'Share sizes, colours, finishing and delivery requirements…';
$uploadAssuranceTitle = $serviceUploadConfig['assurance_title'] ?? 'Production Assurance';
$uploadAssuranceText = $serviceUploadConfig['assurance_text'] ?? 'Every order receives an artwork and production review before confirmation.';
$uploadCta = $serviceUploadConfig['cta'] ?? 'Request a Quotation';
?>
<section class="section section-white service-upload-section" id="service-upload" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Direct Upload Studio</div>
      <h2><?= e($uploadTitle) ?></h2>
      <p class="section-subtitle"><?= e($uploadDescription) ?></p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component service-upload-studio">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="service_name" value="<?= e($uploadServiceName) ?>">

        <div class="upload-studio-grid">
          <div>
            <div class="dropzone-container">
              <input type="file" name="artwork_file" class="file-input" accept=".png,.jpg,.jpeg,.pdf,.ai,.psd,.svg" style="display:none;">
              <div class="dropzone-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
              </div>
              <div class="dropzone-title"><?= e($uploadFileTitle) ?></div>
              <div class="dropzone-hint"><?= e($uploadFileHint) ?></div>
              <button type="button" class="btn btn-sm btn-outline-gold">Select File</button>
            </div>
            <div class="file-preview-area"></div>

            <aside class="service-upload-assurance">
              <span class="service-upload-assurance__eyebrow">Prepress check included</span>
              <h3><?= e($uploadAssuranceTitle) ?></h3>
              <p><?= e($uploadAssuranceText) ?></p>
            </aside>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label"><?= e($uploadMaterialLabel) ?> <span class="required">*</span></label>
                <select name="material_options" class="form-control" required>
                  <?php foreach ($uploadMaterialOptions as $option): ?>
                    <option value="<?= e($option) ?>"><?= e($option) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Quantity <span class="required">*</span></label>
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
                <label class="form-label"><?= e($uploadNotesLabel) ?></label>
                <textarea name="customer_notes" class="form-control" placeholder="<?= e($uploadNotesPlaceholder) ?>"></textarea>
              </div>
            </div>

            <div class="service-upload-submit-row">
              <small>✓ Artwork review &bull; Digital proof support &bull; Pan-India dispatch</small>
              <button type="submit" class="btn btn-primary btn-lg"><span><?= e($uploadCta) ?></span> &rarr;</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
