<?php
/**
 * OrderPrintex - T-Shirt Printing Service Page
 */

$pageTitle = "Delhi's Custom T-Shirt Printing Studio | Printex";
$pageDesc  = "We take your brand from concept to production, printing vibrant high-density graphics onto those comfy 180-240 GSM bio-washed combed cotton tees with the perfect oversized fit, or the sleek athletic wear polyester.";

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 't-shirt-printing'");
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Apparel Production &amp; Merchandise - Made to Order</span>
      </div>
      <h1 class="page-intro-title">Delhi's Custom T-Shirt Printing Studio - Where Art Meets Fabric</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        We take your brand from concept to production, printing vibrant high-density graphics onto those comfy 180-240 GSM bio-washed combed cotton tees with the perfect oversized fit, or the sleek athletic wear polyester. Whether you're just starting out or producing in bulk for your company, we've got you covered.
      </p>
    </div>
  </div>
</section>

<!-- Like Retail, But Better -->
<section class="section section-white">
  <div class="container">
    <div class="service-overview-grid">
      <div class="service-overview-content reveal slide-right">
        <div class="section-label">Garment Standards - What Makes Our T-Shirts So Special</div>
        <h2>Like Retail, But Better - Custom T-Shirt Printing on Premium Bio-Washed Fabrics</h2>
        <p>
          At OrderPrintex, we bridge that gap between making a few special items for your event and churning out a thousand corporate tees in a week. Using the latest digital technology we pair with super-soft combed cotton, we bring you shirts that look and feel like the real deal.
        </p>
        <p>
          Our inks are blended so they stay soft, breathable and hold their colour through even the heaviest washing. And they don't crack or peel - they're built to last. Whether it's 10 tees for the wedding party, or 5,000 corporate dry-fit jerseys for that marathon, we ensure spot on colour matching and speedy dispatch from our Delhi facility.
        </p>
        <p>
          We handle everything, from custom t-shirt printing for a wedding party to a major branding push for your company. One stop shop, every time.
        </p>

        <div class="service-cta-group">
          <a href="#upload-tshirt" class="btn service-btn-primary">Hit us up for a T-Shirt quote</a>
          <a href="<?= url('products.php') ?>" class="btn service-btn-secondary">Check out our Garment styles</a>
        </div>
      </div>

      <div class="service-overview-media reveal slide-left">
        <div class="service-media-frame">
          <img src="<?= url('assets/images/custom_tshirt_apparel.jpg') ?>" alt="Finished Custom Printed T-Shirts" loading="lazy">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Packages Section -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Packages</div>
      <h2>T-Shirt Printing Packages for Every Occasion - Because Life's Too Short For Boring Tees</h2>
      <p class="section-subtitle" style="max-width:850px; margin:0 auto 2rem; text-align:left;">
        Choose from our flexible order quantities, swanky packaging and an array of tees to make your day - or week - special.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.25rem;">
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Brand Merch: Heavy streetwear tees that feel good, look great and have those all important hash tags and labels.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Couple &amp; Family Tees: Get matching his and hers tees, tasteful anniversary gifts and some awesome typography to celebrate life's milestones.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Wedding &amp; Event Tees: We've got coordinated squad tees, college fest tees, reunion tees and sporty team kits to boot.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-size:1.1rem; line-height:1.2; font-weight:800;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Corporate Polos: High quality, comfy pique cotton polos with the perfect embroidered or DTF chest logo to show your company pride.</p>
      </div>
    </div>
  </div>
</section>

