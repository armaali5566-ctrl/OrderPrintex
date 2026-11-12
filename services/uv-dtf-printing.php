<?php
/**
 * OrderPrintex - UV DTF Printing Service Page
 */

$pageTitle = "Industrial UV DTF Printing in Delhi | Printex";
$pageDesc  = "Instant transfer stickers with a 3d embossed tactile finish - they stick to glass, acrylic, stainless steel, wood and plastic like glue and don't need a heat press.";

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'uv-dtf-printing'");
?>

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Hard-Surface Decal Innovation</span>
      </div>
      <h1 class="page-intro-title">Industrial UV DTF Printing in Delhi</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Instant transfer stickers with a 3d embossed tactile finish - they stick to glass, acrylic, stainless steel, wood and plastic like glue and don't need a heat press. This makes the quality instant, and forever-bonded to the surface
      </p>
    </div>
  </div>
</section>

<!-- Overview -->
<section class="section section-white">
  <div class="container">
    <div class="service-overview-grid">
      <div class="service-overview-content reveal slide-right">
        <div class="page-intro-eyebrow" style="margin-bottom:0.5rem;">
          <span class="page-intro-dot"></span>
          <span>Printing The Impossible</span>
        </div>
        <h2>Take Your Designs To Any Surface with UV DTF Printing</h2>
        <p>
          At Printex, we've got a knack for printing high-quality UV DTF in Delhi, helping our customers - big brands, small businesses &amp; creatives - turn physical objects that can't fit under a heat press into one-of-a-kind works of art. We ship UV DTF stickers all over the country, to every major city and all points in between.
        </p>
        <p>
          Our industrial UV DTF machines are state-of-the-art - they put down 3 layers at the same time: a base coat, a vibrant CMYK colour layer and a glossy UV varnish. All this gets cured instantly by super-powerful UV LEDs onto a specialized AB adhesive sheet.
        </p>
        <p>
          Applying the decals is a breeze - just peel the backing off, slap it on a tumbler or mug, rub it gently and lift off the top clear layer. What you're left with is a clean, raised graphic that sticks like crazy.
        </p>
        <p>
          Whether you run a tumbler brand, a business that sells cool gifts or a print shop, a UV DTF transfer lets you add a top-end, tactile logo to pretty much any hard product without breaking the bank, or needing special equipment.
        </p>
        <p>
          Want to get a quote or see some of our UV decal work? Head on over to the bottom of this page to upload your design, or browse our gallery to get some ideas
        </p>

        <div class="service-cta-group">
          <a href="#upload-uv" class="btn service-btn-primary">Upload Design for UV DTF Quote</a>
          <a href="<?= url('gallery.php') ?>" class="btn service-btn-secondary">View UV Decal Gallery</a>
        </div>
      </div>

      <div class="service-overview-media reveal slide-left">
        <div class="service-media-frame">
          <img src="<?= url('assets/images/uv_dtf_drinkware.jpg') ?>" alt="UV DTF Printing on Curved Drinkware" loading="lazy">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- How Does UV DTF Printing Work? -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">The Technology</div>
      <h2>How Does UV DTF Printing Work?</h2>
    </div>

    <div style="max-width:850px; line-height:1.8; color:var(--text-secondary); font-size:1.05rem;">
      <p style="margin-bottom:1.25rem;">
        UV DTF stands for ultraviolet direct-to-film - it's like this : the design gets printed in layers on a special sheet, then cured with UV light, so it dries instantly &amp; comes out all raised and shiny. Once it's printed you just slap on a clear layer on top - that's why you can just move it from the sheet to your product by hand.
      </p>
      <p>
        Regular old DTF transfers need a heat press and are made for fabrics. UV DTF decals are different - they stick cold, with no heat at all, so they work great on hard &amp; curved surfaces like glass, metal, ceramic and plastic. Looking for t-shirt or hoodies instead? We've got you covered - check out our DTF printing page for info on garment prints.
      </p>
    </div>
  </div>
</section>

