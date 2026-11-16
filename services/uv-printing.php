<?php
/**
 * OrderPrintex - UV Printing Services Page (Direct Flatbed UV)
 */

require_once __DIR__ . '/../includes/functions.php';

$pageTitle = "Direct UV Flatbed Printing Services in Delhi | Rigid Surface Printing – Printex";
$pageDesc  = "Industrial direct UV flatbed printing on acrylic, wood, glass, metal, and leather in Delhi. High-resolution CMYK + White + Spot Gloss Varnish with instant UV LED curing.";
$canonical = service_url('uv-printing');
$pageSchemaKey = 'services-uv-printing';

require_once __DIR__ . '/../includes/header.php';

$service = db_fetch_one("SELECT * FROM services WHERE slug = 'uv-printing'");
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Industrial Flatbed UV Printing Studio</span>
      </div>
      <h1 class="page-intro-title">Direct UV Printing Services in Delhi - Industrial Flatbed Precision on Rigid Surfaces</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Direct-to-substrate digital UV printing engineered for acrylic, wood, glass, brushed metals, leather, and industrial enclosures. Utilizing high-intensity UV LED curing lamps, multi-channel white underbases, and tactile clear gloss varnishes for photorealistic, scratch-resistant finishes.
      </p>
    </div>
  </div>
</section>

<!-- Direct Flatbed UV vs UV DTF: Technical Distinction -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-odd is-visible">
      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/uv_dtf_drinkware.jpg') ?>" alt="Direct Flatbed UV Printing on Rigid Substrates" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>Direct-to-Object Flatbed</span>
          </div>
        </div>
      </div>

      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--bright-gold, #FFD76A);">Direct Flatbed UV vs. UV DTF Decals</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          Direct-to-Substrate Flatbed Printing Explained
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1;">
          While both methods utilize UV-curable inks, <strong style="color:#FFD76A;">Direct UV Flatbed Printing</strong> and <strong style="color:#FFD76A;">UV DTF</strong> serve distinctly different manufacturing needs:
        </p>

        <p class="tech-desc" style="color:#CBD5E1;">
          In <strong style="color:#F8FAFC;">Direct UV Printing</strong>, your actual rigid object—such as an acrylic plaque, wooden memento, glass pane, or metal panel—is placed directly onto the printer's vacuum flatbed table. Piezo printheads jet microscopic droplets of UV ink straight onto the product surface, which are instantly polymerized and cured solid by ultraviolet LED lamps in microseconds. There is no transfer film, no adhesive layer, and zero peeling.
        </p>

        <p class="tech-desc" style="color:#CBD5E1;">
          By contrast, <a href="<?= service_url('uv-dtf-printing') ?>" style="color:var(--bright-gold, #FFD76A); text-decoration:underline;">UV DTF Printing</a> prints inks onto an intermediate carrier film, creating peel-and-stick decals designed for irregular or cylindrical objects that cannot fit onto a flatbed. If you have flat rigid items, Direct UV provides superior industrial adhesion, seamless tactile embossing, and lower unit costs for volume runs.
        </p>

        <div class="service-cta-group" style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1.5rem;">
          <a href="<?= url('get-quote') ?>?service=uv-printing" class="btn btn-primary btn-lg">
            <span>Request Flatbed UV Quote</span> &rarr;
          </a>
          <a href="<?= service_url('uv-dtf-printing') ?>" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
            <span>Compare with UV DTF Decals</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Substrates & Compatible Materials -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--blue, #2563EB);">Engineered Versatility</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Substrates We Direct UV Print In Our Delhi Studio
      </h2>
      <p style="color:#94A3B8; font-size:1.05rem; margin-top:0.5rem;">
        Equipped with specialized adhesion promoters and primers, our flatbed UV printers bond permanently to almost any rigid surface up to 100mm in thickness.
      </p>
    </div>

    <div class="service-feature-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem; margin-bottom:2rem;">
      <div class="service-feature-card reveal slide-up stagger-1">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Cast Acrylic &amp; Plexiglass</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Direct front-surface or second-surface (reverse printed on clear acrylic) with solid white backer for floating 3D depth. Perfect for corporate trophies, awards, and backlit signs.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-2">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Natural Wood, Bamboo &amp; MDF</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          The UV ink bonds into the organic timber grain, allowing natural wood textures to show through unprinted areas or applying opaque full-color coverage for plaques, coasters, and boxes.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-3">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Tempered Glass &amp; Mirrors</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Using molecular silane glass primers, we print photographic wall art, architectural partitions, glass awards, and decorative tabletops that are impervious to moisture and UV fading.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-1">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Metals &amp; Anodized Aluminum</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Brushed steel, brass, coated tin, and aluminum plates for industrial machine nameplates, serial tags, high-end signage, and executive desk name blocks.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-2">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">Leather &amp; PU Synthetics</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          Flexible UV ink sets stretch with genuine and faux leather without micro-fracturing. Ideal for luxury diary covers, branded wallets, hotel compendiums, and leather patches.
        </p>
      </div>

      <div class="service-feature-card reveal slide-up stagger-3">
        <h3 style="color:#FFD76A; font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">PVC Foam Board, Sunboard &amp; ACP</h3>
        <p style="color:#CBD5E1; margin:0; line-height:1.65;">
          High-volume direct printing on lightweight foam PVC and Aluminum Composite Panels (ACP) for retail POS displays, exhibition booths, and directional wayfinding signage.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Specialized Finishes & Tactile Textures -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="tech-zigzag-row tech-row-even is-visible">
      <div class="tech-zigzag-content">
        <div class="section-label" style="color:var(--crimson, #DC2626);">Finishing Capabilities</div>
        <h2 style="font-size:clamp(1.75rem, 2.7vw, 2.4rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
          Spot Varnish, Tactile Embossing &amp; 3D Braille
        </h2>
        <div class="tech-accent-line line-gold"></div>

        <p class="tech-desc" style="color:#CBD5E1; font-size:1.04rem; line-height:1.8;">
          Direct UV flatbed printing is not just about vibrant color—it allows you to add tactile dimensions that users can feel:
        </p>

        <ul style="color:#CBD5E1; font-size:1.02rem; line-height:1.9; margin:1.25rem 0; padding-left:1.5rem;">
          <li><strong style="color:#FFD76A;">Spot Gloss Varnish:</strong> Selective gloss highlighting over logos, text, or patterns that catches ambient light and contrasts with matte backgrounds.</li>
          <li><strong style="color:#FFD76A;">Tactile 3D Embossing:</strong> Building up repeated layers of UV clear ink to create authentic raised textures, woodgrain feels, oil painting impasto, and leather ripples.</li>
          <li><strong style="color:#FFD76A;">ADA Compliant Braille:</strong> Printing raised Braille dots that comply with accessibility and signage regulations.</li>
          <li><strong style="color:#FFD76A;">High-Opacity White Layer:</strong> A dense white underbase that blocks transparent or dark substrates, guaranteeing 100% true CMYK color accuracy.</li>
        </ul>

        <div style="margin-top:1.75rem;">
          <a href="<?= url('get-quote') ?>?service=uv-printing" class="btn btn-primary btn-lg">
            <span>Get a Custom Flatbed Quote</span> &rarr;
          </a>
        </div>
      </div>

      <div class="tech-zigzag-media">
        <div class="tech-zigzag-img-wrap">
          <img src="<?= url('assets/images/branding_stationery.jpg') ?>" alt="Tactile UV Varnish & Embossing" class="tech-zigzag-img" loading="lazy" width="1200" height="896">
          <div class="tech-media-badge">
            <span class="tech-media-badge-dot"></span>
            <span>Spot Varnish &amp; 3D Tactile</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Comparison Table: Direct UV Flatbed vs UV DTF vs Screen Print on Rigid Objects -->
