<?php
/**
 * OrderPrintex - Custom Hoodie Printing Service Page
 */

require_once __DIR__ . '/../includes/functions.php';

$pageTitle = "Custom Hoodie Printing in Delhi | Heavyweight Fleece & Streetwear – Printex";
$pageDesc  = "Custom hoodie and sweatshirt printing in Delhi by OrderPrintex. Premium 280-450 GSM fleece, oversized pullovers, zip-ups, high-density DTF & screen printing with zero cracking.";
$canonical = service_url('custom-hoodie-printing');
$pageSchemaKey = 'services-custom-hoodie-printing';

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'custom-hoodie-printing'");
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Apparel Production &bull; Winterwear Studio</span>
      </div>
      <h1 class="page-intro-title">Custom Hoodie Printing in Delhi - Heavyweight Fleece &amp; Streetwear Standards</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Engineered for luxury streetwear labels, collegiate merchandise, and corporate winter uniforms. We print high-density, crack-resistant graphics onto premium 280 to 450 GSM brushed fleece and French terry hoodies with vibrant colors that flex naturally and survive 50+ wash cycles.
      </p>
    </div>
  </div>
</section>

<!-- Overview & Fabric Engineering -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-odd is-visible">
      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/custom_tshirt_apparel.jpg') ?>" alt="Custom Printed Heavyweight Hoodie Production" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>280–450 GSM Heavyweight Fleece</span>
          </div>
        </div>
      </div>

      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--bright-gold, #FFD76A);">Heavyweight Fabric Engineering</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          No Cracking, No Stiff Plasticky Feel — Prints Built to Stretch with Fleece
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1;">
          Printing on heavyweight hoodies requires far more technical care than standard t-shirts. The thick pile, fleece lining, and natural stretch of 320+ GSM cotton can cause inferior prints to split, feel like cardboard, or peel after a couple of machine washes.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          At OrderPrintex, we formulate our Direct-to-Film (DTF) transfers with high-elasticity polyurethane adhesives and premium pigment inks calibrated specifically for winterwear. Our pneumatic commercial presses bond the ink deep into the fabric weave, creating a soft, breathable hand that stretches and recovers effortlessly.
        </p>
        <p class="tech-desc" style="color:#CBD5E1;">
          Whether you supply your own blanks or choose from our catalog of pre-shrunk, bio-washed cotton-poly hoodies, we guarantee retail-ready edge sharpness, deep blacks, and brilliant colors across every piece.
        </p>

        <div class="service-cta-group" style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1.5rem;">
          <a href="<?= url('get-quote') ?>?service=custom-hoodie-printing" class="btn btn-primary btn-lg">
            <span>Get a Hoodie Quote</span> &rarr;
          </a>
          <a href="<?= url('products') ?>" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
            <span>Browse Hoodie Blanks</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Suitable Materials & Hoodie Styles -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Fabric Compatibility</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Hoodie Styles &amp; Fabric Blends We Custom Print
      </h2>
      <p style="color:#94A3B8; font-size:1.05rem; margin-top:0.5rem;">
        From luxury streetwear silhouettes to corporate team pullovers, we handle the entire spectrum of hoodie fabrics.
      </p>
    </div>

    <div class="service-feature-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem; margin-bottom:2rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">100% Combed Cotton Brushed Fleece</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Super-soft inner brushed nap with a tightly woven, lint-free exterior face. Ideal for high-end fashion brands requiring maximum comfort and crisp print definition (300–350 GSM).
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-2">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Heavyweight French Terry (380–450 GSM)</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Featuring an unbrushed loop interior and substantial weight, French terry gives hoodies an architectural, boxy drape favoured by modern urban streetwear labels.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-3">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Cotton-Poly Tech Fleece Blends</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          80/20 or 70/30 cotton-polyester blends engineered for zero shrinkage, high tensile durability, and athletic performance. Perfect for university clubs and sports teams.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-1">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Oversized &amp; Drop-Shoulder Pullovers</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Wide chest profiles, dropped shoulders, and relaxed sleeves designed for statement back prints and bold streetwear typography.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-2">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Classic Kangaroo-Pocket Pullovers</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Double-stitched front kangaroo pouch with seamless DTF transfer options right above or across the pocket seam for iconic collegiate looks.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-3">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Full-Zip &amp; Quarter-Zip Sweatshirts</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Premium brass or nylon zippers with laser-calibrated split-front printing or left-chest embroidery and oversized back artwork.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Print Placements & Specifications -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-even is-visible">
      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--crimson, #DC2626);">Print Placements</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          Versatile Print Positions on Every Hoodie
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1; font-size:1.04rem; line-height:1.8;">
          We print anywhere you want your brand visible. Our specialized platens and heat press attachments allow flawless transfer onto tricky curved surfaces and seams:
        </p>

        <ul style="color:#CBD5E1; font-size:1.02rem; line-height:1.9; margin:1.25rem 0; padding-left:1.5rem;">
          <li><strong style="color:#FFD76A;">Left or Center Chest:</strong> 3.5" to 4.5" subtle crests, logos, or typography.</li>
          <li><strong style="color:#FFD76A;">Oversized Full Back:</strong> Up to 16" x 22" high-impact graphic illustrations.</li>
          <li><strong style="color:#FFD76A;">Sleeve Lengths:</strong> Long vertical branding running from shoulder to rib cuff.</li>
          <li><strong style="color:#FFD76A;">Kangaroo Pocket:</strong> Precision centered transfers on pouch fabric.</li>
          <li><strong style="color:#FFD76A;">Hood Brim &amp; Side:</strong> Subtle exterior hood branding visible when worn down.</li>
          <li><strong style="color:#FFD76A;">Custom Neck &amp; Hem Tags:</strong> Screen-printed care labels &amp; woven tags.</li>
        </ul>

        <div style="margin-top:1.75rem;">
          <a href="<?= url('get-quote') ?>?service=custom-hoodie-printing" class="btn btn-primary btn-lg">
            <span>Calculate Hoodie Pricing</span> &rarr;
          </a>
        </div>
      </div>

      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/streetwear_tee.jpg') ?>" alt="Hoodie Print Placements" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>Multi-Location Printing</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Comparison Table: DTF vs Screen Print vs Embroidery on Hoodies -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.25rem;">
      <div class="section-label" style="color:var(--bright-gold, #FFD76A);">Method Comparison</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Which Technique is Best for Your Hoodie Order?
      </h2>
    </div>

    <div class="elevated-compare-card reveal slide-up">
      <table class="compare-table">
        <thead>
          <tr>
            <th>Production Factor</th>
            <th class="flagship-col">DTF on Hoodies (Recommended)</th>
            <th>Screen Printing</th>
            <th>Computerized Embroidery</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Color Complexity &amp; Gradients</td>
            <td class="flagship-col">Photorealistic full color with zero extra fees</td>
            <td>Costly per additional screen colour</td>
            <td>Limited thread color palette</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Minimum Order Quantity</td>
            <td class="flagship-col">Low minimums (from 5 pcs)</td>
            <td>High minimums (50+ pcs per design)</td>
            <td>Moderate minimums (20+ pcs)</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Feel &amp; Flexibility on Fleece</td>
            <td class="flagship-col">Ultra-soft, flexes with fabric stretch</td>
            <td>Can feel heavy with thick plastisol ink</td>
            <td>Stiff backing can pull on heavy fleece</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Fine Lines &amp; Typography</td>
            <td class="flagship-col">Extremely sharp down to 0.5pt details</td>
            <td>Moderate resolution</td>
            <td>Coarse resolution on small letters</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Turnaround Speed</td>
            <td class="flagship-col">24–48 Hours express turnaround</td>
            <td>4–7 Days for screen setup</td>
            <td>3–5 Days for digitizing &amp; stitch</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- 4-Step Production Process -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:760px; margin:0 auto 3rem auto;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Our Quality Workflow</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        How We Produce Custom Hoodies in Our Delhi Facility
      </h2>
    </div>

    <div class="service-feature-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.5rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <div style="color:var(--bright-gold, #FFD76A); font-size:2rem; font-weight:900; margin-bottom:0.5rem;">01</div>
        <h3 style="color:#F8FAFC; font-size:1.15rem; font-weight:700; margin-bottom:0.5rem;">Prepress Calibration</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.6;">
          Our prepress team checks your artwork resolution, ensures CMYK color profiles, and applies bleed adjustments to match fleece stretch factors.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-2">
        <div style="color:var(--bright-gold, #FFD76A); font-size:2rem; font-weight:900; margin-bottom:0.5rem;">02</div>
        <h3 style="color:#F8FAFC; font-size:1.15rem; font-weight:700; margin-bottom:0.5rem;">Industrial Roll Printing</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.6;">
          High-opacity white underbase and vibrant CMYK inks are deposited onto premium release film at 1440 DPI for razor-sharp fidelity.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-3">
        <div style="color:var(--bright-gold, #FFD76A); font-size:2rem; font-weight:900; margin-bottom:0.5rem;">03</div>
        <h3 style="color:#F8FAFC; font-size:1.15rem; font-weight:700; margin-bottom:0.5rem;">Elastic Curing &amp; Bonding</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.6;">
          Polyurethane hot-melt adhesive powder is applied automatically and cured in an infrared conveyor oven to achieve maximum elasticity.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-4">
        <div style="color:var(--bright-gold, #FFD76A); font-size:2rem; font-weight:900; margin-bottom:0.5rem;">04</div>
        <h3 style="color:#F8FAFC; font-size:1.15rem; font-weight:700; margin-bottom:0.5rem;">Pneumatic Heat Press &amp; QC</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.6;">
          Dual-stage pneumatic pressing bonds transfers deep into the fleece knit at 160°C. Each piece is inspected and fold-packed in polybags.
        </p>
      </div>
    </div>
  </div>
</section>

<?php
$serviceUploadConfig = [
  'service_name' => 'Custom Hoodie Printing',
  'title' => 'Get a Custom Hoodie Printing Quote',
  'description' => 'Upload your artwork and share garment, size and quantity details for a factory-ready hoodie quote.',
  'file_title' => 'Upload hoodie artwork or placement reference',
  'file_hint' => 'PNG, JPG, PDF, AI, PSD or SVG (max 25 MB)',
  'material_label' => 'Hoodie Fabric / Style',
  'material_options' => ['Combed Cotton Brushed Fleece', 'Heavyweight French Terry', 'Cotton-Poly Tech Fleece', 'Oversized Pullover Hoodie', 'Full-Zip / Quarter-Zip Hoodie', 'Other Garment Style'],
  'notes_label' => 'Size Breakdown, Placement & Colour Notes',
  'notes_placeholder' => 'Share garment colours, sizes, front/back/sleeve placement, quantity and delivery timeline…',
  'assurance_title' => 'Hoodie Production Assurance',
  'assurance_text' => 'We review garment fabric, artwork placement and print method before issuing your production proof.',
  'cta' => 'Request Hoodie Printing Quote',
];
require __DIR__ . '/../includes/service-upload-studio.php';
?>

<!-- FAQ Accordion -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container" style="max-width:880px;">
    <div class="section-header text-center reveal slide-up" style="margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--bright-gold, #FFD76A);">Frequently Asked Questions</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Custom Hoodie Printing FAQs
      </h2>
    </div>

    <div class="faq-accordion service-faq-accordion reveal slide-up">
      <div class="faq-item">
        <div class="faq-question">
          <span>What is the minimum order quantity for custom printed hoodies?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>We accommodate short-run brand drops starting from just 5 hoodies, all the way to 5,000+ units for university fests and corporate bulk orders. The per-piece rate reduces significantly with volume tiers.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Will the print crack or peel when the hoodie stretches?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>No. We use specially formulated high-stretch TPU adhesive powder and flexible pigment inks designed specifically for heavyweight elastic fleece. When properly heat-set, the print stretches naturally with the fabric and withstands 50+ home laundry cycles.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Can I bring or send my own hoodie blanks for printing?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Yes, absolutely! Many streetwear brands courier their own manufactured or imported blanks to our Delhi production studio. We test a sample piece first to optimize press temperature and time before completing the full run.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Can you print across the kangaroo pocket and zipper seams?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Yes. We use custom silicone press pads and laser alignment jigs that distribute even pressure over pocket ridges and zip tracks, allowing seamless multi-placement prints without uneven ink transfer.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>What file format should I submit for the best print sharpness?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>We recommend vector formats (PDF, AI, EPS, or SVG) with all fonts outlined. For raster files (PNG, PSD, TIFF), ensure a transparent background and a minimum resolution of 300 DPI at 100% target print size.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action / Quotation Studio -->
<section class="section section-white text-center" style="padding-top:clamp(4rem, 5vw, 5.5rem); padding-bottom:clamp(4rem, 5vw, 5.5rem);">
  <div class="container" style="max-width:760px;">
    <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
      Ready to Manufacture Custom Hoodies for Your Brand?
    </h2>
    <p style="color:#CBD5E1; font-size:1.05rem; line-height:1.7; margin-bottom:2rem;">
      Upload your artwork and receive an instant commercial quote with volume discounts and digital proofing within 2 business hours.
    </p>
    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="<?= url('get-quote') ?>?service=custom-hoodie-printing" class="btn btn-primary btn-lg">
        <span>Request Instant Hoodie Quote</span> &rarr;
      </a>
      <a href="https://wa.me/919354028199?text=<?= urlencode('Hi Printex, I would like to get a quote for Custom Hoodie Printing.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
        <span>Chat on WhatsApp</span>
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
      if (icon) icon.textContent = !isOpen ? '−' : '+';
    });
  });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
