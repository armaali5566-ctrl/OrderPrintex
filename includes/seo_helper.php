<?php
/**
 * OrderPrintex - Centralized SEO, Sitemap, LLMs.txt, Schema.org JSON-LD & Documentation Engine
 * Complete Website-Wide Automated Synchronization for Search Engines and AI Discovery.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

/**
 * Return curated FAQs for use across documentation, LLMs, and Schema generation.
 * Covers all 8 core printing and prepress operational categories.
 */
function seo_get_curated_faqs(): array {
    return [
        [
            'category'  => 'General Questions',
            'question'  => 'What is OrderPrintex and what printing services do you manufacture?',
            'answer'    => 'OrderPrintex is a premier manufacturing facility and bulk printing studio based in New Delhi, India. We specialize in industrial-grade Direct-to-Film (DTF) apparel transfers, UV DTF multi-surface transfer decals, custom screen-quality t-shirt printing, and turnkey corporate promotional kits. We cater to apparel brands, promotional agencies, screen printers, and independent creators across India.'
        ],
        [
            'category'  => 'General Questions',
            'question'  => 'Do you serve B2B clients, print studios, and wholesale resellers?',
            'answer'    => 'Yes! A significant portion of our daily production is dedicated to job-work and white-label wholesale supply for screen printing studios, DTG printers, uniform manufacturers, and retail clothing brands. We provide rolls and gang sheets ready for you to press and distribute to your own customers.'
        ],
        [
            'category'  => 'Artwork & File Upload',
            'question'  => 'Which file formats can I upload for printing?',
            'answer'    => 'We accept high-resolution files in Transparent PNG (300 DPI, recommended for single designs and gang sheets), vector Adobe Illustrator (.ai), EPS (.eps), layered Adobe Photoshop (.psd at 300 DPI), and print-ready vector PDF with embedded fonts.'
        ],
        [
            'category'  => 'Artwork & File Upload',
            'question'  => 'How do I submit my artwork and prepare transparent backgrounds?',
            'answer'    => 'You can upload artwork directly through the Upload Your Design workbench on our Homepage, on our Service pages, or via the Get a Quote form. Ensure your artwork has transparent negative spaces wherever you do not want white ink backing to print. If you submit a JPEG with a white background, that white square will print as solid opaque white ink.'
        ],
        [
            'category'  => 'Artwork & File Upload',
            'question'  => 'Can I request a digital design proof before production begins?',
            'answer'    => 'Yes, absolutely. Every order undergoes rigorous prepress review by our color technicians. We verify resolution, line thickness, color gamut, and bleed margins. For custom projects, digital mockups and RIP proofs are shared via the client portal for formal approval before physical printing starts.'
        ],
        [
            'category'  => 'DTF Printing',
            'question'  => 'What is DTF printing and which fabrics does it bond to?',
            'answer'    => 'DTF (Direct-to-Film) is a cutting-edge digital garment decoration technique. Artwork is printed onto specialized PET release film using high-density CMYK textile inks plus a solid white underbase, followed by a layer of hot-melt polyurethane adhesive powder cured under heat. DTF transfers apply flawlessly to virtually any fabric: 100% cotton, combed cotton, 100% polyester, poly-cotton blends, fleece, denim, nylon, canvas, and performance spandex.'
        ],
        [
            'category'  => 'DTF Printing',
            'question'  => 'What is the recommended heat press setting for DTF transfers?',
            'answer'    => 'For optimal washability and soft-hand feel: 1) Pre-press garment 3-5 seconds to remove moisture. 2) Transfer Press at 160°C (320°F) for 15 seconds under medium-to-firm pressure (40-50 PSI / 4-5 bar). 3) Cold Peel: Allow the transfer to cool completely to room temperature, then peel PET film smoothly. 4) Post-press: Cover graphic with Teflon or silicone parchment paper and press for 5 seconds to seal the transfer into the weave.'
        ],
        [
            'category'  => 'DTF Printing',
            'question'  => 'How do I wash DTF-printed garments to ensure 50+ wash longevity?',
            'answer'    => 'Turn garments inside-out, wash in cold or lukewarm water (max 30°C / 85°F) with mild detergent, avoid bleach or harsh spot removers, tumble dry on gentle low heat or hang dry in shade, and never iron directly on the printed graphic.'
        ],
        [
            'category'  => 'UV DTF Printing',
            'question'  => 'What is UV DTF printing and how does it work?',
            'answer'    => 'UV DTF is an innovative cold-transfer technology engineered specifically for hard, non-porous surfaces. Using piezoelectric printheads, the printer deposits white ink, vibrant CMYK inks, and a high-gloss 3D tactile clear varnish, cured instantaneously by ultraviolet LED lamps. Prints are applied directly to products without heat presses or ovens: peel, align, rub firmly, and peel away the carrier film.'
        ],
        [
            'category'  => 'UV DTF Printing',
            'question'  => 'Which surfaces are compatible with UV DTF transfer stickers?',
            'answer'    => 'UV DTF stickers bond permanently to clean, rigid, and smooth or lightly contoured materials including glassware, stainless steel tumblers, aluminum water flasks, ceramic mugs, plastics, acrylics, cell phone cases, rigid electronics, lacquered wood, and cosmetic packaging.'
        ],
        [
            'category'  => 'UV DTF Printing',
            'question'  => 'Are UV DTF decals waterproof, scratch-resistant, and dishwasher safe?',
            'answer'    => 'Yes. Because inks are cross-linked under high-intensity UV LED light and sealed with a heavy clear spot varnish layer, UV DTF stickers are 100% waterproof, UV-resistant, and scratch-resistant under normal handling. For maximum longevity on drinkware, hand-washing with a soft sponge is recommended.'
        ],
        [
            'category'  => 'T-Shirt Printing',
            'question'  => 'Can I order custom T-shirts and what garment qualities are available?',
            'answer'    => 'Yes! We offer complete end-to-end t-shirt manufacturing and printing. Clients can supply their own blanks or choose from our factory inventory: 180 GSM bio-washed combed cotton, 240 GSM heavyweight oversized streetwear tees, dry-fit sports polyester jerseys, and pique knit corporate polo shirts.'
        ],
        [
            'category'  => 'T-Shirt Printing',
            'question'  => 'Where can graphics be placed on custom t-shirts?',
            'answer'    => 'We support Left Chest pocket prints, Center Chest, Full Front (up to 12x16 inches), Full Back (up to 12x18 inches), Left/Right Sleeve branding, and interior neck custom brand labels. Multiple positions can be combined on a single run.'
        ],
        [
            'category'  => 'Orders & Quantities',
            'question'  => 'Can I build gang sheets with mixed logos, neck tags, and artwork sizes?',
            'answer'    => 'Yes! On our 22-inch roll gang sheets, you can arrange dozens of different logos, neck labels, chest graphics, and sleeve designs on a single roll with no per-design setup fees. You only pay for the total linear length of the gang roll.'
        ],
        [
            'category'  => 'Orders & Quantities',
            'question'  => 'What is the minimum order quantity (MOQ)?',
            'answer'    => 'For DTF gang sheets, you can order from as little as 1 meter roll. For custom printed t-shirts and corporate merchandise, single prototype samples are available before committing to bulk production runs of 50, 500, or 5,000+ units.'
        ],
        [
            'category'  => 'Quotes & Pricing',
            'question'  => 'How can I request a quotation for my printing project?',
            'answer'    => 'Submit your artwork and required dimensions via our online Get a Quote form, upload via the homepage design tool, or contact our sales team via WhatsApp (+91 93540 28199). Prepress coordinators calculate optimal sheet yield and provide an itemized formal quotation with transparent per-unit and volume tiered pricing.'
        ],
        [
            'category'  => 'Quotes & Pricing',
            'question'  => 'Do you offer tiered discounts for continuous gang rolls and bulk orders?',
            'answer'    => 'Yes. Commercial printers and brand owners who order recurring volume or continuous rolls (10m, 25m, 50m, 100m+) receive preferential trade wholesale pricing. Contact our Delhi facility to set up a dedicated B2B trade account.'
        ],
        [
            'category'  => 'Delivery & Tracking',
            'question'  => 'How can I track my order once dispatched?',
            'answer'    => 'Once your order passes our final quality control inspection, it is packaged in heavy-duty moisture-resistant protective tubes or corrugated mailers. Dispatch details and tracking links (Blue Dart, DTDC, Delhivery, or India Post Speed Post) are updated in your customer portal and notified via WhatsApp or Email.'
        ],
        [
            'category'  => 'Delivery & Tracking',
            'question'  => 'What is your standard turnaround time for gang sheets and printed merchandise?',
            'answer'    => 'Standard production for approved DTF gang sheets and UV DTF decals is typically 24 to 48 hours. Finished custom t-shirts and bulk promotional merchandise take 3 to 5 business days depending on volume. Expedited same-day dispatch from our New Delhi hub is available for urgent rush orders upon request.'
        ]
    ];
}

