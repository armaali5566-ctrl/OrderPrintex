<?php
/**
 * OrderPrintex - Mug & Cup Printing Service Page
 */

require_once __DIR__ . '/../includes/functions.php';

$pageTitle = "Custom Mug & Cup Printing in Delhi | Ceramic & Tumbler Studio – Printex";
$pageDesc  = "Custom coffee mug and drinkware printing in Delhi by OrderPrintex. Ceramic mugs, insulated steel tumblers, and travel cups with sublimation & rotary UV printing.";
$canonical = service_url('mug-cup-printing');
$pageSchemaKey = 'services-mug-cup-printing';

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'mug-cup-printing'");
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Apparel &amp; Merchandise &bull; Drinkware Production Studio</span>
      </div>
      <h1 class="page-intro-title">Custom Mug &amp; Cup Printing in Delhi - Ceramic Mugs, Steel Tumblers &amp; Drinkware</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Full-color 360-degree cylindrical wrap printing on premium AAA-grade ceramic mugs, double-wall stainless steel insulated tumblers, travel cups, and enamel camp mugs. Dishwasher safe, microwave safe, and packaged in protective crash-lock mailers for zero transit breakage across India.
      </p>
    </div>
  </div>
</section>

<!-- Overview & Customization Technologies -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-odd is-visible">
      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/corporate_merch_box.jpg') ?>" alt="Custom Printed Ceramic Mugs & Tumblers" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>360° Wrap Sublimation &amp; Rotary UV</span>
          </div>
        </div>
      </div>

      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--bright-gold, #FFD76A);">High-Gloss Ceramic &amp; Steel Engineering</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          Brilliant Colors, Dishwasher Durability, and Zero Peeling
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1;">
          Custom drinkware is the cornerstone of corporate welcome kits, promotional marketing, and cafe retail branding. At OrderPrintex, we operate dedicated drinkware production lines utilizing two advanced manufacturing techniques:
        </p>

        <p class="tech-desc" style="color:#CBD5E1;">
          <strong style="color:#FFD76A;">1. 360° Dye-Sublimation:</strong> Under calibrated 200°C heat and pneumatic cylindrical pressure, solid sublimation dyes gasify and infuse directly into the polymeric glaze of our AAA-grade Orca-coated ceramic mugs. The image becomes molecularly part of the mug—it cannot scratch, crack, or peel off, and is 100% dishwasher and microwave safe.
        </p>

        <p class="tech-desc" style="color:#CBD5E1;">
          <strong style="color:#FFD76A;">2. Cylindrical Rotary Direct UV:</strong> For stainless steel thermal flasks, matte tumblers, and glassware, our rotary UV equipment prints CMYK + White + Embossed 3D clear gloss directly onto curved surfaces with instant LED curing, offering tactile luxury finishes.
        </p>

        <div class="service-cta-group" style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1.5rem;">
          <a href="<?= url('get-quote') ?>?service=mug-cup-printing" class="btn btn-primary btn-lg">
            <span>Get a Drinkware Quote</span> &rarr;
          </a>
          <a href="<?= url('products') ?>" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
            <span>Explore Drinkware Models</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Drinkware Styles & Substrates -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Drinkware Catalog</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Mug &amp; Tumbler Models Ready for Custom Production
      </h2>
      <p style="color:#94A3B8; font-size:1.05rem; margin-top:0.5rem;">
        We stock a comprehensive range of blanks in our Delhi studio for rapid same-day prototyping and high-volume delivery.
      </p>
    </div>

    <div class="service-feature-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem; margin-bottom:2rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Classic 11oz &amp; 15oz Ceramic Coffee Mugs</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          High-gloss white glazed porcelain mugs with ergonomic C-handles. The industry benchmark for corporate desk branding and souvenir collections.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-2">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Two-Tone Inner Color Accent Mugs</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Vibrant contrasting interior glaze and handle colors (black, navy, red, gold, orange) perfectly complementing your brand's corporate identity.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-3">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Double-Wall Stainless Steel Tumblers</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Vacuum-insulated food-grade 304 stainless steel travel mugs that keep beverages hot for 8 hours or cold for 16 hours. Printed with rotary UV or laser engraving.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-1">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Magic Heat Color-Changing Mugs</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Appear solid matte black at room temperature; pouring hot coffee reveals your hidden full-color graphic underneath. High-novelty gift favorite.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-2">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Vintage Enamel Camping Mugs</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Rustic steel mugs coated in thick enamel glaze with classic black or blue rolled rims. Highly durable, shatterproof, and popular for outdoor lifestyle brands.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-3">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Frosted &amp; Clear Beer Steins &amp; Glassware</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Sublimated frosted glass steins and barware with photo-quality transparency gradients and dishwasher durability for cafes, breweries, and events.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Packaging & Safe Shipping Guarantee -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-even is-visible">
      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--crimson, #DC2626);">Logistics &amp; Safety</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          Crash-Lock Thermocol Packaging — Zero Breakage Guarantee
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1; font-size:1.04rem; line-height:1.8;">
          The biggest fear when ordering ceramic mugs online is receiving shattered pieces. We eliminate transit anxiety entirely through industrial packaging:
        </p>

        <ul style="color:#CBD5E1; font-size:1.02rem; line-height:1.9; margin:1.25rem 0; padding-left:1.5rem;">
          <li><strong style="color:#FFD76A;">Individual Thermocol Shells:</strong> Every single mug is encased in its own high-density expanded polystyrene clam-shell before boxing.</li>
          <li><strong style="color:#FFD76A;">3-Ply Corrugated Outer Mailers:</strong> Thick protective cartons withstand heavy courier handling and multi-state transit.</li>
          <li><strong style="color:#FFD76A;">Palletized Bulk Shipments:</strong> Large orders (500+ pieces) are strapped, corner-boarded, and shrink-wrapped on wooden pallets.</li>
          <li><strong style="color:#FFD76A;">100% Replacement Guarantee:</strong> In the rare event of damage during shipping, we replace affected units immediately with zero hassle.</li>
        </ul>

        <div style="margin-top:1.75rem;">
          <a href="<?= url('get-quote') ?>?service=mug-cup-printing" class="btn btn-primary btn-lg">
            <span>Calculate Bulk Mug Order</span> &rarr;
          </a>
        </div>
      </div>

      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/uv_dtf_drinkware.jpg') ?>" alt="Safe Thermocol Packaging for Drinkware" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>Zero Breakage Packaging</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Comparison Table: Sublimation vs Rotary UV vs Vinyl Stickers -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.25rem;">
      <div class="section-label" style="color:var(--bright-gold, #FFD76A);">Drinkware Print Comparison</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Which Printing Method is Right for Your Drinkware?
      </h2>
    </div>

    <div class="elevated-compare-card reveal slide-up">
      <table class="compare-table">
        <thead>
          <tr>
            <th>Key Metric</th>
            <th class="flagship-col">360° Sublimation (Mugs)</th>
            <th>Rotary Direct UV (Tumblers)</th>
            <th>Standard Vinyl Decals</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Durability &amp; Washability</td>
            <td class="flagship-col">Dishwasher &amp; Microwave Safe (Permanent glaze bond)</td>
            <td>Hand-wash recommended, highly scratch-resistant</td>
            <td>Peels off easily when washed</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Print Feel</td>
            <td class="flagship-col">Completely smooth, zero surface lip</td>
            <td>Tactile, raised glossy texture</td>
            <td>Thick raised sticker edge</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Color &amp; Resolution</td>
            <td class="flagship-col">Photorealistic CMYK continuous tone</td>
            <td>Vivid CMYK + White + Clear Varnish</td>
            <td>Limited single or spot colors</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Best Substrates</td>
            <td class="flagship-col">Polymer-coated ceramics &amp; glass</td>
            <td>Stainless steel, flasks, frosted glass</td>
            <td>Temporary promotional giveaways</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php
