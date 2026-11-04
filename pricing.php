<?php
/**
 * OrderPrintex - Commercial Wholesale & Volume Pricing Workbench
 * Direct factory rates for DTF Gang Sheets, UV DTF Decals, Custom Tees & Corporate Swag.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/catalog_helper.php';

$pageTitle = "Wholesale Pricing & Volume Discount Tiers | OrderPrintex Delhi";
$pageDesc  = "Transparent manufacturer rates for 22-inch DTF gang rolls, UV DTF decals, custom printed apparel, and packaging. Pan-India express dispatch with zero plate fees.";
$canonical = pricing_url();

require_once __DIR__ . '/includes/header.php';

$products = get_catalog_products();
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Factory Direct Wholesale Rates</span>
      </div>
      <h1 class="page-intro-title">Commercial Printing &amp; Volume Pricing</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Direct Delhi NCR manufacturer rates for apparel brands, promotional agencies, and contract printers. No setup fees, zero hidden plate charges, and full 300 DPI prepress audit included.
      </p>
    </div>
  </div>
</section>

<!-- Pricing Matrices Grid -->
<section class="section section-white" style="padding-top:3.5rem; padding-bottom:4rem;">
  <div class="container">
    
    <div class="pricing-cards-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:2rem; margin-bottom:3.5rem;">
      
      <!-- Tier Card 1: DTF Gang Rolls -->
      <div class="card" style="background:#181818; border:1px solid #40351E; border-radius:12px; padding:2rem; box-shadow:0 8px 30px rgba(0,0,0,0.7); display:flex; flex-direction:column; justify-content:space-between;">
        <div>
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <span class="badge" style="background:rgba(212,175,55,0.15); color:#FFD76A; border:1px solid rgba(212,175,55,0.3); font-weight:700;">Textile Printing</span>
            <span style="font-size:0.8rem; color:#10B981; font-weight:700;">● Same-Day Dispatch</span>
          </div>
          <h3 style="font-size:1.4rem; color:#F5F1E8; font-weight:800; margin-bottom:0.5rem;">22" DTF Gang Sheets</h3>
          <p style="font-size:0.88rem; color:#B8B1A3; margin-bottom:1.5rem;">
            Dual Epson i3200 printheads with dense white backing and TPU powder cure.
          </p>

          <div style="background:#111111; border-radius:8px; padding:1.25rem; margin-bottom:1.5rem; border:1px solid #40351E;">
            <div style="font-size:0.8rem; font-weight:700; color:#E8C45A; text-transform:uppercase; margin-bottom:0.5rem;">Volume Pricing Tiers:</div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; border-bottom:1px dashed #40351E; font-size:0.88rem; color:#F5F1E8;">
              <span>Sample / 1 – 5 Metres:</span>
              <strong style="color:#F5F1E8;">₹299 / Metre</strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; border-bottom:1px dashed #40351E; font-size:0.88rem; color:#F5F1E8;">
              <span>Wholesale / 6 – 20 Metres:</span>
              <strong style="color:#FFD76A;">₹249 / Metre</strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; font-size:0.88rem; color:#F5F1E8;">
              <span>Contract / 21+ Metres:</span>
              <strong style="color:#10B981;">₹199 / Metre</strong>
            </div>
          </div>

          <ul style="list-style:none; padding:0; margin:0 0 1.5rem; font-size:0.85rem; color:#B8B1A3; display:flex; flex-direction:column; gap:0.5rem;">
            <li>✓ 300 DPI Prepress Resolution Check</li>
            <li>✓ 50+ Commercial Wash Durability</li>
            <li>✓ Free Technical Gang Sheet Optimization</li>
          </ul>
        </div>

        <a href="<?= product_url('custom-dtf-gang-sheet') ?>" class="btn btn-primary" style="width:100%; text-align:center; justify-content:center;">
          Configure Gang Sheet &rarr;
        </a>
      </div>

      <!-- Tier Card 2: UV DTF Decal Rolls -->
      <div class="card" style="background:#181818; border:1px solid #40351E; border-radius:12px; padding:2rem; box-shadow:0 8px 30px rgba(0,0,0,0.7); display:flex; flex-direction:column; justify-content:space-between;">
        <div>
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <span class="badge" style="background:rgba(212,175,55,0.15); color:#FFD76A; border:1px solid rgba(212,175,55,0.3); font-weight:700;">Hard Surfaces</span>
            <span style="font-size:0.8rem; color:#E8C45A; font-weight:700;">★ 3D Tactile Gloss</span>
          </div>
          <h3 style="font-size:1.4rem; color:#F5F1E8; font-weight:800; margin-bottom:0.5rem;">UV DTF Decal Sheets</h3>
          <p style="font-size:0.88rem; color:#B8B1A3; margin-bottom:1.5rem;">
            No heat press needed. Peel, stick, and rub onto glass, tumblers, wood, and acrylic.
          </p>

          <div style="background:#111111; border-radius:8px; padding:1.25rem; margin-bottom:1.5rem; border:1px solid #40351E;">
            <div style="font-size:0.8rem; font-weight:700; color:#E8C45A; text-transform:uppercase; margin-bottom:0.5rem;">Volume Pricing Tiers:</div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; border-bottom:1px dashed #40351E; font-size:0.88rem; color:#F5F1E8;">
              <span>Sample / 1 – 3 Metres:</span>
              <strong style="color:#F5F1E8;">₹449 / Metre</strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; border-bottom:1px dashed #40351E; font-size:0.88rem; color:#F5F1E8;">
              <span>Studio / 4 – 15 Metres:</span>
              <strong style="color:#FFD76A;">₹389 / Metre</strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; font-size:0.88rem; color:#F5F1E8;">
              <span>Agency / 16+ Metres:</span>
              <strong style="color:#10B981;">₹329 / Metre</strong>
            </div>
          </div>

          <ul style="list-style:none; padding:0; margin:0 0 1.5rem; font-size:0.85rem; color:#B8B1A3; display:flex; flex-direction:column; gap:0.5rem;">
            <li>✓ Waterproof &amp; Dishwasher Safe</li>
            <li>✓ Spot Varnish High-Build Texture</li>
            <li>✓ Sticks Permanently to Any Rigid Medium</li>
          </ul>
        </div>

        <a href="<?= product_url('custom-uv-dtf-decal-sheet') ?>" class="btn btn-primary" style="width:100%; text-align:center; justify-content:center;">
          Configure UV Decals &rarr;
        </a>
      </div>

      <!-- Tier Card 3: Custom Apparel -->
      <div class="card" style="background:#181818; border:1px solid #40351E; border-radius:12px; padding:2rem; box-shadow:0 8px 30px rgba(0,0,0,0.7); display:flex; flex-direction:column; justify-content:space-between;">
        <div>
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <span class="badge" style="background:rgba(212,175,55,0.15); color:#FFD76A; border:1px solid rgba(212,175,55,0.3); font-weight:700;">Streetwear &amp; Polos</span>
            <span style="font-size:0.8rem; color:#10B981; font-weight:700;">240 GSM Combed</span>
          </div>
          <h3 style="font-size:1.4rem; color:#F5F1E8; font-weight:800; margin-bottom:0.5rem;">Finished Custom Apparel</h3>
          <p style="font-size:0.88rem; color:#B8B1A3; margin-bottom:1.5rem;">
            Bio-washed combed cotton tees, oversized drops, and corporate employee uniforms.
          </p>

          <div style="background:#111111; border-radius:8px; padding:1.25rem; margin-bottom:1.5rem; border:1px solid #40351E;">
            <div style="font-size:0.8rem; font-weight:700; color:#E8C45A; text-transform:uppercase; margin-bottom:0.5rem;">Volume Pricing Tiers:</div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; border-bottom:1px dashed #40351E; font-size:0.88rem; color:#F5F1E8;">
              <span>10 – 25 Pieces:</span>
              <strong style="color:#F5F1E8;">₹549 / Tee</strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; border-bottom:1px dashed #40351E; font-size:0.88rem; color:#F5F1E8;">
              <span>26 – 100 Pieces:</span>
              <strong style="color:#FFD76A;">₹449 / Tee</strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:0.4rem 0; font-size:0.88rem; color:#F5F1E8;">
              <span>101+ Bulk Fleet:</span>
              <strong style="color:#10B981;">₹389 / Tee</strong>
            </div>
          </div>

          <ul style="list-style:none; padding:0; margin:0 0 1.5rem; font-size:0.85rem; color:var(--slate); display:flex; flex-direction:column; gap:0.5rem;">
            <li>✓ Custom Front, Back &amp; Sleeve Print</li>
            <li>✓ Soft-Hand Polyurethane Transfer Finish</li>
            <li>✓ Custom Woven Neck Label Support</li>
          </ul>
        </div>

        <a href="<?= product_url('streetwear-oversized-tee') ?>" class="btn btn-primary" style="width:100%; text-align:center; justify-content:center;">
          Explore Custom Tees &rarr;
        </a>
      </div>

    </div>

    <!-- Quick Wholesale Quote Banner -->
    <div style="background:linear-gradient(135deg, #181818 0%, #221c1c 100%); border:1.5px solid #40351E; border-radius:12px; padding:2.5rem; color:#F5F1E8; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1.5rem; box-shadow:0 8px 24px rgba(0,0,0,0.85);">
      <div style="max-width:560px;">
        <h3 style="color:#FFD76A; font-size:1.5rem; font-weight:800; margin-bottom:0.5rem;">Need a Custom Corporate or Bulk Factory Quote?</h3>
        <p style="margin:0; font-size:0.95rem; color:#B8B1A3; line-height:1.6;">
          Upload your print-ready vectors or talk to our prepress engineering desk in New Delhi for binding contract rates.
        </p>
      </div>
      <div style="display:flex; gap:1rem; flex-wrap:wrap;">
        <a href="<?= url('get-quote/') ?>" class="btn btn-gold btn-lg">
          Upload Artwork &rarr;
        </a>
        <a href="<?= url('contact/') ?>" class="btn btn-outline-white btn-lg">
          Contact Studio
        </a>
      </div>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

