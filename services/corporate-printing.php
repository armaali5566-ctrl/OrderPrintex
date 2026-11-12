<?php
/**
 * OrderPrintex - Corporate Printing Service Page
 */

$pageTitle = "Bringing Your Brand to Life in Delhi with Custom Merchandise | Printex";
$pageDesc  = "Branded merchandise that your team and clients actually get excited to use - and keep on their desks, bags and bottles. From welcome kits to summit giveaways, we handle corporate printing in Delhi with squash colours, top-notch finishes and GST-ready billing - so your brand looks sharp and just the way you want it on every surface.";

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
      <h1 class="page-intro-title">Bringing Your Brand to Life in Delhi with Custom Merchandise</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Branded merchandise that your team and clients actually get excited to use - and keep on their desks, bags and bottles. From welcome kits to summit giveaways, we handle corporate printing in Delhi with squash colours, top-notch finishes and GST-ready billing - so your brand looks sharp and just the way you want it on every surface.
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
          <span>One Vendor, End-to-End Solutions</span>
        </div>
        <h2>Your Brand on Every Desk, Bag and Bottle - and Loving It</h2>
        <p>
          At Printex, we've picked up a thing or two about corporate printing in Delhi that really makes companies look the part. Big corporates, fast-growth startups, agencies and HR teams all come to us to turn plain products into branded merchandise that people are actually happy to carry around. We get orders shipped to every major city and all the smaller ones too.
        </p>
        <p>
          We take care of corporate merchandise printing from start to finish: matching your Pantone colours, double-checking the pre-press proofing, laser etching, UV DTF decal bonding and even packing each item individually just the way you want them. You deal with one team instead of trying to juggle with five different vendors, and your logo and colours stay spot on every time.
        </p>
        <p>
          Need some ideas for corporate gifts in Delhi for your clients, or bulk corporate gifting across India for a special occasion? We're happy to scale up from a small team order to thousands of pieces without losing the finish. Every order comes with an official GST invoice, competitive wholesale pricing and fast door-to-door delivery to your office.
        </p>
        <p>
          Want a quote or some inspiration? Head to the bottom of this page to send us your logo, or browse our gallery to see what we've been up to recently.
        </p>

        <div class="service-cta-group">
          <a href="#upload-corporate" class="btn service-btn-primary">Send us a Request Corporate Proposal</a>
          <a href="<?= url('contact.php') ?>" class="btn service-btn-secondary">Schedule a Consultation with our team</a>
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