/**
 * Return curated portfolio showcase specimens.
 */
function seo_get_portfolio_items(): array {
    $dbItems = db_fetch_all("SELECT * FROM gallery WHERE is_published = 1 ORDER BY display_order ASC, id ASC");
    if (!empty($dbItems)) {
        return $dbItems;
    }
    return [
        [
            'id' => 1,
            'title' => 'High-Detail DTF Apparel Print on Dark Garment',
            'category' => 'dtf',
            'image_url' => 'assets/images/dtf_fabric_print.jpg',
            'description' => 'Direct-to-Film transfer showcasing ultra-fine color gradients, opaque white underbase, and soft stretch elasticity without hand feel stiffness.'
        ],
        [
            'id' => 2,
            'title' => 'UV DTF Decals on Glass & Tumbler Curved Surfaces',
            'category' => 'uv_dtf',
            'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
            'description' => 'Industrial-adhesion 3D tactile UV DTF stickers applied seamlessly onto curved stainless steel bottles and glassware with spot gloss varnish.'
        ],
        [
            'id' => 3,
            'title' => 'Heavyweight 240 GSM Streetwear Graphic Tee',
            'category' => 'tshirt',
            'image_url' => 'assets/images/streetwear_tee.jpg',
            'description' => 'Vintage washed boxy drop-shoulder t-shirt with photographic DTF chest graphic, double-needle stitched seams, and tight retail ribbing.'
        ],
        [
            'id' => 4,
            'title' => 'Luxury Apparel Unboxing Mailer Packaging Box',
            'category' => 'packaging',
            'image_url' => 'assets/images/packaging_box.jpg',
            'description' => 'Custom matte black corrugated mailer box with hot-stamped gold foil logo, spot UV accents, and inner branded tissue wrap.'
        ],
        [
            'id' => 5,
            'title' => 'Custom Die-Cut & Holographic Decal Collection',
            'category' => 'uv_dtf',
            'image_url' => 'assets/images/custom_stickers.jpg',
            'description' => 'Multi-surface peel-and-stick vinyl decals with embossed gloss spot varnish, iridescent light reflection, and permanent acrylic bond.'
        ],
        [
            'id' => 6,
            'title' => 'Executive Corporate Swag & Client Gifting Kit',
            'category' => 'corporate',
            'image_url' => 'assets/images/corporate_swag_kit.jpg',
            'description' => 'Turnkey corporate onboarding kit runs featuring customized insulated drinkware, embossed diary, and stylus pen in a bespoke gift hamper.'
        ],
        [
            'id' => 7,
            'title' => 'Retail Brand Collection & Crimson Studio Apparel',
            'category' => 'tshirt',
            'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
            'description' => 'Studio fashion apparel featuring rich crimson red garment dyes, true-match ICC color profiling, and breathable photographic chest prints.'
        ],
        [
            'id' => 8,
            'title' => 'Luxury Embossed Branding Collateral & Stationery',
            'category' => 'packaging',
            'image_url' => 'assets/images/branding_stationery.jpg',
            'description' => 'Thick crimson red and warm beige business cards with edge gilding, debossed linen corporate journal, and executive presentation folders.'
        ]
    ];
}

/**
 * Synchronize all four SEO, AI & Documentation artifacts:
 * 1. sitemap.xml
 * 2. llms.txt
 * 3. schema.json
 * 4. ORDERPRINTEX.md (and SITE_OVERVIEW.md)
 *
 * @return array Detailed operation metrics and validation summary.
 */
