<?php
/**
 * OrderPrintex - Corporate Printing Service Page
 */

$pageTitle = "Corporate Printing in Delhi | Promotional Merchandise & Corporate Gifts – Printex";
$pageDesc  = "Corporate printing in Delhi: custom corporate merchandise, employee onboarding kits, branded mugs, polos, and promotional gifts with pan-India delivery.";

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'corporate-printing'");
?>

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Corporate Printing in Delhi</span>
      </div>
      <h1 class="page-intro-title">Bringing Your Brand to Life in Delhi with Custom Merchandise</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        We handle all sorts of corporate printing in Delhi: employee onboarding kits, promotional items, branded drinkware and uniform sets. With our help, your team will look the part, your clients will remember you and your brand identity will stay strong. Fast door-to-door delivery all over India, straight from our studio in Delhi.
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
          <img src="<?= url('assets/images/corporate_swag_kit.jpg') ?>" alt="Corporate Printing Production Run" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>Corporate Swag &amp; Gifting</span>
          </div>
        </div>
      </div>

      <div class="tech-zigzag-content">
        <div class="page-intro-eyebrow" style="margin-bottom:0.5rem;">
          <span class="page-intro-dot"></span>
          <span>One Vendor, End-to-End Solutions</span>
        </div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          Your Brand on Every Desk, Bag and Bottle - and Loving It
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1;">
          At Printex, we've picked up a thing or two about corporate printing in Delhi that really makes companies look the part. Big corporates, fast-growth startups, agencies and HR teams all come to us to turn plain products into branded merchandise that people are actually happy to carry around. We get orders shipped to every major city and all the smaller ones too.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          We take care of corporate merchandise printing from start to finish: matching your Pantone colours, double-checking the pre-press proofing, laser etching, UV DTF decal bonding and even packing each item individually just the way you want them. You deal with one team instead of trying to juggle with five different vendors, and your logo and colours stay spot on every time.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          Need some ideas for corporate gifts in Delhi for your clients, or bulk corporate gifting across India for a special occasion? We're happy to scale up from a small team order to thousands of pieces without losing the finish. Every order comes with an official GST invoice, competitive wholesale pricing and fast door-to-door delivery to your office.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          Want a quote or some inspiration? Head to the bottom of this page to send us your logo, or browse our gallery to see what we've been up to recently.
        </p>

        <div class="service-cta-group" style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1.5rem;">
          <a href="#upload-corporate" class="btn btn-primary btn-lg">
            <span>Send us a Request Corporate Proposal</span> &rarr;
          </a>
          <a href="<?= url('contact.php') ?>" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
            <span>Schedule a Consultation with our team</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Our Core Corporate Print Categories -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:760px; margin:0 auto 2.5rem;">
      <div class="section-label" style="justify-content:center; color:var(--crimson, #DC2626);">Catalog Offerings</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Our Core Corporate Print Categories
      </h2>
      <p class="section-subtitle" style="color:#CBD5E1; font-size:1.05rem;">
        Everything can be customised with your company logo, employee names and exact brand palette, just the way you want.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.5rem;">
      <div class="reveal slide-up stagger-1" style="background:#111111; border:1px solid rgba(212,175,55,0.25); border-top:3px solid var(--primary-gold, #D4AF37); border-radius:14px; padding:1.85rem; box-shadow:0 6px 20px rgba(15,23,42,0.04);">
        <h4 style="color:#F8FAFC; margin-bottom:0.75rem; font-size:1.15rem; font-weight:700;">1. Custom Ceramic Mugs - A Great Way to Start the Day</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.65; margin:0;">
          11oz and 15oz Grade AAA white and black ceramic mugs with vibrant, full-wrap, dishwasher-safe prints. Our custom branded mugs for corporate gifting are a solid choice for client thank-yous and festive hampers.
        </p>
      </div>

      <div class="reveal slide-up stagger-2" style="background:#111111; border:1px solid rgba(212,175,55,0.25); border-top:3px solid var(--primary-gold, #D4AF37); border-radius:14px; padding:1.85rem; box-shadow:0 6px 20px rgba(15,23,42,0.04);">
        <h4 style="color:#F8FAFC; margin-bottom:0.75rem; font-size:1.15rem; font-weight:700;">2. Onboarding Gift Kits - A Warm Welcome</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.65; margin:0;">
          A curated employee onboarding kit with a branded notebook, metallic stylus pen, vacuum bottle and polo, all neatly packed in a welcome box. Every welcome kit for new employees can be tailored to fit your company's role, department or office.
        </p>
      </div>

      <div class="reveal slide-up stagger-3" style="background:#111111; border:1px solid rgba(212,175,55,0.25); border-top:3px solid var(--primary-gold, #D4AF37); border-radius:14px; padding:1.85rem; box-shadow:0 6px 20px rgba(15,23,42,0.04);">
        <h4 style="color:#F8FAFC; margin-bottom:0.75rem; font-size:1.15rem; font-weight:700;">3. Uniforms and Polos - Team Spirit</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.65; margin:0;">
          We do corporate polo shirt printing, staff aprons and volunteer apparel with wash-resistant prints. Our corporate uniform printing keeps teams looking sharp, and branded hoodies for employees are always a hit for winter kits and team celebrations.
        </p>
      </div>

      <div class="reveal slide-up stagger-4" style="background:#111111; border:1px solid rgba(212,175,55,0.25); border-top:3px solid var(--primary-gold, #D4AF37); border-radius:14px; padding:1.85rem; box-shadow:0 6px 20px rgba(15,23,42,0.04);">
        <h4 style="color:#F8FAFC; margin-bottom:0.75rem; font-size:1.15rem; font-weight:700;">4. Event and Summit Giveaways - Memorable Swag</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.65; margin:0;">
          Conference tote bags, printed satin lanyards, metal name badges, keychains and tech accessories designed to get your brand noticed at exhibitions. When you need promotional merchandise in Delhi that turns heads, Printex is the team to turn to.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Which Corporate Merchandise Is Right for You? -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.25rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Comparison</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Which Corporate Merchandise Is Right for You?
      </h2>
    </div>

    <div class="elevated-compare-card reveal slide-up">
      <table class="compare-table" style="width:100%; min-width:700px;">
        <thead>
          <tr>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Feature</th>
            <th class="flagship-col" style="padding:1rem 1.25rem; font-weight:800;">Custom Mugs</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Onboarding Kits</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Uniforms &amp; Polos</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Event Giveaways</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Best for</td>
            <td class="flagship-col">Client gifts, desk use</td>
            <td>New joiners, HR</td>
            <td>Teams, staff, volunteers</td>
            <td>Summits, trade shows</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Typical items</td>
            <td class="flagship-col">Ceramic mugs</td>
            <td>Diary, pen, bottle, polo</td>
            <td>Polos, hoodies, aprons</td>
            <td>Totes, lanyards, badges</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Customisation</td>
            <td class="flagship-col">Full-wrap logo print</td>
            <td>Logo and name per kit</td>
            <td>Logo, name, role</td>
            <td>Logo, event name</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Reorder-friendly</td>
            <td class="flagship-col">Yes</td>
            <td>Yes</td>
            <td>Yes</td>
            <td>Yes</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Packaging</td>
            <td class="flagship-col">Gift box optional</td>
            <td>Welcome box included</td>
            <td>Individual or bulk</td>
            <td>Bulk packed</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="reveal slide-up" style="margin-top:1.25rem;">
      <div style="display:inline-flex; align-items:center; gap:0.6rem; background:rgba(212,175,55,0.12); border:1px solid rgba(212,175,55,0.32); border-radius:999px; padding:0.5rem 1.35rem; font-weight:700; color:#FFD76A; font-size:1.02rem;">
        <span style="color:#D4AF37; font-size:1.15rem;">★</span>
        <span>Looking for multi-product kits? We coordinate proofing, packaging, and delivery under one roof.</span>
      </div>
    </div>
  </div>