<!-- Our Core Corporate Print Categories -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Catalog Offerings</div>
      <h2>Our Core Corporate Print Categories</h2>
      <p class="section-subtitle">
        Everything can be customised with your company logo, employee names and exact brand palette, just the way you want.
      </p>
    </div>

    <div class="service-specs-grid">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--navy); margin-bottom:0.5rem;">1. Custom Ceramic Mugs - A Great Way to Start the Day</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          11oz and 15oz Grade AAA white and black ceramic mugs with vibrant, full-wrap, dishwasher-safe prints. Our custom branded mugs for corporate gifting are a solid choice for client thank-yous and festive hampers.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--navy); margin-bottom:0.5rem;">2. Onboarding Gift Kits - A Warm Welcome</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          A curated employee onboarding kit with a branded notebook, metallic stylus pen, vacuum bottle and polo, all neatly packed in a welcome box. Every welcome kit for new employees can be tailored to fit your company's role, department or office.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--navy); margin-bottom:0.5rem;">3. Uniforms and Polos - Team Spirit</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          We do corporate polo shirt printing, staff aprons and volunteer apparel with wash-resistant prints. Our corporate uniform printing keeps teams looking sharp, and branded hoodies for employees are always a hit for winter kits and team celebrations.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--navy); margin-bottom:0.5rem;">4. Event and Summit Giveaways - Memorable Swag</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Conferences and trade shows need giveaways people remember. Our event giveaways printing covers custom printed tote bags and lanyards, metal badges and promotional sticker packs, with company logo printing on products done exactly to your brand guidelines.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Which Corporate Merchandise Is Right for You? -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Comparison</div>
      <h2>Which Corporate Merchandise Is Right for You?</h2>
    </div>

    <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); overflow-x:auto; margin-bottom:1.5rem; box-shadow:var(--shadow-card);">
      <table class="compare-table" style="width:100%; min-width:700px;">
        <thead>
          <tr style="background:var(--bg-surface-elevated); border-bottom:2px solid var(--border-color);">
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Feature</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Custom Mugs</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Onboarding Kits</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Uniforms &amp; Polos</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Event Giveaways</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Best for</td>
            <td>Client gifts, desk use</td>
            <td>New joiners, HR</td>
            <td>Teams, staff, volunteers</td>
            <td>Summits, trade shows</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Typical items</td>
            <td>Ceramic mugs</td>
            <td>Diary, pen, bottle, polo</td>
            <td>Polos, hoodies, aprons</td>
            <td>Totes, lanyards, badges</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Customisation</td>
            <td>Full-wrap logo print</td>
            <td>Logo and name per kit</td>
            <td>Logo, name, role</td>
            <td>Logo, event name</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Reorder-friendly</td>
            <td>Yes</td>
            <td>Yes</td>
            <td>Yes</td>
            <td>Yes</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Packaging</td>
            <td>Gift box optional</td>
            <td>Welcome box included</td>
            <td>Individual or bulk</td>
            <td>Bulk packed</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Promotional Merchandise in Delhi for Every Occasion -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Versatility</div>
      <h2>Promotional Merchandise in Delhi for Every Occasion Where Quality Really Does Meet Flexibility</h2>
      <p class="section-subtitle" style="max-width:850px; margin:0 auto 2rem; text-align:left;">
        No matter the occasion, our corporate printing in Delhi is designed to keep your brand looking top-notch and consistent. As a local partner in promotional merchandise in Delhi, we tailor the product to the moment:
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.25rem;">
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">New hire welcome: a thoughtful onboarding kit with a notebook, bottle and polo that they'll actually use</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Client appreciation: a show of gratitude with branded mugs and some nice premium stationery</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Festive gifting: a Diwali hamper or a year-end gift box - we've got that covered too</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Events and summits: tote bags, lanyards, badges and sticker packs in bulk and on time - no hassle</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm); grid-column:1 / -1;">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Team uniforms: polos, hoodies or staff aprons - we can even help with custom orders</p>
      </div>
    </div>
  </div>
</section>

<!-- Who Turns to Printex for Their Corporate Printing in Delhi -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Our Partners</div>
      <h2>Who Turns to Printex for Their Corporate Printing in Delhi</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.25rem;">
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">HR teams who want to make a lasting impression on the first day for new employees.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Marketing managers who need merchandise that genuinely reflects their brand.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Event planners who're looking for hassle-free giveaways in bulk and on time.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Startups and agencies who want premium branded gifts but don't have a huge budget.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm); grid-column:1 / -1;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Resellers and gifting companies who buy in bulk and then brand products for their own customers.</p>
      </div>
    </div>
  </div>
</section>

<!-- The Benefits of Choosing Printex for Corporate Merchandise Printing -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Advantages</div>
      <h2>The Benefits of Choosing Printex for Corporate Merchandise Printing</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.75rem; padding:1.25rem 0;">
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Colour accuracy is a priority: Pantone matching ensures your logo looks the exact same on every item.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">See it before you spend a single rupee: we give you a digital 3D mockup for your stakeholders to approve before we start making anything.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">One stop shop for all your needs: we can make mugs, kits, uniforms and giveaways from a single corporate printing in Delhi studio.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Easy billing for business: official GST invoices with every order for your records.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Finishing touches: we use top quality materials and do our best to give you crisp, long-lasting prints.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Great packaging too: we can do individual packing and even luxury packaging if you want it.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; grid-column:1 / -1;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">One order, hassle free repeat orders: same quality on your repeat order as your first.</p>
      </div>
    </div>
  </div>
</section>

<!-- How to Prepare Your Logo for Corporate Printing -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Prepress Guide</div>
      <h2>How to Prepare Your Logo for Corporate Printing</h2>
    </div>

    <div style="max-width:850px; line-height:1.8; color:var(--text-secondary); font-size:1.05rem;">
      <p>
        To get the best result send over a vector file such as AI, EPS or PDF with all fonts converted to outlines. We can also handle PSD and PNG files up to 50MB. If you've got brand guidelines or Pantone codes, share them with us too and try to avoid very thin lines and tiny text as they can get lost on smaller products. If you're not sure about your file, send it over and our team will take a look before we even think about production.
      </p>
    </div>
  </div>
</section>