function seo_sync_all(): array {
    $rootDir = realpath(__DIR__ . '/..');
    if (!$rootDir) {
        $rootDir = 'C:/Movies/Internship/OrderPrintex';
    }

    $errors = [];
    $today = date('Y-m-d');
    $nowIso = date('c');

    try {
        // -------------------------------------------------------------
        // FETCH ALL RELEVANT DATA FROM DATABASE
        // -------------------------------------------------------------
        $siteName = get_setting('site_name', 'OrderPrintex');
        $siteEmail = get_setting('contact_email', 'contact@orderprintex.com');
        $sitePhone = get_setting('contact_phone', '+91 93540 28199');
        $siteAddress = get_setting('contact_address', 'Okhla Industrial Area, Phase II, New Delhi - 110020');
        $siteHours = get_setting('operating_hours', 'Monday - Saturday: 9:30 AM - 7:30 PM IST');
        $metaDesc = get_setting('meta_description', 'OrderPrintex is a premier manufacturing facility and digital transfer printing studio based in New Delhi, India specializing in DTF apparel transfers, UV DTF decals, custom printed apparel, and corporate merchandise.');

        // Products with category and pricing
        $products = db_fetch_all("
            SELECT p.*, c.name as category_name, c.slug as category_slug, s.title as service_title, s.slug as service_slug
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN services s ON p.service_id = s.id
            WHERE p.is_active = 1
            ORDER BY p.display_order ASC, p.id ASC
        ") ?: [];

        // Fetch sizes and pricing tiers for all products
        $productSizes = [];
        $sizesRows = db_fetch_all("SELECT * FROM product_sizes ORDER BY display_order ASC, id ASC") ?: [];
        foreach ($sizesRows as $sr) {
            $productSizes[$sr['product_id']][] = $sr;
        }

        $pricingTiers = [];
        $tierRows = db_fetch_all("SELECT * FROM product_pricing_tiers ORDER BY min_quantity ASC, id ASC") ?: [];
        foreach ($tierRows as $tr) {
            $pricingTiers[$tr['product_id']][] = $tr;
        }

        // Reviews
        $reviewsByProduct = [];
        try {
            $revRows = db_fetch_all("SELECT * FROM product_reviews WHERE status = 'approved' ORDER BY id DESC") ?: [];
            foreach ($revRows as $rr) {
                $reviewsByProduct[$rr['product_id']][] = $rr;
            }
        } catch (Exception $e) {
            // table optional
        }

        // Categories
        $categories = db_fetch_all("SELECT * FROM categories ORDER BY display_order ASC, id ASC") ?: [];

        // Services
        $services = db_fetch_all("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC, id ASC") ?: [];

        // Blog Posts
        $blogPosts = db_fetch_all("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC") ?: [];

        // Equipment
        $equipment = db_fetch_all("SELECT * FROM equipment_tools WHERE is_active = 1 ORDER BY display_order ASC, id ASC") ?: [];

        // Static Pages
        $staticPages = db_fetch_all("SELECT * FROM pages WHERE is_published = 1 ORDER BY id ASC") ?: [];

        // Curated FAQs
        $faqs = seo_get_curated_faqs();

        // Portfolio Items
        $portfolioItems = seo_get_portfolio_items();

        // -------------------------------------------------------------
        // 1. GENERATE SITEMAP.XML (AND SITEMAP_INDEX.XML)
        // -------------------------------------------------------------
        $urls = [];

        // Core Static Landing Pages
        $corePages = [
            ['loc' => url(''),             'lastmod' => $today, 'changefreq' => 'daily',   'priority' => '1.0'],
            ['loc' => url('products/'),     'lastmod' => $today, 'changefreq' => 'daily',   'priority' => '0.95'],
            ['loc' => url('services/'),     'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.95'],
            ['loc' => url('pricing/'),      'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.90'],
            ['loc' => url('get-quote/'),    'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.90'],
            ['loc' => url('gallery/'),      'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.85'],
            ['loc' => url('blog/'),         'lastmod' => $today, 'changefreq' => 'daily',   'priority' => '0.90'],
            ['loc' => url('about/'),        'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['loc' => url('faq/'),          'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.80'],
            ['loc' => url('contact/'),      'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.80'],
        ];
        foreach ($corePages as $cp) {
            $urls[] = $cp;
        }

        // Individual Service Pages
        foreach ($services as $srv) {
            $modTime = !empty($srv['updated_at']) ? substr($srv['updated_at'], 0, 10) : (!empty($srv['created_at']) ? substr($srv['created_at'], 0, 10) : $today);
            $urls[] = [
                'loc'        => service_url($srv['slug']),
                'lastmod'    => $modTime,
                'changefreq' => 'weekly',
                'priority'   => '0.90'
            ];
        }

        // Category Pages
        foreach ($categories as $cat) {
            $urls[] = [
                'loc'        => category_url($cat['slug']),
                'lastmod'    => $today,
                'changefreq' => 'weekly',
                'priority'   => '0.85'
            ];
        }

        // Active Product Pages
        foreach ($products as $p) {
            $modTime = !empty($p['updated_at']) ? substr($p['updated_at'], 0, 10) : (!empty($p['created_at']) ? substr($p['created_at'], 0, 10) : $today);
            $urls[] = [
                'loc'        => product_url($p['slug']),
                'lastmod'    => $modTime,
                'changefreq' => 'weekly',
                'priority'   => '0.85'
            ];
        }

        // Published Blog Articles
        foreach ($blogPosts as $post) {
            $modTime = !empty($post['updated_at']) ? substr($post['updated_at'], 0, 10) : (!empty($post['created_at']) ? substr($post['created_at'], 0, 10) : $today);
            $urls[] = [
                'loc'        => blog_url($post['slug']),
                'lastmod'    => $modTime,
                'changefreq' => 'monthly',
                'priority'   => '0.80'
            ];
        }

        // Static Policy / Legal Pages
        foreach ($staticPages as $sp) {
            if (in_array($sp['slug'], ['privacy-policy', 'terms-and-conditions', 'shipping-policy'])) {
                $urls[] = [
                    'loc'        => url($sp['slug'] . '/'),
                    'lastmod'    => $today,
                    'changefreq' => 'monthly',
                    'priority'   => '0.50'
                ];
            }
        }

        $urlCount = count($urls);

        // Build XML string
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
        $xml .= "        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
        $xml .= "        xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
        $xml .= "        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n\n";

        foreach ($urls as $u) {
            $loc = htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8');
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$u['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$u['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$u['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>\n";

        $sitemapPath = $rootDir . '/sitemap.xml';
        $sitemapTmp = $rootDir . '/sitemap.xml.tmp';
        file_put_contents($sitemapTmp, $xml, LOCK_EX);
        if (file_exists($sitemapTmp)) {
            @unlink($sitemapPath);
            rename($sitemapTmp, $sitemapPath);
        }

        // sitemap_index.xml
        $indexXml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $indexXml .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        $indexXml .= "  <sitemap>\n";
        $indexXml .= "    <loc>" . url('sitemap.xml') . "</loc>\n";
        $indexXml .= "    <lastmod>{$today}</lastmod>\n";
        $indexXml .= "  </sitemap>\n";
        $indexXml .= "</sitemapindex>\n";
        $indexTmp = $rootDir . '/sitemap_index.xml.tmp';
        file_put_contents($indexTmp, $indexXml, LOCK_EX);
        if (file_exists($indexTmp)) {
            $indexPath = $rootDir . '/sitemap_index.xml';
            @unlink($indexPath);
            rename($indexTmp, $indexPath);
        }

        // -------------------------------------------------------------
        // 2. GENERATE LLMS.TXT (AI DISCOVERY & SEMANTIC AGENT GUIDE)
        // -------------------------------------------------------------
        $llms = "# {$siteName} — Commercial Printing & Custom Transfer Manufacturing\n\n";
        $llms .= "> {$siteName} is a premier industrial printing facility and digital transfer manufacturer headquartered in Okhla Industrial Area, New Delhi, India. We specialize in Direct-to-Film (DTF) apparel transfers, UV DTF 3D tactile hard-surface decals, custom streetwear and corporate t-shirt printing, bespoke packaging, and corporate merchandise.\n\n";

        $llms .= "## Commercial Business Model & Order Lifecycle\n\n";
        $llms .= "OrderPrintex operates on a dedicated **B2B & Bespoke Enquiry-to-Quotation Workflow**. The site does not utilize self-service e-commerce carts or instant checkout.\n\n";
        $llms .= "1. **Explore Products & Services**: Clients review print technologies, substrate compatibility, and base pricing.\n";
        $llms .= "2. **Submit Enquiry & Artwork**: Clients upload transparent artwork (PNG/PDF) and provide dimensions and quantity requirements via the online quote tool or WhatsApp.\n";
        $llms .= "3. **Prepress Engineering & Formal Quotation**: Our color engineers verify file resolution, line weights, and ink coverage, issuing a formal itemized quotation with 18% GST and volume discounts.\n";
        $llms .= "4. **Digital Proof Approval**: The client logs into their secure portal account to inspect the digital RIP proof and accept the quote.\n";
        $llms .= "5. **High-Speed Industrial Production**: Orders enter physical production on dual Epson i3200 micro-piezo heads with automated TPU powder curing.\n";
        $llms .= "6. **Tracked Nationwide Dispatch**: Finished orders are packed in rigid moisture-proof containers and dispatched via express courier across India.\n\n";

        $llms .= "## Industrial Equipment & Machinery Fleet\n\n";
        foreach ($equipment as $eq) {
            $llms .= "- **{$eq['name']}** ({$eq['category']})\n";
            $llms .= "  - **Purpose**: {$eq['purpose']}\n";
            $llms .= "  - **Impact**: {$eq['impact']}\n";
        }
        $llms .= "\n";

        $llms .= "## Core Services Directory\n\n";
        foreach ($services as $srv) {
            $llms .= "### [{$srv['title']}](" . service_url($srv['slug']) . ")\n";
            $llms .= "- **URL**: " . service_url($srv['slug']) . "\n";
            $desc = !empty($srv['description']) ? strip_tags($srv['description']) : (!empty($srv['short_desc']) ? strip_tags($srv['short_desc']) : 'High-definition industrial printing service.');
            $llms .= "- **Overview**: {$desc}\n";
            $llms .= "- **Standard Turnaround**: 24-48 Hours (Express same-day dispatch available on request)\n\n";
        }

        $llms .= "## Product Categories\n\n";
        foreach ($categories as $cat) {
            $catDesc = !empty($cat['description']) ? strip_tags($cat['description']) : 'Commercial print product category.';
            $llms .= "- **[{$cat['name']}](" . category_url($cat['slug']) . ")**: {$catDesc}\n";
        }
        $llms .= "\n";

        $llms .= "## Exhaustive Product Catalog\n\n";
        foreach ($products as $p) {
            $price = $p['sale_price'] ?? $p['regular_price'] ?? $p['starting_price'] ?? 0;
            $priceStr = $price > 0 ? "₹" . number_format($price, 2) : "Custom Quoted";
            $sku = $p['sku'] ?: ('OP-PRD-' . $p['id']);
            $lead = $p['lead_time'] ?: '24-48 Hours';
            $moq = $p['min_order_qty'] ?: ($p['min_order'] ?: '1 Unit');

            $llms .= "### [{$p['title']}](" . product_url($p['slug']) . ")\n";
            $llms .= "- **Product URL**: " . product_url($p['slug']) . "\n";
            $llms .= "- **SKU**: `{$sku}` | **Category**: " . ($p['category_name'] ?: 'Printing') . "\n";
            $llms .= "- **Base Pricing**: {$priceStr} " . (!empty($p['price_unit']) ? "({$p['price_unit']})" : "") . "\n";
            $llms .= "- **Minimum Order Quantity (MOQ)**: {$moq} | **Lead Time**: {$lead}\n";
            
            $pDesc = !empty($p['short_desc']) ? strip_tags($p['short_desc']) : (!empty($p['description']) ? substr(strip_tags($p['description']), 0, 200) . '...' : '');
            if ($pDesc) {
                $llms .= "- **Summary**: {$pDesc}\n";
            }

            // Specs
            if (!empty($p['specifications'])) {
                $specLines = array_filter(array_map('trim', explode("\n", strip_tags($p['specifications']))));
                if (!empty($specLines)) {
                    $llms .= "- **Specifications**: " . implode(' | ', array_slice($specLines, 0, 5)) . "\n";
                }
            }

            // Sizes
            if (!empty($productSizes[$p['id']])) {
                $sizeStrings = [];
                foreach ($productSizes[$p['id']] as $sz) {
                    $lbl = $sz['size_label'] ?: $sz['size_name'];
                    $dim = $sz['dimensions'] ? " ({$sz['dimensions']})" : "";
                    $prc = $sz['base_price'] > 0 ? " @ ₹" . number_format($sz['base_price'], 2) : "";
                    $sizeStrings[] = "{$lbl}{$dim}{$prc}";
                }
                $llms .= "- **Available Sizes / Options**: " . implode(', ', $sizeStrings) . "\n";
            }

            // Tiers
            if (!empty($pricingTiers[$p['id']])) {
                $tierStrings = [];
                foreach ($pricingTiers[$p['id']] as $pt) {
                    $max = ($pt['max_quantity'] > 90000) ? '+' : " - {$pt['max_quantity']}";
                    $disc = $pt['discount_percent'] > 0 ? " ({$pt['discount_percent']}% off)" : ($pt['unit_price'] ? " (₹{$pt['unit_price']}/unit)" : "");
                    $tierStrings[] = "{$pt['min_quantity']}{$max} units{$disc}";
                }
                $llms .= "- **Volume Discount Tiers**: " . implode('; ', $tierStrings) . "\n";
            }

            if (!empty($p['artwork_guidelines'])) {
                $llms .= "- **Artwork Guide**: " . substr(strip_tags($p['artwork_guidelines']), 0, 150) . "...\n";
            }
            if (!empty($p['application_instructions'])) {
                $llms .= "- **Application**: " . substr(strip_tags($p['application_instructions']), 0, 150) . "...\n";
            }
            $llms .= "\n";
        }

        $llms .= "## Prepress & Artwork Submission Standards\n\n";
        $llms .= "- **Resolution Requirement**: 300 DPI at 100% physical finished print size.\n";
        $llms .= "- **Accepted File Types**: Transparent PNG, vector PDF, Adobe Illustrator (.ai), layered Photoshop (.psd).\n";
        $llms .= "- **Color Space & Profiles**: CMYK (US Web Coated SWOP or Coated FOGRA39) or sRGB IEC61966-2.1. Rich black formula: `C:60 M:40 Y:40 K:100`.\n";
        $llms .= "- **Background Transparency**: Backgrounds must be 100% transparent. Opaque white boxes will print as solid opaque white ink underbase.\n";
        $llms .= "- **Vector Outlines**: All text and font elements must be converted to curves/outlines prior to submission.\n";
        $llms .= "- **Minimum Stroke Width**: Minimum 0.5pt (0.18mm) for fine text, lines, and negative space gaps.\n\n";

        $llms .= "## Production Showcase & Factory Gallery\n\n";
        foreach ($portfolioItems as $idx => $item) {
            $llms .= "- **{$item['title']}** (" . strtoupper($item['category']) . ")\n";
            $llms .= "  - {$item['description']}\n";
            $llms .= "  - Image Asset: " . url($item['image_url']) . "\n";
        }
        $llms .= "\n";

        $llms .= "## Knowledge Base & Printing Guides\n\n";
        foreach ($blogPosts as $post) {
            $excerpt = !empty($post['excerpt']) ? strip_tags($post['excerpt']) : 'Comprehensive printing industry guide.';
            $llms .= "### [{$post['title']}](" . blog_url($post['slug']) . ")\n";
            $llms .= "- **URL**: " . blog_url($post['slug']) . "\n";
            $llms .= "- **Author**: " . ($post['author'] ?: 'OrderPrintex Editorial Team') . " | **Read Time**: " . ($post['read_time'] ?: '5 min read') . "\n";
            $llms .= "- **Summary**: {$excerpt}\n\n";
        }

        $llms .= "## Frequently Asked Questions (Curated Knowledge Base)\n\n";
        foreach ($faqs as $faq) {
            $llms .= "### {$faq['question']}\n";
            $llms .= "**Category**: {$faq['category']}\n\n";
            $llms .= "{$faq['answer']}\n\n";
        }

        $llms .= "## Corporate Directory & Contact Details\n\n";
        $llms .= "- **Entity Name**: {$siteName}\n";
        $llms .= "- **Headquarters & Production Facility**: {$siteAddress}\n";
        $llms .= "- **Official Email**: {$siteEmail}\n";
        $llms .= "- **Telephone / WhatsApp**: {$sitePhone} (<https://wa.me/919354028199>)\n";
        $llms .= "- **Operating Hours**: {$siteHours}\n";
        $llms .= "- **Geographical Coverage**: Nationwide delivery across all states in India & Worldwide B2B Export.\n";
        $llms .= "- **Direct Quote Request**: " . url('get-quote/') . "\n\n";

        $llms .= "## Complete Public URL Directory & Inter-Page Relationships\n\n";
        foreach ($urls as $u) {
            $llms .= "- <{$u['loc']}>\n";
        }
        $llms .= "\n";

        $llmsPath = $rootDir . '/llms.txt';
        $llmsTmp = $rootDir . '/llms.txt.tmp';
        file_put_contents($llmsTmp, $llms, LOCK_EX);
        if (file_exists($llmsTmp)) {
            @unlink($llmsPath);
            rename($llmsTmp, $llmsPath);
        }

        // -------------------------------------------------------------
        // 3. GENERATE SCHEMA.JSON (ENTERPRISE JSON-LD STRUCTURED GRAPH)
        // -------------------------------------------------------------
        $graph = [];

        // Organization / LocalBusiness / ProfessionalService
        $orgNode = [
            '@type'             => 'ProfessionalService',
            '@id'               => url('#organization'),
            'name'              => $siteName,
            'legalName'         => 'OrderPrintex Commercial Printing Studio',
            'url'               => url(''),
            'logo'              => url('assets/images/logo.png'),
            'image'             => url('assets/images/logo.png'),
            'description'       => $metaDesc,
            'email'             => $siteEmail,
            'telephone'         => $sitePhone,
            'priceRange'        => '₹₹',
            'currenciesAccepted'=> 'INR',
            'paymentAccepted'   => 'Bank Transfer, UPI, Commercial Invoice, Credit Card',
            'address'           => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $siteAddress,
                'addressLocality' => 'New Delhi',
                'addressRegion'   => 'Delhi',
                'postalCode'      => '110020',
                'addressCountry'  => 'IN'
            ],
            'geo'               => [
                '@type'           => 'GeoCoordinates',
                'latitude'        => 28.5355,
                'longitude'       => 77.2713
            ],
            'openingHoursSpecification' => [
                [
                    '@type'       => 'OpeningHoursSpecification',
                    'dayOfWeek'   => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    'opens'       => '09:30',
                    'closes'      => '19:30'
                ]
            ],
            'areaServed'        => [
                ['@type' => 'Country', 'name' => 'India'],
                ['@type' => 'City', 'name' => 'Delhi NCR'],
                ['@type' => 'City', 'name' => 'Mumbai'],
                ['@type' => 'City', 'name' => 'Bengaluru']
            ],
            'sameAs'            => [
                'https://www.facebook.com/orderprintex',
                'https://www.instagram.com/orderprintex',
                'https://www.linkedin.com/company/orderprintex'
            ]
        ];
        $graph[] = $orgNode;

        // WebSite Node
        $siteNode = [
            '@type'             => 'WebSite',
            '@id'               => url('#website'),
            'name'              => $siteName,
            'url'               => url(''),
            'description'       => $metaDesc,
            'publisher'         => ['@id' => url('#organization')],
            'potentialAction'   => [
                '@type'       => 'SearchAction',
                'target'      => url('products/?search={search_term_string}'),
                'query-input' => 'required name=search_term_string'
            ]
        ];
        $graph[] = $siteNode;

        // WebPage Nodes for Main Pages
        foreach ($corePages as $cp) {
            $pageTitle = $siteName;
            if (str_contains($cp['loc'], 'products/'))   $pageTitle = "Commercial Print Product Catalog | {$siteName}";
            elseif (str_contains($cp['loc'], 'services/')) $pageTitle = "Printing & Manufacturing Services | {$siteName}";
            elseif (str_contains($cp['loc'], 'pricing/'))  $pageTitle = "Trade Pricing & Volume Discount Tiers | {$siteName}";
            elseif (str_contains($cp['loc'], 'get-quote/'))$pageTitle = "Request a Custom Print Quotation | {$siteName}";
            elseif (str_contains($cp['loc'], 'gallery/'))  $pageTitle = "Production Portfolio & Showcase | {$siteName}";
            elseif (str_contains($cp['loc'], 'blog/'))     $pageTitle = "Printing Knowledge Base & Prepress Guides | {$siteName}";
            elseif (str_contains($cp['loc'], 'about/'))    $pageTitle = "About Our Facility & Printing Studio | {$siteName}";
            elseif (str_contains($cp['loc'], 'faq/'))      $pageTitle = "Frequently Asked Questions | {$siteName}";
            elseif (str_contains($cp['loc'], 'contact/'))  $pageTitle = "Contact & Factory Location | {$siteName}";

            $graph[] = [
                '@type'           => 'WebPage',
                '@id'             => $cp['loc'] . '#webpage',
                'url'             => $cp['loc'],
                'name'            => $pageTitle,
                'isPartOf'        => ['@id' => url('#website')],
                'breadcrumb'      => [
                    '@type'       => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $pageTitle, 'item' => $cp['loc']]
                    ]
                ]
            ];
        }

        // Service Nodes
        foreach ($services as $srv) {
            $srvUrl = service_url($srv['slug']);
            $srvDesc = !empty($srv['description']) ? strip_tags($srv['description']) : (!empty($srv['short_desc']) ? strip_tags($srv['short_desc']) : 'Industrial printing service.');
            $graph[] = [
                '@type'         => 'Service',
                '@id'           => $srvUrl . '#service',
                'name'          => $srv['title'],
                'url'           => $srvUrl,
                'serviceType'   => 'Commercial Printing',
                'provider'      => ['@id' => url('#organization')],
                'description'   => $srvDesc,
                'areaServed'    => ['@type' => 'Country', 'name' => 'India']
            ];
        }

        // Product Nodes
        foreach ($products as $p) {
            $pUrl = product_url($p['slug']);
            $price = $p['sale_price'] ?? $p['regular_price'] ?? $p['starting_price'] ?? 0;
            $sku = $p['sku'] ?: ('OP-PRD-' . $p['id']);
            $pDesc = !empty($p['short_desc']) ? strip_tags($p['short_desc']) : (!empty($p['description']) ? substr(strip_tags($p['description']), 0, 200) : 'Industrial transfer product.');
            $img = !empty($p['image_url']) ? url($p['image_url']) : url('assets/images/logo.png');

            $prodNode = [
                '@type'         => 'Product',
                '@id'           => $pUrl . '#product',
                'name'          => $p['title'],
                'url'           => $pUrl,
                'image'         => $img,
                'description'   => $pDesc,
                'sku'           => $sku,
                'category'      => $p['category_name'] ?: 'Printing',
                'brand'         => [
                    '@type'       => 'Brand',
                    'name'        => $siteName
                ],
                'offers'        => [
                    '@type'         => 'Offer',
                    'url'           => $pUrl,
                    'priceCurrency' => 'INR',
                    'price'         => number_format((float) $price, 2, '.', ''),
                    'availability'  => 'https://schema.org/InStock',
                    'itemCondition' => 'https://schema.org/NewCondition'
                ]
            ];

            // Add genuine review if exists in database
            if (!empty($reviewsByProduct[$p['id']])) {
                $revs = $reviewsByProduct[$p['id']];
                $prodNode['review'] = [];
                $ratingSum = 0;
                foreach ($revs as $r) {
                    $ratingVal = (int) ($r['rating'] ?? 5);
                    $ratingSum += $ratingVal;
                    $prodNode['review'][] = [
                        '@type'         => 'Review',
                        'reviewRating'  => [
                            '@type'       => 'Rating',
                            'ratingValue' => (string) $ratingVal,
                            'bestRating'  => '5'
                        ],
                        'author'        => [
                            '@type'       => 'Person',
                            'name'        => $r['customer_name'] ?: ($r['reviewer_name'] ?: 'Verified Client')
                        ],
                        'reviewBody'    => strip_tags($r['review_text'] ?? '')
                    ];
                }
                $avgRating = round($ratingSum / count($revs), 1);
                $prodNode['aggregateRating'] = [
                    '@type'       => 'AggregateRating',
                    'ratingValue' => (string) $avgRating,
                    'reviewCount' => (string) count($revs),
                    'bestRating'  => '5'
                ];
            }

            // Specs as additionalProperty
            $props = [];
            if (!empty($p['lead_time'])) {
                $props[] = ['@type' => 'PropertyValue', 'name' => 'Lead Time', 'value' => $p['lead_time']];
            }
            if (!empty($p['min_order_qty'])) {
                $props[] = ['@type' => 'PropertyValue', 'name' => 'Minimum Order Quantity', 'value' => (string) $p['min_order_qty']];
            }
            if (!empty($props)) {
                $prodNode['additionalProperty'] = $props;
            }

            $graph[] = $prodNode;
        }

        // Blog Article Nodes
        foreach ($blogPosts as $post) {
            $bUrl = blog_url($post['slug']);
            $bImg = !empty($post['featured_image']) ? url($post['featured_image']) : url('assets/images/logo.png');
            $graph[] = [
                '@type'             => 'BlogPosting',
                '@id'               => $bUrl . '#article',
                'headline'          => $post['title'],
                'url'               => $bUrl,
                'image'             => $bImg,
                'description'       => !empty($post['excerpt']) ? strip_tags($post['excerpt']) : '',
                'datePublished'     => !empty($post['created_at']) ? date('c', strtotime($post['created_at'])) : $nowIso,
                'dateModified'      => !empty($post['updated_at']) ? date('c', strtotime($post['updated_at'])) : $nowIso,
                'author'            => [
                    '@type'           => 'Person',
                    'name'            => $post['author'] ?: 'OrderPrintex Editorial Team'
                ],
                'publisher'         => ['@id' => url('#organization')],
                'mainEntityOfPage'  => [
                    '@type'           => 'WebPage',
                    '@id'             => $bUrl
                ]
            ];
        }

        // Portfolio Showcase Nodes (CreativeWork)
        foreach ($portfolioItems as $item) {
            $graph[] = [
                '@type'             => 'CreativeWork',
                'name'              => $item['title'],
                'image'             => url($item['image_url']),
                'description'       => $item['description'],
                'creator'           => ['@id' => url('#organization')]
            ];
        }

        // FAQPage Node
        $faqEntities = [];
        foreach ($faqs as $f) {
            $faqEntities[] = [
                '@type'           => 'Question',
                'name'            => $f['question'],
                'acceptedAnswer'  => [
                    '@type'         => 'Answer',
                    'text'          => $f['answer']
                ]
            ];
        }
        $graph[] = [
            '@type'             => 'FAQPage',
            '@id'               => url('faq/#faqpage'),
            'name'              => 'OrderPrintex Frequently Asked Questions',
            'url'               => url('faq/'),
            'mainEntity'        => $faqEntities
        ];

        $schemaDoc = [
            '@context'          => 'https://schema.org',
            '@graph'            => $graph
        ];

        $schemaJson = json_encode($schemaDoc, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $schemaPath = $rootDir . '/schema.json';
        $schemaTmp = $rootDir . '/schema.json.tmp';
        file_put_contents($schemaTmp, $schemaJson, LOCK_EX);
        if (file_exists($schemaTmp)) {
            @unlink($schemaPath);
            rename($schemaTmp, $schemaPath);
        }

        // -------------------------------------------------------------
        // 4. GENERATE ORDERPRINTEX.MD (MAIN MARKDOWN DISCOVERY GUIDE)
        // -------------------------------------------------------------
        $md = "# ORDERPRINTEX — Complete Commercial Printing, Technical Specifications & Website Documentation\n\n";
        $md .= "*Generated Automatically on " . date('d F Y, H:i:s T') . " | Production Version: 2.4.0*\n\n";

        $md .= "---\n\n";
        $md .= "## Table of Contents\n\n";
        $md .= "1. [Executive Summary & Corporate Profile](#1-executive-summary--corporate-profile)\n";
        $md .= "2. [B2B Commercial Business Model & The Enquiry-to-Quotation Architecture](#2-b2b-commercial-business-model--the-enquiry-to-quotation-architecture)\n";
        $md .= "3. [Industrial Machinery Fleet & Manufacturing Infrastructure](#3-industrial-machinery-fleet--manufacturing-infrastructure)\n";
        $md .= "4. [Commercial Printing Services Directory](#4-commercial-printing-services-directory)\n";
        $md .= "5. [Exhaustive Product Catalog by Category](#5-exhaustive-product-catalog-by-category)\n";
        $md .= "6. [Prepress Engineering & Artwork Standards](#6-prepress-engineering--artwork-standards)\n";
        $md .= "7. [Production Portfolio & Specimen Gallery](#7-production-portfolio--specimen-gallery)\n";
        $md .= "8. [Industry Knowledge Base & Educational Guides](#8-industry-knowledge-base--educational-guides)\n";
        $md .= "9. [Frequently Asked Questions (19 Comprehensive Q&As)](#9-frequently-asked-questions)\n";
        $md .= "10. [Corporate Directory, Operating Hours & Contact Channels](#10-corporate-directory-operating-hours--contact-channels)\n";
        $md .= "11. [Master Website Sitemap & Public URL Directory](#11-master-website-sitemap--public-url-directory)\n\n";

        $md .= "---\n\n";

        // Section 1
        $md .= "## 1. Executive Summary & Corporate Profile\n\n";
        $md .= "**Entity Name**: {$siteName}\n";
        $md .= "**Headquarters & Manufacturing Hub**: {$siteAddress}\n";
        $md .= "**Core Specialization**: High-speed industrial Direct-to-Film (DTF) apparel transfers, UV DTF 3D tactile hard-surface decals, commercial screen-quality custom t-shirt printing, luxury unboxing packaging, and corporate merchandise kits.\n";
        $md .= "**Facility Capabilities**: Dual Epson i3200 micro-piezo heads, continuous roll-to-roll automated powder curing tunnels, multi-axis hybrid UV LED flatbeds, sub-millimeter optical contour plotters, and pneumatic transfer presses.\n";
        $md .= "**Quality Standards**: OEKO-TEX Standard 100 certified eco-pigments, 1440 × 2880 DPI photographic color depth, wash longevity rated at 50+ commercial cycles, and full compliance with 18% GST commercial invoicing.\n\n";

        // Section 2
        $md .= "## 2. B2B Commercial Business Model & The Enquiry-to-Quotation Architecture\n\n";
        $md .= "Unlike standard consumer retail e-commerce stores with automated shopping carts and credit card checkout, OrderPrintex operates as an **industrial-grade print-on-demand and commercial job-work manufacturing facility**. Every print run is custom-tailored to client substrate specifications, color gamuts, and physical volume.\n\n";
        $md .= "### Step-by-Step Order Lifecycle:\n\n";
        $md .= "1. **Discovery & Specification**: The customer visits [orderprintex.com](" . url('') . "), browses the product catalogue or service descriptions, and determines required transfer formats (e.g. 22\" DTF gang sheets, UV DTF decals, or finished garments).\n";
        $md .= "2. **Artwork Upload & Enquiry Submission**: The customer uploads high-resolution artwork (transparent PNG, vector PDF, or AI) through the online interactive workbench or the [Get a Quote](" . url('get-quote/') . ") form.\n";
        $md .= "3. **Prepress Engineering & RIP Audit**: Our in-house color engineers audit the vector lines, transparency layers, color profiles (CMYK vs RGB), and calculate optimal gang sheet linear yields.\n";
        $md .= "4. **Itemized Quotation Issuance**: A formal commercial quotation is generated containing base unit prices, volume-tiered discounts, prepress setup (if any), and 18% GST.\n";
        $md .= "5. **Client Portal Review & Acceptance**: The client accesses their secure account dashboard, inspects the itemized line items and proof, and accepts the quotation.\n";
        $md .= "6. **Manufacturing Execution**: Production begins immediately using calibrated RIP software, piezoelectric micro-drop heads, and automated IR conveyor curing.\n";
        $md .= "7. **Quality Control & Tracked Shipping**: Transfers undergo elongation, adhesion, and optical checks before moisture-sealed packaging and express dispatch via tier-1 national couriers.\n\n";

        // Section 3
        $md .= "## 3. Industrial Machinery Fleet & Manufacturing Infrastructure\n\n";
        foreach ($equipment as $eq) {
            $md .= "### {$eq['name']} ({$eq['category']})\n";
            $md .= "- **Purpose & Technology**: {$eq['purpose']}\n";
            $md .= "- **Production Advantage & Impact**: {$eq['impact']}\n\n";
        }

        // Section 4
        $md .= "## 4. Commercial Printing Services Directory\n\n";
        foreach ($services as $srv) {
            $md .= "### [{$srv['title']}](" . service_url($srv['slug']) . ")\n\n";
            $md .= "- **Canonical URL**: <" . service_url($srv['slug']) . ">\n";
            $desc = !empty($srv['description']) ? strip_tags($srv['description']) : (!empty($srv['short_desc']) ? strip_tags($srv['short_desc']) : 'High-definition industrial printing service.');
            $md .= "- **Overview**: {$desc}\n";
            $md .= "- **Turnaround & Dispatch**: 24-48 hours standard. Express same-day production available on pre-approved artwork.\n";
            $md .= "- **Substrate Compatibility**: 100% Cotton, Poly-Blends, Canvas, Denim, Leather, Stainless Steel, Glass, Acrylic, Ceramics, and Rigid Paperboard.\n\n";
        }

        // Section 5
        $md .= "## 5. Exhaustive Product Catalog by Category\n\n";
        $groupedProducts = [];
        foreach ($products as $p) {
            $cName = $p['category_name'] ?: 'General Printing';
            $groupedProducts[$cName][] = $p;
        }

        foreach ($groupedProducts as $categoryName => $catProds) {
            $md .= "### Category: {$categoryName}\n\n";
            foreach ($catProds as $p) {
                $sku = $p['sku'] ?: ('OP-PRD-' . $p['id']);
                $price = $p['sale_price'] ?? $p['regular_price'] ?? $p['starting_price'] ?? 0;
                $priceStr = $price > 0 ? "₹" . number_format($price, 2) : "Custom Quoted";
                $lead = $p['lead_time'] ?: '24-48 Hours';
                $moq = $p['min_order_qty'] ?: ($p['min_order'] ?: '1 Unit');

                $md .= "#### [{$p['title']}](" . product_url($p['slug']) . ")\n\n";
                $md .= "- **Direct URL**: <" . product_url($p['slug']) . ">\n";
                $md .= "- **SKU**: `{$sku}`\n";
                $md .= "- **Base Pricing**: {$priceStr} " . (!empty($p['price_unit']) ? "({$p['price_unit']})" : "") . "\n";
                $md .= "- **Minimum Order Quantity (MOQ)**: {$moq}\n";
                $md .= "- **Lead Time**: {$lead}\n";
                
                $pDesc = !empty($p['short_desc']) ? strip_tags($p['short_desc']) : (!empty($p['description']) ? strip_tags($p['description']) : '');
                if ($pDesc) {
                    $md .= "- **Description**: {$pDesc}\n";
                }

                // Specifications
                if (!empty($p['specifications'])) {
                    $md .= "- **Technical Specifications**:\n";
                    $specLines = array_filter(array_map('trim', explode("\n", strip_tags($p['specifications']))));
                    foreach ($specLines as $sl) {
                        $md .= "  - {$sl}\n";
                    }
                }

                // Available Sizes
                if (!empty($productSizes[$p['id']])) {
                    $md .= "- **Standard Formats & Sizes**:\n";
                    foreach ($productSizes[$p['id']] as $sz) {
                        $lbl = $sz['size_label'] ?: $sz['size_name'];
                        $dim = $sz['dimensions'] ? " ({$sz['dimensions']})" : "";
                        $prc = $sz['base_price'] > 0 ? " — ₹" . number_format($sz['base_price'], 2) : "";
                        $md .= "  - **{$lbl}**{$dim}{$prc}\n";
                    }
                }

                // Volume Pricing Tiers
                if (!empty($pricingTiers[$p['id']])) {
                    $md .= "- **Volume Quantity Discount Structure**:\n";
                    foreach ($pricingTiers[$p['id']] as $pt) {
                        $max = ($pt['max_quantity'] > 90000) ? '+' : " to {$pt['max_quantity']}";
                        $disc = $pt['discount_percent'] > 0 ? " — {$pt['discount_percent']}% discount applied" : ($pt['unit_price'] ? " — ₹{$pt['unit_price']} per unit" : "");
                        $md .= "  - Tier {$pt['min_quantity']}{$max} units{$disc}\n";
                    }
                }

                if (!empty($p['artwork_guidelines'])) {
                    $md .= "- **Artwork Submission Guide**: " . strip_tags($p['artwork_guidelines']) . "\n";
                }
                if (!empty($p['application_instructions'])) {
                    $md .= "- **Application Instructions**: " . strip_tags($p['application_instructions']) . "\n";
                }
                $md .= "\n";
            }
        }

        // Section 6
        $md .= "## 6. Prepress Engineering & Artwork Standards\n\n";
        $md .= "To guarantee press-ready color precision and crisp edges, all customer files must adhere to our prepress technical guidelines:\n\n";
        $md .= "- **Target DPI**: Minimum 300 DPI at 100% scale. Lower resolutions cause pixelation and blur along transfer borders.\n";
        $md .= "- **Alpha Transparency**: Backgrounds must be 100% transparent PNG or vector shapes. Any semi-transparent or white background pixels will print as solid opaque white ink.\n";
        $md .= "- **Color Profiling**: CMYK color space is preferred (US Web Coated SWOP or Coated FOGRA39). For rich, deep blacks on garments, specify `C:60 M:40 Y:40 K:100`.\n";
        $md .= "- **Font Outlining**: Convert all typography to vector paths/curves (`Type > Create Outlines` in Adobe Illustrator).\n";
        $md .= "- **Minimum Vector Stroke**: Maintain minimum stroke weight of 0.5pt (0.18mm) for positive lines and 0.75pt (0.26mm) for negative reverse lines.\n\n";

        // Section 7
        $md .= "## 7. Production Portfolio & Specimen Gallery\n\n";
        $md .= "Our studio portfolio showcases genuine commercial production runs and demonstration specimens manufactured at our New Delhi studio:\n\n";
        foreach ($portfolioItems as $item) {
            $md .= "### {$item['title']} (" . strtoupper($item['category']) . ")\n";
            $md .= "- **Image Asset**: <" . url($item['image_url']) . ">\n";
            $md .= "- **Technical Summary**: {$item['description']}\n\n";
        }

        // Section 8
        $md .= "## 8. Industry Knowledge Base & Educational Guides\n\n";
        foreach ($blogPosts as $post) {
            $md .= "### [{$post['title']}](" . blog_url($post['slug']) . ")\n";
            $md .= "- **Canonical Route**: <" . blog_url($post['slug']) . ">\n";
            $md .= "- **Editorial Author**: " . ($post['author'] ?: 'OrderPrintex Technical Team') . " | **Reading Time**: " . ($post['read_time'] ?: '5 min read') . "\n";
            $excerpt = !empty($post['excerpt']) ? strip_tags($post['excerpt']) : 'Comprehensive guide on digital printing, DTF technology, and commercial substrates.';
            $md .= "- **Synopsis**: {$excerpt}\n\n";
        }

        // Section 9
        $md .= "## 9. Frequently Asked Questions\n\n";
        foreach ($faqs as $idx => $faq) {
            $num = $idx + 1;
            $md .= "### Q{$num}. [{$faq['category']}] {$faq['question']}\n\n";
            $md .= "{$faq['answer']}\n\n";
        }

        // Section 10
        $md .= "## 10. Corporate Directory, Operating Hours & Contact Channels\n\n";
        $md .= "- **Corporate Name**: {$siteName}\n";
        $md .= "- **Manufacturing Facility**: {$siteAddress}\n";
        $md .= "- **Customer Support & Orders**: {$siteEmail}\n";
        $md .= "- **Direct Phone / WhatsApp Desk**: {$sitePhone} (<https://wa.me/919354028199>)\n";
        $md .= "- **Facility Working Hours**: {$siteHours}\n";
        $md .= "- **Tax Compliance**: Registered GSTIN with 18% GST invoices issued on all orders.\n";
        $md .= "- **B2B Trade Accounts**: Wholesale commercial pricing available for volume printers, apparel brands, and uniform suppliers.\n\n";

        // Section 11
        $md .= "## 11. Master Website Sitemap & Public URL Directory\n\n";
        $md .= "| Public Canonical URL | Category | Change Frequency | Priority |\n";
        $md .= "| :--- | :--- | :--- | :--- |\n";
        foreach ($urls as $u) {
            $catLabel = 'Core Landing';
            if (str_contains($u['loc'], '/services/')) $catLabel = 'Printing Service';
            elseif (str_contains($u['loc'], '/product/category/')) $catLabel = 'Product Category';
            elseif (str_contains($u['loc'], '/product/')) $catLabel = 'Product Details';
            elseif (str_contains($u['loc'], '/blog/')) $catLabel = 'Knowledge Base';
            elseif (str_contains($u['loc'], '-policy')) $catLabel = 'Legal & Terms';

            $md .= "| <{$u['loc']}> | {$catLabel} | {$u['changefreq']} | {$u['priority']} |\n";
        }
        $md .= "\n";

        $mdPath = $rootDir . '/ORDERPRINTEX.md';
        $mdTmp = $rootDir . '/ORDERPRINTEX.md.tmp';
        file_put_contents($mdTmp, $md, LOCK_EX);
        if (file_exists($mdTmp)) {
            @unlink($mdPath);
            rename($mdTmp, $mdPath);
        }

        // Also duplicate to SITE_OVERVIEW.md for standard AI discovery
        $overviewPath = $rootDir . '/SITE_OVERVIEW.md';
        @copy($mdPath, $overviewPath);

        // -------------------------------------------------------------
        // PERSIST METADATA & SYSTEM LOGS
        // -------------------------------------------------------------
        set_setting('seo_last_generated', date('Y-m-d H:i:s'), 'seo');
        set_setting('seo_generation_status', 'success', 'seo');
        set_setting('seo_url_count', (string) $urlCount, 'seo');
        set_setting('seo_products_count', (string) count($products), 'seo');
        set_setting('seo_services_count', (string) count($services), 'seo');
        set_setting('seo_categories_count', (string) count($categories), 'seo');
        set_setting('seo_articles_count', (string) count($blogPosts), 'seo');
        set_setting('seo_portfolio_count', (string) count($portfolioItems), 'seo');

        log_activity(null, 'seo_sync_all', "Synchronized sitemap.xml ({$urlCount} URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.");

        return [
            'success'          => true,
            'timestamp'        => date('Y-m-d H:i:s'),
            'sitemap_path'     => $sitemapPath,
            'sitemap_urls'     => $urlCount,
            'llms_path'        => $llmsPath,
            'llms_size'        => file_exists($llmsPath) ? filesize($llmsPath) : 0,
            'schema_path'      => $schemaPath,
            'schema_nodes'     => count($graph),
            'markdown_path'    => $mdPath,
            'markdown_size'    => file_exists($mdPath) ? filesize($mdPath) : 0,
            'counts'           => [
                'urls'       => $urlCount,
                'products'   => count($products),
                'services'   => count($services),
                'categories' => count($categories),
                'articles'   => count($blogPosts),
                'portfolio'  => count($portfolioItems),
                'faqs'       => count($faqs)
            ],
            'message'          => "Successfully synchronized all 4 discovery files: sitemap.xml ({$urlCount} URLs), llms.txt, schema.json ({$urlCount} entities), and ORDERPRINTEX.md."
        ];

    } catch (Exception $e) {
        set_setting('seo_generation_status', 'error: ' . $e->getMessage(), 'seo');
        return [
            'success'   => false,
            'timestamp' => date('Y-m-d H:i:s'),
            'error'     => $e->getMessage()
        ];
    }
}

/**
 * Return current status and file metadata for all 4 SEO & AI discovery files.
 */
function seo_get_status(): array {
    $rootDir = realpath(__DIR__ . '/..');
    if (!$rootDir) {
        $rootDir = 'C:/Movies/Internship/OrderPrintex';
    }

    $sitemapPath = $rootDir . '/sitemap.xml';
    $llmsPath = $rootDir . '/llms.txt';
    $schemaPath = $rootDir . '/schema.json';
    $mdPath = $rootDir . '/ORDERPRINTEX.md';

    $sitemapExists = file_exists($sitemapPath);
    $llmsExists = file_exists($llmsPath);
    $schemaExists = file_exists($schemaPath);
    $mdExists = file_exists($mdPath);

    return [
        'sitemap_exists'   => $sitemapExists,
        'sitemap_size'     => $sitemapExists ? filesize($sitemapPath) : 0,
        'sitemap_mtime'    => $sitemapExists ? date('Y-m-d H:i:s', filemtime($sitemapPath)) : null,
        'sitemap_path'     => $sitemapPath,

        'llms_exists'      => $llmsExists,
        'llms_size'        => $llmsExists ? filesize($llmsPath) : 0,
        'llms_mtime'       => $llmsExists ? date('Y-m-d H:i:s', filemtime($llmsPath)) : null,
        'llms_path'        => $llmsPath,

        'schema_exists'    => $schemaExists,
        'schema_size'      => $schemaExists ? filesize($schemaPath) : 0,
        'schema_mtime'     => $schemaExists ? date('Y-m-d H:i:s', filemtime($schemaPath)) : null,
        'schema_path'      => $schemaPath,

        'markdown_exists'  => $mdExists,
        'markdown_size'    => $mdExists ? filesize($mdPath) : 0,
        'markdown_mtime'   => $mdExists ? date('Y-m-d H:i:s', filemtime($mdPath)) : null,
        'markdown_path'    => $mdPath,

        'last_generated'   => get_setting('seo_last_generated', 'Never'),
        'last_status'      => get_setting('seo_generation_status', 'Not initialized'),
        'url_count'        => (int) get_setting('seo_url_count', '0'),
        'products_count'   => (int) get_setting('seo_products_count', '0'),
        'services_count'   => (int) get_setting('seo_services_count', '0'),
        'categories_count' => (int) get_setting('seo_categories_count', '0'),
        'articles_count'   => (int) get_setting('seo_articles_count', '0'),
        'portfolio_count'  => (int) get_setting('seo_portfolio_count', '0')
    ];
}

/**
 * Generate Schema.org JSON-LD for Organization
 */
function seo_jsonld_organization(): string {
    $siteName = get_setting('site_name', 'OrderPrintex');
    $siteEmail = get_setting('contact_email', 'contact@orderprintex.com');
    $sitePhone = get_setting('contact_phone', '+91 93540 28199');
    $siteAddress = get_setting('contact_address', 'Okhla Industrial Area, Phase II, New Delhi - 110020');

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => $siteName,
        'url' => url(''),
        'logo' => url('assets/images/logo.png'),
        'description' => 'Commercial DTF printing, UV DTF gang sheets, and custom merchandise manufacturer in New Delhi.',
        'email' => $siteEmail,
        'telephone' => $sitePhone,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $siteAddress,
            'addressLocality' => 'New Delhi',
            'addressRegion' => 'Delhi',
            'postalCode' => '110020',
            'addressCountry' => 'IN'
        ],
        'sameAs' => [
            'https://www.facebook.com/orderprintex',
            'https://www.instagram.com/orderprintex'
        ]
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Generate Schema.org JSON-LD for a Product
 */
function seo_jsonld_product(array $product): string {
    $price = $product['sale_price'] ?? $product['regular_price'] ?? $product['starting_price'] ?? 0;
    $sku = $product['sku'] ?? ('OP-PRD-' . $product['id']);
    $desc = !empty($product['short_desc']) ? strip_tags($product['short_desc']) : (!empty($product['description']) ? substr(strip_tags($product['description']), 0, 180) : 'Industrial-grade custom printed product.');

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product['title'] ?? 'Custom Print Product',
        'image' => !empty($product['image_url']) ? url($product['image_url']) : url('assets/images/logo.png'),
        'description' => $desc,
        'sku' => $sku,
        'brand' => [
            '@type' => 'Brand',
            'name' => 'OrderPrintex'
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => product_url($product['slug'] ?? ''),
            'priceCurrency' => 'INR',
            'price' => number_format((float) $price, 2, '.', ''),
            'availability' => ($product['stock_status'] ?? 'in_stock') === 'out_of_stock' ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
            'itemCondition' => 'https://schema.org/NewCondition'
        ]
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Generate Schema.org JSON-LD for a Blog Article
 */
function seo_jsonld_article(array $post): string {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post['title'] ?? '',
        'description' => !empty($post['excerpt']) ? strip_tags($post['excerpt']) : '',
        'image' => !empty($post['featured_image']) ? url($post['featured_image']) : url('assets/images/logo.png'),
        'author' => [
            '@type' => 'Person',
            'name' => $post['author'] ?? 'OrderPrintex Editorial Team'
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'OrderPrintex',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => url('assets/images/logo.png')
            ]
        ],
        'datePublished' => !empty($post['created_at']) ? date('c', strtotime($post['created_at'])) : date('c'),
        'dateModified' => !empty($post['updated_at']) ? date('c', strtotime($post['updated_at'])) : date('c'),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => blog_url($post['slug'] ?? '')
        ]
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}