<section class="section section-ivory" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container">
    <div class="section-header reveal slide-up" style="max-width:760px; margin-bottom:2.25rem;">
      <div class="section-label" style="color:var(--bright-gold, #FFD76A);">Rigid Substrate Comparison</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Direct UV Flatbed vs. UV DTF Decals vs. Screen Printing
      </h2>
    </div>

    <div class="elevated-compare-card reveal slide-up">
      <table class="compare-table">
        <thead>
          <tr>
            <th>Technical Spec</th>
            <th class="flagship-col">Direct UV Flatbed</th>
            <th>UV DTF Stickers</th>
            <th>Screen Printing on Rigid</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-weight:700;">Substrate Application</td>
            <td class="flagship-col">Direct to substrate on vacuum bed</td>
            <td>Peel-and-stick film transfer</td>
            <td>Manual screen squeegee pass</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Adhesion &amp; Durability</td>
            <td class="flagship-col">Permanent molecular bond with UV primer</td>
            <td>High adhesive bond, edge lift risk on abrasion</td>
            <td>High durability, solvent ink cure</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Tactile 3D Build-Up</td>
            <td class="flagship-col">Up to 2mm multi-pass layer height</td>
            <td>Single pass gloss varnish height</td>
            <td>Flat ink layer only</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Curved / Irregular Shapes</td>
            <td class="flagship-col">Best on flat rigid surfaces (≤100mm)</td>
            <td>Best for compound curves &amp; bottles</td>
            <td>Difficult on compound curves</td>
          </tr>
          <tr>
            <td style="font-weight:700;">Full-Color Detail</td>
            <td class="flagship-col">1440 DPI photographic precision</td>
            <td>1440 DPI photographic precision</td>
            <td>Dot halftone screen limitation</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php
