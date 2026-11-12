<?php
/**
 * OrderPrintex - Premium About Us Page
 * Redesigned for high visual impact, bold typography, verified technology showcase, and smooth UX.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "About Printex | Your Go To DTF & T-Shirt Printing Studio in Delhi Since 2010";
$pageDesc  = "Printex has been around since 2010, a Delhi printing studio that's been churning out top quality DTF, UV DTF and custom t-shirt prints for brands, shops and businesses in Delhi. You can find us on Pankha Road - come on over and say hi.";
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
            'name' => 'DTF Printing for T-Shirts & Garments',
            'category' => 'Textile Printing',
            'purpose' => 'Our DTF printers do this really neat trick where they lay down a white base and full colour all at once, on one sheet of film. That white base is what keeps the colours looking their best on dark or black garments - small text and fine details stay sharp too, whether it\'s a simple logo or something more intricate.',
            'impact' => 'Sharp text, fine detail & bright colours on darks'
        ],
        [
            'name' => 'Powdering & Curing for Long Lasting Prints',
            'category' => 'Transfer Curing',
            'purpose' => 'After we print a design, we give the film a coat of adhesive powder and then cure it properly. This is what really decides how well the print sticks to the fabric, and how it feels when you wash it - we don\'t rush this step, we take our time.',
            'impact' => 'Properly coated & cured'
        ],
        [
            'name' => 'UV DTF Printing for Stickers & Decals',
            'category' => 'UV Hard Surfaces',
            'purpose' => 'Our UV DTF printer lays down a layer of colour, white and a clear gloss layer on special film. The finished sticker is raised, shiny, and waterproof and goes straight onto glass, steel, acrylic, plastic or wood without needing a heat press - easy peasy!',
            'impact' => 'Glossy 3D finish'
        ],
        [
            'name' => 'Precise Cutting for Clean Edges',
            'category' => 'Finishing & Decals',
            'purpose' => 'We cut out your sticker or decal from the film after printing - that way you get nice neat edges and nothing extra to trim. We also offer kiss-cut sheets, so you can peel one sticker at a time, and die-cut pieces come completely separated.',
            'impact' => 'Kiss-cut & die-cut options'
        ],
        [
            'name' => 'Heat Pressing for an Even Bond',
            'category' => 'Garment Pressing',
            'purpose' => 'When you\'re ordering t-shirts, hoodies or caps, we use a heat press to get the print to bond smoothly right across the design. This is especially important for big orders, where you need every single item to come out looking the same.',
            'impact' => 'Even pressure & heat'
        ],
        [
            'name' => 'File & Colour Check Before Printing',
            'category' => 'Prepress & Quality',
            'purpose' => 'Before we print anything, we take a close look at your file to make sure everything is right - size, resolution, transparent background, the works. After printing, we check the colours and finish before we pack it up. Any problems get sorted out early on, rather than turning up on the final order.',
            'impact' => 'Free file check before printing'
        ]
    ];
}

// Verified technical metadata and editorial highlights for zigzag technology showcase
$techMeta = [
    0 => [
        'num' => '1',
        'image' => 'assets/images/dtf_fabric_print.jpg',
        'badge' => 'Dual Micro-Piezo Inks',
        'pill_class' => 'pill-crimson',
        'line_class' => 'line-crimson',
        'title' => 'DTF Printing for T-Shirts & Garments',
        'desc' => "Our DTF printers do this really neat trick where they lay down a white base and full colour all at once, on one sheet of film. That white base is what keeps the colours looking their best on dark or black garments - small text and fine details stay sharp too, whether it's a simple logo or something more intricate.",
        'highlights' => [
            'Sharp text, fine detail & bright colours on darks',
            'Print in gang sheets to save on film and reduce costs'
        ]
    ],
    1 => [
        'num' => '2',
        'image' => 'assets/images/streetwear_tee.jpg',
        'badge' => 'Automated IR Tunnel',
        'pill_class' => 'pill-gold',
        'line_class' => 'line-gold',
        'title' => 'Powdering & Curing for Long Lasting Prints',
        'desc' => "After we print a design, we give the film a coat of adhesive powder and then cure it properly. This is what really decides how well the print sticks to the fabric, and how it feels when you wash it - we don't rush this step, we take our time.",
        'highlights' => [
            'Properly coated & cured',
            'No peeling or cracking',
            'Smooth, comfortable feel on the fabric'
        ]
    ],
    2 => [
        'num' => '3',
        'image' => 'assets/images/uv_dtf_drinkware.jpg',
        'badge' => 'High-Build 3D Gloss',
        'pill_class' => 'pill-blue',
        'line_class' => 'line-blue',
        'title' => 'UV DTF Printing for Stickers & Decals',
        'desc' => "Our UV DTF printer lays down a layer of colour, white and a clear gloss layer on special film. The finished sticker is raised, shiny, and waterproof and goes straight onto glass, steel, acrylic, plastic or wood without needing a heat press - easy peasy!",
        'highlights' => [
            'Glossy 3D finish',
            'Waterproof & scratch-resistant',
            'Sticks to glass, steel, plastic and acrylic'
        ]
    ],
    3 => [
        'num' => '4',
        'image' => 'assets/images/custom_stickers.jpg',
        'badge' => 'Optical Blade Sensor',
        'pill_class' => 'pill-gold',
        'line_class' => 'line-gold',
        'title' => 'Precise Cutting for Clean Edges',
        'desc' => "We cut out your sticker or decal from the film after printing - that way you get nice neat edges and nothing extra to trim. We also offer kiss-cut sheets, so you can peel one sticker at a time, and die-cut pieces come completely separated.",
        'highlights' => [
            'Kiss-cut & die-cut options',
            'Clean, accurate edges',
            'Ready to peel and apply'
        ]
    ],
    4 => [
        'num' => '5',
        'image' => 'assets/images/custom_tshirt_apparel.jpg',
        'badge' => 'Pneumatic Even-Bond',
        'pill_class' => 'pill-crimson',
        'line_class' => 'line-crimson',
        'title' => 'Heat Pressing for an Even Bond',
        'desc' => "When you're ordering t-shirts, hoodies or caps, we use a heat press to get the print to bond smoothly right across the design. This is especially important for big orders, where you need every single item to come out looking the same.",
        'highlights' => [
            'Even pressure & heat',
            'No scorch marks or cold spots',
            'Same result every time'
        ]
    ],
    5 => [
        'num' => '6',
        'image' => 'assets/images/branding_stationery.jpg',
        'badge' => 'ICC Spectrophotometer',
        'pill_class' => 'pill-blue',
        'line_class' => 'line-blue',
        'title' => 'File & Colour Check Before Printing',
        'desc' => "Before we print anything, we take a close look at your file to make sure everything is right - size, resolution, transparent background, the works. After printing, we check the colours and finish before we pack it up. Any problems get sorted out early on, rather than turning up on the final order.",
        'highlights' => [
            'Free file check before printing',
            'Colour & finish check on every batch',
            "We'll help if there's something wrong on our end"
        ]
    ]
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- ============================================================== -->
<!-- 1. HERO SECTION -->
<!-- ============================================================== -->
<section class="section section-navy page-intro-header" style="position:relative; overflow:hidden; padding-top:clamp(4.5rem, 7vw, 6.5rem); padding-bottom:clamp(4rem, 6vw, 5.5rem); background: radial-gradient(circle at 80% 20%, rgba(212, 175, 55, 0.16) 0%, transparent 55%), radial-gradient(circle at 20% 80%, rgba(143, 107, 30, 0.12) 0%, transparent 50%), #080808;">
  <div class="container text-center">
    <div class="page-intro-box" style="max-width:960px;">
      <!-- Facility Badge -->
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>DELHI Printing Studio &bull; Since 2010</span>
      </div>

      <!-- Required Large Headline -->
      <h1 class="page-intro-title" style="max-width:920px;">
        DTF &amp; T-Shirt Printing in Delhi Done Properly.
      </h1>

      <div class="page-intro-divider"></div>

      <!-- Required Short Supporting Text (1-2 sentences) -->
      <p class="page-intro-desc" style="max-width:780px; margin-bottom:2.5rem;">
        We're Printex, a printing studio based out of Pankha Road, Delhi. We specialise in making DTF transfers, UV DTF stickers and custom tees, hoodies and mugs for brands, print shops and businesses all over India. We're pretty proud of our work.
      </p>

      <!-- Action Buttons -->
      <div style="display:flex; justify-content:center; align-items:center; gap:1.15rem; flex-wrap:wrap; margin-bottom:3.5rem;">
        <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-lg">
          <span>Get a Quote</span> &rarr;
        </a>
        <a href="#what-we-do" class="btn btn-outline" style="border-color:rgba(255,255,255,0.35); color:#FFFFFF;">
          <span>Check Out Our Services</span> &darr;
        </a>
      </div>

    <!-- Metrics Strip -->
    <div class="metrics-strip">
      <div class="metrics-item">
        <div class="metrics-item-value">Since 10 years ago</div>
        <div class="metrics-item-label">We're Still Going Strong on Pankha Road</div>
      </div>
      <div class="metrics-item">
        <div class="metrics-item-value">DTF + UV DTF</div>
        <div class="metrics-item-label">Transfers, stickers, t-shirts - all our speciality</div>
      </div>
      <div class="metrics-item">
        <div class="metrics-item-value">We Check Your Files</div>
        <div class="metrics-item-label">To make sure everything runs smoothly before we print</div>
      </div>
      <div class="metrics-item">
        <div class="metrics-item-value">All Over India</div>
        <div class="metrics-item-label">We deliver our prints via courier with tracking</div>
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
      <span class="section-label" style="justify-content:center; color:var(--blue);">What We Do - Printing Services in Delhi for Anyone</span>
      <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:var(--navy); font-weight:800; line-height:1.2; margin-bottom:0.85rem;">
          Printing Services For Brands, Businesses and Anyone Else
      </h2>
      <p class="section-subtitle" style="font-size:1.05rem; color:var(--slate); margin:0 auto; line-height:1.65;">
        Whether you need 1 sample or 3,000 pieces, we take the stress out of printing. No screens, no setup plates - just tell us what you want to print, and we'll figure out the best way to get it done for you.
      </p>
    </div>

    <div class="about-solutions-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(310px, 1fr)); gap:2rem;">
      <!-- Solution 1: Apparel Brands & Streetwear -->
      <div class="card reveal slide-up stagger-1" style="padding:2.5rem 2rem; display:flex; flex-direction:column; border-top:4px solid var(--crimson);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
          <span style="font-size:2.5rem; line-height:1;">👕</span>
          <span style="background:rgba(168,62,60,0.1); color:var(--crimson); font-size:0.75rem; font-weight:800; padding:0.35rem 0.75rem; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em;">
            DTF Transfers for Tee's
          </span>
        </div>
        <h3 style="font-size:1.35rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">
          We do them for Apparel Brands &amp; Print Shops
        </h3>
        <p style="color:var(--slate); font-size:0.95rem; line-height:1.7; margin-bottom:1.75rem; flex:1;">
          We make DTF transfers that are good to go straight to the press, and gang sheets for everything from cotton tees to hoodies and sportswear. The colours are bright, it feels great to the touch and the prints stay looking good after a few washes. Press em yourself or let us do it for you.
        </p>
        <div style="padding-top:1.25rem; border-top:1px solid rgba(20,35,60,0.08); display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.825rem; font-weight:700; color:var(--slate-light);">Gang Sheets are perfect for Bulk Rolls</span>
          <a href="<?= service_url('dtf-printing') ?>" style="color:var(--crimson); font-weight:700; font-size:0.9rem; text-decoration:none;">Learn more &rarr;</a>
        </div>
      </div>

      <!-- Solution 2: Hard Surfaces & Product Decals -->
      <div class="card reveal slide-up stagger-2" style="padding:2.5rem 2rem; display:flex; flex-direction:column; border-top:4px solid var(--gold);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
          <span style="font-size:2.5rem; line-height:1;">✨</span>
          <span style="background:rgba(185,154,97,0.14); color:var(--gold-dark); font-size:0.75rem; font-weight:800; padding:0.35rem 0.75rem; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em;">
            UV DTF Stickers
          </span>
        </div>
        <h3 style="font-size:1.35rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">
          We do them for Mugs, Bottles &amp; Other Hard Surfaces
        </h3>
        <p style="color:var(--slate); font-size:0.95rem; line-height:1.7; margin-bottom:1.75rem; flex:1;">
          We make UV DTF stickers with a glossy finish that looks like it was meant to be there. They stick to all sorts of things like glass, steel, wood and plastic without even needing a heat press. They're great for mugs, tumblers, gift items and all sorts of other things.
        </p>
        <div style="padding-top:1.25rem; border-top:1px solid rgba(20,35,60,0.08); display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.825rem; font-weight:700; color:var(--slate-light);">They're also waterproof &amp; scratch resistant</span>
          <a href="<?= service_url('uv-dtf-printing') ?>" style="color:var(--gold-dark); font-weight:700; font-size:0.9rem; text-decoration:none;">Learn more &rarr;</a>
        </div>
      </div>

      <!-- Solution 3: Corporate & Commercial Merchandising -->
      <div class="card reveal slide-up stagger-3" style="padding:2.5rem 2rem; display:flex; flex-direction:column; border-top:4px solid var(--gold);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
          <span style="font-size:2.5rem; line-height:1;">🏢</span>
          <span style="background:rgba(212,175,55,0.12); color:#FFD76A; border:1px solid rgba(212,175,55,0.25); font-size:0.75rem; font-weight:800; padding:0.35rem 0.75rem; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em;">
            T-Shirts &amp; Merch
          </span>
        </div>
        <h3 style="font-size:1.35rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">
          We print them for Teams, Events &amp; Corporate Orders
        </h3>
        <p style="color:var(--slate); font-size:0.95rem; line-height:1.7; margin-bottom:1.75rem; flex:1;">
          Custom t-shirt printing, hoodie printing and mug printing from our workshop in Delhi. Plus bulk corporate t-shirts and branded merchandise for offices, teams and events. We'll even give you a GST invoice if you need it.
        </p>
        <div style="padding-top:1.25rem; border-top:1px solid rgba(20,35,60,0.08); display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.825rem; font-weight:700; color:var(--slate-light);">Bulk Orders are no problem for us</span>
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
      <span class="section-label" style="justify-content:center; color:var(--gold-dark);">How We Get the Job Done &bull; Our Workshops in Delhi</span>
      <h2 style="font-size:clamp(2rem, 3.4vw, 2.85rem); color:var(--navy); font-weight:800; line-height:1.2; margin-bottom:0.9rem; letter-spacing:-0.02em;">
        What Goes into Every Print
      </h2>
      <p class="section-subtitle" style="font-size:1.05rem; color:var(--slate); margin:0 auto; line-height:1.68;">
        Every print that comes out of our Pankha Road workshop gets the full treatment - printing, curing, cutting, pressing and a quality check all the way to the end. Then its all packaged up and shipped out to you.
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
        $name = $meta['title'] ?? (!empty($tool['name']) ? $tool['name'] : 'Industrial Printing System');
        $desc = $meta['desc'];
        $highlights = $meta['highlights'];
      ?>
        <div class="tech-zigzag-row <?= $rowClass ?>" data-index="<?= $idx ?>">
          <!-- Media Container (Left on Odd rows 1, 3, 5; Right on Even rows 2, 4, 6) -->
          <div class="tech-zigzag-media">
            <div class="tech-zigzag-img-wrap">
              <img src="<?= url($imgUrl) ?>"
                   alt="<?= e($name) ?>OrderPrintex Delhi"
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
        Why Printex?
      </h2>
      <p class="section-subtitle" style="font-size:1.05rem; color:var(--slate); margin:0 auto; line-height:1.65;">
        We're a small design and print studio based in Delhi, with our own equipment right here - so you can talk to the people actually doing the printing.
      </p>
    </div>

    <!-- 4 Visually Attractive Feature Cards -->
    <div class="about-features-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.75rem;">
      <!-- Feature 1 -->
      <div class="feature-card reveal slide-up stagger-1">
        <div style="width:48px; height:48px; border-radius:10px; background:rgba(212,175,55,0.12); color:#FFD76A; border:1px solid rgba(212,175,55,0.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem;">
          🎨
        </div>
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:700; margin-bottom:0.6rem;">
          No Middlemen Here
        </h3>
        <p style="color:var(--slate); font-size:0.92rem; line-height:1.65; margin:0; flex:1;">
          You get to deal with the team that's going to be printing and packing your order, which means you get straight answers about price, quantity and delivery time.
        </p>
      </div>

      <!-- Feature 2 -->
      <div class="feature-card reveal slide-up stagger-2">
        <div style="width:48px; height:48px; border-radius:10px; background:rgba(185,154,97,0.14); color:var(--gold-dark); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem;">
          ⚡
        </div>
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:700; margin-bottom:0.6rem;">
          Orders Big or Small
        </h3>
        <p style="color:var(--slate); font-size:0.92rem; line-height:1.65; margin:0; flex:1;">
          You can start with a single sample or get a big batch done - no need to pay for pricey screens or setup plates, and repeat orders are a cakewalk.
        </p>
      </div>

      <!-- Feature 3 -->
      <div class="feature-card reveal slide-up stagger-3">
        <div style="width:48px; height:48px; border-radius:10px; background:rgba(13,130,105,0.1); color:var(--emerald); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem;">
          🛡️
        </div>
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:700; margin-bottom:0.6rem;">
          Long-Lasting Prints
        </h3>
        <p style="color:var(--slate); font-size:0.92rem; line-height:1.65; margin:0; flex:1;">
          We get the curing and adhesive just right so your DTF prints don't start to crack or peel off after a few washes. And they stay soft and nice on the fabric too.
        </p>
      </div>

      <!-- Feature 4 -->
      <div class="feature-card reveal slide-up stagger-4">
        <div style="width:48px; height:48px; border-radius:10px; background:rgba(168,62,60,0.1); color:var(--crimson); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem;">
          🔍
        </div>
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:700; margin-bottom:0.6rem;">
          Free File Check
        </h3>
        <p style="color:var(--slate); font-size:0.92rem; line-height:1.65; margin:0; flex:1;">
          Just send us your design - we'll check the size, resolution and make sure you've got a transparent background before we even print it. Any issues get sorted out right away, rather than after the print's all done.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================== -->
<!-- 5. OUR PROCESS (Design → Print → Quality Check → Delivery) -->
<!-- ============================================================== -->
<section class="section section-navy" style="padding-top:clamp(4.5rem, 6.5vw, 6.5rem); padding-bottom:clamp(4.5rem, 6.5vw, 6.5rem); background: radial-gradient(circle at 50% 10%, rgba(212, 175, 55, 0.14) 0%, transparent 60%), #080808;">
  <div class="container">
    <div class="section-header text-center reveal slide-up" style="max-width:760px; margin:0 auto clamp(2.5rem, 4vw, 4rem);">
      <span class="hero-badge" style="display:inline-flex; align-items:center; gap:0.5rem; background:rgba(255,255,255,0.08); color:var(--gold); border:1px solid rgba(185,154,97,0.3); padding:0.4rem 1.15rem; border-radius:999px; font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:1rem;">
        How It Works
      </span>
      <h2 style="font-size:clamp(2rem, 3.2vw, 2.75rem); color:#FFFFFF; font-weight:800; line-height:1.2; margin-bottom:0.85rem;">
        From Your Design to Your Door in 4 Easy Steps
      </h2>
      <p class="section-subtitle" style="font-size:1.05rem; color:rgba(255,255,255,0.80); margin:0 auto; line-height:1.65;">
        Making an order is super easy. Just send us your design and we take care of the printing and delivery.
      </p>
    </div>

    <!-- 4 Connected Process Cards -->
    <div class="process-grid">
      <!-- Step 01: Design -->
      <div class="process-step-card reveal slide-up stagger-1">
        <div class="process-step-num">01</div>
        <h3 class="process-step-title">Send Your Design</h3>
        <p class="process-step-desc">
          Upload it on our website or hit us up on WhatsApp - PNG, PDF, AI and PSD files are all no problem.
        </p>
      </div>

      <!-- Step 02: Print -->
      <div class="process-step-card reveal slide-up stagger-2">
        <div class="process-step-num">02</div>
        <h3 class="process-step-title">Get A Quote</h3>
        <p class="process-step-desc">
          We'll take a look at your file and send you a clear quote with price, quantity and delivery time. GST invoice on request, too.
        </p>
      </div>

      <!-- Step 03: Quality Check -->
      <div class="process-step-card reveal slide-up stagger-3">
        <div class="process-step-num">03</div>
        <h3 class="process-step-title">Print &amp; Check</h3>
        <p class="process-step-desc">
          We get your order printed, cured and checked for colour and finish before we pack it up.
        </p>
      </div>

      <!-- Step 04: Delivery -->
      <div class="process-step-card reveal slide-up stagger-4">
        <div class="process-step-num">04</div>
        <h3 class="process-step-title">Delivery</h3>
        <p class="process-step-desc">
          Packed up safe and sound, and sent out to you by courier anywhere in India. Bonus - if you're in Delhi, you can even swing by and pick it up in person.
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
        <span class="section-label" style="justify-content:center; color:var(--crimson);">GOT A PRINTING PROJECT IN MIND?</span>
      </div>

      <!-- Required CTA Headline -->
      <h2 style="font-size:clamp(2.1rem, 3.5vw, 3rem); color:var(--navy); margin-bottom:1rem; font-weight:800; line-height:1.2;">
        Ready to Get Your Print Job Started?
      </h2>

      <p style="color:var(--slate); font-size:1.1rem; line-height:1.7; margin:0 auto 2.5rem; max-width:620px;">
        Just send us your design or give us some idea of what you're after, and we'll check the file, suggest the right printing method and send you a clear quote.
      </p>

      <!-- Required Get a Quote Button & Secondary CTAs -->
      <div style="display:flex; justify-content:center; align-items:center; gap:1.25rem; flex-wrap:wrap;">
        <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-lg cta-wide-btn">
          <span>Get a Quote</span> &rarr;
        </a>
        <a href="<?= url('services/') ?>" class="btn btn-navy btn-lg cta-wide-btn">
          <span>Take a Look at Our Services</span>
        </a>
        <a href="<?= url('contact/') ?>" class="btn btn-outline btn-lg cta-wide-btn">
          <span>Let's Chat</span>
        </a>
      </div>

      <!-- Quick WhatsApp Helpline -->
      <div style="margin-top:2.25rem; padding-top:1.5rem; border-top:1px solid rgba(20,35,60,0.08); display:flex; align-items:center; justify-content:center; gap:0.65rem; font-size:0.9rem; color:var(--slate);">
        <span>Need a quick chat about DTF or UV DTF? -</span>
        <a href="https://wa.me/919354028199" target="_blank" rel="noopener" style="color:#25D366; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
          <span>WhatsApp us</span> &rarr;
        </a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>