</section>

<!-- Promotional Merchandise in Delhi for Every Occasion -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-even is-visible">
      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--blue, #2563EB);">Versatility</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          Promotional Merchandise in Delhi for Every Occasion Where Quality Really Does Meet Flexibility
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="section-subtitle" style="max-width:850px; margin:0 auto 2rem; text-align:left; color:#CBD5E1; font-size:1.05rem; line-height:1.75;">
          No matter the occasion, our corporate printing in Delhi is designed to keep your brand looking top-notch and consistent. As a local partner in promotional merchandise in Delhi, we tailor the product to the moment:
        </p>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.25rem;">
          <div class="service-feature-card reveal slide-up stagger-1">
            <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
            <div>
              <h4 style="color:#F8FAFC; margin-bottom:0.4rem; font-size:1.1rem; font-weight:700;">Employee Onboarding Kits</h4>
              <p>Make every new hire feel valued from day one with a branded welcome box that includes their essentials.</p>
            </div>
          </div>

          <div class="service-feature-card reveal slide-up stagger-2">
            <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
            <div>
              <h4 style="color:#F8FAFC; margin-bottom:0.4rem; font-size:1.1rem; font-weight:700;">Conferences and Trade Shows</h4>
              <p>High-visibility tote bags, lanyards, pens, and desk swag that delegates take home and actually use.</p>
            </div>
          </div>

          <div class="service-feature-card reveal slide-up stagger-3">
            <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
            <div>
              <h4 style="color:#F8FAFC; margin-bottom:0.4rem; font-size:1.1rem; font-weight:700;">Festive and Milestone Gifting</h4>
              <p>Diwali hampers, anniversary gift boxes, and client appreciation sets tailored to your budget.</p>
            </div>
          </div>

          <div class="service-feature-card reveal slide-up stagger-4">
            <span style="color:var(--primary-gold, #D4AF37); font-size:1.35rem; line-height:1; font-weight:800;">•</span>
            <div>
              <h4 style="color:#F8FAFC; margin-bottom:0.4rem; font-size:1.1rem; font-weight:700;">Everyday Branded Office Essentials</h4>
              <p>Desk mats, coasters, ceramic mugs, and uniform apparel that reinforce brand pride day after day.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/branding_stationery.jpg') ?>" alt="Custom Corporate Branding & Stationery Sets" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>Branding &amp; Stationery Sets</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Who Turns to Printex for Their Corporate Printing in Delhi -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Our Partners</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Who Turns to Printex for Their Corporate Printing in Delhi
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.25rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Fast-growing tech startups that need onboarding kits and welcome packs as their teams expand.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Corporations that want premium promotional merchandise in Delhi to keep their brand top of mind with clients.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Marketing and event agencies looking for a reliable printing partner who can deliver bulk swag on time.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Human resources departments organising festive gifts, annual day swag and employee appreciation kits.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-5" style="grid-column:1 / -1;">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">•</span>
        <p style="font-weight:600;">Small business owners looking to step up their game with branded packaging and professional gifts.</p>
      </div>
    </div>
  </div>
