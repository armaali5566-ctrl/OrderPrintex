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
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-odd is-visible">
      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/uv_dtf_drinkware.jpg') ?>" alt="UV DTF 3D Embossed Decals on Drinkware" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>UV DTF 3D Embossed Decal</span>
          </div>
        </div>
      </div>

      <div class="tech-zigzag-content">
        <div class="page-intro-eyebrow" style="margin-bottom:0.5rem;">
          <span class="page-intro-dot"></span>
          <span>Printing The Impossible</span>
        </div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          Take Your Designs To Any Surface with UV DTF Printing
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1;">
          At Printex, we've got a knack for printing high-quality UV DTF in Delhi, helping our customers - big brands, small businesses &amp; creatives - turn physical objects that can't fit under a heat press into one-of-a-kind works of art. We ship UV DTF stickers all over the country, to every major city and all points in between.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          Our industrial UV DTF machines are state-of-the-art - they put down 3 layers at the same time: a base coat, a vibrant CMYK colour layer and a glossy UV varnish. All this gets cured instantly by super-powerful UV LEDs onto a specialized AB adhesive sheet.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          Applying the decals is a breeze - just peel the backing off, slap it on a tumbler or mug, rub it gently and lift off the top clear layer. What you're left with is a clean, raised graphic that sticks like crazy.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          Whether you run a tumbler brand, a business that sells cool gifts or a print shop, a UV DTF transfer lets you add a top-end, tactile logo to pretty much any hard product without breaking the bank, or needing special equipment.
        </p>

        <p style="font-weight:700; color:#F8FAFC; margin-top:0.5rem; margin-bottom:1.5rem; font-size:1.02rem;">
          Upload Your Design and we'll give You a Quote for UV DTF Printing - or Get in Touch with our Delhi Print Team to Discuss Your Project
        </p>

        <div class="service-cta-group" style="display:flex; gap:1rem; flex-wrap:wrap;">
          <a href="#upload-uv" class="btn btn-primary btn-lg">
            <span>Upload Design for UV DTF Quote</span> &rarr;
          </a>
          <a href="<?= url('contact.php') ?>" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
            <span>Contact Printex Team</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- How Does UV DTF Printing Work? -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-even is-visible">
      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--crimson, #DC2626);">The Technology</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          How Does UV DTF Printing Work?
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1; font-size:1.04rem; line-height:1.8;">
          UV DTF stands for ultraviolet direct-to-film - it's like this : the design gets printed in layers on a special sheet, then cured with UV light, so it dries instantly &amp; comes out all raised and shiny. Once it's printed you just slap on a clear layer on top - that's why you can just move it from the sheet to your product by hand.
        </p>
        <p class="tech-desc" style="color:#CBD5E1; font-size:1.04rem; line-height:1.8;">
          Regular old DTF transfers need a heat press and are made for fabrics. UV DTF decals are different - they stick cold, with no heat at all, so they work great on hard &amp; curved surfaces like glass, metal, ceramic and plastic. Looking for t-shirt or hoodies instead? We've got you covered - check out our DTF printing page for info on garment prints.
        </p>
        <p class="tech-desc" style="color:#CBD5E1; font-size:1.02rem; line-height:1.8; margin-top:1rem;">
          Want to get a quote or see some of our UV decal work? Head on over to the bottom of this page to upload your design, or browse our gallery to get some ideas
        </p>
      </div>

      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/custom_stickers.jpg') ?>" alt="UV DTF Hard-Surface Decals and Custom Stickers" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>Instant Hard-Surface Decal</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- UV DTF Stickers vs Vinyl Stickers vs Regular DTF -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.25rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Comparison</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        UV DTF Stickers vs Vinyl Stickers vs Regular DTF
      </h2>
    </div>

    <div class="elevated-compare-card reveal slide-up">
      <table class="compare-table" style="width:100%; min-width:600px;">
        <thead>
          <tr>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Feature</th>
            <th class="flagship-col" style="padding:1rem 1.25rem; font-weight:800;">UV DTF stickers</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Vinyl stickers</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Regular DTF</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Best for</td>
            <td class="flagship-col">Hard and curved surfaces</td>
            <td>Flat surfaces, labels</td>
            <td>Fabric and garments</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Heat press needed</td>
            <td class="flagship-col">No</td>
            <td>No</td>
            <td>Yes</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Look</td>
            <td class="flagship-col">3D, raised and glossy</td>
            <td>Flat</td>
            <td>Soft and flexible</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Fine details and full colour</td>
            <td class="flagship-col">Sharp</td>
            <td>Limited by cutting</td>
            <td>Sharp</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Ready to apply</td>
            <td class="flagship-col">Yes, pre-masked</td>
            <td>Needs cutting and weeding</td>
            <td>Needs pressing</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="reveal slide-up" style="margin-top:1.25rem;">
      <div style="display:inline-flex; align-items:center; gap:0.6rem; background:rgba(212,175,55,0.12); border:1px solid rgba(212,175,55,0.32); border-radius:999px; padding:0.5rem 1.35rem; font-weight:700; color:#FFD76A; font-size:1.02rem;">
        <span style="color:#D4AF37; font-size:1.15rem;">★</span>
        <span>Branding on hard goods without heat presses? UV DTF is the undisputed winner.</span>
      </div>
    </div>
  </div>
