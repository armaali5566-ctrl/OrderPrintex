<?php
/**
 * OrderPrintex - All Printing Services Overview
 */

$pageTitle = "Industrial Printing Services — DTF, UV DTF & Apparel Transfers | OrderPrintex";
$pageDesc  = "Explore OrderPrintex's verified printing services: Direct-to-Film (DTF) apparel transfers, UV DTF decal stickers, custom screen-grade t-shirt printing, and corporate promotional merchandise.";

require_once __DIR__ . '/includes/header.php';

$services = db_fetch_all("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC");

if (empty($services)) {
    $services = [
        [
            'id' => 1,
            'slug' => 'dtf-printing',
            'title' => 'DTF Printing',
            'badge' => 'Flagship Service',
            'hero_tagline' => 'Ultra Soft Hand-Feel & 50+ Wash Cycles',
            'full_description' => 'Printex is a top manufacturer & bulk supplier of DTF transfers in India. Direct-to-Film (DTF) technology allows full-color photographic designs with gradients, fine lines, and white backing to be pressed seamlessly onto dark, light, or synthetic fabrics without weeding. Every sheet is printed with calibrated ICC color profiles and sealed with premium hot-melt polyurethane adhesive powder for stretch resistance and long-lasting wash cycles.',
            'image_url' => 'assets/images/dtf_fabric_print.jpg',
            'specifications' => 'Max Print Width: 24 inches (Gang Sheets up to 100 meters) | Inks: Premium Water-based Textile Pigment Inks | Powder: High-Grade TPU Hot Melt Adhesive | Press Temp: 160°C for 15s'
        ],
        [
            'id' => 2,
            'slug' => 'uv-dtf-printing',
            'title' => 'UV DTF Printing',
            'badge' => 'High Demand',
            'hero_tagline' => 'No Heat Press Required — Peel, Stick & Rub',
            'full_description' => 'Our UV DTF printing service in Delhi provides industrial-grade UV cured transfer decals. UV DTF uses micro-piezo printheads to lay down white ink, vibrant CMYK, and high-gloss clear varnish topped with durable pressure-sensitive adhesive film. The result is a waterproof, scratch-resistant 3D embossed sticker that bonds permanently to hard surfaces like stainless steel mugs, glass bottles, electronics, plastic packaging, and signage without requiring a heat press or lamination machine.',
            'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
            'specifications' => 'Resolution: Up to 1440x2880 DPI | Layers: White Underbase + CMYK + Spot Gloss Varnish | Adhesion: High-tack permanent acrylic | Water Resistance: 100% Waterproof & Dishwasher Resistant'
        ],
        [
            'id' => 3,
            'slug' => 't-shirt-printing',
            'title' => 'T-Shirt Printing',
            'badge' => 'Bulk Supplier',
            'hero_tagline' => 'Premium Combed Cotton & High-Density Prints',
            'full_description' => 'From independent streetwear labels to bulk organizational uniforms, OrderPrintex delivers ready-to-wear printed t-shirts with exceptional print fidelity. We combine 100% combed cotton and breathable poly-rich fabrics with state-of-the-art DTF and digital print technology. Whether you need single sample prototypes or bulk runs for college fests, corporate teams, or brand merchandise, our Delhi production facility delivers crisp detail with vibrant pigments.',
            'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
            'specifications' => 'Garment Types: 180–240 GSM Bio-Washed Combed Cotton, Dry-Fit Polyester, Oversized Streetwear Tees, Polo Collars | Sizes: XS to 3XL | Placement: Front Chest, Back Full, Sleeves'
        ],
        [
            'id' => 4,
            'slug' => 'corporate-printing',
            'title' => 'Corporate Printing',
            'badge' => 'B2B Solutions',
            'hero_tagline' => 'Complete Branded Merchandise & Business Swag',
            'full_description' => 'Printex handles comprehensive corporate and promotional printing requirements across India. We produce high-impact branded merchandise including personalized ceramic and stainless mugs, premium corporate diaries, metal pens, branded ID lanyards, uniform polo shirts, and customized packaging boxes. With strict quality inspection and reliable door-to-door delivery across India, we empower businesses to present a unified, high-end brand identity.',
            'image_url' => 'assets/images/corporate_swag_kit.jpg',
            'specifications' => 'Product Categories: Custom Printed Drinkware (Mugs/Sipper Bottles), Executive Stationery, Event Giveaways, Custom Branded Packaging | Customization: UV DTF, Screen Print, Sublimation'
        ]
    ];
}
?>

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Commercial Print Capabilities</span>
      </div>
      <h1 class="page-intro-title">Advanced Industrial Printing Services</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        We engineer press-ready transfers with vivid color gamut, durable washability, and rapid turnaround for apparel brands, promotional studios, and print businesses.
      </p>
    </div>
  </div>
</section>

<!-- Services Grid -->
<section class="section section-white">
  <div class="container">
    <div style="display:flex; flex-direction:column; gap:3.5rem;">
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $idx => $svc): ?>
          <div class="service-showcase-row <?= ($idx % 2 !== 0) ? 'reverse' : '' ?> reveal slide-up">
            <div class="service-showcase-content">
              <span class="service-feature-pill"><?= e($svc['badge']) ?></span>
              <h2 style="margin-bottom:0.75rem; font-size:clamp(1.8rem, 2.8vw, 2.35rem); color:var(--text-primary); font-weight:800;"><?= e($svc['title']) ?></h2>
              <p style="font-size:1.05rem; color:var(--primary-gold); font-weight:600; margin-bottom:0.85rem;">
                <?= e($svc['hero_tagline']) ?>
              </p>
              <p style="margin-bottom:1.5rem; color:var(--text-secondary); line-height:1.7;">
                <?= e($svc['full_description']) ?>
              </p>

              <?php if (!empty($svc['specifications'])): ?>
                <div class="service-key-specs" style="background-color:var(--bg-surface-elevated); border:1px solid var(--border-color); border-left:3px solid var(--primary-gold); padding:1rem 1.25rem; border-radius:var(--radius-sm); margin-bottom:1.75rem; font-size:0.88rem; color:var(--text-primary); line-height:1.6;">
                  <strong style="color:var(--primary-gold); font-weight:700;">Key Specs:</strong> <?= e($svc['specifications']) ?>
                </div>
              <?php endif; ?>

              <div class="service-cta-group">
                <a href="<?= service_url($svc['slug']) ?>" class="btn service-btn-secondary">
                  <span>Detailed Specifications</span> &rarr;
                </a>
                <a href="<?= url('get-quote.php?service_id=' . e($svc['id'])) ?>" class="btn service-btn-primary">
                  <span>Get Quotation</span>
                </a>
              </div>
            </div>

            <div class="service-showcase-media">
              <img src="<?= url(e($svc['image_url'])) ?>" alt="<?= e($svc['title']) ?>" loading="lazy" style="width:100%; height:100%; object-fit:contain; display:block; padding:0.75rem;">
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Direct Upload Prompt -->
<section class="section section-ivory text-center">
  <div class="container container-narrow reveal scale-in">
    <div class="section-label" style="justify-content:center;">Ready to Produce?</div>
    <h2 style="margin-bottom:1rem;">Send Us Your Artwork for Free Prepress File Analysis</h2>
    <p style="margin-bottom:2rem;">
      Upload your artwork now. Our prepress team will check color trapping, resolution, and transparency, then deliver an itemized quote within hours.
    </p>
    <a href="<?= url('get-quote.php') ?>" class="btn btn-primary btn-lg">
      <span>Upload Artwork for Instant Quote</span> &rarr;
    </a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