</section>

<!-- The Benefits of Choosing Printex for Corporate Merchandise Printing -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Advantages</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        The Benefits of Choosing Printex for Corporate Merchandise Printing
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.35rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">One partner for everything: from mugs to polos to custom boxes, we handle the whole lot so you don't have to juggle vendors.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Spot-on colour matching: we take Pantone accuracy seriously, so your brand colours look right across every product.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Mockups before production: you get a digital 3D mockup of your order so you can review and sign off before we print.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Official GST invoicing: full B2B compliance, itemised billing and proper GST invoices with every order.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-5">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Bulk pricing: the more you order, the better the rates - helping you get the best value from your marketing budget.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-6">
        <span style="color:var(--primary-gold, #D4AF37); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Delivery across India: whether you need items delivered to a single Delhi office or multiple locations nationwide, we take care of it.</p>
      </div>
    </div>
  </div>
</section>

<!-- How to Prepare Your Logo for Corporate Printing -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Prepress Guide</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        How to Prepare Your Logo for Corporate Printing
      </h2>
    </div>

    <div class="reveal slide-up" style="background:#111111; border:1px solid rgba(212,175,55,0.25); border-left:4px solid var(--primary-gold, #D4AF37); border-radius:14px; padding:2rem 2.25rem; max-width:920px; box-shadow:0 6px 20px rgba(15,23,42,0.04);">
      <p style="line-height:1.85; color:#CBD5E1; font-size:1.05rem; margin:0;">
        For clean, sharp results, we suggest sending your logo as a vector file - AI, EPS, SVG or high-resolution PDF with all fonts outlined. If you have Pantone references, include those too so we can dial in the colours. High-res PNG or PSD files at 300 DPI work well for most products too. Not sure about your files? Just send what you have and our prepress team will check them over before we think about production.
      </p>
    </div>
  </div>