</section>

<!-- UV DTF Stickers for Glass, Tumblers, Acrylic & Packaging -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:850px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Applications</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
        UV DTF Stickers for Glass, Tumblers, Acrylic &amp; Packaging - Where Quality Meets Flexibility
      </h2>
      <p class="section-subtitle" style="max-width:850px; margin:0; text-align:left; color:#CBD5E1; font-size:1.05rem; line-height:1.75;">
        We've engineered our UV DTF decals to stick exceptionally well to a wide range of materials. They're water-proof, scratch-proof and chemically stable - making them perfect for all sorts of commercial products like drinkware, gadgets and retail packaging.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.5rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
        <div>
          <h4 style="color:#F8FAFC; margin-bottom:0.5rem; font-size:1.15rem; font-weight:700;">Drinkware &amp; Bottles</h4>
          <p>
            Think stainless steel insulated tumblers, glass mason jars, ceramic mugs, and sports bottles. - or any other drinkware that you'd like to personalise.
          </p>
        </div>
      </div>

      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
        <div>
          <h4 style="color:#F8FAFC; margin-bottom:0.5rem; font-size:1.15rem; font-weight:700;">Electronics &amp; Gadgets</h4>
          <p>
            We've got you covered with laptop lids, smartphone cases, wireless power banks, tablets, and even audio gear.
          </p>
        </div>
      </div>

      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
        <div>
          <h4 style="color:#F8FAFC; margin-bottom:0.5rem; font-size:1.15rem; font-weight:700;">Acrylic &amp; Signage</h4>
          <p>
            Our decals work great on clear acrylic awards, corporate door signs, POS displays, trophy plates - in fact, any type of signage or award that you need to make an impact.
          </p>
        </div>
      </div>

      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
        <div>
          <h4 style="color:#F8FAFC; margin-bottom:0.5rem; font-size:1.15rem; font-weight:700;">Retail Packaging</h4>
          <p>
            And for retail packaging - we've got rigid gift boxes, cosmetic glass jars, metal tins, candle holders, wood boards all covered.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Who Turns to Printex for UV DTF Stickers -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Our Clients</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Who Turns to Printex for UV DTF Stickers
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.25rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Tumbler and drinkware brands who want a high-end, 3D logo on every single bottle they produce.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Companies that specialise in corporate gifting - branding mugs, diaries, power banks and welcome kits for businesses.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Packaging and cosmetic brands who decorate glass jars, tins and rigid boxes to give their products an extra wow factor.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Signage and award makers who need sharp graphics on acrylic and metal plates that grab the attention.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-5" style="grid-column:1 / -1;">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">And print shops and resellers who buy UV DTF printing in bulk and use it to brand products for their own customers.</p>
      </div>
    </div>
  </div>
</section>

<!-- The Benefits of Printing with UV DTF -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Advantages</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        The Benefits of Printing with UV DTF
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.35rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">No need for a heat press: decals can be applied cold, so you can brand objects that just can't be heated.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Premium 3D finish: the raised gloss varnish gives your designs a tactile, high-end feel that just can't be beaten.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Waterproof and scratch-resistant: our decals are made to withstand the daily knocks that come with being used.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Full colour on any design: whether you want photos, gradients or fine logos, you can print them all in full colour, without any extra costs.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-5">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Fast and easy to apply: it's a simple peel, stick, rub, lift process - no special skills or equipment needed.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-6">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">One-off or one thousand: you can start with a single sheet and scale up your production when you're ready.</p>
      </div>
    </div>
  </div>