$serviceUploadConfig = [
  'service_name' => 'UV Printing',
  'title' => 'Get a Direct UV Printing Quote',
  'description' => 'Upload your artwork or product reference and tell us the substrate, size and quantity for a commercial quote.',
  'file_title' => 'Upload UV print artwork or product reference',
  'file_hint' => 'PNG, JPG, PDF, AI, PSD or SVG (max 25 MB)',
  'material_label' => 'Substrate / Material',
  'material_options' => ['Acrylic / Plexiglass', 'Wood / MDF / Bamboo', 'Glass / Mirror', 'Metal / Aluminum', 'Leather / PU', 'PVC / ACP / Foam Board', 'Other Rigid Surface'],
  'notes_label' => 'Object Size, Finish & Production Notes',
  'notes_placeholder' => 'Share object dimensions, print side, white ink or varnish requirements, quantity and delivery timeline…',
  'assurance_title' => 'UV Print Compatibility Check',
  'assurance_text' => 'Our team confirms adhesion, primer and finish requirements for your substrate before production.',
  'cta' => 'Request UV Printing Quote',
];
require __DIR__ . '/../includes/service-upload-studio.php';
?>

<!-- FAQ Accordion -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container" style="max-width:880px;">
    <div class="section-header text-center reveal slide-up" style="margin-bottom:2.5rem;">
      <div class="section-label" style="color:var(--bright-gold, #FFD76A);">Technical FAQs</div>
      <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24;">
        Direct UV Flatbed Printing FAQs
      </h2>
    </div>

    <div class="faq-accordion service-faq-accordion reveal slide-up">
      <div class="faq-item">
        <div class="faq-question">
          <span>What is the difference between Direct UV printing and UV DTF?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Direct UV printing prints ink straight onto the actual object placed on our flatbed machine, instantly cured by UV light. UV DTF prints the ink onto a transfer film with adhesive, which you peel and stick like a heavy-duty sticker onto items that can't fit on a flatbed machine. Direct UV has zero sticker borders and permanent industrial adhesion.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>What is the maximum object size and thickness you can print on?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Our flatbed UV printers accommodate sheets and objects up to 600mm x 900mm in area and up to 100mm (10 cm) in total height/thickness. Custom jig trays are used for multi-piece batches like phone cases, pens, and keychains.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Is the print scratch-resistant and waterproof?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Yes. UV LED curing causes the ink monomers to instantly cross-link into a resilient polymer shell. Combined with surface-specific adhesion primers for glass and metal, the print resists water, oils, household chemicals, and everyday scratch resistance.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>Can I print white ink on dark or transparent items?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Yes! Our flatbed machines feature dedicated high-density white ink channels. We can print White + CMYK in a single pass, ensuring brilliant, fully opaque graphics on black acrylic, dark wood, or clear glass.</p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>How should artwork files with spot varnish and white channels be prepared?</span>
          <span class="faq-icon">+</span>
        </div>
        <div class="faq-answer">
          <p>Submit your artwork in vector format (AI, PDF, EPS). Place your color design on one layer, and create separate spot color layers named 'White' and 'Varnish' representing the exact areas you want white underbase or gloss coating applied. Our prepress team can also assist with layer generation.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="section section-ivory text-center" style="padding-top:clamp(4rem, 5vw, 5.5rem); padding-bottom:clamp(4rem, 5vw, 5.5rem);">
  <div class="container" style="max-width:760px;">
    <h2 style="font-size:clamp(1.9rem, 3vw, 2.5rem); color:#F8FAFC; font-weight:800; line-height:1.24; margin-bottom:1rem;">
      Bring Industrial Flatbed UV Quality to Your Products
    </h2>
    <p style="color:#CBD5E1; font-size:1.05rem; line-height:1.7; margin-bottom:2rem;">
      Send us your substrate details or drop off physical samples at our Pankha Road, Delhi production studio for rapid prototyping.
    </p>
    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="<?= url('get-quote') ?>?service=uv-printing" class="btn btn-primary btn-lg">
        <span>Request UV Printing Quote</span> &rarr;
      </a>
      <a href="https://wa.me/919354028199?text=<?= urlencode('Hi Printex, I want to inquire about Direct UV Flatbed Printing on rigid materials.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
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