<!-- Fabrics Table Section -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Fabric Guide</div>
      <h2>T-Shirt Fabrics and Styles You Can Choose</h2>
    </div>

    <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); overflow-x:auto; margin-bottom:1.25rem; box-shadow:var(--shadow-card);">
      <table class="compare-table" style="width:100%; min-width:600px;">
        <thead>
          <tr style="background:var(--bg-surface-elevated); border-bottom:2px solid var(--border-color);">
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Garment</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Fabric</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Best for</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Round neck tee</td>
            <td>180 GSM, 100% bio-washed cotton</td>
            <td>Everyday wear, events, promotions</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Oversized tee</td>
            <td>240 GSM heavyweight cotton</td>
            <td>Streetwear brands and merchandise</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Polo shirt</td>
            <td>Cotton pique</td>
            <td>Corporate staff and uniforms</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Dry-fit tee</td>
            <td>Athletic polyester</td>
            <td>Sports teams, marathons, gym wear</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p style="font-weight:600; color:var(--text-secondary); font-size:0.95rem; margin:0;">
      All t-shirts use 100% super-combed bio-washed ringspun cotton with double-needle stitched neck and sleeves.
    </p>
  </div>
</section>

<!-- DTF vs Screen Printing Table Section -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Print Method</div>
      <h2>Why We Use DTF T-Shirt Printing for Custom Orders</h2>
      <p class="section-subtitle" style="max-width:850px; margin:0 auto 1.5rem; text-align:left;">
        DTF t-shirt printing prints your design on film first and presses it onto the garment, so even full-colour artwork with gradients and fine details comes out sharp. There are no screens to make and no extra charge for every colour, which keeps small and mixed-design orders affordable.
      </p>
    </div>

    <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); overflow-x:auto; margin-bottom:1.25rem; box-shadow:var(--shadow-card);">
      <table class="compare-table" style="width:100%; min-width:600px;">
        <thead>
          <tr style="background:var(--bg-surface-elevated); border-bottom:2px solid var(--border-color);">
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Feature</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">DTF t-shirt printing</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Screen printing</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Set-up</td>
            <td>None</td>
            <td>Screens for every colour</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Many colours</td>
            <td>Same price</td>
            <td>Costs more</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Small orders</td>
            <td>Great</td>
            <td>Expensive</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Feel</td>
            <td>Soft and flexible</td>
            <td>Soft</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p style="margin-top:1.25rem; font-weight:600;">
      <a href="<?= service_url('dtf-printing') ?>" style="color:var(--primary-gold); text-decoration:underline;">
        Want to know how the transfers are made? Read about our DTF printing process.
      </a>
    </p>
  </div>
</section>

<!-- Bulk T-Shirt Printing -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Bulk Orders</div>
      <h2>Bulk T-Shirt Printing in Delhi for Companies, Events and Brands</h2>
    </div>

    <div style="max-width:850px; line-height:1.8; color:var(--text-secondary); font-size:1.05rem;">
      <p style="margin-bottom:1.25rem;">
        Planning a team launch, a college fest or a staff uniform drive? Our bulk t-shirt printing keeps colours consistent from the first piece to the last, and the rate per piece comes down as your quantity grows. Share your total quantity with a size breakdown and your colour preferences, and we send a clear quote.
      </p>
      <p>
        Corporate t-shirt printing is packed in bulk and dispatched on time, with polos and dry-fit jerseys available for staff and sports teams. For brand merchandise, we can add neck label tags and individual poly-bags so your tees arrive ready to sell.
      </p>
    </div>
  </div>
</section>

<!-- Logo T-Shirt Printing -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Placement Options</div>
      <h2>Logo T-Shirt Printing for Teams, Startups and Events</h2>
      <p class="section-subtitle" style="max-width:850px; margin:0 auto 1.5rem; text-align:left;">
        Logo t-shirt printing is our most popular order. Choose where your design goes:
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.25rem; margin-bottom:1.5rem;">
      <div style="display:flex; align-items:flex-start; gap:0.85rem; padding:1.25rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem;">Front chest (A4)</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:0.85rem; padding:1.25rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem;">Front chest and full back (A3)</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:0.85rem; padding:1.25rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem;">Left chest pocket only</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:0.85rem; padding:1.25rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem;">Custom placements on sleeve or neckline</p>
      </div>
    </div>

    <p style="color:var(--text-secondary); line-height:1.7; font-size:1.02rem; margin:0;">
      Send your logo as a transparent PNG at 300 DPI, or as a vector file, and we will place it exactly where you want it.
    </p>
  </div>
</section>

