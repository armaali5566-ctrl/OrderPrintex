<?php
/**
 * OrderPrintex - DTF Printing Service Page
 */

require_once __DIR__ . '/../includes/functions.php';

$pageTitle = "DTF Printing in Delhi | Bulk DTF Transfers & Gang Sheets – Printex";
$pageDesc  = "DTF printing in Delhi by Printex, a DTF printing manufacturer in India. Bulk DTF transfers and gang sheets on 22 & 24 inch rolls. Upload your design for a quote.";
$canonical = service_url('dtf-printing');
$pageSchemaKey = 'services-dtf-printing';

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'dtf-printing'");
?>

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>DTF Printing Services in Delhi</span>
      </div>
      <h1 class="page-intro-title">DTF Printing in Delhi - Making Your Designs Pop with Long-Lasting Results</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Want to get that cool design on a t-shirt or hoodie without breaking the bank or dealing with the hassle of screens, set up costs, and colour restrictions? Our DTF printing services in Delhi cover all bases. Whether it's a single sheet or bulk rolls, your prints will arrive across India in no time and look fantastic - the colours are bright, the feel is soft, and the prints are super durable.
      </p>
    </div>
  </div>
</section>

<!-- What's All this About DTF Printing -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-odd is-visible">
      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/dtf_fabric_print.jpg') ?>" alt="DTF Transfer Print on Dark Fabric" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>Direct-to-Film 24" Print</span>
          </div>
        </div>
      </div>

      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--blue, #2563EB);">Direct-to-Film</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          What's All this About DTF Printing (and how does it work?)
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1;">
          You probably know it as Direct-to-Film. Here's the lowdown: your design gets printed directly onto a special film that's coated with a bit of adhesive. Then, with a bit of heat, its pressed onto the garment. The result is colours that stay bright on even the darkest fabrics, and the fine details are still as sharp as can be.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          Our DTF transfer printing service here in Delhi is geared towards brands, print shops, resellers and anyone new to the game. You can send us your artwork and we will make ready-to-press transfers for you to apply yourself, or send us your garments and we will do the pressing for you. These films work like a charm on cotton, polyester, blends - you name it.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          DTF is a great choice when you want to steer clear of screens, colour separation and all the extra per-colour charges that come with it. Small batches, repeat orders, bulk runs - DTF is a real winner when it comes to these types of print jobs. Hit us up with your artwork and we'll have a look at it before we get to work.
        </p>

        <p style="font-weight:700; color:#F8FAFC; margin-top:0.5rem; margin-bottom:1.5rem; font-size:1.02rem;">
          Upload Your Design and we'll give You a Quote for DTF Printing - and Check out our Gang Sheet Options While You're At It
        </p>

        <div class="service-cta-group" style="display:flex; gap:1rem; flex-wrap:wrap;">
          <a href="#upload-dtf" class="btn btn-primary btn-lg">
            <span>Upload Design for DTF Quote</span> &rarr;
          </a>
          <a href="<?= url('products.php') ?>" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
            <span>View Gang Sheet Options</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DTF vs Screen Printing vs Vinyl: Which Is Better? -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.25rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Comparison</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        DTF vs Screen Printing vs Vinyl: Which Is Better?
      </h2>
    </div>

    <div class="elevated-compare-card reveal slide-up">
      <table class="compare-table">
        <thead>
          <tr>
            <th>Feature</th>
            <th class="flagship-col">DTF printing</th>
            <th>Screen printing</th>
            <th>Vinyl</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Set-up</td>
            <td class="flagship-col">None</td>
            <td>Separate screen per colour</td>
            <td>Cutting and weeding</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Full colour and gradients</td>
            <td class="flagship-col">Yes, no extra cost</td>
            <td>Costly, more colours = more cost</td>
            <td>Limited</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Small and repeat orders</td>
            <td class="flagship-col">Great</td>
            <td>Expensive</td>
            <td>Okay</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Dark fabrics</td>
            <td class="flagship-col">Bright, thanks to white base</td>
            <td>Needs underbase</td>
            <td>Works</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Feel</td>
            <td class="flagship-col">Soft and flexible</td>
            <td>Soft</td>
            <td>Can feel plasticky</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="reveal slide-up" style="margin-top:1.25rem;">
      <div style="display:inline-flex; align-items:center; gap:0.6rem; background:rgba(212,175,55,0.12); border:1px solid rgba(212,175,55,0.32); border-radius:999px; padding:0.5rem 1.35rem; font-weight:700; color:#FFD76A; font-size:1.02rem;">
        <span style="color:#D4AF37; font-size:1.15rem;">★</span>
        <span>Ten pieces in six colours? DTF is the clear winner.</span>
      </div>
    </div>
  </div>
