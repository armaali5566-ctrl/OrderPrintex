<?php
/**
 * OrderPrintex - DTF Printing Service Page
 */

$pageTitle = "DTF Printing in India — Direct-to-Film Transfers for Garments | OrderPrintex";
$pageDesc  = "OrderPrintex is India's bulk manufacturer of DTF garment transfers. Ultra soft-hand feel, 50+ wash cycles, 22-inch gang sheets, and fast nationwide dispatch.";

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'dtf-printing'");
?>

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Apparel &amp; Textile Innovation</span>
      </div>
      <h1 class="page-intro-title">Commercial DTF Printing (Direct-to-Film)</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        High-density white underbase, vibrant textile pigments, and stretchable TPU adhesive for flawless pressing onto cotton, poly-blends, nylon, and performance wear.
      </p>
    </div>
  </div>
</section>

<!-- Specifications & Overview -->
<section class="section section-white">
  <div class="container">
    <div class="service-overview-grid">
      <div class="service-overview-content reveal slide-right">
        <div class="section-label">Garment Transfer Engineering</div>
        <h2>Why OrderPrintex DTF Outperforms Traditional Screen &amp; Vinyl</h2>
        <p>
          Direct-to-Film (DTF) transfer printing has revolutionized garment customization across India. Unlike screen printing which requires expensive mesh setup and color separation fees per shade, DTF prints photorealistic artwork, gradients, shadows, and fine line-art directly onto release film in full vibrant color.
        </p>
        <p>
          Every sheet produced at our Delhi facility is backed with uniform, high-grade thermoplastic polyurethane (TPU) powder that bonds mechanically to the fibers of cotton, polyester, spandex, leather, and canvas.
        </p>
        <p>
          No weeding, no vinyl cutting, and zero mess. Simply cut the transfer, heat-press at 160&deg;C for 15 seconds, cold peel, and post-press for a factory-finish feel.
        </p>

        <div class="service-cta-group">
          <a href="#upload-dtf" class="btn service-btn-primary">Upload Design for DTF Quote</a>
          <a href="<?= url('products.php') ?>" class="btn service-btn-secondary">View Gang Sheet Options</a>
        </div>
      </div>

      <div class="service-overview-media reveal slide-left">
        <div class="service-media-frame">
          <img src="<?= url('assets/images/dtf_fabric_print.jpg') ?>" alt="DTF Transfer Print on Dark Fabric" loading="lazy">
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

    <div class="service-specs-grid">
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

            <!-- Interactive Live Calculated Gang Sheet Result Box -->
            <div class="dtf-calc-result-box" style="background:#111111; border:1.5px solid #40351E; border-radius:10px; padding:1.25rem; margin-top:1.25rem;">
              <div style="font-size:0.8rem; font-weight:800; color:#D4AF37; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem;">
                Estimated Print Summary
              </div>

              <div class="dtf-calc-metrics">
                <div class="dtf-metric-item">
                  <span class="dtf-metric-label">Total Area</span>
                  <span class="dtf-metric-value" id="calcAreaDisplay">264 sq in (1.83 sq ft)</span>
                </div>
                <div class="dtf-metric-item">
                  <span class="dtf-metric-label">Unit Rate</span>
                  <span class="dtf-metric-value" id="calcUnitRateDisplay">₹249.00</span>
                </div>
                <div class="dtf-metric-item">
                  <span class="dtf-metric-label">Wholesale Tier</span>
                  <span class="dtf-metric-value" id="calcTierDisplay" style="color:#4ade80; font-size:1rem;">Standard</span>
                </div>
              </div>

              <div style="display:flex; justify-content:space-between; align-items:baseline; padding-top:0.75rem; border-top:1px dashed #40351E;">
                <div>
                  <span style="font-weight:700; color:#F5F1E8; font-size:1.05rem;">Estimated Total:</span>
                  <div style="font-size:0.75rem; color:#B8B1A3;">(Estimated, incl. 18% GST)</div>
                </div>
                <div style="text-align:right;">
                  <span class="dtf-metric-value highlight-gold" id="calcGrandTotalDisplay" style="font-size:1.6rem;">₹293.82</span>
                </div>
              </div>

              <div style="background:rgba(212,175,55,0.08); border:1px solid #40351E; border-radius:6px; padding:0.6rem 0.85rem; margin-top:0.85rem; font-size:0.78rem; color:#FFD76A;">
                ℹ <strong>Minimum Order:</strong> 1 Foot (12" linear length). All amounts are real estimates based on calibrated roll volume tiers.
              </div>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Gang Sheet Width</label>
                <select name="width_inches" id="calcWidthSelect" class="form-control" onchange="runDtfCalculation()">
                  <option value="22" selected>22" Standard Industrial Roll</option>
                  <option value="24">24" Ultra-Wide Commercial Roll</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Length / Height (Inches) <span class="required">*</span></label>
                <input type="number" step="1" name="height_inches" id="calcLengthInput" class="form-control" value="12" min="12" placeholder="Min. 12 inches" oninput="runDtfCalculation()" required>
                <div style="display:flex; gap:0.4rem; margin-top:0.4rem;">
                  <button type="button" class="btn btn-xs btn-outline" style="border-color:#40351E; color:#D4AF37; padding:2px 6px;" onclick="setDtfLength(12)">1 Ft (12")</button>
                  <button type="button" class="btn btn-xs btn-outline" style="border-color:#40351E; color:#D4AF37; padding:2px 6px;" onclick="setDtfLength(24)">2 Ft (24")</button>
                  <button type="button" class="btn btn-xs btn-outline" style="border-color:#40351E; color:#D4AF37; padding:2px 6px;" onclick="setDtfLength(39.37)">1m (39.4")</button>
                  <button type="button" class="btn btn-xs btn-outline" style="border-color:#40351E; color:#D4AF37; padding:2px 6px;" onclick="setDtfLength(60)">5 Ft (60")</button>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Material / Film Grade</label>
                <select name="film_material" id="calcMaterialSelect" class="form-control" onchange="runDtfCalculation()">
                  <option value="cold_peel" data-extra="0" selected>Double-Matte Cold Peel PET (Standard)</option>
                  <option value="hot_peel" data-extra="20">Hot Peel Instant Release Film (+₹20/ft)</option>
                  <option value="reflective" data-extra="45">Reflective Silver DTF (+₹45/ft)</option>
                  <option value="neon" data-extra="35">Fluorescent Neon CMYK+W (+₹35/ft)</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Quantity (Rolls/Sheets) <span class="required">*</span></label>
                <input type="number" name="quantity" id="calcQtyInput" class="form-control" value="1" min="1" oninput="runDtfCalculation()" required>
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

<script>
function setDtfLength(inches) {
  const input = document.getElementById('calcLengthInput');
  if (input) {
    input.value = inches;
    runDtfCalculation();
  }
}

function runDtfCalculation() {
  const width = parseFloat(document.getElementById('calcWidthSelect')?.value) || 22;
  const length = Math.max(12, parseFloat(document.getElementById('calcLengthInput')?.value) || 12);
  const qty = Math.max(1, parseInt(document.getElementById('calcQtyInput')?.value, 10) || 1);
  const matSelect = document.getElementById('calcMaterialSelect');
  const matExtraPerFt = parseFloat(matSelect?.options[matSelect.selectedIndex]?.getAttribute('data-extra')) || 0;

  // Real Area
  const totalSqInches = width * length;
  const totalSqFt = totalSqInches / 144;

  // Linear Foot Count (min 1 foot)
  const linearFeet = Math.max(1, length / 12);

  // Base Pricing Rules directly from DTF database tiers:
  // 1 ft = ₹249, 2 ft = ₹450, 1m (~3.28 ft) = ₹599, 5 ft = ₹1099
  let ratePerFt = 249;
  if (linearFeet >= 5) {
    ratePerFt = 1099 / 5; // ~219.8 / ft
  } else if (linearFeet >= 3.28) {
    ratePerFt = 599 / 3.28; // ~182.6 / ft
  } else if (linearFeet >= 2) {
    ratePerFt = 450 / 2; // 225 / ft
  } else {
    ratePerFt = 249;
  }

  // Adjust for 24" roll (+9% area)
  if (width > 22) {
    ratePerFt = ratePerFt * (width / 22);
  }

  // Add material extra
  ratePerFt += matExtraPerFt;

  let unitBase = ratePerFt * linearFeet;

  // Wholesale Quantity Tiers (from product_pricing_tiers):
  // 1-4: 0%, 5-9: 8%, 10-24: 15%, 25+: 25%
  let discountPct = 0;
  let tierText = 'Standard (1-4)';
  if (qty >= 25) {
    discountPct = 25;
    tierText = '25% Bulk Discount';
  } else if (qty >= 10) {
    discountPct = 15;
    tierText = '15% Bulk Discount';
  } else if (qty >= 5) {
    discountPct = 8;
    tierText = '8% Wholesale Discount';
  }

  const finalUnitPrice = unitBase * (1 - (discountPct / 100));
  const subtotal = finalUnitPrice * qty;
  const gst = subtotal * 0.18;
  const grandTotal = subtotal + gst;

  // Update DOM
  const areaDisp = document.getElementById('calcAreaDisplay');
  if (areaDisp) areaDisp.textContent = `${Math.round(totalSqInches)} sq in (${totalSqFt.toFixed(2)} sq ft)`;

  const unitDisp = document.getElementById('calcUnitRateDisplay');
  if (unitDisp) unitDisp.textContent = `₹${finalUnitPrice.toFixed(2)}`;

  const tierDisp = document.getElementById('calcTierDisplay');
  if (tierDisp) {
    tierDisp.textContent = tierText;
    tierDisp.style.color = discountPct > 0 ? '#4ade80' : '#FFD76A';
  }

  const grandDisp = document.getElementById('calcGrandTotalDisplay');
  if (grandDisp) grandDisp.textContent = `₹${grandTotal.toFixed(2)}`;
}

document.addEventListener('DOMContentLoaded', runDtfCalculation);
</script>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