<!-- Occasions Section -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Occasions</div>
      <h2>Custom T-Shirt Printing for Weddings, Events, Colleges and Companies</h2>
      <p class="section-subtitle" style="max-width:850px; margin:0 auto 1.5rem; text-align:left;">
        Every occasion needs its own look, and custom t-shirt printing makes a group feel like a team. Here is what our customers order most:
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.25rem;">
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Wedding squad tees for the bride, groom and friends</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">College fest and society batches with names and logos</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Corporate team-building, offsite and staff appreciation tees</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Sports tournaments, marathons and gym events in dry-fit</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Startup launches, brand drops and merchandise runs</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; background:rgba(212,175,55,0.03); border:1px solid rgba(212,175,55,0.18); border-left:3px solid var(--primary-gold); border-radius:var(--radius-sm);">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.1rem; line-height:1.2;">•</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.5;">Family reunions, birthdays and anniversary sets</p>
      </div>
    </div>
  </div>
</section>

<!-- Print Size and Placement Guide -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Placement Guide</div>
      <h2>Print Size and Placement Guide for Custom T-Shirt Printing</h2>
    </div>

    <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); overflow-x:auto; margin-bottom:1.25rem; box-shadow:var(--shadow-card);">
      <table class="compare-table" style="width:100%; min-width:600px;">
        <thead>
          <tr style="background:var(--bg-surface-elevated); border-bottom:2px solid var(--border-color);">
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Placement</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Typical size</th>
            <th style="padding:1rem 1.25rem; color:var(--primary-gold); font-weight:800;">Best for</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Front chest</td>
            <td>A4</td>
            <td>Large front graphics, event tees</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Front chest plus full back</td>
            <td>A3</td>
            <td>Team names, numbers, brand drops</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Left chest pocket</td>
            <td>Small logo</td>
            <td>Polos and staff uniforms</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Custom placement</td>
            <td>Sleeve or neckline</td>
            <td>Detail prints and branding</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p style="font-weight:600; color:var(--text-secondary); font-size:0.95rem; margin:0;">
      Not sure what fits your design? Tell us the garment and we will suggest a placement.
    </p>
  </div>
</section>

<!-- How Custom T-Shirt Printing Works at Printex -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Production Workflow</div>
      <h2>How Custom T-Shirt Printing Works at Printex</h2>
    </div>

    <div class="service-specs-grid" style="grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));">
      <div class="timeline-step reveal slide-up stagger-1">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 1</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          1. Get Your Design and Details to us: send across your artwork - and not forgetting to let us know which t-shirt you're after, how you'd like it printed and how many you need.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-2">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 2</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          2. We work up a tailored quote: check over your design and send you a customised price breakdown with all the sizes and costs laid out in black and white.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-3">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 3</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          3. We get to work on your order: your custom t-shirt design gets printed on high-quality DTF and then pressed onto our pre-shrunk t-shirts.
        </p>
      </div>

      <div class="timeline-step reveal slide-up stagger-4">
        <h4 style="color:var(--primary-gold); margin-bottom:0.5rem;">Step 4</h4>
        <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.6;">
          4. We check them over and get them in the post: every single t-shirt gets a quality check, they're then packaged up in bulk and sent out to all corners of India.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- How to Get Your Design Ready -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Prepress Guide</div>
      <h2>How to Get Your Design Ready for Our Custom T-Shirt Printing</h2>
    </div>

    <div style="max-width:850px; line-height:1.8; color:var(--text-secondary); font-size:1.05rem;">
      <p>
        Don't upload a random .jpg, come prepared with a transparent PNG at 300 DPI, or a vector file (like an Adobe Illustrator file) in AI, SVG or PDF format. Make sure any fonts have been knocked up into outlines and steer clear of super thin lines on dark coloured tees - and don't forget to mention your size breakdown (XS to 3XL, for example) and any colour preferences you might have, so we can get it spot on from the get go.
      </p>
    </div>
  </div>
</section>