$serviceUploadConfig = [
  'service_name' => 'Mug & Cup Printing',
  'title' => 'Get a Custom Mug & Cup Printing Quote',
  'description' => 'Upload your logo, artwork or reference and share the drinkware quantity for a production-ready quote.',
  'file_title' => 'Upload mug artwork or branding file',
  'file_hint' => 'PNG, JPG, PDF, AI, PSD or SVG (max 25 MB)',
  'material_label' => 'Drinkware Type',
  'material_options' => ['11oz Ceramic Mug', '15oz Ceramic Mug', 'Steel Tumbler / Travel Cup', 'Enamel Mug', 'Glassware / Beer Stein', 'Other Drinkware'],
  'notes_label' => 'Print Area, Colours & Delivery Notes',
  'notes_placeholder' => 'Share mug model, print placement, number of colours, packaging and delivery requirements…',
  'assurance_title' => 'Drinkware Print Assurance',
  'assurance_text' => 'We check artwork dimensions, print method and product compatibility before your production is scheduled.',
  'cta' => 'Request Mug Printing Quote',
];
require __DIR__ . '/../includes/service-upload-studio.php';
?>

<!-- FAQ Accordion -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container" style="max-width:880px;">
    <div class="section-header text-center reveal slide-up" style="margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--bright-gold, #FFD76A);">Customer FAQs</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Custom Mug &amp; Cup Printing FAQs
      </h2>
    </div>

    <div class="faq-accordion service-faq-accordion reveal slide-up">
      <div class="faq-item">
        <div class="faq-question">
          <span>Are custom printed ceramic mugs safe for microwaves and dishwashers?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Yes. Our dye-sublimated ceramic mugs use genuine Orca polymer coatings that fuse the ink inside the ceramic glaze. They are 100% food safe, non-toxic, microwave safe, and rated for 1,000+ domestic dishwasher cycles without fading.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>What is the minimum order quantity for custom mugs?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>We accept short runs starting from 12 mugs for boutique cafes and startups, and fulfill volume runs of 10,000+ units for multinational corporate swag campaigns. Volume tier discounts apply automatically.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Can you print artwork that wraps all the way around the mug?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Yes. We offer panoramic 360-degree wrap printing. For an 11oz mug, the print canvas is approximately 200mm wide by 95mm high, leaving a neat 15mm gap around the handle.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Do you supply the mugs or do I need to ship blanks to you?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>We stock thousands of AAA-grade white ceramic mugs, inner-color mugs, and stainless steel tumblers directly in our Delhi warehouse, so you don't need to ship anything. However, if you have specialized drinkware shapes, our rotary UV equipment can print on your supplied blanks as well.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>What happens if any mugs break during delivery?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Because each mug is enclosed in protective individual thermocol molding, our transit breakage rate is below 0.1%. In the unlikely event that any unit arrives damaged, simply share an unboxing photo and we will reprint and dispatch replacements immediately at our expense.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="section section-ivory text-center" style="padding-top:clamp(4rem, 5vw, 5.5rem); padding-bottom:clamp(4rem, 5vw, 5.5rem);">
  <div class="container" style="max-width:760px;">
    <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
      Ready to Order Custom Mugs &amp; Tumblers?
    </h2>
    <p style="color:#CBD5E1; font-size:1.05rem; line-height:1.7; margin-bottom:2rem;">
      Get a fast commercial quote with digital mockups prepared by our Delhi design team within 2 hours.
    </p>
    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="<?= url('get-quote') ?>?service=mug-cup-printing" class="btn btn-primary btn-lg">
        <span>Request Drinkware Quote</span> &rarr;
      </a>
      <a href="https://wa.me/919354028199?text=<?= urlencode('Hi Printex, I want to inquire about Custom Mug & Cup Printing.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
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