<!-- UV DTF Stickers vs Vinyl Stickers vs Regular DTF -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Comparison</div>
      <h2>UV DTF Stickers vs Vinyl Stickers vs Regular DTF</h2>
    </div>

    <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); overflow-x:auto; margin-bottom:1.5rem; box-shadow:var(--shadow-card);">
      <table class="compare-table" style="width:100%; min-width:600px;">
        <thead>
          <tr style="background:var(--bg-surface-elevated); border-bottom:2px solid var(--border-color);">
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Feature</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">UV DTF stickers</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Vinyl stickers</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Regular DTF</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Best for</td>
            <td>Hard and curved surfaces</td>
            <td>Flat surfaces, labels</td>
            <td>Fabric and garments</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Heat press needed</td>
            <td>No</td>
            <td>No</td>
            <td>Yes</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Look</td>
            <td>3D, raised and glossy</td>
            <td>Flat</td>
            <td>Soft and flexible</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Fine details and full colour</td>
            <td>Sharp</td>
            <td>Limited by cutting</td>
            <td>Sharp</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Ready to apply</td>
            <td>Yes, pre-masked</td>
            <td>Needs cutting and weeding</td>
            <td>Needs pressing</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- UV DTF Stickers for Glass, Tumblers, Acrylic & Packaging -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Applications</div>
      <h2>UV DTF Stickers for Glass, Tumblers, Acrylic &amp; Packaging - Where Quality Meets Flexibility</h2>
      <p class="section-subtitle" style="max-width:850px; margin:0 auto 2rem; text-align:left;">
        We've engineered our UV DTF decals to stick exceptionally well to a wide range of materials. They're water-proof, scratch-proof and chemically stable - making them perfect for all sorts of commercial products like drinkware, gadgets and retail packaging.
      </p>
    </div>

    <div class="service-specs-grid">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--navy); margin-bottom:0.5rem;">Drinkware &amp; Bottles</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Think stainless steel insulated tumblers, glass mason jars, ceramic mugs, and sports bottles. - or any other drinkware that you'd like to personalise.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--navy); margin-bottom:0.5rem;">Electronics &amp; Gadgets</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          We've got you covered with laptop lids, smartphone cases, wireless power banks, tablets, and even audio gear.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--navy); margin-bottom:0.5rem;">Acrylic &amp; Signage</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Our decals work great on clear acrylic awards, corporate door signs, POS displays, trophy plates - in fact, any type of signage or award that you need to make an impact.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--navy); margin-bottom:0.5rem;">Retail Packaging</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          And for retail packaging - we've got rigid gift boxes, cosmetic glass jars, metal tins, candle holders, wood boards all covered.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Who Turns to Printex for UV DTF Stickers -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Our Clients</div>
      <h2>Who Turns to Printex for UV DTF Stickers</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.25rem;">
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Tumbler and drinkware brands who want a high-end, 3D logo on every single bottle they produce.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Companies that specialise in corporate gifting - branding mugs, diaries, power banks and welcome kits for businesses.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Packaging and cosmetic brands who decorate glass jars, tins and rigid boxes to give their products an extra wow factor.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Signage and award makers who need sharp graphics on acrylic and metal plates that grab the attention.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm); grid-column:1 / -1;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">And print shops and resellers who buy UV DTF printing in bulk and use it to brand products for their own customers.</p>
      </div>
    </div>
  </div>
</section>

<!-- The Benefits of Printing with UV DTF -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Advantages</div>
      <h2>The Benefits of Printing with UV DTF</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.75rem; padding:1.25rem 0;">
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">No need for a heat press: decals can be applied cold, so you can brand objects that just can't be heated.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Premium 3D finish: the raised gloss varnish gives your designs a tactile, high-end feel that just can't be beaten.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Waterproof and scratch-resistant: our decals are made to withstand the daily knocks that come with being used.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Full colour on any design: whether you want photos, gradients or fine logos, you can print them all in full colour, without any extra costs.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Fast and easy to apply: it's a simple peel, stick, rub, lift process - no special skills or equipment needed.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">One-off or one thousand: you can start with a single sheet and scale up your production when you're ready.</p>
      </div>
    </div>
  </div>
</section>