</section>

<!-- Corporate Printing in Delhi in Any Quantity - From Small Orders to Big -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Scale &amp; Capacity</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Corporate Printing in Delhi in Any Quantity - From Small Orders to Big
      </h2>
    </div>

    <div class="reveal slide-up" style="background:#111111; border:1px solid rgba(212,175,55,0.25); border-radius:14px; padding:2rem 2.25rem; max-width:920px; box-shadow:0 6px 20px rgba(15,23,42,0.04);">
      <p style="margin-bottom:1.25rem; line-height:1.8; color:#CBD5E1; font-size:1.05rem;">
        Whether you need a starter run of 25 welcome kits for a newly hired team or 5,000 promotional mugs for an upcoming industry conference, our setup scales smoothly. We offer stepped quantity discounts so that larger corporate printing runs deliver great value per piece, while keeping minimum order quantities reasonable for growing businesses.
      </p>
      <p style="margin:0; line-height:1.8; color:#CBD5E1; font-size:1.05rem;">
        Get in touch with your estimated quantities and product preferences, and we will put together an itemised quote with clear turnaround times.
      </p>
    </div>
  </div>
</section>

<!-- The Lowdown on Corporate Printing at Printex - The Inside Scoop -->
<section class="section section-navy" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem); background: radial-gradient(circle at 50% 10%, rgba(212, 175, 55, 0.16) 0%, transparent 60%), #080808;">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:740px; margin:0 auto clamp(2.5rem, 4vw, 3.5rem);">
      <div class="section-label" style="justify-content:center; color:var(--bright-gold, #FFD76A);">Workflow</div>
      <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:#FFFFFF; font-weight:800; line-height:1.2; margin-bottom:0.85rem;">
        The Lowdown on Corporate Printing at Printex - The Inside Scoop
      </h2>
    </div>

    <div class="process-grid" style="grid-template-columns:repeat(auto-fit, minmax(210px, 1fr));">
      <div class="process-step-card reveal slide-up stagger-1">
        <div class="process-step-num">01</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 1</h4>
        <p class="process-step-desc">
          Tell us what you need: give us the lowdown on products, quantity and occasion, and upload your logo or brand kit.
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-2">
        <div class="process-step-num">02</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 2</h4>
        <p class="process-step-desc">
          Check out the mockup: you get a digital 3D mockup of your order to show your team before we lift a finger.
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-3">
        <div class="process-step-num">03</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 3</h4>
        <p class="process-step-desc">
          Prepress and colour matching: we double-check your artwork, sizing and Pantone accuracy to get the print spot-on for your brand.
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-4">
        <div class="process-step-num">04</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 4</h4>
        <p class="process-step-desc">
          Getting it done and packed: your order gets printed, etched or bonded right here in our Delhi studio and then we pack it up with care.
        </p>
      </div>

      <div class="process-step-card reveal slide-up stagger-5">
        <div class="process-step-num">05</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 5</h4>
        <p class="process-step-desc">
          Delivery Time: it gets shipped door to door across India with a GST invoice no worries.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Why You Can Trust Printex for Corporate Printing in Delhi -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--crimson, #DC2626);">Trust &amp; Reliability</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Why You Can Trust Printex for Corporate Printing in Delhi
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Free artwork check - no surprises: we take a squint at your design before printing to make sure everything matches up.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-2">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Consistent, top-notch finish: it's always the same great result - whether it's your first order or your tenth.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-3">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Flexible orders: one kit or a full company run - we're happy to cover you.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-4">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Your dedicated B2B prepress manager: one point of contact for your whole project, no juggling around.</p>
      </div>
      <div class="service-feature-card reveal slide-up stagger-5" style="grid-column:1 / -1;">
        <span style="color:#16A34A; font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="font-weight:600;">Nationwide delivery: from our Delhi studio to your head office, wherever that may be.</p>
      </div>
    </div>
  </div>