<!-- How to Keep Your Printed T-Shirts Looking their Best -->
<section class="section section-white">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">Garment Care</div>
      <h2>How to Keep Your Printed T-Shirts Looking their Best</h2>
    </div>

    <div style="max-width:850px; line-height:1.8; color:var(--text-secondary); font-size:1.05rem;">
      <p>
        When you're washing your custom tees, try keeping them on the inside out in either cold or warm water, and steer clear of bleach altogether. Don't iron straight onto the print either - follow these simple tips and your custom t-shirt printing will stay looking its best for longer.
      </p>
    </div>
  </div>
</section>

<!-- Why Should You Choose Printex for Your T-Shirt Printing in Delhi? -->
<section class="section section-ivory">
  <div class="container">
    <div class="section-header reveal slide-up">
      <div class="section-label">The Printex Advantage</div>
      <h2>Why Should You Choose Printex for Your T-Shirt Printing in Delhi?</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.75rem; padding:1.25rem 0;">
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">We only use the best fabrics: pre-shrunk, bio-washed combed cotton tees with double-needle stitching for a top-notch finish.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">Our soft, stretchy prints: are made to move with you, staying put even after multiple washes.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">We can print in any quantity you need: from a handful to thousands - we can make it happen.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">We match colours to perfection: our colour matching is so tight you'll get the same results from each and every t-shirt.</p>
      </div>
      <div style="display:flex; align-items:flex-start; gap:1rem; grid-column:1 / -1;">
        <span style="color:var(--primary-gold); font-weight:800; font-size:1.25rem; line-height:1;">✓</span>
        <p style="margin:0; font-weight:600; color:var(--text-primary); font-size:1rem; line-height:1.6;">We dispatch across India: we print and package up your custom tees in our Delhi studio, then get them shipped out to you.</p>
      </div>
    </div>
  </div>
</section>

<!-- Frequently Asked Questions -->
<section class="section section-white">
  <div class="container container-narrow">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Common Queries</div>
      <h2>Some Questions We Get Asked About Custom T-Shirt Printing a Lot</h2>
    </div>

    <div class="faq-accordion">
      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>1. What's the minimum order for custom t-shirt printing?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          You can order as few as 10 t-shirts - and we can print thousands if you need that many.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>2. How much does it cost to have t-shirts printed?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          It all depends on what type of tee you choose, where you want the print placed and how many you're after. Just share your design and numbers with us and we'll get back to you with a clear quote.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>3. Do you supply the t-shirts to print on?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yep, we print on our own premium cotton and polyester tops, so you don't need to come looking for blanks elsewhere.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>4. What types of t-shirt fabrics can I choose from?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          We've got 180 GSM round neck cotton, 240 GSM heavyweight oversized tees, cotton pique polos and dry-fit athletic polyester.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>5. Are DTF prints washable?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes they are. Our prints are super soft, stretchy and don't crack or peel after multiple home machine washes.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>6. Can I order different designs and sizes together?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          No problem. Just share a size breakdown and any colour preferences you might have, and we'll get your mixed order sorted.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>7. Can I get a logo printed on polo shirts?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes. We can print a DTF chest logo on corporate polos, and we also do embroidery options if you need that sort of thing.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>8. Can I print names and numbers on team jerseys?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes. Send us the list of names, numbers and sizes and we will set up your team kit.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>9. How long does custom t-shirt printing take?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Turnaround depends on quantity and design. Share your deadline and we will confirm the timeline in your quote. [timeline confirm karo]
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>10. Do you deliver all over India?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Yes, we dispatch bulk and corporate orders to all major cities and towns.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" style="padding:1.25rem 1.5rem; font-weight:700; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
          <span>11. Where can I get t-shirt printing near me in Delhi?</span>
          <span class="faq-icon" style="color:var(--primary-gold); font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer">
          Visit our studio at Chanakya Palace, Pankha Road, or send your design online and we will deliver to you.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Direct Upload / Order Form -->
