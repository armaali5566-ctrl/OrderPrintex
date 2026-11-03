<?php
/**
 * OrderPrintex - Premium About Us Page
 * Redesigned for high visual impact, bold typography, verified technology showcase, and smooth UX.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "About OrderPrintex — Turning Your Ideas Into Exceptional Prints";
$pageDesc  = "OrderPrintex is a specialized manufacturing facility in New Delhi delivering high-precision DTF transfers, UV DTF decals, and commercial merchandise across India.";
$canonical = url('about/');

// Fetch verified tools and machinery from DB
$tools = [];
if (class_exists('Database') && Database::isConnected()) {
    try {
        $tools = db_fetch_all("SELECT * FROM equipment_tools ORDER BY display_order ASC");
    } catch (Exception $e) {
        $tools = [];
    }
}

// Verified company equipment fallback
if (empty($tools)) {
    $tools = [
        [
            'name' => 'Industrial Micro-Piezo Dual-Head DTF Printers',
            'category' => 'Textile Printing',
            'purpose' => 'Dual Epson i3200 micro-piezo heads laying down high-density white underbase and CMYK textile pigments simultaneously.',
            'impact' => 'Photorealistic 1440x2880 DPI resolution with ultra-smooth gradient transitions and flawless solid color fill on dark garments.'
        ],
        [
            'name' => 'Automatic Powder Shaker & Infrared Conveyor Oven',
            'category' => 'Transfer Curing',
            'purpose' => 'Automated roll-to-roll powdering unit with electrostatic vibration and calibrated infrared heat tunnels.',
            'impact' => 'Guarantees uniform TPU adhesive distribution and complete polyurethane polymerization for 50+ wash longevity.'
        ],
        [
            'name' => 'Multi-Axis UV DTF Hybrid Flatbed & Roll System',
            'category' => 'UV Hard Surfaces',
            'purpose' => 'UV LED curing system printing opaque white, CMYK, and high-build clear spot varnish onto release film.',
            'impact' => 'Creates waterproof, scratchproof 3D tactile embossed decals for glass, tumblers, wood, and electronics.'
        ],
        [
            'name' => 'Digital Precision Optical Contour Cutting Plotters',
            'category' => 'Finishing & Decals',
            'purpose' => 'High-speed cutting plotters with optical registration cameras for precision blade contouring.',
            'impact' => 'Flawless kiss-cut and die-cut borders with sub-millimeter registration accuracy on vinyl and holographic stocks.'
        ],
        [
            'name' => 'Industrial Pneumatic Auto-Open Heat Presses',
            'category' => 'Garment Pressing',
            'purpose' => 'Digital dual-platen pneumatic presses with microprocessor temperature control and consistent pneumatic pressure.',
            'impact' => 'Ensures 100% mechanical fiber bonding of transfers without hot spots, scorching, or uneven adhesion.'
        ],
        [
            'name' => 'X-Rite Prepress Color Spectrophotometers',
            'category' => 'Prepress & Quality',
            'purpose' => 'Spectrophotometric color management instruments calibrating ICC profiles for CMYK and Pantone reproduction.',
            'impact' => 'Guarantees consistent color fidelity between digital client mockups and physical press-ready output.'
        ]
    ];
}

// Verified technical metadata and editorial highlights for zigzag technology showcase
$techMeta = [
    0 => [
        'num' => '01',
        'image' => 'assets/images/dtf_fabric_print.jpg',
        'badge' => 'Dual Micro-Piezo Inks',
        'pill_class' => 'pill-crimson',
        'line_class' => 'line-crimson',
        'desc' => 'Dual Epson i3200 micro-piezo print heads deposit high-density opaque white underbase and CMYK textile pigments simultaneously in a single synchronized pass. This eliminates layer shifts and ensures photorealistic print clarity across both dark and light apparel.',
        'highlights' => [
            '1440 × 2880 DPI photorealistic print resolution',
            'Dual-head synchronized single-pass printing',
            'High-density white underbase for deep contrast'
        ]
    ],
    1 => [
        'num' => '02',
        'image' => 'assets/images/streetwear_tee.jpg',
        'badge' => 'Automated IR Tunnel',
        'pill_class' => 'pill-gold',
        'line_class' => 'line-gold',
        'desc' => 'Automated roll-to-roll powdering system with electrostatic vibration and calibrated multi-zone infrared heat tunnels. It distributes TPU adhesive powder uniformly across wet inks and cures it to exact melt specifications for unmatched elasticity and wash endurance.',
        'highlights' => [
            'Calibrated multi-zone infrared heat tunnels',
            'Electrostatic powder circulation & reclamation',
            '50+ commercial wash cycles guaranteed longevity'
        ]
    ],
    2 => [
        'num' => '03',
        'image' => 'assets/images/uv_dtf_drinkware.jpg',
        'badge' => 'High-Build 3D Gloss',
        'pill_class' => 'pill-blue',
        'line_class' => 'line-blue',
        'desc' => 'Advanced UV LED curing engine that prints opaque white, rich CMYK inks, and high-build clear spot varnish onto specialized carrier film. Transfers effortlessly onto curved glass, stainless steel, acrylic, and wood with zero heat application.',
        'highlights' => [
            '3D tactile embossed spot gloss varnish',
            'Waterproof, scratchproof & dishwasher-safe',
            'Permanent adhesion on glass, metal & plastic'
        ]
    ],
    3 => [
        'num' => '04',
        'image' => 'assets/images/custom_stickers.jpg',
        'badge' => 'Optical Blade Sensor',
        'pill_class' => 'pill-gold',
        'line_class' => 'line-gold',
        'desc' => 'High-speed servo cutting plotters equipped with high-resolution optical registration cameras. Automatically reads printed corner registration marks to execute sub-millimeter contour cuts, kiss-cuts, and die-cuts with flawless edge precision.',
        'highlights' => [
            'Optical registration camera mark tracking',
            'Sub-millimeter blade contour accuracy',
            'Clean kiss-cut & full die-cut border separation'
        ]
    ],
    4 => [
        'num' => '05',
        'image' => 'assets/images/custom_tshirt_apparel.jpg',
        'badge' => 'Pneumatic Even-Bond',
        'pill_class' => 'pill-crimson',
        'line_class' => 'line-crimson',
        'desc' => 'Digital dual-platen pneumatic presses delivering micro-calibrated pressure and digital PID temperature regulation across the entire heating plate. Ensures 100% mechanical fiber bonding without scorching, cold spots, or fabric crush marks.',
        'highlights' => [
            'Dual-platen pneumatic even-pressure distribution',
            'Microprocessor PID digital temperature consistency',
            'Electromagnetic auto-release prevents heat scorching'
        ]
    ],
    5 => [
        'num' => '06',
        'image' => 'assets/images/branding_stationery.jpg',
        'badge' => 'ICC Spectrophotometer',
        'pill_class' => 'pill-blue',
        'line_class' => 'line-blue',
        'desc' => 'Laboratory-grade spectrophotometric measurement instruments used to calibrate and profile our RIP software. Continuous ICC color profiling maintains Delta E < 1.5 color variance between digital client artwork and physical transfers.',
        'highlights' => [
            'Delta E < 1.5 color variance tolerance',
            'Custom ICC profiling across all film media & inks',
            'Spot Pantone & CMYK prepress color validation'
        ]
    ]
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- ============================================================== -->
<!-- 1. HERO SECTION -->
<!-- ============================================================== -->
<section class="section section-navy page-intro-header" style="position:relative; overflow:hidden; padding-top:clamp(4.5rem, 7vw, 6.5rem); padding-bottom:clamp(4rem, 6vw, 5.5rem); background: radial-gradient(circle at 80% 20%, rgba(185, 154, 97, 0.16) 0%, transparent 55%), radial-gradient(circle at 20% 80%, rgba(49, 89, 166, 0.14) 0%, transparent 50%), var(--navy);">
  <div class="container text-center">
    <div class="page-intro-box" style="max-width:960px;">
      <!-- Facility Badge -->
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Delhi Production Facility &bull; Direct Manufacturer</span>
      </div>

      <!-- Required Large Headline -->
      <h1 class="page-intro-title" style="max-width:920px;">
        Turning Your Ideas Into Exceptional Prints.
      </h1>

      <div class="page-intro-divider"></div>

      <!-- Required Short Supporting Text (1-2 sentences) -->
      <p class="page-intro-desc" style="max-width:780px; margin-bottom:2.5rem;">
        OrderPrintex is a specialized manufacturing facility in New Delhi delivering high-precision Direct-to-Film (DTF) apparel transfers, 3D UV DTF decals, and commercial merchandise for brands and print businesses across India.
      </p>

      <!-- Action Buttons -->
      <div style="display:flex; justify-content:center; align-items:center; gap:1.15rem; flex-wrap:wrap; margin-bottom:3.5rem;">
        <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-lg">
          <span>Get a Quote</span> &rarr;
        </a>
        <a href="#what-we-do" class="btn btn-outline" style="border-color:rgba(255,255,255,0.35); color:#FFFFFF;">
          <span>Explore Capabilities</span> &darr;
        </a>
      </div>

    <!-- Metrics Strip -->
    <div class="metrics-strip">
      <div class="metrics-item">
        <div class="metrics-item-value">1440 DPI</div>
        <div class="metrics-item-label">Dual Micro-Piezo Fidelity</div>
      </div>
      <div class="metrics-item">
        <div class="metrics-item-value">50+ Washes</div>
        <div class="metrics-item-label">High-Tack TPU Longevity</div>
      </div>
      <div class="metrics-item">
        <div class="metrics-item-value">100+ Meters</div>
        <div class="metrics-item-label">Daily Roll Production Capacity</div>
      </div>
      <div class="metrics-item">
        <div class="metrics-item-value">PAN-India</div>
        <div class="metrics-item-label">Express Doorstep Dispatch</div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- 2. WHAT WE DO SECTION -->
<!-- ============================================================== -->
<section class="section section-white" id="what-we-do" style="padding-top:clamp(4rem, 6vw, 6rem); padding-bottom:clamp(4rem, 6vw, 6rem);">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:760px; margin:0 auto clamp(2.5rem, 4vw, 3.5rem);">
      <span class="section-label" style="justify-content:center; color:var(--blue);">Specialized Printing Technologies &bull; What We Do</span>
      <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:var(--navy); font-weight:800; line-height:1.2; margin-bottom:0.85rem;">
        Complete Printing Solutions for Businesses, Brands &amp; Individuals
      </h2>
      <p class="section-subtitle" style="font-size:1.05rem; color:var(--slate); margin:0 auto; line-height:1.65;">
        We engineer direct digital transfer solutions that eliminate expensive screen separation plates and minimum order quantities, making retail-grade custom printing accessible for everyone.
      </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(310px, 1fr)); gap:2rem;">
      <!-- Solution 1: Apparel Brands & Streetwear -->
      <div class="card reveal slide-up stagger-1" style="padding:2.5rem 2rem; display:flex; flex-direction:column; border-top:4px solid var(--crimson);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
          <span style="font-size:2.5rem; line-height:1;">👕</span>
          <span style="background:rgba(168,62,60,0.1); color:var(--crimson); font-size:0.75rem; font-weight:800; padding:0.35rem 0.75rem; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em;">
            DTF Transfers
          </span>
        </div>
        <h3 style="font-size:1.35rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">
          For Apparel Brands &amp; Creators
        </h3>
        <p style="color:var(--slate); font-size:0.95rem; line-height:1.7; margin-bottom:1.75rem; flex:1;">
          Continuous roll-to-roll DTF transfers engineered with dual-head opaque white underbases. Press seamlessly onto cotton, poly blends, hoodies, and activewear with ultra-soft hand feel and 50+ wash cycles.
        </p>
        <div style="padding-top:1.25rem; border-top:1px solid rgba(20,35,60,0.08); display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.825rem; font-weight:700; color:var(--slate-light);">24-Inch Gang Sheets &bull; TPU Adhesive</span>
          <a href="<?= service_url('dtf-printing') ?>" style="color:var(--crimson); font-weight:700; font-size:0.9rem; text-decoration:none;">Learn more &rarr;</a>
        </div>
      </div>

      <!-- Solution 2: Hard Surfaces & Product Decals -->
      <div class="card reveal slide-up stagger-2" style="padding:2.5rem 2rem; display:flex; flex-direction:column; border-top:4px solid var(--gold);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
          <span style="font-size:2.5rem; line-height:1;">✨</span>
          <span style="background:rgba(185,154,97,0.14); color:var(--gold-dark); font-size:0.75rem; font-weight:800; padding:0.35rem 0.75rem; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em;">
            UV DTF 3D Decals
          </span>
        </div>
        <h3 style="font-size:1.35rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">
          For Product Packaging &amp; Hard Substrates
        </h3>
        <p style="color:var(--slate); font-size:0.95rem; line-height:1.7; margin-bottom:1.75rem; flex:1;">
          Peel-and-stick 3D tactile decals featuring clear spot gloss varnish. Permanent waterproof adhesion on glass, tumblers, stainless steel, acrylic, electronics, and retail packaging with zero heat press required.
        </p>
        <div style="padding-top:1.25rem; border-top:1px solid rgba(20,35,60,0.08); display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.825rem; font-weight:700; color:var(--slate-light);">Spot Gloss Varnish &bull; Dishwasher Safe</span>
          <a href="<?= service_url('uv-dtf-printing') ?>" style="color:var(--gold-dark); font-weight:700; font-size:0.9rem; text-decoration:none;">Learn more &rarr;</a>
        </div>
      </div>

      <!-- Solution 3: Corporate & Commercial Merchandising -->
      <div class="card reveal slide-up stagger-3" style="padding:2.5rem 2rem; display:flex; flex-direction:column; border-top:4px solid var(--blue);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
          <span style="font-size:2.5rem; line-height:1;">🏢</span>
          <span style="background:rgba(49,89,166,0.1); color:var(--blue); font-size:0.75rem; font-weight:800; padding:0.35rem 0.75rem; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em;">
            B2B Commercial
          </span>
        </div>
        <h3 style="font-size:1.35rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">
          For Enterprises &amp; Corporate Teams
        </h3>
        <p style="color:var(--slate); font-size:0.95rem; line-height:1.7; margin-bottom:1.75rem; flex:1;">
          Turnkey merchandise kits, custom uniform t-shirts, branded corporate stationery, and customized presentation boxes. Supported by itemized GST tax invoicing and reliable scheduled bulk dispatch across India.
        </p>
        <div style="padding-top:1.25rem; border-top:1px solid rgba(20,35,60,0.08); display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.825rem; font-weight:700; color:var(--slate-light);">GST Invoicing &bull; Wholesale Tiers</span>
          <a href="<?= service_url('corporate-printing') ?>" style="color:var(--blue); font-weight:700; font-size:0.9rem; text-decoration:none;">Learn more &rarr;</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- 3. OUR TECHNOLOGY SECTION (Verified Equipment Only) -->
<!-- ============================================================== -->
<section class="section section-ivory" id="our-technology" style="padding-top:clamp(4.5rem, 7vw, 6.5rem); padding-bottom:clamp(4.5rem, 7vw, 6.5rem);">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:780px; margin:0 auto clamp(3rem, 5vw, 4.5rem);">
      <span class="section-label" style="justify-content:center; color:var(--gold-dark);">Industrial Print Facility &bull; Verified Equipment</span>
      <h2 style="font-size:clamp(2rem, 3.4vw, 2.85rem); color:var(--navy); font-weight:800; line-height:1.2; margin-bottom:0.9rem; letter-spacing:-0.02em;">
        Tools &amp; Printing Technologies
      </h2>
      <p class="section-subtitle" style="font-size:1.05rem; color:var(--slate); margin:0 auto; line-height:1.68;">
        Inside our Pankha Road Delhi workshop, industrial print engines, automated curing tunnels, and spectrophotometric color management deliver guaranteed production fidelity.
      </p>
    </div>

    <!-- Editorial Alternating Zigzag Layout (Rows 01 to 06) -->
    <div class="tech-zigzag-container">
      <?php foreach ($tools as $idx => $tool):
        $meta = $techMeta[$idx] ?? $techMeta[0];
        $isOdd = ($idx % 2 === 0); // 0, 2, 4 -> Row 1, Row 3, Row 5 (Odd: Image Left, Text Right)
        $rowClass = $isOdd ? 'tech-row-odd' : 'tech-row-even';
        $num = $meta['num'];
        $imgUrl = (!empty($tool['image_url']) && ($idx === 0 || $tool['image_url'] !== 'assets/images/dtf_fabric_print.jpg')) 
                  ? $tool['image_url'] 
                  : $meta['image'];
        $category = !empty($tool['category']) ? $tool['category'] : 'Printing Technology';
        $name = !empty($tool['name']) ? $tool['name'] : 'Industrial Printing System';
        $desc = $meta['desc'];
        $highlights = $meta['highlights'];
      ?>
        <div class="tech-zigzag-row <?= $rowClass ?>" data-index="<?= $idx ?>">
          <!-- Media Container (Left on Odd rows 1, 3, 5; Right on Even rows 2, 4, 6) -->
          <div class="tech-zigzag-media">
            <div class="tech-zigzag-img-wrap">
              <img src="<?= url($imgUrl) ?>"
                   alt="<?= e($name) ?> - OrderPrintex Delhi"
                   class="tech-zigzag-img"
                   loading="lazy"
                   width="1200"
                   height="896">
              <div class="tech-media-badge">
                <span class="tech-media-badge-dot"></span>
                <span><?= e($meta['badge']) ?></span>
              </div>
            </div>
          </div>

          <!-- Content Container (Right on Odd rows 1, 3, 5; Left on Even rows 2, 4, 6) -->
          <div class="tech-zigzag-content">
            <div class="tech-header-meta">
              <span class="tech-category-pill <?= $meta['pill_class'] ?>"><?= e($category) ?></span>
              <span class="tech-num"><?= $num ?></span>
            </div>

            <h3 class="tech-title"><?= e($name) ?></h3>

            <div class="tech-accent-line <?= $meta['line_class'] ?>"></div>

            <p class="tech-desc"><?= e($desc) ?></p>

            <div class="tech-highlights">
              <?php foreach ($highlights as $hl): ?>
                <div class="tech-highlight-item">
                  <svg class="tech-highlight-icon" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="1.8" opacity="0.25"/>
                    <path d="M6 10.2L8.7 13L14 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span><?= e($hl) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- 4. WHY CHOOSE US SECTION (3-4 Feature Cards) -->
<!-- ============================================================== -->
<section class="section section-white" style="padding-top:clamp(4rem, 6vw, 6rem); padding-bottom:clamp(4rem, 6vw, 6rem);">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:740px; margin:0 auto clamp(2.5rem, 4vw, 3.5rem);">
      <span class="section-label" style="justify-content:center; color:var(--crimson);">The Printex Advantage</span>
      <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:var(--navy); font-weight:800; line-height:1.2; margin-bottom:0.85rem;">
        Why Choose OrderPrintex?
      </h2>
      <p class="section-subtitle" style="font-size:1.05rem; color:var(--slate); margin:0 auto; line-height:1.65;">
        Engineered from prepress file audit to final curing for uncompromised print clarity, wash resistance, and predictable turnaround.
      </p>
    </div>

    <!-- 4 Visually Attractive Feature Cards -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.75rem;">
      <!-- Feature 1 -->
      <div class="feature-card reveal slide-up stagger-1">
        <div style="width:48px; height:48px; border-radius:10px; background:rgba(49,89,166,0.1); color:var(--blue); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem;">
          🎨
        </div>
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:700; margin-bottom:0.6rem;">
          Dual-Head Japanese Inks
        </h3>
        <p style="color:var(--slate); font-size:0.92rem; line-height:1.65; margin:0; flex:1;">
          Calibrated high-opacity white ink underbases deliver rich, photorealistic color saturation on any light or dark garment with seamless micro-gradient transitions.
        </p>
      </div>

      <!-- Feature 2 -->
      <div class="feature-card reveal slide-up stagger-2">
        <div style="width:48px; height:48px; border-radius:10px; background:rgba(185,154,97,0.14); color:var(--gold-dark); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem;">
          ⚡
        </div>
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:700; margin-bottom:0.6rem;">
          Zero Setup Plates or Fees
        </h3>
        <p style="color:var(--slate); font-size:0.92rem; line-height:1.65; margin:0; flex:1;">
          Digital roll-to-roll gang sheet printing eliminates costly screen separations and rigid minimum order limits. Print single sample drops or 1,000+ meter rolls with identical per-sq-inch efficiency.
        </p>
      </div>

      <!-- Feature 3 -->
      <div class="feature-card reveal slide-up stagger-3">
        <div style="width:48px; height:48px; border-radius:10px; background:rgba(13,130,105,0.1); color:var(--emerald); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem;">
          🛡️
        </div>
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:700; margin-bottom:0.6rem;">
          50+ Wash Longevity
        </h3>
        <p style="color:var(--slate); font-size:0.92rem; line-height:1.65; margin:0; flex:1;">
          Automated infrared curing tunnels and high-grade TPU adhesive powder provide unbeatable elasticity and long-lasting wash performance without cracking, peeling, or fading.
        </p>
      </div>

      <!-- Feature 4 -->
      <div class="feature-card reveal slide-up stagger-4">
        <div style="width:48px; height:48px; border-radius:10px; background:rgba(168,62,60,0.1); color:var(--crimson); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem;">
          🔍
        </div>
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:700; margin-bottom:0.6rem;">
          Free Prepress File Audit
        </h3>
        <p style="color:var(--slate); font-size:0.92rem; line-height:1.65; margin:0; flex:1;">
          Every artwork upload is inspected by Delhi prepress technicians for alpha transparency, line resolution, bleed margins, and CMYK trapping before a single drop of ink is laid.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- 5. OUR PROCESS (Design → Print → Quality Check → Delivery) -->
<!-- ============================================================== -->
<section class="section section-navy" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem); background: radial-gradient(circle at 50% 10%, rgba(49, 89, 166, 0.18) 0%, transparent 60%), var(--navy);">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:760px; margin:0 auto clamp(2.5rem, 4vw, 4rem);">
      <span class="hero-badge" style="display:inline-flex; align-items:center; gap:0.5rem; background:rgba(255,255,255,0.08); color:var(--gold); border:1px solid rgba(185,154,97,0.3); padding:0.4rem 1.15rem; border-radius:999px; font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:1rem;">
        Design &bull; Print &bull; Quality Check &bull; Delivery
      </span>
      <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:#FFFFFF; font-weight:800; line-height:1.2; margin-bottom:0.85rem;">
        Simple Four-Step Production Flow
      </h2>
      <p class="section-subtitle" style="font-size:1.05rem; color:rgba(255,255,255,0.80); margin:0 auto; line-height:1.65;">
        A predictable, high-yield manufacturing pipeline engineered for dependable turnaround from digital artwork to finished parcel.
      </p>
    </div>

    <!-- 4 Connected Process Cards -->
    <div class="process-grid">
      <!-- Step 01: Design -->
      <div class="process-step-card reveal slide-up stagger-1">
        <div class="process-step-num">01</div>
        <h3 class="process-step-title">Design</h3>
        <p class="process-step-desc">
          Upload your transparent PNG, vector PDF, or gang sheet. Technicians inspect resolution trapping and size specifications.
        </p>
      </div>

      <!-- Step 02: Print -->
      <div class="process-step-card reveal slide-up stagger-2">
        <div class="process-step-num">02</div>
        <h3 class="process-step-title">Print</h3>
        <p class="process-step-desc">
          Dual-head industrial print engines lay down high-density white underbase and rich CMYK pigments simultaneously onto PET release film.
        </p>
      </div>

      <!-- Step 03: Quality Check -->
      <div class="process-step-card reveal slide-up stagger-3">
        <div class="process-step-num">03</div>
        <h3 class="process-step-title">Quality Check</h3>
        <p class="process-step-desc">
          Infrared powder curing is monitored, followed by adhesion pull tests and spectrophotometric color verification against ICC standards.
        </p>
      </div>

      <!-- Step 04: Delivery -->
      <div class="process-step-card reveal slide-up stagger-4">
        <div class="process-step-num">04</div>
        <h3 class="process-step-title">Delivery</h3>
        <p class="process-step-desc">
          Moisture-sealed protective packaging and express insured courier dispatch to your doorstep anywhere in India with live milestone tracking.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- 6. FINAL CTA SECTION -->
<!-- ============================================================== -->
<section class="section section-white" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem);">
  <div class="container container-narrow reveal scale-in">
    <div class="card" style="padding:clamp(2.5rem, 4vw, 4rem) clamp(1.75rem, 3.5vw, 3.5rem); text-align:center; border:1px solid rgba(185,154,97,0.35); border-top:5px solid var(--gold); box-shadow:0 8px 24px rgba(15,25,45,0.12), 0 20px 48px rgba(15,25,45,0.14);">
      
      <div style="margin-bottom:1rem;">
        <span class="section-label" style="justify-content:center; color:var(--crimson);">Have a Printing Project in Mind?</span>
      </div>

      <!-- Required CTA Headline -->
      <h2 style="font-size:clamp(2.1rem, 3.5vw, 3rem); color:var(--navy); margin-bottom:1rem; font-weight:800; line-height:1.2;">
        Ready to Bring Your Ideas to Life?
      </h2>

      <p style="color:var(--slate); font-size:1.1rem; line-height:1.7; margin:0 auto 2.5rem; max-width:620px;">
        Share your design files or gang sheet dimensions today for a complimentary prepress audit, instant color advisory, and itemized commercial quote.
      </p>

      <!-- Required Get a Quote Button & Secondary CTAs -->
      <div style="display:flex; justify-content:center; align-items:center; gap:1.25rem; flex-wrap:wrap;">
        <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-lg" style="padding:1rem 2.5rem; font-size:1.05rem;">
          <span>Get a Quote</span> &rarr;
        </a>
        <a href="<?= url('services/') ?>" class="btn btn-navy btn-lg" style="padding:1rem 2.25rem; font-size:1.05rem;">
          <span>Explore Services</span>
        </a>
        <a href="<?= url('contact/') ?>" class="btn btn-outline btn-lg" style="padding:1rem 2.25rem; font-size:1.05rem;">
          <span>Contact Delhi Studio</span>
        </a>
      </div>

      <!-- Quick WhatsApp Helpline -->
      <div style="margin-top:2.25rem; padding-top:1.5rem; border-top:1px solid rgba(20,35,60,0.08); display:flex; align-items:center; justify-content:center; gap:0.65rem; font-size:0.9rem; color:var(--slate);">
        <span>Need urgent gang sheet advice?</span>
        <a href="https://wa.me/919354028199" target="_blank" rel="noopener" style="color:#25D366; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
          <span>Chat on WhatsApp</span> &rarr;
        </a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
