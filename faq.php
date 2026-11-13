<?php
/**
 * OrderPrintex - Frequently Asked Questions (FAQ) Page
 * Comprehensive Knowledge Base covering all 8 print & prepress categories.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "Frequently Asked Questions — Prepress, DTF, UV DTF & Ordering | OrderPrintex";
$pageDesc  = "Explore comprehensive answers on DTF transfers, UV DTF decals, custom t-shirt printing, artwork file preparation, gang sheet efficiency, and quotation workflows.";
$canonical = url('faq');
$pageSchemaKey = 'faq';

require_once __DIR__ . '/includes/header.php';

// Define the 8 required categories
$categories = [
    'general'   => 'General Questions',
    'artwork'   => 'Artwork & File Upload',
    'dtf'       => 'DTF Printing',
    'uv-dtf'    => 'UV DTF Printing',
    't-shirt'   => 'T-Shirt Printing',
    'orders'    => 'Orders & Quantities',
    'pricing'   => 'Quotes & Pricing',
    'delivery'  => 'Delivery & Tracking',
];

// Rich curated FAQs covering all 8 categories
$faqItems = [
    // 1. General Questions
    [
        'category' => 'general',
        'cat_label' => 'General Questions',
        'question' => 'What is OrderPrintex and what printing services do you manufacture?',
        'answer' => '<p>OrderPrintex is a premier manufacturing facility and bulk printing studio based in New Delhi, India. We specialize in industrial-grade <strong>Direct-to-Film (DTF) apparel transfers</strong>, <strong>UV DTF multi-surface transfer decals</strong>, custom screen-quality t-shirt printing, and turnkey corporate promotional kits. We cater to apparel brands, promotional agencies, screen printers, and independent creators across India.</p>'
    ],
    [
        'category' => 'general',
        'cat_label' => 'General Questions',
        'question' => 'Do you serve B2B clients, print studios, and wholesale resellers?',
        'answer' => '<p>Yes! A significant portion of our daily production is dedicated to <strong>job-work and white-label wholesale supply</strong> for screen printing studios, DTG printers, uniform manufacturers, and retail clothing brands. We provide rolls and gang sheets ready for you to press and distribute to your own customers.</p>'
    ],

    // 2. Artwork & File Upload
    [
        'category' => 'artwork',
        'cat_label' => 'Artwork & File Upload',
        'question' => 'Which file formats can I upload for printing?',
        'answer' => '<p>We accept high-resolution files in the following formats:</p>
        <ul>
            <li><strong>PNG (.png):</strong> Recommended for single designs and gang sheets. Must have a transparent background.</li>
            <li><strong>Adobe Illustrator (.ai) & EPS (.eps):</strong> Vector formats. All fonts must be converted to curves/outlines.</li>
            <li><strong>Adobe Photoshop (.psd):</strong> Layered PSD with transparent background at 300 DPI.</li>
            <li><strong>PDF (.pdf):</strong> High-resolution print-ready press PDF with embedded fonts.</li>
        </ul>
        <p>Our online <em>Upload Your Design</em> tool supports files up to 25 MB directly in the browser.</p>'
    ],
    [
        'category' => 'artwork',
        'cat_label' => 'Artwork & File Upload',
        'question' => 'How do I submit my artwork and prepare transparent backgrounds?',
        'answer' => '<p>You can upload artwork directly through the <strong>Upload Your Design</strong> workbench on our Homepage, on our Service pages, or via the <strong>Get a Quote</strong> form. Ensure your artwork has transparent negative spaces wherever you do not want white ink backing to print. If you submit a JPEG with a white background, that white square will be printed unless our prepress team is instructed to remove it.</p>'
    ],
    [
        'category' => 'artwork',
        'cat_label' => 'Artwork & File Upload',
        'question' => 'Can I request a digital design proof before production begins?',
        'answer' => '<p><strong>Yes, absolutely.</strong> Every order undergoes rigorous prepress check by our color technicians. We check resolution, line thickness, color gamut, and bleed margins. For custom projects, digital mockups and RIP proofs are uploaded to your customer portal for approval before physical printing starts.</p>'
    ],

    // 3. DTF Printing
    [
        'category' => 'dtf',
        'cat_label' => 'DTF Printing',
        'question' => 'What is DTF printing and which fabrics does it bond to?',
        'answer' => '<p><strong>DTF (Direct-to-Film)</strong> is a cutting-edge digital garment decoration technique. Artwork is printed onto a specialized PET release film using high-density CMYK textile inks plus a solid white underbase, followed by a layer of hot-melt polyurethane adhesive powder cured under heat. Unlike direct-to-garment (DTG) or screen printing, DTF transfers apply flawlessly to virtually any fabric: <strong>100% cotton, combed cotton, 100% polyester, poly-cotton blends, fleece, denim, nylon, canvas, and performance spandex</strong>.</p>'
    ],
    [
        'category' => 'dtf',
        'cat_label' => 'DTF Printing',
        'question' => 'What is the recommended heat press setting for DTF transfers?',
        'answer' => '<p>For optimal washability and soft-hand feel, follow this industrial application standard:</p>
        <ol>
            <li><strong>Pre-press:</strong> Press the garment for 3–5 seconds to eliminate moisture and flatten fabric fibers.</li>
            <li><strong>Transfer Press:</strong> 160&deg;C (320&deg;F) for 15 seconds under medium-to-firm pressure (40–50 PSI / 4–5 bar).</li>
            <li><strong>Peel:</strong> Wait until the transfer cools completely to room temperature (Cold Peel), then gently peel back the PET carrier film in a smooth, continuous motion.</li>
            <li><strong>Post-press / Sealing:</strong> Cover the graphic with Teflon or silicone parchment paper and press for an additional 5 seconds to seal the transfer into the garment weave.</li>
        </ol>'
    ],
    [
        'category' => 'dtf',
        'cat_label' => 'DTF Printing',
        'question' => 'How do I wash DTF-printed garments to ensure 50+ wash longevity?',
        'answer' => '<p>DTF prints engineered with our high-grade TPU powder withstand 50+ machine washes when cared for properly:</p>
        <ul>
            <li>Turn garment inside-out prior to washing.</li>
            <li>Wash in cold or lukewarm water (max 30&deg;C / 85&deg;F) using mild detergent.</li>
            <li>Avoid bleach, strong optical brighteners, and harsh stain removers directly over the graphic.</li>
            <li>Tumble dry on gentle low heat or hang dry in the shade.</li>
            <li>Never iron directly onto the printed transfer; iron inside-out or cover with a pressing cloth.</li>
        </ul>'
    ],

    // 4. UV DTF Printing
    [
        'category' => 'uv-dtf',
        'cat_label' => 'UV DTF Printing',
        'question' => 'What is UV DTF printing and how does it work?',
        'answer' => '<p><strong>UV DTF</strong> is an innovative cold-transfer technology engineered specifically for hard, non-porous surfaces. Using piezoelectric printheads, the printer deposits white ink, vibrant CMYK inks, and a high-gloss 3D tactile clear varnish, cured instantaneously by ultraviolet LED lamps. The prints are sandwiched between a release paper and a high-tack transfer film. <strong>No heat press or sublimation oven is required.</strong> You simply peel, align, rub firmly, and peel the carrier film away.</p>'
    ],
    [
        'category' => 'uv-dtf',
        'cat_label' => 'UV DTF Printing',
        'question' => 'Which surfaces are compatible with UV DTF transfer stickers?',
        'answer' => '<p>UV DTF stickers bond permanently to clean, rigid, and smooth or lightly contoured materials, including:</p>
        <ul>
            <li><strong>Glassware:</strong> Wine glasses, beer mugs, candle jars, mirrors, and storefront glass.</li>
            <li><strong>Metals:</strong> Stainless steel tumblers, aluminum water flasks, metal business cards, and laptop enclosures.</li>
            <li><strong>Ceramics:</strong> Coffee mugs, decorative plates, tiles, and porcelain.</li>
            <li><strong>Plastics & Acrylics:</strong> Phone cases, acrylic awards, signage, hard luggage, and cosmetic packaging.</li>
            <li><strong>Hard Woods & Leather:</strong> Coated wooden boxes, plaques, leather notebooks, and binders.</li>
        </ul>'
    ],
    [
        'category' => 'uv-dtf',
        'cat_label' => 'UV DTF Printing',
        'question' => 'Are UV DTF decals waterproof, scratch-resistant, and dishwasher safe?',
        'answer' => '<p>Yes. Because the inks are cross-linked under high-intensity UV light and topped with a durable varnish layer, UV DTF stickers are 100% waterproof, UV-sunlight resistant, and scratch-resistant under normal handling. While they endure mild dishwasher cycles, hand-washing with a soft sponge is recommended to ensure maximum longevity on drinkware and tumblers.</p>'
    ],

    // 5. T-Shirt Printing
    [
        'category' => 't-shirt',
        'cat_label' => 'T-Shirt Printing',
        'question' => 'Can I order custom T-shirts and what garment qualities are available?',
        'answer' => '<p>Yes, we offer complete end-to-end t-shirt manufacturing and printing. You can provide your own blanks or choose from our factory-stocked blanks:</p>
        <ul>
            <li><strong>180 GSM Bio-Washed Combed Cotton:</strong> Soft, breathable, pre-shrunk round-neck tees for events, creators, and brands.</li>
            <li><strong>240 GSM Heavyweight Oversized Cotton:</strong> Streetwear drop-shoulder tees with luxury collar ribbing.</li>
            <li><strong>Dry-Fit Polyester & Micro-PP:</strong> Breathable sports jerseys and marathon activewear.</li>
            <li><strong>Pique Knit Polo Collars:</strong> Premium corporate and team polo shirts with optional embroidery or DTF breast logos.</li>
        </ul>'
    ],
    [
        'category' => 't-shirt',
        'cat_label' => 'T-Shirt Printing',
        'question' => 'Where can graphics be placed on custom t-shirts?',
        'answer' => '<p>We support versatile placement configurations: Front Left Chest (pocket print), Center Chest, Full Front (up to 12&times;16 inches), Full Back (up to 12&times;18 inches), Left/Right Sleeve logos, and inside neck brand labels. Multiple print positions can be combined on a single order.</p>'
    ],

    // 6. Orders & Quantities
    [
        'category' => 'orders',
        'cat_label' => 'Orders & Quantities',
        'question' => 'Can I build gang sheets with mixed logos, neck tags, and artwork sizes?',
        'answer' => '<p><strong>Yes!</strong> That is one of our most popular and cost-effective solutions. On our 22-inch roll gang sheets, you can arrange dozens of different logos, neck labels, chest graphics, and sleeve designs on a single roll. There are no color limitations or per-graphic setup fees. You only pay for the total linear length of the gang roll.</p>'
    ],
    [
        'category' => 'orders',
        'cat_label' => 'Orders & Quantities',
        'question' => 'What is the minimum order quantity (MOQ)?',
        'answer' => '<p>We accommodate both small batch sample runs and high-volume commercial production. For DTF gang sheets, you can order from as little as 1 meter roll. For custom printed t-shirts and corporate merchandise, single prototype samples are available before committing to bulk runs of 50, 500, or 5,000+ units.</p>'
    ],

    // 7. Quotes & Pricing
    [
        'category' => 'pricing',
        'cat_label' => 'Quotes & Pricing',
        'question' => 'How can I request a quotation for my printing project?',
        'answer' => '<p>You can request an instant quotation in two simple ways:</p>
        <ol>
            <li>Submit your artwork and required dimensions via our online <strong>Get a Quote</strong> form or the <strong>Upload Your Design</strong> workbench.</li>
            <li>Connect directly with our sales team via WhatsApp (<a href="https://wa.me/919354028199" target="_blank" rel="noopener">+91 93540 28199</a>) with your dimensions, artwork files, and quantity requirements.</li>
        </ol>
        <p>Our prepress coordinators calculate optimal sheet yield and provide an itemized formal quotation with transparent per-unit and volume tiered pricing.</p>'
    ],
    [
        'category' => 'pricing',
        'cat_label' => 'Quotes & Pricing',
        'question' => 'Do you offer tiered discounts for continuous gang rolls and bulk orders?',
        'answer' => '<p>Yes. Commercial printers and brand owners who order recurring volume or continuous rolls (10m, 25m, 50m, 100m+) receive preferential trade wholesale pricing. Contact our Delhi office to set up a dedicated B2B commercial account.</p>'
    ],

    // 8. Delivery & Tracking
    [
        'category' => 'delivery',
        'cat_label' => 'Delivery & Tracking',
        'question' => 'How can I track my order once dispatched?',
        'answer' => '<p>Once your order passes our final quality control inspection, it is packaged in heavy-duty moisture-resistant protective tubes or corrugated mailers. A dispatch confirmation with courier tracking link (Blue Dart, DTDC, Delhivery, or Indian Post Speed Post) is automatically updated in your customer portal and sent via WhatsApp or Email.</p>'
    ],
    [
        'category' => 'delivery',
        'cat_label' => 'Delivery & Tracking',
        'question' => 'What is your standard turnaround time for gang sheets and printed merchandise?',
        'answer' => '<p>Standard production for approved DTF gang sheets and UV DTF decals is typically <strong>24 to 48 hours</strong>. Finished custom t-shirt and bulk promotional merchandise production depends on order quantity (typically 3 to 5 business days). Expedited same-day dispatch from our New Delhi hub is available for urgent orders upon request.</p>'
    ]
];

// Calculate count per category
$categoryCounts = [];
foreach ($categories as $catKey => $catTitle) {
    $categoryCounts[$catKey] = 0;
}
foreach ($faqItems as $item) {
    if (isset($categoryCounts[$item['category']])) {
        $categoryCounts[$item['category']]++;
    }
}
$totalFaqCount = count($faqItems);
?>

<!-- FAQ Hero Section -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Knowledge Base &amp; Prepress Help</span>
      </div>
      <h1 class="page-intro-title">Frequently Asked Questions</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Industrial guidance on DTF transfers, UV DTF cold peel decals, artwork resolution, gang roll economy, heat press calibration, and nationwide dispatch.
      </p>
    </div>
  </div>
</section>

<!-- FAQs Content Section -->
<section class="section section-white" style="padding-top:3.5rem; padding-bottom:5rem;">
  <div class="container container-narrow">

    <!-- Interactive Filter & Search Bar -->
    <div class="faq-filter-wrapper reveal slide-up">
      <!-- Search Box -->
      <div class="faq-search-bar">
        <svg class="faq-search-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
        </svg>
        <input 
          type="text" 
          class="faq-search-input" 
          placeholder="Search questions (e.g. heat press, resolution, gang sheet, formats)..." 
          aria-label="Search FAQs"
        >
        <button type="button" class="faq-search-clear" aria-label="Clear search">&times;</button>
      </div>

      <!-- 8 Category Filter Chips -->
      <div class="faq-categories-pills">
        <button type="button" class="faq-pill-btn active" data-category="all">
          <span>All Questions</span>
          <span class="faq-pill-count"><?= $totalFaqCount ?></span>
        </button>
        <?php foreach ($categories as $catKey => $catTitle): ?>
          <button type="button" class="faq-pill-btn" data-category="<?= e($catKey) ?>">
            <span><?= e($catTitle) ?></span>
            <span class="faq-pill-count"><?= $categoryCounts[$catKey] ?? 0 ?></span>
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Accordion Container -->
    <div class="faq-accordion reveal slide-up">
      <?php foreach ($faqItems as $idx => $faq): ?>
        <article class="faq-item <?= $idx === 0 ? 'active' : '' ?>" data-category="<?= e($faq['category']) ?>">
          <button type="button" class="faq-trigger" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>">
            <div class="faq-question">
              <span class="faq-badge-cat"><?= e($faq['cat_label']) ?></span>
              <span><?= e($faq['question']) ?></span>
            </div>
            <div class="faq-icon-toggle" aria-hidden="true">
              <svg viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 2a.75.75 0 01.75.75v4.5h4.5a.75.75 0 010 1.5h-4.5v4.5a.75.75 0 01-1.5 0v-4.5h-4.5a.75.75 0 010-1.5h4.5v-4.5A.75.75 0 018 2z"/>
              </svg>
            </div>
          </button>
          <div class="faq-body">
            <?= $faq['answer'] ?>
          </div>
        </article>
      <?php endforeach; ?>

      <!-- No Results Placeholder for search filter -->
      <div class="faq-no-results" style="display:none;">
        <h4 style="margin-bottom:0.5rem; color:var(--navy);">No matching questions found</h4>
        <p style="color:var(--slate); margin-bottom:1.25rem;">
          Try searching with different keywords or browse our categories above.
        </p>
        <button type="button" class="btn btn-sm btn-outline" onclick="document.querySelector('.faq-pill-btn[data-category=all]').click(); const inp = document.querySelector('.faq-search-input'); if(inp){ inp.value=''; inp.dispatchEvent(new Event('input')); }">
          Reset Filter
        </button>
      </div>
    </div>

    <!-- Prepress Guidance Footnote -->
    <div style="margin-top:2.5rem; padding:1rem 1.5rem; background-color:var(--ivory); border-radius:var(--radius-sm); border-left:3px solid var(--gold); font-size:0.86rem; color:var(--slate);">
      <strong>Prepress Notice:</strong> Production timelines, gang roll specifications, and technical guidelines represent standard studio practices at our New Delhi facility. Exact delivery estimates and custom pricing are confirmed upon inspection of your high-resolution artwork files.
    </div>

    <!-- Direct Assistance Box -->
    <div style="margin-top:3.5rem; text-align:center; padding:3rem 2rem; background:linear-gradient(135deg, var(--white) 0%, var(--ivory) 100%); border-radius:var(--radius-lg); border:1.5px solid var(--border-gold); box-shadow:var(--shadow-sm);" class="reveal scale-in">
      <div class="section-label" style="justify-content:center;">Direct Prepress Support</div>
      <h3 style="margin-bottom:0.65rem; color:var(--navy); font-size:1.75rem;">Still Have a Technical or Ordering Question?</h3>
      <p style="margin-bottom:2rem; color:var(--slate); max-width:560px; margin-left:auto; margin-right:auto; font-size:0.98rem; line-height:1.6;">
        Our New Delhi prepress and production specialists inspect files daily. Contact us directly for immediate file check guidance or custom quotation assistance.
      </p>
      <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
        <a href="tel:+919354028199" class="btn btn-navy btn-lg" style="display:inline-flex; align-items:center; gap:0.6rem;">
          <svg style="width:18px; height:18px;" viewBox="0 0 20 20" fill="currentColor">
            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/>
          </svg>
          <span>Call +91 93540 28199</span>
        </a>
        <a href="https://wa.me/919354028199?text=Hi%2C%20I%20would%20like%20to%20enquire%20about%20your%20printing%20services." target="_blank" rel="noopener" class="btn btn-primary btn-lg" style="display:inline-flex; align-items:center; gap:0.6rem;">
          <svg style="width:18px; height:18px;" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.586-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.06-2.001-.482-1.637-.68-2.678-2.348-2.759-2.458-.081-.109-.659-.877-.659-1.673 0-.796.417-1.188.566-1.349.148-.162.325-.202.434-.202.108 0 .216 0 .311.006.1.006.234-.038.366.279.135.324.46 1.123.5 1.205.04.082.067.177.013.283-.054.108-.081.175-.162.27-.081.095-.171.212-.245.285-.082.08-.168.168-.072.333.095.165.424.7 0.91 1.134.626.558 1.155.731 1.319.813.165.082.261.07.359-.043.098-.113.42-0.49.532-.658.113-.169.227-.14.382-.083.155.056.985.464 1.154.549.169.084.283.127.324.197.04.07.04.406-.104.811z"/>
          </svg>
          <span>Chat on WhatsApp</span>
        </a>
      </div>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