</section>

<!-- DTF Gang Sheets & Bulk DTF Transfers -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-even is-visible">
      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--crimson, #DC2626);">Maximum Value</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          DTF Gang Sheets &amp; Bulk DTF Transfers - Make the Most of Your Ink
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1; font-size:1.04rem; line-height:1.8;">
          A DTF gang sheet is basically one big sheet of film with loads of designs all crammed in together, so there's zero waste and you only pay for what you use. Logos and big graphics can happily coexist on the same sheet.
        </p>
        <p class="tech-desc" style="color:#CBD5E1; font-size:1.04rem; line-height:1.8;">
          Our bulk DTF transfers come in two sizes, 22 and 24 inches wide, and the per foot price drops as you order more. You'll get a custom quote for your DTF sheet based on length, how many you're ordering and the number of designs. Just let us know what you're working with and we'll get a quote sorted.
        </p>
      </div>

      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/streetwear_tee.jpg') ?>" alt="DTF Gang Sheets and Transfers" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>22" &amp; 24" Industrial Rolls</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DTF Printing for T-Shirts, Hoodies, Sportswear and Loads More -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Versatility</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        DTF Printing for T-Shirts, Hoodies, Sportswear and Loads More
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.35rem; margin-bottom:2rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
        <p>T-shirts and those massive oversized tees - both print out great</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
        <p>Hoodies and sweatshirts - our prints are nice and flexible, move with the fabric no problem</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
        <p>Sportswear and team jerseys - from match day to training, we've got you covered</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
        <p>Caps, tote bags and uniforms - pretty much anything will work in a pinch</p>
      </div>
    </div>

    <p class="reveal slide-up" style="color:#CBD5E1; line-height:1.75; font-size:1.02rem; max-width:860px; margin:0;">
      We supply all sorts of print shops and brands across India, and we ship all over the country. Want to know how we deal with nylon or other performance fabrics? Just ask and we'll sort you out with the right film.
    </p>
  </div>
</section>

<!-- How Printex Do DTF -->
<section class="section section-navy" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem); background: radial-gradient(circle at 50% 10%, rgba(212, 175, 55, 0.16) 0%, transparent 60%), #080808;">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:740px; margin:0 auto clamp(2.5rem, 4vw, 3.5rem);">
      <span class="section-label" style="justify-content:center; color:var(--bright-gold, #FFD76A);">Our Process</span>
      <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:#FFFFFF; font-weight:800; line-height:1.2; margin-bottom:0.85rem;">
        How Printex Do DTF
      </h2>
    </div>

    <div class="process-grid">
      <div class="process-step-card reveal slide-up stagger-1">
        <div class="process-step-num">01</div>
        <h3 class="process-step-title" style="color:#D4AF37;">Step 1</h3>
        <p class="process-step-desc">
          Send us your design - just a transparent PNG, PDF or vector file (AI or SVG) would be great, ideally get it up to 300 DPI.
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-2">
        <div class="process-step-num">02</div>
        <h3 class="process-step-title" style="color:#D4AF37;">Step 2</h3>
        <p class="process-step-desc">
          We have a look: check for resolution, margins, sheet layout and all that jazz. Then we get back to you with a clear quote.
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-3">
        <div class="process-step-num">03</div>
        <h3 class="process-step-title" style="color:#D4AF37;">Step 3</h3>
        <p class="process-step-desc">
          We get to work: your design is printed on some film, we slap on the adhesive and throw the sheet through the curing process.
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-4">
        <div class="process-step-num">04</div>
        <h3 class="process-step-title" style="color:#D4AF37;">Step 4</h3>
        <p class="process-step-desc">
          We send it off: every sheet gets a double check, is packed up and shipped all over India.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Printex for DTF Printing in Delhi -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">The Printex Advantage</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Why Choose Printex for DTF Printing in Delhi - We've Got You Covered
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p>Free artwork check - we make sure everything's good to go, no extra charge</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p>Consistent colour: your second order looks the same as the first, no complaints</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p>We're flexible on quantities - want one sheet or the whole roll? Your call</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p>Pan-India shipping: we print in Delhi and get your order delivered to your doorstep.</p>
      </div>
    </div>
  </div>