</section>

<!-- How to Prepare Your Artwork for UV DTF Printing -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Prepress Guide</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        How to Prepare Your Artwork for UV DTF Printing
      </h2>
    </div>

    <div class="reveal slide-up" style="background:#111111; border:1px solid rgba(212,175,55,0.25); border-left:4px solid var(--primary-gold, #D4AF37); border-radius:14px; padding:2rem 2.25rem; max-width:920px; box-shadow:0 6px 20px rgba(15,23,42,0.04);">
      <p style="line-height:1.85; color:#CBD5E1; font-size:1.05rem; margin:0;">
        To get the best out of your decals, make sure your artwork is good to go. We need a transparent PNG or a vector file (AI, SVG or PDF) with fonts converted to outlines, at 300 DPI. Try to avoid super thin lines and teeny tiny text as these can get lost on a 3D finish. And if you want to add a gloss varnish to a specific part of your design, just let us know in the notes and we'll set it up. If you're not sure about your file, send it over and our team will check it out before we print it
      </p>
    </div>
  </div>
</section>

<!-- UV DTF Decals in Any Size - One Sheet to Bulk -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Order Scale</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        UV DTF Decals in Any Size - One Sheet to Bulk
      </h2>
    </div>

    <div class="reveal slide-up" style="background:#111111; border:1px solid rgba(212,175,55,0.25); border-radius:14px; padding:2rem 2.25rem; max-width:920px; box-shadow:0 6px 20px rgba(15,23,42,0.04);">
      <p style="margin-bottom:1.25rem; line-height:1.8; color:#CBD5E1; font-size:1.05rem;">
        Our UV DTF printing starts with a single sheet - so you can trial a design or order bulk decals for your whole product run. Send us your logo or artwork, along with the size and quantity you need, and we'll fit as many decals as we can onto each sheet to keep your costs down. Our rates start from ₹____ per sheet.
      </p>
      <p style="margin:0; line-height:1.8; color:#CBD5E1; font-size:1.05rem;">
        And, to make things even easier, every order comes with carrier tape pre-applied, so your customers just need to peel, stick and rub. If you're a regular customer, you'll qualify for better rates as you order more - so let us know your monthly requirements and we'll quote you accordingly.
      </p>
    </div>
  </div>
</section>

<!-- The Lowdown on UV DTF Printing at Printex -->
<section class="section section-navy" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem); background: radial-gradient(circle at 50% 10%, rgba(212, 175, 55, 0.16) 0%, transparent 60%), #080808;">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:740px; margin:0 auto clamp(2.5rem, 4vw, 3.5rem);">
      <div class="section-label" style="justify-content:center; color:var(--bright-gold, #FFD76A);">Workflow</div>
      <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:#FFFFFF; font-weight:800; line-height:1.2; margin-bottom:0.85rem;">
        The Lowdown on UV DTF Printing at Printex
      </h2>
      <p class="section-subtitle" style="color:#B8B1A3; font-size:1.05rem;">
        Get your design readywe've got our preferences - a nice transparent PNG or vector file, ideally 300 DPI, with fonts nice and outlined.
      </p>
    </div>

    <div class="process-grid" style="grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));">
      <div class="process-step-card reveal slide-up stagger-1">
        <div class="process-step-num">01</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 1</h4>
        <p class="process-step-desc">
          We take a look: before we give you a quote, we check the resolution, margins, and sheet layout to make sure everything is good to go.
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-2">
        <div class="process-step-num">02</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 2</h4>
        <p class="process-step-desc">
          Print &amp; Cure Magic: White base, colours &amp; gloss varnish get printed and cured with UV light - all in one go!
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-3">
        <div class="process-step-num">03</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 3</h4>
        <p class="process-step-desc">
          It's off to you: every sheet gets inspected, carefully packed and shipped off to anywhere in India from our Delhi studio.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Why We're the Ones to Trust for UV DTF Printing in Delhi -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Trust &amp; Reliability</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Why We're the Ones to Trust for UV DTF Printing in Delhi
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Free artwork check: We take a glance at your design before printing to make sure everything's good to print.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">We've got a sharp finish that will knock your socks off - bright colours with a raised, glossy varnish that really makes your decals pop.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">With us, you get quality that's consistentsame great results on your first order as you will on your repeat order.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">We're all about flexibilitywhether its one sheet or a whole production run, we've got you covered.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-5" style="grid-column:1 / -1;">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Our UV DTF stickers ship from our Delhi studio to your doorstep all over India.</p>
      </div>
    </div>
  </div>
