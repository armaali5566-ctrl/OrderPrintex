<?php
/**
 * OrderPrintex - Corporate Printing Service Page
 */

$pageTitle = "Corporate Printing & Promotional Merchandise in Delhi, India | OrderPrintex";
$pageDesc  = "OrderPrintex provides turnkey corporate printing: custom branded mugs, onboarding gift kits, corporate polo shirts, event giveaways, and custom packaging across India.";

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'corporate-printing'");
?>

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>B2B Promotional Solutions</span>
      </div>
      <h1 class="page-intro-title">Corporate Printing &amp; Custom Merchandise</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Elevate your company's physical brand presence. We design and manufacture executive welcome kits, custom drinkware, premium stationery, and corporate event giveaways.
      </p>
    </div>
  </div>
</section>

<!-- Overview -->
<section class="section section-white">
  <div class="container">
    <div class="service-overview-grid">
      <div class="service-overview-content reveal slide-right">
        <div class="section-label">End-to-End B2B Production</div>
        <h2>Unified Brand Identity Across Every Corporate Touchpoint</h2>
        <p>
          Corporate gifting and company merchandise represent your brand's commitment to quality. At <strong>OrderPrintex</strong>, we manage high-volume corporate production with strict Pantone color adherence and premium substrate selection.
        </p>
        <p>
          Whether welcoming new hires with custom hardbound diaries, steel tumblers, and branded hoodies, or distributing thousands of branded ceramic mugs for client appreciation, our Delhi team handles prepress proofing, laser etching, UV DTF decal bonding, and individual gift packaging.
        </p>
        <p>
          We provide GST invoicing, tiered wholesale volume pricing, and reliable door-to-door delivery to corporate offices across India.
        </p>

        <div class="service-cta-group">
          <a href="#upload-corporate" class="btn service-btn-primary">Request Corporate Proposal</a>
          <a href="<?= url('contact.php') ?>" class="btn service-btn-secondary">Schedule Consultation</a>
        </div>
      </div>

      <div class="service-overview-media reveal slide-left">
        <div class="service-media-frame">
          <img src="<?= url('assets/images/corporate_swag_kit.jpg') ?>" alt="Corporate Printing Production Run" loading="lazy">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Popular Corporate Items -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Catalog Offerings</div>
      <h2>Core Corporate Print Categories</h2>
      <p class="section-subtitle">
        Fully customizable with your company logo, employee names, and brand palette.
      </p>
    </div>

    <div class="service-specs-grid">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Custom Ceramic Mugs</h4>
        <p style="font-size:0.85rem; color:var(--slate);">11oz and 15oz Grade AAA white/black ceramic mugs with vibrant full-wrap dishwasher-safe prints.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Onboarding Gift Kits</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Curated welcome boxes featuring branded notebook, metallic stylus pen, vacuum bottle, and polo.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Uniforms &amp; Polos</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Corporate polo shirts, team hoodies, staff apron prints, and event volunteer apparel.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Event &amp; Summit Giveaways</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Custom printed tote bags, branded ID lanyards, metal badges, and promotional sticker packs.</p>
      </div>
    </div>
  </div>
</section>

<!-- Direct Upload / Corporate Quote Form -->
<section class="section section-white" id="upload-corporate">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">B2B Prepress Studio</div>
      <h2>Request an Itemized Corporate Quotation</h2>
      <p class="section-subtitle">
        Upload your vector logo or brand guidelines and specify your required quantities for bespoke corporate merchandise.
      </p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="service_name" value="Corporate Printing">

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
              <div class="dropzone-title">Upload Company Logo or Brand Kit</div>
              <div class="dropzone-hint">AI, EPS, PDF, PSD, SVG or PNG (Max 50MB)</div>
              <button type="button" class="btn btn-sm btn-outline-gold">Select Brand Files</button>
            </div>

            <div class="file-preview-area"></div>

            <div style="background:var(--bg-surface-elevated); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-color);">
              <div style="font-size:0.85rem; font-weight:700; color:var(--text-primary); margin-bottom:0.35rem;">B2B Project Standards</div>
              <p style="font-size:0.8rem; color:var(--text-secondary); margin:0;">
                All corporate production runs include digital 3D mockups for stakeholder review, official GST tax invoicing, and optional custom luxury packaging.
              </p>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Product Category <span class="required">*</span></label>
                <select name="material_options" class="form-control">
                  <option value="Ceramic Mugs">Custom Printed Ceramic Mugs</option>
                  <option value="Executive Welcome Kit">Curated Employee Onboarding Kit</option>
                  <option value="Corporate Apparel">Company Polos &amp; Uniforms</option>
                  <option value="Drinkware / Bottles">Vacuum Insulated Flasks / Sippers</option>
                  <option value="Multiple Corporate Items">Combination / Multiple Items</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Estimated Quantity <span class="required">*</span></label>
                <input type="number" name="quantity" class="form-control" value="50" min="1" required>
              </div>

              <div class="form-group">
                <label class="form-label">Company / Organization Name</label>
                <input type="text" name="company_name" class="form-control" placeholder="e.g. Acme Tech Solutions">
              </div>

              <div class="form-group">
                <label class="form-label">Contact Person Name <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control" placeholder="e.g. Pooja Verma" required>
              </div>

              <div class="form-group">
                <label class="form-label">Work Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" class="form-control" placeholder="e.g. pooja@company.com" required>
              </div>

              <div class="form-group">
                <label class="form-label">Phone / WhatsApp <span class="required">*</span></label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="+91 93540 28199" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label">Project Scope &amp; Target Delivery Date</label>
                <textarea name="customer_notes" class="form-control" placeholder="Specify items needed, custom gift box requirements, shipping address/city, and event deadlines..."></textarea>
              </div>
            </div>

            <div style="margin-top:2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ Official GST Invoicing &bull; PAN-India Logistics &bull; Dedicated B2B Prepress Manager
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Submit Corporate Proposal</span> &rarr;
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