<!-- How to Prepare Your Artwork for UV DTF Printing -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Prepress Guide</div>
      <h2>How to Prepare Your Artwork for UV DTF Printing</h2>
    </div>

    <div style="max-width:850px; line-height:1.8; color:var(--text-secondary); font-size:1.05rem;">
      <p>
        To get the best out of your decals, make sure your artwork is good to go. We need a transparent PNG or a vector file (AI, SVG or PDF) with fonts converted to outlines, at 300 DPI. Try to avoid super thin lines and teeny tiny text as these can get lost on a 3D finish. And if you want to add a gloss varnish to a specific part of your design, just let us know in the notes and we'll set it up. If you're not sure about your file, send it over and our team will check it out before we print it
      </p>
    </div>
  </div>
</section>

<!-- UV DTF Decals in Any Size - One Sheet to Bulk -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Order Scale</div>
      <h2>UV DTF Decals in Any Size - One Sheet to Bulk</h2>
    </div>

    <div style="max-width:850px; line-height:1.8; color:var(--text-secondary); font-size:1.05rem;">
      <p style="margin-bottom:1.25rem;">
        Our UV DTF printing starts with a single sheet - so you can trial a design or order bulk decals for your whole product run. Send us your logo or artwork, along with the size and quantity you need, and we'll fit as many decals as we can onto each sheet to keep your costs down. Our rates start from ₹____ per sheet.
      </p>
      <p>
        And, to make things even easier, every order comes with carrier tape pre-applied, so your customers just need to peel, stick and rub. If you're a regular customer, you'll qualify for better rates as you order more - so let us know your monthly requirements and we'll quote you accordingly.
      </p>
    </div>
  </div>
</section>

<!-- The Lowdown on UV DTF Printing at Printex -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Workflow</div>
      <h2>The Lowdown on UV DTF Printing at Printex</h2>
      <p class="section-subtitle">
        Get your design readywe've got our preferences - a nice transparent PNG or vector file, ideally 300 DPI, with fonts nice and outlined.
      </p>
    </div>

    <div class="service-specs-grid" style="grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 1</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          We take a look: before we give you a quote, we check the resolution, margins, and sheet layout to make sure everything is good to go.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 2</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Print &amp; Cure Magic: White base, colours &amp; gloss varnish get printed and cured with UV light - all in one go!
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 3</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          It's off to you: every sheet gets inspected, carefully packed and shipped off to anywhere in India from our Delhi studio.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Why We're the Ones to Trust for UV DTF Printing in Delhi -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Trust &amp; Reliability</div>
      <h2>Why We're the Ones to Trust for UV DTF Printing in Delhi</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.75rem; padding:1.25rem 0;">
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Free artwork check: We take a glance at your design before printing to make sure everything's good to print.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">We've got a sharp finish that will knock your socks off - bright colours with a raised, glossy varnish that really makes your decals pop.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">With us, you get quality that's consistentsame great results on your first order as you will on your repeat order.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">We're all about flexibilitywhether its one sheet or a whole production run, we've got you covered.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; grid-column:1 / -1;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Our UV DTF stickers ship from our Delhi studio to your doorstep all over India.</p>
      </div>
    </div>
  </div>
</section>

<!-- How to Apply a UV DTF Sticker Like a Pro -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Application Instructions</div>
      <h2>How to Apply a UV DTF Sticker Like a Pro</h2>
    </div>

    <div class="service-specs-grid" style="grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 1</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Clean and clear: make sure the surface is dust, oil and moisture free - a quick wipe will do the trick.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 2</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Peel off the backing: remove the carrier backing and pop the decal in place.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 3</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Give it a rub: take a squeegee or your finger and gently press the decal in from the centre outwards.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 4</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Lift the clear film: peel away the top film slowly and carefully, leaving your decal looking great.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-5">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 5</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Looking after your decal: wash by hand and avoid harsh scrubbing - its all about keeping it looking new for longer.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Direct Upload Card / CTA Section -->