</section>

<!-- Looking After Your Branded Swag -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Maintenance Guide</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Looking After Your Branded Swag
      </h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.75rem;">
      <div class="reveal slide-up stagger-1" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">Drinkware</div>
        <h4 style="color:#F8FAFC; margin-bottom:0.5rem; font-size:1.15rem; font-weight:700;">Mugs and bottles</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Mugs and bottles: a gentle hand wash is all you need to keep the print looking new, just avoid scrubbing too hard.
        </p>
      </div>

      <div class="reveal slide-up stagger-2" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">Apparel</div>
        <h4 style="color:#F8FAFC; margin-bottom:0.5rem; font-size:1.15rem; font-weight:700;">Polos and hoodies</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Polos and hoodies: wash them inside out in cold water and keep an eye on the iron - don't scorch the print with high heat.
        </p>
      </div>

      <div class="reveal slide-up stagger-3" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">Accessories</div>
        <h4 style="color:#F8FAFC; margin-bottom:0.5rem; font-size:1.15rem; font-weight:700;">Tote bags and lanyards</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Tote bags and lanyards: just clean off any spills with a soft cloth and the colours will stay bright.
        </p>
      </div>

      <div class="reveal slide-up stagger-4" style="background:#111111; border:1px solid rgba(212,175,55,0.22); border-radius:12px; padding:1.75rem; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
        <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold, #D4AF37); margin-bottom:0.65rem;">Warehousing</div>
        <h4 style="color:#F8FAFC; margin-bottom:0.5rem; font-size:1.15rem; font-weight:700;">Storage 101</h4>
        <p style="font-size:0.95rem; color:#CBD5E1; line-height:1.6; margin:0;">
          Storage 101: keep those gift kits in a cool, dry place till it's time to hand them out.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Quote Form Section -->