</section>

<!-- How to Press a DTF Transfer - Heat, Time and a Bit of Pressure -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Application Guide</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        How to Press a DTF Transfer - Heat, Time and a Bit of Pressure
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.75rem;">
      <div class="reveal slide-up stagger-1" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">01 / Temperature</div>
        <h4 style="color:#F8FAFC; margin-bottom:0.65rem; font-size:1.15rem; font-weight:700;">Temperature</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.65; margin:0;">
          Temperature: about 150 to 160°C so that adhesive bonds in properly.
        </p>
      </div>

      <div class="reveal slide-up stagger-2" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">02 / Pressing</div>
        <h4 style="color:#F8FAFC; margin-bottom:0.65rem; font-size:1.15rem; font-weight:700;">Time and Pressure</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.65; margin:0;">
          Time and pressure: 10 to 15 seconds at medium pressure is good. You can use a bit of heat and your press should be set up to get even pressure.
        </p>
      </div>

      <div class="reveal slide-up stagger-3" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">03 / Release</div>
        <h4 style="color:#F8FAFC; margin-bottom:0.65rem; font-size:1.15rem; font-weight:700;">Peeling</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.65; margin:0;">
          Peeling: you can use a cold or warm peel - just let it cool down a bit, then peel it off in one smooth go
        </p>
      </div>

      <div class="reveal slide-up stagger-4" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">04 / Sizing</div>
        <h4 style="color:#F8FAFC; margin-bottom:0.65rem; font-size:1.15rem; font-weight:700;">Sheet Width</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.65; margin:0;">
          Sheet width: our gang sheets and rolls are made to order - 22 or 24 inches
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Artwork Guidelines & Upload / CTA Section -->
<section class="section section-white" id="upload-dtf" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Direct Upload Studio</div>
      <h2>Get a Quote for DTF Printing in Delhi</h2>
      <p class="section-subtitle">
        Just upload your design as a PNG, PDF or vector file, tell us the size and quantity you need and we'll get back to you with a clear price quote.
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
                <select name="film_material" id="calcMaterialSelect" class="form-control" onchange="runDtfCalculation()"
                        data-enable-other="true"
                        data-other-name="custom_film_material"
                        data-other-label="Specify Custom Film or Specialty Foil"
                        data-other-placeholder="e.g. Glitter DTF Film, Glow-in-the-Dark PET, Gold Metallic Foil">
                  <option value="cold_peel" data-extra="0" selected>Double-Matte Cold Peel PET (Standard)</option>
                  <option value="hot_peel" data-extra="20">Hot Peel Instant Release Film (+₹20/ft)</option>
                  <option value="reflective" data-extra="45">Reflective Silver DTF (+₹45/ft)</option>
                  <option value="neon" data-extra="35">Fluorescent Neon CMYK+W (+₹35/ft)</option>
                  <option value="other" data-extra="0">Other (Specify Custom Film / Foil)</option>
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

