<?php
/**
 * OrderPrintex - UV DTF Printing Service Page
 */

$pageTitle = "UV DTF Printing in Delhi, India — Cold Decals for Hard Surfaces | OrderPrintex";
$pageDesc  = "OrderPrintex is a leading manufacturer of UV DTF printing in Delhi, India. Pre-cured, waterproof, scratch-resistant 3D gloss stickers for tumblers, glass, mugs, metal, and packaging without heat press.";

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'uv-dtf-printing'");
?>

<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem;">
  <div class="container text-center reveal slide-up">
    <div class="section-label" style="justify-content:center;">Hard-Surface Decal Innovation</div>
    <h1 style="font-size:3.2rem; margin-bottom:1rem;">Industrial UV DTF Printing in Delhi</h1>
    <p class="section-subtitle" style="color:rgba(255,255,255,0.75); margin:0 auto;">
      Instant transfer stickers with 3D embossed tactile varnish and industrial adhesion. Bonds permanently to glass, acrylic, stainless steel, wood, and plastic — zero heat press required.
    </p>
  </div>
</section>

<!-- Overview -->
<section class="section section-white">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:3.5rem; align-items:center;">
      <div class="reveal slide-right">
        <div class="section-label">Surface Printing Without Limits</div>
        <h2 style="margin-bottom:1.25rem;">Transform Any Rigid or Curved Surface Instantly</h2>
        <p>
          At <strong>Printex</strong>, we specialize in delivering high-quality UV DTF printing services in Delhi, helping brands, businesses, and creators brand physical objects that cannot fit under traditional heat presses.
        </p>
        <p>
          Our state-of-the-art UV DTF machines deposit three synchronized layers in a single pass: an opaque white base, saturated CMYK color pigments, and a raised clear gloss UV varnish, all cured instantaneously by high-output LED UV lamps onto specialized AB adhesive film.
        </p>
        <p>
          Application is effortless: peel the carrier backing, position onto the tumbler, mug, laptop, or glass bottle, squeegee or rub firmly, and lift away the top clear film. What remains is a crystal-clear, 3D raised graphic with permanent bond strength.
        </p>

        <div style="margin-top:2rem; display:flex; gap:1rem;">
          <a href="#upload-uv" class="btn btn-primary">Upload Design for UV DTF Quote</a>
          <a href="<?= url('gallery.php') ?>" class="btn btn-outline">View UV Decal Gallery</a>
        </div>
      </div>

      <div class="reveal slide-left">
        <div style="border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-lg); border:1px solid var(--border-gold);">
          <img src="<?= url('assets/images/uv_dtf_drinkware.jpg') ?>" alt="UV DTF Printing on Curved Drinkware" style="width:100%; height:440px; object-fit:cover;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Surface Compatibility Grid -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Applications</div>
      <h2>Engineered for High-Tack Adhesion on Diverse Substrates</h2>
      <p class="section-subtitle">
        100% waterproof, scratch-resistant, and chemically stable for commercial drinkware, tech gear, and retail packaging.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1.5rem;">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Drinkware &amp; Bottles</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Stainless steel insulated tumblers, glass mason jars, ceramic mugs, sports bottles.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Electronics &amp; Gadgets</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Laptop lids, smartphone cases, wireless power banks, tablets, audio gear.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Acrylic &amp; Signage</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Clear acrylic awards, corporate door signs, POS displays, trophy plates.</p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--navy); margin-bottom:0.35rem;">Retail Packaging</h4>
        <p style="font-size:0.85rem; color:var(--slate);">Rigid gift boxes, cosmetic glass jars, metal tins, candle holders, wood boards.</p>
      </div>
    </div>
  </div>
</section>

<!-- Direct Upload Card -->
<section class="section section-white" id="upload-uv">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Order UV DTF Transfers</div>
      <h2>Get an Instant UV DTF Quotation</h2>
      <p class="section-subtitle">
        Upload your transparent artwork or logo sheet for quick turnaround production.
      </p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="service_name" value="UV DTF Printing">

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
              <div class="dropzone-title">Upload UV DTF artwork (transparent PNG or vector)</div>
              <div class="dropzone-hint">Minimum 300 DPI, outlined fonts, 25 MB max</div>
              <button type="button" class="btn btn-sm btn-outline-gold">Browse Files</button>
            </div>

            <div class="file-preview-area"></div>

            <div style="background:var(--ivory); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-light);">
              <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.85rem; font-weight:700; color:var(--navy);">Calculated Decal Area:</span>
                <strong class="sq-inches-display text-navy" style="font-size:1.15rem; color:var(--blue);">—</strong>
              </div>
              <small style="color:var(--slate-light); display:block; margin-top:4px; font-size:0.78rem;">
                UV DTF decals are supplied pre-masked with carrier tape for peel, stick, and rub application.
              </small>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Target Substrate / Material <span class="required">*</span></label>
                <select name="material_options" class="form-control">
                  <option value="Glass / Bottles">Glassware / Bottles</option>
                  <option value="Stainless Steel / Tumblers">Stainless Steel Tumblers / Mugs</option>
                  <option value="Acrylic / Plastic">Clear Acrylic / Plastic Packaging</option>
                  <option value="Wood / Metal">Finished Wood / Metal Plates</option>
                  <option value="General Hard Surface" selected>General Hard Surface</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Quantity of Decals <span class="required">*</span></label>
                <input type="number" name="quantity" class="form-control" value="10" min="1" required>
              </div>

              <div class="form-group">
                <label class="form-label">Decal Width (Inches)</label>
                <input type="number" step="0.1" name="width_inches" class="form-control" placeholder="e.g. 3.5">
              </div>

              <div class="form-group">
                <label class="form-label">Decal Height (Inches)</label>
                <input type="number" step="0.1" name="height_inches" class="form-control" placeholder="e.g. 3.5">
              </div>

              <div class="form-group">
                <label class="form-label">Your Name <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control" placeholder="Your Name" required>
              </div>

              <div class="form-group">
                <label class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" class="form-control" placeholder="Your Email" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label">Phone / WhatsApp <span class="required">*</span></label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="+91 93540 28199" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label">Special Finish or Spot Varnish Notes</label>
                <textarea name="customer_notes" class="form-control" placeholder="Specify if 3D embossed gloss varnish is needed for the entire design or specific logo areas..."></textarea>
              </div>
            </div>

            <div style="margin-top:2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ Waterproof &bull; Scratch-Resistant &bull; No Heat Press Needed
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Request UV DTF Quotation</span> &rarr;
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
