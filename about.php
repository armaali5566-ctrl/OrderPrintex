<?php
/**
 * OrderPrintex - About Us Page
 * Features:
 *   1. Specialized Printing Technologies (The Printex Advantage)
 *   2. Why Choose OrderPrintex? (8 Feature Cards)
 *   3. Simple Four-Step Production Flow (Timeline)
 *   4. Tools and Technology (Industrial machinery & prepress software)
 *   5. Company Introduction (Commercial print background)
 *   6. Strong Call to Action (Upload, Quote, Contact)
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "About OrderPrintex — Specialized Printing Technologies & Commercial Production";
$pageDesc  = "Learn about OrderPrintex: our Pankha Road Delhi workshop, dual-head DTF printers, UV DTF decal technology, and four-step prepress workflow engineered for apparel brands and print businesses.";
$canonical = url('about/');

// Fetch tools and machinery from DB
$tools = [];
if (class_exists('Database') && Database::isConnected()) {
    try {
        $tools = db_fetch_all("SELECT * FROM equipment_tools ORDER BY display_order ASC");
    } catch (Exception $e) {
        $tools = [];
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- ============================================================== -->
<!-- SECTION 5: COMPANY INTRODUCTION HERO -->
<!-- ============================================================== -->
<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem;">
  <div class="container text-center reveal slide-up">
    <span class="section-badge" style="background:rgba(255,255,255,0.1); color:var(--gold); border:1px solid rgba(185,154,97,0.3);">
      About Our Delhi Production Studio
    </span>
    <h1 style="font-size:clamp(2.4rem, 4vw, 3.6rem); margin-top:0.75rem; margin-bottom:1.25rem; color:#FFFFFF; line-height:1.2;">
      Commercial Printing Engineered for High-Yield Output
    </h1>
    <p class="section-subtitle" style="color:rgba(255,255,255,0.82); margin:0 auto; max-width:720px; font-size:1.1rem; line-height:1.7;">
      OrderPrintex is a specialized manufacturing facility and wholesale supplier based in New Delhi. We engineer commercial Direct-to-Film (DTF) apparel transfers, 3D UV DTF hard-surface decals, and ready-to-wear corporate merchandise for brands across India.
    </p>
  </div>
</section>

<!-- Company Overview & Story -->
<section class="section section-white">
  <div class="container">
    <div style="display:grid; grid-template-columns:1.05fr 0.95fr; gap:3.5rem; align-items:center;">
      <div class="reveal slide-right">
        <span class="section-label">Company Overview</span>
        <h2 style="font-size:clamp(1.8rem, 2.5vw, 2.3rem); color:var(--navy); margin-bottom:1.25rem; line-height:1.3;">
          A Dedicated Print Manufacturing Partner for Modern Brands
        </h2>
        <p style="color:var(--text-muted); line-height:1.8; margin-bottom:1.25rem;">
          Founded with a focus on engineering precision, <strong>OrderPrintex</strong> bridges the gap between raw digital artwork and physical retail-grade merchandise. Independent streetwear labels, promotional merchandise agencies, and commercial print shops rely on our Delhi workshop for calibrated color reproduction, rigorous prepress inspection, and dependable turnaround times.
        </p>
        <p style="color:var(--text-muted); line-height:1.8; margin-bottom:1.75rem;">
          By operating continuous roll-to-roll DTF printers and UV flatbed transfer systems, we eliminate traditional screen printing constraints — such as expensive color separation plates, messy chemicals, and restrictive minimum order quantities — allowing clients to print anywhere from single custom prototypes to thousands of commercial gang sheets daily.
        </p>

        <div style="display:flex; gap:1rem; flex-wrap:wrap;">
          <a href="<?= url('get-quote/') ?>" class="btn btn-primary">
            <span>Request Prepress Quote</span> &rarr;
          </a>
          <a href="<?= url('contact/') ?>" class="btn btn-outline">
            <span>Contact Delhi Studio</span>
          </a>
        </div>
      </div>

      <div class="reveal slide-left">
        <div style="position:relative; border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-lg); border:1px solid var(--border-gold); background:#F8F5F0;">
          <img src="<?= url('assets/images/branding_stationery.jpg') ?>" alt="OrderPrintex Delhi Printing Facility" style="width:100%; height:420px; object-fit:cover; display:block;">
          <div style="position:absolute; bottom:0; left:0; right:0; background:linear-gradient(to top, rgba(16,27,48,0.92), transparent); padding:2rem 1.75rem; color:#fff;">
            <div style="font-weight:700; font-size:1.15rem; color:var(--gold); margin-bottom:0.25rem;">
              Industrial DTF &amp; UV Decal Manufacturing
            </div>
            <div style="font-size:0.85rem; color:rgba(255,255,255,0.85); line-height:1.5;">
              Every print roll is calibrated with spectrophotometric ICC profiles for true-to-design Pantone accuracy.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 1: SPECIALIZED PRINTING TECHNOLOGIES -->
<!-- ============================================================== -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <span class="section-label" style="justify-content:center;">The Printex Advantage</span>
      <h2 style="font-size:clamp(1.9rem, 2.8vw, 2.5rem); color:var(--navy); margin-bottom:0.6rem;">Specialized Printing Technologies</h2>
      <h3 style="font-size:1.15rem; color:var(--royal); font-weight:700; margin-bottom:1rem;">Engineered for High-Yield Commercial Output</h3>
      <p class="section-subtitle" style="margin:0 auto; max-width:680px;">
        Why India's top apparel labels, print-on-demand brands, and corporate gift suppliers partner with OrderPrintex.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.75rem; margin-top:3rem;">
      <!-- Card 01 -->
      <div class="product-card reveal slide-up stagger-1" style="background:#FFFFFF; padding:2rem; border-radius:var(--radius-md); border:1px solid var(--border-light); box-shadow:var(--shadow-sm); display:flex; flex-direction:column;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
          <span style="font-size:2rem; font-weight:900; color:rgba(29,78,216,0.15); font-family:var(--font-heading); line-height:1;">01</span>
          <span style="width:40px; height:40px; border-radius:8px; background:rgba(29,78,216,0.08); color:var(--royal); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">🎨</span>
        </div>
        <h4 style="font-size:1.2rem; color:var(--navy); margin-bottom:0.75rem; font-weight:700;">Dual-Head Japanese Inks</h4>
        <p style="color:var(--text-muted); font-size:0.92rem; line-height:1.7; flex:1; margin:0;">
          Dual micro-piezo heads laying down calibrated high-opacity white ink underbases for photorealistic color saturation on any dark or light garment.
        </p>
      </div>

      <!-- Card 02 -->
      <div class="product-card reveal slide-up stagger-2" style="background:#FFFFFF; padding:2rem; border-radius:var(--radius-md); border:1px solid var(--border-light); box-shadow:var(--shadow-sm); display:flex; flex-direction:column;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
          <span style="font-size:2rem; font-weight:900; color:rgba(29,78,216,0.15); font-family:var(--font-heading); line-height:1;">02</span>
          <span style="width:40px; height:40px; border-radius:8px; background:rgba(185,154,97,0.12); color:var(--gold); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">✨</span>
        </div>
        <h4 style="font-size:1.2rem; color:var(--navy); margin-bottom:0.75rem; font-weight:700;">Gloss Varnish UV Decals</h4>
        <p style="color:var(--text-muted); font-size:0.92rem; line-height:1.7; flex:1; margin:0;">
          Proprietary UV varnish layer delivers scratchproof, waterproof 3D tactile decals for glass, tumblers, wood, and acrylics with instant pressure-bonding.
        </p>
      </div>

      <!-- Card 03 -->
      <div class="product-card reveal slide-up stagger-3" style="background:#FFFFFF; padding:2rem; border-radius:var(--radius-md); border:1px solid var(--border-light); box-shadow:var(--shadow-sm); display:flex; flex-direction:column;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
          <span style="font-size:2rem; font-weight:900; color:rgba(29,78,216,0.15); font-family:var(--font-heading); line-height:1;">03</span>
          <span style="width:40px; height:40px; border-radius:8px; background:rgba(16,185,129,0.08); color:#059669; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">🏭</span>
        </div>
        <h4 style="font-size:1.2rem; color:var(--navy); margin-bottom:0.75rem; font-weight:700;">Delhi Factory Manufacturer</h4>
        <p style="color:var(--text-muted); font-size:0.92rem; line-height:1.7; flex:1; margin:0;">
          Our Pankha Road workshop operates continuous roll-to-roll printers and automatic shaker ovens capable of processing 100+ meters daily.
        </p>
      </div>

      <!-- Card 04 -->
      <div class="product-card reveal slide-up stagger-4" style="background:#FFFFFF; padding:2rem; border-radius:var(--radius-md); border:1px solid var(--border-light); box-shadow:var(--shadow-sm); display:flex; flex-direction:column;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
          <span style="font-size:2rem; font-weight:900; color:rgba(29,78,216,0.15); font-family:var(--font-heading); line-height:1;">04</span>
          <span style="width:40px; height:40px; border-radius:8px; background:rgba(239,68,68,0.08); color:var(--crimson); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">🔍</span>
        </div>
        <h4 style="font-size:1.2rem; color:var(--navy); margin-bottom:0.75rem; font-weight:700;">Free Prepress File Audit</h4>
        <p style="color:var(--text-muted); font-size:0.92rem; line-height:1.7; flex:1; margin:0;">
          Every client artwork upload is inspected by technicians for alpha transparency, CMYK trapping, resolution, and line thickness.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 2: WHY CHOOSE ORDERPRINTEX? (8 FEATURE CARDS) -->
<!-- ============================================================== -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <span class="section-label" style="justify-content:center;">Client Commitment</span>
      <h2 style="font-size:clamp(1.9rem, 2.8vw, 2.5rem); color:var(--navy); margin-bottom:0.75rem;">Why Choose OrderPrintex?</h2>
      <p class="section-subtitle" style="margin:0 auto; max-width:640px;">
        More than printing — a reliable partner for bringing your ideas to life.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.75rem; margin-top:3rem;">
      <!-- Feature 1 -->
      <div class="feature-card reveal slide-up stagger-1" style="padding:1.75rem; background:var(--ivory); border-radius:var(--radius-md); border:1px solid var(--border-light); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:0.75rem;">⚡</div>
        <h4 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">1. Advanced Printing Technology</h4>
        <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin:0;">
          High-precision piezoelectric heads and automated curing tunnels ensure razor-sharp edges, micro-details, and seamless gradients.
        </p>
      </div>

      <!-- Feature 2 -->
      <div class="feature-card reveal slide-up stagger-2" style="padding:1.75rem; background:var(--ivory); border-radius:var(--radius-md); border:1px solid var(--border-light); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:0.75rem;">📐</div>
        <h4 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">2. Professional Prepress Inspection</h4>
        <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin:0;">
          Every submitted file is verified for bleed margins, resolution trapping, and layout optimization to prevent misprints and material waste.
        </p>
      </div>

      <!-- Feature 3 -->
      <div class="feature-card reveal slide-up stagger-3" style="padding:1.75rem; background:var(--ivory); border-radius:var(--radius-md); border:1px solid var(--border-light); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:0.75rem;">🎨</div>
        <h4 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">3. Custom Printing Solutions</h4>
        <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin:0;">
          Tailored print processes for apparel, hard drinkware, rigid mailers, holographic stickers, and corporate onboarding packages.
        </p>
      </div>

      <!-- Feature 4 -->
      <div class="feature-card reveal slide-up stagger-4" style="padding:1.75rem; background:var(--ivory); border-radius:var(--radius-md); border:1px solid var(--border-light); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:0.75rem;">📜</div>
        <h4 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">4. Multiple Material &amp; Finishing Options</h4>
        <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin:0;">
          Choice of combed cotton, poly blends, rigid corrugated mailers, matte lamination, velvet soft-touch, and metallic gold foil stamping.
        </p>
      </div>

      <!-- Feature 5 -->
      <div class="feature-card reveal slide-up stagger-1" style="padding:1.75rem; background:var(--ivory); border-radius:var(--radius-md); border:1px solid var(--border-light); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:0.75rem;">🛡️</div>
        <h4 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">5. Quality-Focused Production</h4>
        <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin:0;">
          Batch pull tests, adhesive thickness checks, and 50+ wash longevity standards ensure every shipment meets stringent commercial quality.
        </p>
      </div>

      <!-- Feature 6 -->
      <div class="feature-card reveal slide-up stagger-2" style="padding:1.75rem; background:var(--ivory); border-radius:var(--radius-md); border:1px solid var(--border-light); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:0.75rem;">💬</div>
        <h4 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">6. Dedicated Customer Support</h4>
        <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin:0;">
          Direct WhatsApp and phone consultations with experienced prepress technicians to guide file preparation, sizing, and press settings.
        </p>
      </div>

      <!-- Feature 7 -->
      <div class="feature-card reveal slide-up stagger-3" style="padding:1.75rem; background:var(--ivory); border-radius:var(--radius-md); border:1px solid var(--border-light); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:0.75rem;">📦</div>
        <h4 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">7. Commercial &amp; Bulk Order Support</h4>
        <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin:0;">
          High-volume roll manufacturing capacity delivering wholesale pricing tiers, staggered roll drops, and insured courier freight across India.
        </p>
      </div>

      <!-- Feature 8 -->
      <div class="feature-card reveal slide-up stagger-4" style="padding:1.75rem; background:var(--ivory); border-radius:var(--radius-md); border:1px solid var(--border-light); transition:transform 0.3s ease;">
        <div style="font-size:1.75rem; margin-bottom:0.75rem;">💻</div>
        <h4 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.5rem; font-weight:700;">8. Convenient Online Ordering</h4>
        <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin:0;">
          Drop your artwork online, review digital proof proposals with itemized GST invoices, and track orders directly from your customer portal.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 3: SIMPLE FOUR-STEP PRODUCTION FLOW -->
<!-- ============================================================== -->
<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem;">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <span class="section-badge" style="background:rgba(255,255,255,0.1); color:var(--gold); border:1px solid rgba(185,154,97,0.3);">
        Work with Printex
      </span>
      <h2 style="font-size:clamp(1.9rem, 2.8vw, 2.5rem); color:#FFFFFF; margin-bottom:0.75rem;">Simple Four-Step Production Flow</h2>
      <p class="section-subtitle" style="color:rgba(255,255,255,0.75); margin:0 auto; max-width:680px;">
        From transparent digital artwork to press-ready delivery at your doorstep anywhere in India.
      </p>
    </div>

    <!-- Horizontal Desktop / Vertical Mobile Timeline -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:2rem; margin-top:3.5rem; position:relative;">
      <!-- Step 01 -->
      <div class="timeline-step reveal slide-up stagger-1" style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:var(--radius-md); padding:2rem 1.5rem; text-align:center;">
        <div style="width:48px; height:48px; border-radius:50%; background:var(--royal); color:#FFFFFF; font-weight:800; font-size:1.1rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
          01
        </div>
        <h4 style="color:#FFFFFF; font-size:1.15rem; margin-bottom:0.75rem;">Upload Your Design</h4>
        <p style="color:rgba(255,255,255,0.7); font-size:0.88rem; line-height:1.6; margin:0;">
          Drop your vector file (AI, PDF, SVG) or high-resolution transparent PNG into our interactive workbench.
        </p>
      </div>

      <!-- Step 02 -->
      <div class="timeline-step reveal slide-up stagger-2" style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:var(--radius-md); padding:2rem 1.5rem; text-align:center;">
        <div style="width:48px; height:48px; border-radius:50%; background:var(--royal); color:#FFFFFF; font-weight:800; font-size:1.1rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
          02
        </div>
        <h4 style="color:#FFFFFF; font-size:1.15rem; margin-bottom:0.75rem;">Prepress Audit</h4>
        <p style="color:rgba(255,255,255,0.7); font-size:0.88rem; line-height:1.6; margin:0;">
          Technicians verify resolution, color trapping, and optimize your gang sheet layout to minimize waste.
        </p>
      </div>

      <!-- Step 03 -->
      <div class="timeline-step reveal slide-up stagger-3" style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:var(--radius-md); padding:2rem 1.5rem; text-align:center;">
        <div style="width:48px; height:48px; border-radius:50%; background:var(--royal); color:#FFFFFF; font-weight:800; font-size:1.1rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
          03
        </div>
        <h4 style="color:#FFFFFF; font-size:1.15rem; margin-bottom:0.75rem;">Receive Quotation</h4>
        <p style="color:rgba(255,255,255,0.7); font-size:0.88rem; line-height:1.6; margin:0;">
          Review your itemized price proposal with GST and delivery included. Accept or request adjustments online.
        </p>
      </div>

      <!-- Step 04 -->
      <div class="timeline-step reveal slide-up stagger-4" style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:var(--radius-md); padding:2rem 1.5rem; text-align:center;">
        <div style="width:48px; height:48px; border-radius:50%; background:var(--royal); color:#FFFFFF; font-weight:800; font-size:1.1rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
          04
        </div>
        <h4 style="color:#FFFFFF; font-size:1.15rem; margin-bottom:0.75rem;">Production &amp; Dispatch</h4>
        <p style="color:rgba(255,255,255,0.7); font-size:0.88rem; line-height:1.6; margin:0;">
          High-speed printing, curing, inspection, and express insured courier delivery with live milestone tracking.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 4: TOOLS AND TECHNOLOGY -->
<!-- ============================================================== -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <span class="section-label" style="justify-content:center;">Equipment &amp; Machinery</span>
      <h2 style="font-size:clamp(1.9rem, 2.8vw, 2.5rem); color:var(--navy); margin-bottom:0.75rem;">Tools &amp; Printing Technologies</h2>
      <p class="section-subtitle" style="margin:0 auto; max-width:680px;">
        Our Delhi production workshop operates calibrated industrial print engines, automated powder tunnels, and color spectrophotometers.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:2rem; margin-top:3rem;">
      <?php if (!empty($tools)): ?>
        <?php foreach ($tools as $idx => $tool): ?>
          <div class="service-showcase-row reveal slide-up" style="display:flex; flex-direction:column; padding:1.75rem; background:#FFFFFF; border:1px solid var(--border-light); border-radius:var(--radius-md); box-shadow:var(--shadow-sm);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
              <span style="font-size:0.75rem; font-weight:700; color:var(--royal); text-transform:uppercase; background:rgba(29,78,216,0.08); padding:0.25rem 0.65rem; border-radius:4px;">
                <?= e($tool['category']) ?>
              </span>
              <span style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">Calibrated Unit</span>
            </div>

            <h4 style="font-size:1.2rem; color:var(--navy); margin-bottom:0.75rem; font-weight:700;">
              <?= e($tool['name']) ?>
            </h4>

            <div style="margin-bottom:1rem;">
              <strong style="font-size:0.825rem; color:var(--navy); display:block; margin-bottom:0.25rem;">Purpose &amp; Operation:</strong>
              <p style="font-size:0.88rem; color:var(--text-muted); line-height:1.6; margin:0;">
                <?= e($tool['purpose']) ?>
              </p>
            </div>

            <div style="background:var(--ivory); border-left:3px solid var(--gold); padding:0.75rem 1rem; border-radius:4px; margin-top:auto;">
              <strong style="font-size:0.8rem; color:var(--navy);">Quality Impact:</strong>
              <p style="font-size:0.825rem; color:var(--text-muted); line-height:1.5; margin:0.2rem 0 0;">
                <?= e($tool['impact']) ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 6: STRONG CALL TO ACTION -->
<!-- ============================================================== -->
<section class="section section-ivory text-center">
  <div class="container container-narrow reveal scale-in">
    <span class="section-label" style="justify-content:center; color:var(--royal);">Start Your Project</span>
    <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:var(--navy); margin-bottom:1rem; font-weight:800;">
      Have a Printing Project in Mind?
    </h2>
    <p style="color:var(--text-muted); font-size:1.1rem; line-height:1.7; margin:0 auto 2.5rem; max-width:620px;">
      Share your design and printing requirements with our team. We provide free prepress analysis, color advice, and itemized wholesale proposals.
    </p>

    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-lg">
        <span>Upload Your Design</span> &rarr;
      </a>
      <a href="<?= url('get-quote/') ?>" class="btn btn-navy btn-lg">
        <span>Request a Quote</span>
      </a>
      <a href="<?= url('contact/') ?>" class="btn btn-outline btn-lg">
        <span>Contact Us</span>
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