<!-- Frequently Asked Questions -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container container-narrow">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Common Queries</div>
      <h2>Frequently Asked Questions</h2>
    </div>

    <div class="faq-accordion">
      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>1. What's the minimum order I need to place?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          You can start small and order just one sheet, no need to buy a whole roll.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>2. How much will a DTF sheet cost me?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          The price of our DTF sheets depends on a handful of things - how long you need the sheet, how many you buy, and how many designs you're looking to print. Upload your design along with the size you need and we'll send over a clear quote without any fuss.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>3. Are the DTF prints you do washable?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Absolutely. As long as you follow the right washing instructions and get them pressed correctly, the prints will stay bright and flexible even after you've washed them multiple times. Just remember to wash them inside out in cold water and steer clear of bleach.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>4. Which fabrics work best with DTF printing?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          That's pretty much any cotton blend or polyester mix. If you're planning on printing on nylon or sportswear fabrics, it's worth reaching out to us to make sure you're getting the right film.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>5. Can I send my own garments to be printed ?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes you can. You can either order up a batch of ready-to-press transfers or send your garments over and we'll take care of pressing them for you.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>6. What file format should I upload for my design?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Ideally, it's a good idea to upload a transparent PNG, PDF or a vector file like an AI or SVG - and make sure it's at a resolution of 300 DPI to get the best results.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>7. Do you do DTF printing in Delhi and deliver across India?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yep - we're based in Delhi and we deliver to the major cities and towns across India.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>8. What's the difference between DTF and UV DTF printing?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          The main difference here is that DTF transfers are for fabric and need a heat press, while UV DTF is for glass, metal and acrylic and doesn't need any heat. If you want to know more about UV DTF printing, you should probably check out our page on that.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Visit Studio Section -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container container-narrow text-center reveal slide-up">
    <div class="section-label" style="justify-content:center; color:var(--crimson, #DC2626);">Location</div>
    <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
      DTF Printing in Delhi: Come visit us
    </h2>
    <p style="font-size:1.08rem; color:#CBD5E1; margin-bottom:1.5rem; line-height:1.65;">
      You can now order DTF transfers and gang sheets direct from our Delhi studio.
    </p>
    <div style="background:#111111; border:1px solid rgba(212,175,55,0.3); border-top:3px solid var(--primary-gold, #D4AF37); border-radius:14px; padding:1.75rem 2rem; margin:1.5rem auto; max-width:650px; box-shadow:0 8px 24px rgba(15,25,45,0.06);">
      <p style="font-weight:700; color:#FFD76A; font-size:1.05rem; margin-bottom:0.5rem; line-height:1.5;">
        Shop E-6, Chanakya Palace, 21 Street No, Pankha Rd, next to 40 Feet Road, Delhi 110059
      </p>
      <p style="margin:0; color:#CBD5E1; font-size:0.95rem;">
        Give us a call or fire off a WhatsApp to get started.
      </p>
    </div>
    <div style="margin-top:2rem;">
      <a href="<?= url('contact.php') ?>" class="btn btn-primary btn-lg">
        <span>Contact Printex Studio</span> &rarr;
      </a>
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

  let ratePerFt = 249;
  if (linearFeet >= 5) {
    ratePerFt = 1099 / 5;
  } else if (linearFeet >= 3.28) {
    ratePerFt = 599 / 3.28;
  } else if (linearFeet >= 2) {
    ratePerFt = 450 / 2;
  } else {
    ratePerFt = 249;
  }

  if (width > 22) {
    ratePerFt = ratePerFt * (width / 22);
  }

  ratePerFt += matExtraPerFt;

  let unitBase = ratePerFt * linearFeet;

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

document.addEventListener('DOMContentLoaded', function() {
  runDtfCalculation();
  
  // Accordion click handling
  document.querySelectorAll('.faq-question').forEach(function(item) {
    item.addEventListener('click', function() {
      const parent = this.parentElement;
      const isOpen = parent.classList.contains('active');
      const accordion = parent.closest('.faq-accordion');
      if (accordion) {
        accordion.querySelectorAll('.faq-item.active').forEach(function(other) {
          if (other !== parent) {
            other.classList.remove('active');
            const icon = other.querySelector('.faq-icon');
            if (icon) icon.textContent = '+';
          }
        });
      }
      parent.classList.toggle('active', !isOpen);
      const icon = this.querySelector('.faq-icon');
      if (icon) {
        icon.textContent = !isOpen ? '−' : '+';
      }
    });
  });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