<section class="section section-white" id="upload-corporate" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Proposal Studio</div>
      <h2>Get an Instant Corporate Printing Quote</h2>
      <p class="section-subtitle">
        Upload your vector logo or brand kit with the product and quantity, and our team will send an itemised quotation. All corporate runs include a digital 3D mockup, official GST invoicing and optional luxury packaging.
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
              <input type="file" name="artwork_file" class="file-input" accept=".ai,.eps,.pdf,.psd,.svg,.png" style="display:none;">
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

            <div style="background:#111111; padding:1.25rem; border-radius:10px; margin-top:1.25rem; border:1.5px solid #40351E;">
              <div style="font-size:0.85rem; font-weight:700; color:#D4AF37; margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.06em;">
                Corporate Production Highlights
              </div>
              <small style="color:#B8B1A3; display:block; font-size:0.8rem; line-height:1.5;">
                ✓ Official GST Invoicing &bull; PAN-India Logistics &bull; Dedicated B2B Prepress Manager
              </small>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Product Category <span class="required">*</span></label>
                <select name="product_category" class="form-control"
                        data-enable-other="true"
                        data-other-name="custom_product_category"
                        data-other-label="Specify Custom Corporate Merchandise / Kit"
                        data-other-placeholder="e.g. Leather Executive Folders, Wireless Charging Desk Mats, Custom Metal Medals">
                  <option value="Executive Welcome Kit" selected>Executive Welcome / Onboarding Kits</option>
                  <option value="Drinkware & Mugs">Ceramic Mugs & Stainless Steel Bottles</option>
                  <option value="Corporate Apparel">Polo T-Shirts, Hoodies & Uniforms</option>
                  <option value="Event Giveaways">Lanyards, Badges & Tote Bags</option>
                  <option value="Packaging & Boxes">Custom Rigid Mailer Boxes</option>
                  <option value="Multiple Products">Combination / Multi-Product Order</option>
                  <option value="other">Other (Specify Custom Corporate Merchandise)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Estimated Quantity <span class="required">*</span></label>
                <input type="number" name="quantity" class="form-control" value="50" min="10" required>
              </div>

              <div class="form-group">
                <label class="form-label">Company Name <span class="required">*</span></label>
                <input type="text" name="company_name" class="form-control" placeholder="Company Name" required>
              </div>

              <div class="form-group">
                <label class="form-label">Contact Person <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control" placeholder="Contact Person" required>
              </div>

              <div class="form-group">
                <label class="form-label">Work Email <span class="required">*</span></label>
                <input type="email" name="customer_email" class="form-control" placeholder="work@company.com" required>
              </div>

              <div class="form-group">
                <label class="form-label">Phone / WhatsApp <span class="required">*</span></label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="+91 93540 28199" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label">Project Scope &amp; Target Delivery Date</label>
                <textarea name="customer_notes" class="form-control" placeholder="Describe the occasion, items desired, recipient count, and target in-hand date..."></textarea>
              </div>
            </div>

            <div style="margin-top:2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ Official GST Invoice &bull; Pre-production Proofing &bull; Express Metro Dispatch
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Request Corporate Proposal</span> &rarr;
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Frequently Asked Questions About Corporate Printing -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container container-narrow">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Common Queries</div>
      <h2>Frequently Asked Questions About Corporate Printing - We've Got Answers</h2>
    </div>

    <div class="faq-accordion">
      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>1. What is the minimum order size for corporate printing in Delhi?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Tell us the product and quantity and we'll confirm what works for you.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>2. How much will corporate merchandise set me back?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          It varies - type of product, quantity and finish all play a part. Just upload your logo and quantity and we'll send over a clear quote.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>3. Do you include GST invoices with your orders?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Absolutely - every corporate order comes with one.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>4. Which logo file should I upload?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          A vector file (AI, EPS, SVG or PDF) is the way to go. PSD and PNG files work too if they're under 50MB.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>5. Do I get to see a preview before you start printing?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          You bet - all corporate runs include a digital 3D mockup for your approval before we start production.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>6. Can I mix and match products in one order?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          You can - choose the combination option in the quote form and we'll take care of the rest.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>7. Can I personalise items with names and roles?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Sure thing - names, roles and departments can be added on kits, mugs and apparel.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>8. Do you offer gift wrapping or individual packaging?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes and yes - we've got individual gift packing and luxury packaging options available.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>9. Do you deliver corporate orders to all over India?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          You bet - we ship to all major cities and towns.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>10. What areas do you cover for corporate printing in Delhi?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Delhi NCR, Rani Bagh, Pitampura, Noida, Gurgaon and all the rest - we've got you covered.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>11. Can I place a repeat order for new joiners?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          We keep your design details on file so reorders stay consistent, no worries.
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
      Corporate Printing in Delhi: Visit Our Studio
    </h2>
    <p style="font-size:1.08rem; color:#CBD5E1; margin-bottom:1.5rem; line-height:1.65;">
      Order corporate merchandise from our Delhi studio.
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