<section class="section section-ivory" id="upload-tshirt">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Direct Upload Studio</div>
      <h2>Get a Custom T-Shirt Printing Quote</h2>
      <p class="section-subtitle">
        Upload your front or back graphic and choose your quantities and sizes for a direct factory quote.
      </p>
    </div>

    <?= render_flash() ?>

    <div class="upload-card reveal scale-in artwork-upload-component">
      <form action="<?= url('api/submit-enquiry.php') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="service_name" value="T-Shirt Printing">

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
              <div class="dropzone-title">Upload T-Shirt Print Graphic</div>
              <div class="dropzone-hint">Transparent PNG preferred, 300 DPI (Max 25 MB)</div>
              <button type="button" class="btn btn-sm btn-outline-gold">Choose File</button>
            </div>

            <div class="file-preview-area"></div>

            <div style="background:var(--bg-surface-elevated); padding:1.25rem; border-radius:var(--radius-sm); margin-top:1.25rem; border:1px solid var(--border-color);">
              <h5 style="color:var(--text-primary); margin-bottom:0.35rem; font-size:0.95rem;">Fabric &amp; Print Assurance</h5>
              <p style="font-size:0.825rem; color:var(--text-secondary); margin:0;">
                All t-shirts use 100% super-combed bio-washed ringspun cotton with double-needle stitched neck and sleeves.
              </p>
            </div>
          </div>

          <div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Garment Silhouette / Fabric <span class="required">*</span></label>
                <select name="material_options" class="form-control"
                        data-enable-other="true"
                        data-other-name="custom_material_options"
                        data-other-label="Specify Custom Garment Fabric / Silhouette"
                        data-other-placeholder="e.g. 280 GSM French Terry Fleece Hoodie, Bamboo Cotton Tank Top">
                  <option value="180 GSM Round Neck Cotton">180 GSM Round Neck (100% Bio-Washed Cotton)</option>
                  <option value="240 GSM Oversized Streetwear">240 GSM Heavyweight Oversized Tee</option>
                  <option value="Polo Collar Cotton Pique">Polo Collar Shirt (Cotton Pique)</option>
                  <option value="Dry-Fit Sports Polyester">Dry-Fit Athletic Polyester</option>
                  <option value="other">Other (Specify Custom Garment)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Total Quantity <span class="required">*</span></label>
                <input type="number" name="quantity" class="form-control" value="25" min="1" required>
              </div>

              <div class="form-group">
                <label class="form-label">Print Placement</label>
                <select name="print_placement" class="form-control"
                        data-enable-other="true"
                        data-other-name="custom_print_placement"
                        data-other-label="Specify Custom Print Placement"
                        data-other-placeholder="e.g. Both Sleeves + Back Collar Tag + Side Hem">
                  <option value="Front Chest (A4)">Front Chest (A4)</option>
                  <option value="Front + Back Large">Front Chest + Full Back (A3)</option>
                  <option value="Left Chest Pocket Only">Left Chest Pocket Only</option>
                  <option value="other">Other (Specify Custom Placements)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Your Name <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control" placeholder="Your Name" required>
              </div>

              <div class="form-group">
                <label class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" name="customer_email" class="form-control" placeholder="Your Email" required>
              </div>

              <div class="form-group">
                <label class="form-label">Phone / WhatsApp <span class="required">*</span></label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="+91 93540 28199" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label">Size Breakdown &amp; Color Preferences</label>
                <textarea name="customer_notes" class="form-control" placeholder="e.g. 10 Medium Black, 15 Large White, 5 XL Maroon..."></textarea>
              </div>
            </div>

            <div style="margin-top:2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
              <small style="color:var(--slate-subtle); font-size:0.825rem;">
                ✓ Pre-shrunk cotton &bull; High opacity DTF &bull; Bulk packing
              </small>
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Request T-Shirt Quotation</span> &rarr;
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Visit Studio Section -->
<section class="section section-white">
  <div class="container container-narrow text-center reveal slide-up">
    <div class="section-label" style="justify-content:center;">Location</div>
    <h2 style="margin-bottom:1rem;">T-Shirt Printing in Delhi: Visit Our Studio</h2>
    <p style="font-size:1.1rem; color:var(--text-primary); margin-bottom:1rem;">
      Order custom t-shirts from our Delhi studio.
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