<!-- Corporate Printing in Delhi in Any Quantity - From Small Orders to Big -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Scale &amp; Capacity</div>
      <h2>Corporate Printing in Delhi in Any Quantity - From Small Orders to Big</h2>
    </div>

    <div style="max-width:850px; line-height:1.8; color:var(--text-secondary); font-size:1.05rem;">
      <p>
        Whether it's a small team or the whole company, we can handle it. Send us your logo, the products you're interested in and the quantity you need, and we'll plan the production to keep your costs per piece as low as possible. We give regular clients the best rates as volumes grow, so tell us about your yearly gifting and uniform requirements and we'll give you a price that works for you.
      </p>
    </div>
  </div>
</section>

<!-- The Lowdown on Corporate Printing at Printex - The Inside Scoop -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Workflow</div>
      <h2>The Lowdown on Corporate Printing at Printex - The Inside Scoop</h2>
    </div>

    <div class="service-specs-grid" style="grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 1</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Tell us what you need: give us the lowdown on products, quantity and occasion, and upload your logo or brand kit.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 2</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Check out the mockup: you get a digital 3D mockup of your order to show your team before we lift a finger.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 3</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Prepress and colour matching: we double-check your artwork, sizing and Pantone accuracy to get the print spot-on for your brand.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 4</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Getting it done and packed: your order gets printed, etched or bonded right here in our Delhi studio and then we pack it up with care.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-5">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 5</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          Delivery Time: it gets shipped door to door across India with a GST invoice no worries.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Why You Can Trust Printex for Corporate Printing in Delhi -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Trust &amp; Reliability</div>
      <h2>Why You Can Trust Printex for Corporate Printing in Delhi</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.75rem; padding:1.25rem 0;">
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Free artwork check - no surprises: we take a squint at your design before printing to make sure everything matches up.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Consistent, top-notch finish: it's always the same great result - whether it's your first order or your tenth.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Flexible orders: one kit or a full company run - we're happy to cover you.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Your dedicated B2B prepress manager: one point of contact for your whole project, no juggling around.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; grid-column:1 / -1;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Nationwide delivery: from our Delhi studio to your head office, wherever that may be.</p>
      </div>
    </div>
  </div>
</section>

<!-- Looking After Your Branded Swag -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Maintenance Guide</div>
      <h2>Looking After Your Branded Swag</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.75rem; border-top:1px solid var(--border-color); padding-top:2.25rem;">
      <div class="reveal slide-up stagger-1">
        <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold); margin-bottom:0.65rem;">Drinkware</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem; font-size:1.1rem;">Mugs and bottles</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6; margin:0;">
          Mugs and bottles: a gentle hand wash is all you need to keep the print looking new, just avoid scrubbing too hard.
        </p>
      </div>
      <div class="reveal slide-up stagger-2">
        <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold); margin-bottom:0.65rem;">Apparel</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem; font-size:1.1rem;">Polos and hoodies</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6; margin:0;">
          Polos and hoodies: wash them inside out in cold water and keep an eye on the iron - don't scorch the print with high heat.
        </p>
      </div>
      <div class="reveal slide-up stagger-3">
        <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold); margin-bottom:0.65rem;">Accessories</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem; font-size:1.1rem;">Tote bags and lanyards</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6; margin:0;">
          Tote bags and lanyards: just clean off any spills with a soft cloth and the colours will stay bright.
        </p>
      </div>
      <div class="reveal slide-up stagger-4">
        <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--primary-gold); margin-bottom:0.65rem;">Warehousing</div>
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem; font-size:1.1rem;">Storage 101</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6; margin:0;">
          Storage 101: keep those gift kits in a cool, dry place till it's time to hand them out.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Quote Form Section -->
<section class="section section-ivory" id="upload-corporate">
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

            <div style="background:var(--bg-surface-elevated); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-color);">
              <div style="font-size:0.85rem; font-weight:700; color:var(--text-primary); margin-bottom:0.35rem;">
                Corporate Production Highlights
              </div>
              <small style="color:var(--text-muted); display:block; font-size:0.78rem; line-height:1.5;">
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

<!-- Frequently Asked Questions About Corporate Printing -->
<section class="section section-white">
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
<section class="section section-ivory">
  <div class="container container-narrow text-center reveal slide-up">
    <div class="section-label" style="justify-content:center;">Location</div>
    <h2 style="margin-bottom:1rem;">Corporate Printing in Delhi: Visit Our Studio</h2>
    <p style="font-size:1.1rem; color:var(--text-primary); margin-bottom:1rem;">
      Order corporate merchandise from our Delhi studio.
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