<section class="section section-ivory" id="upload-uv">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Order UV DTF Transfers</div>
      <h2>Get an Instant UV DTF Printing Quote</h2>
      <p class="section-subtitle">
        Upload your transparent artwork or logo sheet for quick turnaround production. UV DTF decals are supplied pre-masked with carrier tape for peel, stick and rub application.
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

            <div style="background:var(--bg-surface-elevated); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-color);">
              <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.85rem; font-weight:700; color:var(--text-primary);">Calculated Decal Area:</span>
                <strong class="sq-inches-display text-navy" style="font-size:1.15rem; color:var(--primary-gold);">—</strong>
              </div>
              <small style="color:var(--text-muted); display:block; margin-top:4px; font-size:0.78rem;">
                UV DTF decals are supplied pre-masked with carrier tape for peel, stick, and rub application.
              </small>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Target Substrate / Material <span class="required">*</span></label>
                <select name="material_options" class="form-control"
                        data-enable-other="true"
                        data-other-name="custom_material_options"
                        data-other-label="Specify Custom Target Substrate"
                        data-other-placeholder="e.g. Anodized Aluminum Flasks, Carbon Fiber Panels, Ceramic Mugs">
                  <option value="Glass / Bottles">Glassware / Bottles</option>
                  <option value="Stainless Steel / Tumblers">Stainless Steel Tumblers / Mugs</option>
                  <option value="Acrylic / Plastic">Clear Acrylic / Plastic Packaging</option>
                  <option value="Wood / Metal">Finished Wood / Metal Plates</option>
                  <option value="General Hard Surface" selected>General Hard Surface</option>
                  <option value="other">Other (Specify Custom Substrate)</option>
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

<!-- Frequently Asked Questions About UV DTF Printing -->
<section class="section section-white">
  <div class="container container-narrow">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Common Queries</div>
      <h2>Frequently Asked Questions About UV DTF Printing</h2>
    </div>

    <div class="faq-accordion">
      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>1. What is the minimum order for UV DTF printing?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          You can start with just one sheet. [MOQ confirm karo]
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>2. How much do UV DTF stickers cost?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          It depends on the decal size, quantity and number of designs. Upload your design with the size you need and we will reply with a clear quote.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>3. Are UV DTF stickers waterproof?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes. UV DTF decals are waterproof and scratch-resistant once applied to a clean, dry surface.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>4. Do I need a heat press for UV DTF?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          No. UV DTF decals are applied cold. Just peel, stick and rub.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>5. Which surfaces can I use UV DTF stickers on?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Glass, acrylic, stainless steel, ceramic, metal, wood and most hard plastics. For fabric, use our DTF transfers instead.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>6. Can I stick them on curved bottles and tumblers?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes. UV DTF decals are made for curved and rigid surfaces such as tumblers, mugs and bottles.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>7. Can UV DTF decals go in the dishwasher?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          We recommend gentle hand washing and no harsh scrubbing, so your decal looks new for longer.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>8. How long does a UV DTF sticker last?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          With clean application and normal care, UV DTF stickers stay firmly on the surface through daily use.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>9. Can I get a spot gloss or varnish effect?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes. Mention it in the special finish notes of the quote form and we will set it up on your design.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>10. What file should I upload?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          A transparent PNG or vector file at 300 DPI, with fonts converted to outlines.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>11. What is the difference between UV DTF and regular DTF?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Regular DTF transfers are for fabric and need a heat press. UV DTF decals stick to hard surfaces with no heat.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>12. Do you deliver across India?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes, we dispatch UV DTF stickers to all major cities and towns.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Visit Studio Section -->
<section class="section section-ivory">
  <div class="container container-narrow text-center reveal slide-up">
    <div class="section-label" style="justify-content:center;">Location</div>
    <h2 style="margin-bottom:1rem;">UV DTF Printing in Delhi: Visit Our Studio</h2>
    <p style="font-size:1.1rem; color:var(--text-primary); margin-bottom:1rem;">
      Order UV DTF stickers and decals from our Delhi studio.
    </p>
    <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; margin:1.5rem auto; max-width:650px;">
      <p style="font-weight:700; color:var(--primary-gold); font-size:1.05rem; margin-bottom:0.5rem;">
        Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Delhi 110059
      </p>
      <p style="margin:0; color:var(--text-secondary);">
        Call / WhatsApp:
      </p>
    </div>
    <div style="margin-top:1.5rem;">
      <a href="<?= url('contact.php') ?>" class="btn btn-primary btn-lg">
        <span>Contact Printex Studio</span> &rarr;
      </a>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