</section>

<!-- How to Apply a UV DTF Sticker Like a Pro -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Application Instructions</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        How to Apply a UV DTF Sticker Like a Pro
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(210px, 1fr)); gap:1.5rem;">
      <div class="reveal slide-up stagger-1" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">01 / Clean</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 1</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Clean and clear: make sure the surface is dust, oil and moisture free - a quick wipe will do the trick.
        </p>
      </div>

      <div class="reveal slide-up stagger-2" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">02 / Peel</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 2</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Peel off the backing: remove the carrier backing and pop the decal in place.
        </p>
      </div>

      <div class="reveal slide-up stagger-3" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">03 / Rub</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 3</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Give it a rub: take a squeegee or your finger and gently press the decal in from the centre outwards.
        </p>
      </div>

      <div class="reveal slide-up stagger-4" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">04 / Lift</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 4</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Lift the clear film: peel away the top film slowly and carefully, leaving your decal looking great.
        </p>
      </div>

      <div class="reveal slide-up stagger-5" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">05 / Care</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 5</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Looking after your decal: wash by hand and avoid harsh scrubbing - its all about keeping it looking new for longer.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Direct Upload Card / CTA Section -->
<section class="section section-white" id="upload-uv" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
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

            <!-- UV DTF Estimated Print Summary Box -->
            <div class="dtf-calc-result-box" style="background:#111111; border:1.5px solid #40351E; border-radius:10px; padding:1.25rem; margin-top:1.25rem;">
              <div style="font-size:0.8rem; font-weight:800; color:#D4AF37; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem;">
                UV DTF Print Estimate Summary
              </div>

              <div class="dtf-calc-metrics">
                <div class="dtf-metric-item">
                  <span class="dtf-metric-label">Calculated Decal Area:</span>
                  <span class="dtf-metric-value" id="uvCalcAreaDisplay">—</span>
                </div>
                <div class="dtf-metric-item">
                  <span class="dtf-metric-label">Unit Rate</span>
                  <span class="dtf-metric-value" id="uvCalcUnitRateDisplay">₹299.00</span>
                </div>
                <div class="dtf-metric-item">
                  <span class="dtf-metric-label">Varnish Grade</span>
                  <span class="dtf-metric-value" id="uvCalcFinishDisplay" style="color:#4ade80; font-size:1rem;">Gloss 3D</span>
                </div>
              </div>

              <div style="display:flex; justify-content:space-between; align-items:baseline; padding-top:0.75rem; border-top:1px dashed #40351E;">
                <div>
                  <span style="font-weight:700; color:#F5F1E8; font-size:1.05rem;">Estimated Total:</span>
                  <div style="font-size:0.75rem; color:#B8B1A3;">(Estimated, incl. 18% GST)</div>
                </div>
                <div style="text-align:right;">
                  <span class="dtf-metric-value highlight-gold" id="uvCalcGrandTotalDisplay" style="font-size:1.6rem;">₹352.82</span>
                </div>
              </div>

              <div style="background:rgba(212,175,55,0.08); border:1px solid #40351E; border-radius:6px; padding:0.6rem 0.85rem; margin-top:0.85rem; font-size:0.78rem; color:#FFD76A;">
                ℹ UV DTF decals are supplied pre-masked with carrier tape for peel, stick, and rub application.
              </div>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Target Substrate / Material <span class="required">*</span></label>
                <select name="target_surface" class="form-control"
                        data-enable-other="true"
                        data-other-name="custom_target_surface"
                        data-other-label="Specify Custom Substrate"
                        data-other-placeholder="e.g. Anodized Aluminum, Powder-Coated Metal, Raw Timber">
                  <option value="glassware" selected>Glassware / Bottles</option>
                  <option value="tumblers">Stainless Steel Tumblers / Mugs</option>
                  <option value="acrylic">Clear Acrylic / Plastic Packaging</option>
                  <option value="wood_metal">Finished Wood / Metal Plates</option>
                  <option value="general_hard">General Hard Surface</option>
                  <option value="other">Other (Specify Custom Substrate)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Quantity of Decals <span class="required">*</span></label>
                <input type="number" name="quantity" id="uvQtyInput" class="form-control" value="1" min="1" oninput="runUvCalculation()" required>
              </div>

              <div class="form-group">
                <label class="form-label">Decal Width (Inches)</label>
                <input type="number" step="0.1" name="width_inches" id="uvWidthInput" class="form-control" value="3.5" min="0.5" oninput="runUvCalculation()">
              </div>

              <div class="form-group">
                <label class="form-label">Decal Height (Inches)</label>
                <input type="number" step="0.1" name="height_inches" id="uvHeightInput" class="form-control" value="3.5" min="0.5" oninput="runUvCalculation()">
              </div>

              <div class="form-group">
                <label class="form-label">Your Name <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control" placeholder="e.g. Priya Sharma" required>
              </div>

              <div class="form-group">
                <label class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" class="form-control" placeholder="e.g. priya@studio.com" required>
              </div>

              <div class="form-group">
                <label class="form-label">Phone / WhatsApp <span class="required">*</span></label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="e.g. +91 93540 28199" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label">Special Finish or Spot Varnish Notes</label>
                <textarea name="customer_notes" class="form-control" placeholder="Target substrate details (glass, metal, wood, acrylic), spot varnish requirements, or delivery deadline..."></textarea>
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
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
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
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container container-narrow text-center reveal slide-up">
    <div class="section-label" style="justify-content:center; color:var(--crimson, #DC2626);">Location</div>
    <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
      UV DTF Printing in Delhi: Visit Our Studio
    </h2>
    <p style="font-size:1.08rem; color:#CBD5E1; margin-bottom:1.5rem; line-height:1.65;">
      Order UV DTF stickers and decals from our Delhi studio.
    </p>
    <div style="background:#111111; border:1px solid rgba(212,175,55,0.3); border-top:3px solid var(--primary-gold, #D4AF37); border-radius:14px; padding:1.75rem 2rem; margin:1.5rem auto; max-width:650px; box-shadow:0 8px 24px rgba(15,25,45,0.06);">
      <p style="font-weight:700; color:#FFD76A; font-size:1.05rem; margin-bottom:0.5rem; line-height:1.5;">
        Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Delhi 110059
      </p>
      <p style="margin:0; color:#CBD5E1; font-size:0.95rem;">
        Call / WhatsApp:
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
function runUvCalculation() {
  const w = parseFloat(document.getElementById('uvWidthInput')?.value) || 3.5;
  const h = parseFloat(document.getElementById('uvHeightInput')?.value) || 3.5;
  const qty = Math.max(1, parseInt(document.getElementById('uvQtyInput')?.value, 10) || 1);

  const sqInches = w * h;
  const totalSqIn = sqInches * qty;

  const areaDisp = document.getElementById('uvCalcAreaDisplay');
  if (areaDisp) {
    areaDisp.textContent = `${w.toFixed(1)}" x ${h.toFixed(1)}" (${sqInches.toFixed(1)} sq in / decal)`;
  }

  let ratePerDecal = 45;
  if (sqInches > 25) ratePerDecal = 95;
  else if (sqInches > 12) ratePerDecal = 65;
  else if (sqInches > 6) ratePerDecal = 45;
  else ratePerDecal = 25;

  let discountPct = 0;
  if (qty >= 100) discountPct = 25;
  else if (qty >= 50) discountPct = 15;
  else if (qty >= 20) discountPct = 8;

  const finalUnitPrice = ratePerDecal * (1 - (discountPct / 100));
  const subtotal = finalUnitPrice * qty;
  const gst = subtotal * 0.18;
  const grandTotal = subtotal + gst;

  const unitDisp = document.getElementById('uvCalcUnitRateDisplay');
  if (unitDisp) unitDisp.textContent = '₹' + finalUnitPrice.toFixed(2);

  const grandDisp = document.getElementById('uvCalcGrandTotalDisplay');
  if (grandDisp) grandDisp.textContent = '₹' + grandTotal.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
  runUvCalculation();

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
