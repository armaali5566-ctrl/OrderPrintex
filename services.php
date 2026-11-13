<?php
/**
 * OrderPrintex - All Printing Services Overview
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "Printing Services in Delhi | DTF, UV DTF, T-Shirt | Printex";
$pageDesc  = "DTF Printing, UV DTF Stickers, T-Shirt, Hoodie, and Mug Printing in Delhi - We've got you covered. Printex is the go to for brands, shops and businesses across India . We'll get you a quote in no time.";
$canonical = url('services');
$pageSchemaKey = 'services';

require_once __DIR__ . '/includes/header.php';

$services = db_fetch_all("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC");

if (empty($services)) {
    $services = [
        [
            'id' => 1,
            'slug' => 'dtf-printing',
            'title' => '1.DTF Printing in Delhi',
            'badge' => '1.DTF Printing in Delhi',
            'hero_tagline' => 'Prints on Fabric with Bright, Soft, and Long Lasting Results',
            'full_description' => "Our DTF (Direct-to-Film) printing is perfect for cotton, polyester and blended fabrics. It prints a white base under the colours so your designs stay bright even on black. Want to get started ? You can order ready-to-press transfers and gang sheets for your own pressing or we can do it for you. This service is a hit with apparel brands, resellers and print shops that need reliable bulk DTF printing.",
            'image_url' => 'assets/images/dtf_fabric_print.jpg',
            'specifications' => 'Works on: Cotton, Polyester, Blends | Sheet width: 24 inches - Let us know if you need anything else | Order: Gang sheets and bulk rolls'
        ],
        [
            'id' => 2,
            'slug' => 'uv-dtf-printing',
            'title' => '2. UV DTF Printing - A Popular Choice',
            'badge' => '2. UV DTF Printing - A Popular Choice',
            'hero_tagline' => 'UV DTF Printing & Stickers - Add a 3D Glossy Finish to Your Products',
            'full_description' => 'Peel and stick stickers that just need to be applied - no heat press needed. The glossy layer gives them a raised 3D look, making them waterproof and scratch resistant. They work on glass, steel, acrylic, plastic, and wood, so they are super popular for mugs, tumblers, bottles, gift items and packaging. We make UV DTF stickers in custom sizes and bulk quantities.',
            'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
            'specifications' => 'Layers: Colour + White + Gloss | Surfaces: Glass, Steel, Acrylic, Plastic, Wood | No heat press needed'
        ],
        [
            'id' => 3,
            'slug' => 't-shirt-printing',
            'title' => '3. T-Shirt Printing for Bulk Orders',
            'badge' => '3. T-Shirt Printing for Bulk Orders',
            'hero_tagline' => 'T-Shirt Printing in Delhi - Custom T-Shirts for Brands, Teams and Events',
            'full_description' => 'We print custom t-shirts for brands, offices, colleges, events and teams in Delhi. We use DTF printing, so your full-colour designs and fine text come out sharp. Just send us your design along with the quantity and we will suggest the right fabric and print placement. Price of bulk orders are based on the quantity.',
            'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
            'specifications' => 'Fabrics: Cotton, Poly-Rich, Dry-Fit | Sizes: XS to 3XL | Orders: Single sample to bulk'
        ],
        [
            'id' => 4,
            'slug' => 'corporate-printing',
            'title' => '4. Corporate Printing Services for Businesses',
            'badge' => '4. Corporate Printing Services for Businesses',
            'hero_tagline' => 'Corporate T-Shirt & Merchandise Printing - For Offices, Events and Brands',
            'full_description' => 'Need branded merchandise for your company or event ? Printex is here to help. We print uniform t-shirts, branded mugs and bottles, and giveaways with your logo, in the same colours across the whole batch. GST invoice available on request.',
            'image_url' => 'assets/images/corporate_swag_kit.jpg',
            'specifications' => 'Products: T-Shirts, Mugs, Bottles, Giveaways | Invoice: GST on request | Delivery: Delhi and pan-India'
        ]
    ];
}
?>

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Our Printing Services in Delhi</span>
      </div>
      <h1 class="page-intro-title">DTF, UV DTF &amp; T-Shirt Printing Services in Delhi</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        At Printex we print DTF transfers, UV DTF stickers, t-shirts, hoodies and mugs from our studio in Pankha Road, Delhi. Whether you're looking to order a small sample or a bulk batch , we deliver across India.
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
                  <?= e($svc['specifications']) ?>
                </div>
              <?php endif; ?>

              <div class="service-cta-group">
                <a href="<?= service_url($svc['slug']) ?>" class="btn service-btn-secondary">
                  <span>Know More &rarr;</span>
                </a>
                <a href="<?= url('get-quote.php?service_id=' . e($svc['id'])) ?>" class="btn service-btn-primary">
                  <span>Get a Quote</span>
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