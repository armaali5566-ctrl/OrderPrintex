<?php
/**
 * OrderPrintex - Centralized SEO, Sitemap, LLMs.txt, Schema.org JSON-LD & Documentation Engine
 * Complete Website-Wide Automated Synchronization for Search Engines and AI Discovery.
 * Generates Clean Canonical URLs without trailing slash (e.g. /about, /services/dtf-printing).
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
            'image_url' => 'assets/images/dtf_fabric_print.webp',
            'description' => 'Direct-to-Film transfer showcasing ultra-fine color gradients, opaque white underbase, and soft stretch elasticity without hand feel stiffness.'
        ],
        [
            'id' => 2,
            'title' => 'UV DTF Decals on Glass & Tumbler Curved Surfaces',
            'category' => 'uv_dtf',
            'image_url' => 'assets/images/uv_dtf_drinkware.webp',
            'description' => 'Industrial-adhesion 3D tactile UV DTF stickers applied seamlessly onto curved stainless steel bottles and glassware with spot gloss varnish.'
        ],
        [
            'id' => 3,
            'title' => 'Heavyweight 240 GSM Streetwear Graphic Tee',
            'category' => 'tshirt',
            'image_url' => 'assets/images/streetwear_tee.webp',
            'description' => 'Vintage washed boxy drop-shoulder t-shirt with photographic DTF chest graphic, double-needle stitched seams, and tight retail ribbing.'
        ],
        [
            'id' => 4,
            'title' => 'Luxury Apparel Unboxing Mailer Packaging Box',
            'category' => 'packaging',
            'image_url' => 'assets/images/packaging_box.webp',
            'description' => 'Custom matte black corrugated mailer box with hot-stamped gold foil logo, spot UV accents, and inner branded tissue wrap.'
        ],
        [
            'id' => 5,
            'title' => 'Custom Die-Cut & Holographic Decal Collection',
            'category' => 'uv_dtf',
            'image_url' => 'assets/images/custom_stickers.webp',
            'description' => 'Multi-surface peel-and-stick vinyl decals with embossed gloss spot varnish, iridescent light reflection, and permanent acrylic bond.'
        ],
        [
            'id' => 6,
            'title' => 'Executive Corporate Swag & Client Gifting Kit',
            'category' => 'corporate',
            'image_url' => 'assets/images/corporate_swag_kit.webp',
            'description' => 'Turnkey corporate onboarding kit runs featuring customized insulated drinkware, embossed diary, and stylus pen in a bespoke gift hamper.'
        ],
        [
            'id' => 7,
            'title' => 'Retail Brand Collection & Crimson Studio Apparel',
            'category' => 'tshirt',
            'image_url' => 'assets/images/custom_tshirt_apparel.webp',
            'description' => 'Studio fashion apparel featuring rich crimson red garment dyes, true-match ICC color profiling, and breathable photographic chest prints.'
        ],
        [
            'id' => 8,
            'title' => 'Luxury Embossed Branding Collateral & Stationery',
            'category' => 'packaging',
            'image_url' => 'assets/images/branding_stationery.webp',
            'description' => 'Thick crimson red and warm beige business cards with edge gilding, debossed linen corporate journal, and executive presentation folders.'
        ]
    ];
}

/**
 * Canonical URL Helpers for Production SEO & AI Discovery Files
 * Strictly outputs the verified production domain https://orderprintex.com WITHOUT trailing slash
 */
function seo_prod_url(string $path = ''): string {
    $trimmed = trim(ltrim($path, '/'));
    if ($trimmed === '' || $trimmed === '/') {
        return 'https://orderprintex.com/';
    }
    if (strpos($trimmed, '#') === 0) {
        return 'https://orderprintex.com/' . $trimmed;
    }
    return 'https://orderprintex.com/' . rtrim($trimmed, '/');
}
function seo_prod_service_url(string $slug): string {
    return 'https://orderprintex.com/services/' . trim($slug, '/');
}
function seo_prod_category_url(string $slug): string {
    return 'https://orderprintex.com/product/category/' . trim($slug, '/');
}
function seo_prod_product_url(string $slug): string {
    return 'https://orderprintex.com/product/' . trim($slug, '/');
}
function seo_prod_blog_url(string $slug = ''): string {
    if (empty($slug)) {
        return 'https://orderprintex.com/blog';
    }
    return 'https://orderprintex.com/blog/' . trim($slug, '/');
}
function seo_prod_blog_category_url(string $slug): string {
    return 'https://orderprintex.com/blog/category/' . trim($slug, '/');
}

/**
 * =========================================================================
 * DYNAMIC SCHEMA.ORG BUILDERS (Connected @graph Architecture)
 * =========================================================================
 */

/**
 * Return baseline Top-Level Graph Nodes present across all pages:
 * 1. Organization (Entity ID: url('#organization'))
 * 2. LocalBusiness (Entity ID: url('#localbusiness'))
 * 3. WebSite with SearchAction (Entity ID: url('#website'))
 */
function seo_get_base_graph_nodes(): array {
    $siteUrl = url('');
    $logoUrl = url('assets/images/logo.png');
    $productionImg = url('assets/images/dtf_fabric_print.jpg');

    $orgNode = [
        '@type' => 'Organization',
        '@id' => url('#organization'),
        'name' => 'OrderPrintex',
        'alternateName' => 'PRINTEX Delhi',
        'url' => $siteUrl,
        'logo' => [
            '@type' => 'ImageObject',
            '@id' => url('#logo'),
            'url' => $logoUrl,
            'caption' => 'OrderPrintex Logo'
        ],
        'image' => $productionImg,
        'telephone' => '+919354028199',
        'email' => 'sales@orderprintex.com',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh',
            'addressLocality' => 'New Delhi',
            'addressRegion' => 'Delhi',
            'postalCode' => '110059',
            'addressCountry' => 'IN'
        ],
        'sameAs' => [
            'https://www.instagram.com/order_printex',
            'https://www.facebook.com/orderprintex',
            'https://www.youtube.com/@orderprintex',
            'https://www.linkedin.com/company/orderprintex'
        ]
    ];

    $localBusinessNode = [
        '@type' => 'LocalBusiness',
        '@id' => url('#localbusiness'),
        'name' => 'OrderPrintex Delhi Printing Facility',
        'image' => $productionImg,
        'url' => $siteUrl,
        'telephone' => '+919354028199',
        'email' => 'sales@orderprintex.com',
        'priceRange' => '₹₹',
        'parentOrganization' => [
            '@id' => url('#organization')
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh',
            'addressLocality' => 'New Delhi',
            'addressRegion' => 'Delhi',
            'postalCode' => '110059',
            'addressCountry' => 'IN'
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => 28.6180,
            'longitude' => 77.0805
        ],
        'openingHoursSpecification' => [
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'opens' => '09:30',
                'closes' => '20:00'
            ]
        ]
    ];

    $webSiteNode = [
        '@type' => 'WebSite',
        '@id' => url('#website'),
        'name' => 'OrderPrintex',
        'url' => $siteUrl,
        'publisher' => [
            '@id' => url('#organization')
        ],
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => url('products.php?search={search_term_string}')
            ],
            'query-input' => 'required name=search_term_string'
        ]
    ];

    return [$orgNode, $localBusinessNode, $webSiteNode];
}

/**
 * Page-specific entity nodes for injection into the unified graph
 */
function seo_get_page_nodes(string $key, array $data = []): array {
    $cleanKey = strtolower(trim($key));
    $nodes = [];

    switch ($cleanKey) {
        case 'home':
        case 'index':
        case '':
            $services = db_fetch_all("SELECT slug, title, short_description, image_url FROM services WHERE is_active = 1 ORDER BY display_order ASC");
            $listItems = [];
            foreach ($services as $idx => $s) {
                $sUrl = service_url($s['slug']);
                $sNode = [
                    '@type' => 'Service',
                    '@id' => $sUrl . '#service',
                    'name' => $s['title'],
                    'url' => $sUrl,
                    'serviceType' => $s['slug'] === 'dtf-printing' ? 'Direct-to-Film Transfer Printing' :
                                     ($s['slug'] === 'uv-dtf-printing' ? 'UV DTF Decal & Sticker Printing' :
                                     ($s['slug'] === 't-shirt-printing' ? 'Custom Apparel & T-Shirt Printing' : 'Corporate Merchandise & Kits')),
                    'description' => strip_tags($s['short_description'] ?? ''),
                    'provider' => ['@id' => url('#organization')],
                    'areaServed' => ['@type' => 'Country', 'name' => 'India']
                ];
                if (!empty($s['image_url'])) {
                    $sNode['image'] = url($s['image_url']);
                }
                $listItems[] = [
                    '@type' => 'ListItem',
                    'position' => $idx + 1,
                    'name' => $s['title'],
                    'item' => ['@id' => $sUrl . '#service']
                ];
                $nodes[] = $sNode;
            }
            $nodes[] = [
                '@type' => 'ItemList',
                '@id' => url('#services-list'),
                'name' => 'OrderPrintex Commercial Printing Capabilities',
                'description' => 'Commercial printing services, DTF transfers, UV decals, and custom apparel manufactured in Delhi.',
                'itemListElement' => $listItems
            ];
            break;

        case 'about':
            $aboutUrl = url('about');
            $nodes[] = [
                '@type' => 'AboutPage',
                '@id' => $aboutUrl . '#aboutpage',
                'url' => $aboutUrl,
                'name' => 'About Our Printing Studio & Manufacturing Facility | OrderPrintex',
                'description' => 'Learn about OrderPrintex: Delhi\'s leading commercial DTF and UV DTF printing studio with 10+ years of prepress expertise.',
                'mainEntity' => ['@id' => url('#organization')],
                'breadcrumb' => ['@id' => $aboutUrl . '#breadcrumb']
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $aboutUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'About Us', 'item' => $aboutUrl]
                ]
            ];
            $nodes[] = [
                '@type' => 'Service',
                '@id' => $aboutUrl . '#service',
                'name' => 'Commercial Printing & Manufacturing',
                'serviceType' => 'Industrial DTF & UV DTF Printing',
                'description' => 'Direct-to-Film transfers, UV DTF decals, custom streetwear apparel, and corporate merchandise.',
                'provider' => ['@id' => url('#organization')],
                'areaServed' => ['@type' => 'Country', 'name' => 'India']
            ];
            break;

        case 'services':
            $servicesUrl = url('services');
            $services = db_fetch_all("SELECT slug, title, short_description, image_url FROM services WHERE is_active = 1 ORDER BY display_order ASC");
            $listItems = [];
            foreach ($services as $idx => $s) {
                $sUrl = service_url($s['slug']);
                $sNode = [
                    '@type' => 'Service',
                    '@id' => $sUrl . '#service',
                    'name' => $s['title'],
                    'url' => $sUrl,
                    'serviceType' => 'Commercial Printing',
                    'description' => strip_tags($s['short_description'] ?? ''),
                    'provider' => ['@id' => url('#organization')],
                    'areaServed' => ['@type' => 'Country', 'name' => 'India']
                ];
                if (!empty($s['image_url'])) {
                    $sNode['image'] = url($s['image_url']);
                }
                $listItems[] = [
                    '@type' => 'ListItem',
                    'position' => $idx + 1,
                    'name' => $s['title'],
                    'item' => ['@id' => $sUrl . '#service']
                ];
                $nodes[] = $sNode;
            }
            $nodes[] = [
                '@type' => 'CollectionPage',
                '@id' => $servicesUrl . '#collection',
                'url' => $servicesUrl,
                'name' => 'Industrial Printing & Manufacturing Services | OrderPrintex',
                'description' => 'Explore our end-to-end commercial printing services: DTF fabric transfers, UV DTF decals, custom t-shirts, and corporate swag kits.',
                'mainEntity' => ['@id' => $servicesUrl . '#serviceslist']
            ];
            $nodes[] = [
                '@type' => 'ItemList',
                '@id' => $servicesUrl . '#serviceslist',
                'name' => 'Printing Capabilities Directory',
                'itemListElement' => $listItems
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $servicesUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Printing Services', 'item' => $servicesUrl]
                ]
            ];
            break;

        case 'products':
            $productsUrl = url('products');
            $products = db_fetch_all("SELECT id, title, slug, short_desc, image_url, starting_price, sku FROM products WHERE is_active = 1 ORDER BY display_order ASC");
            $listItems = [];
            foreach ($products as $idx => $p) {
                $pUrl = product_url($p['slug']);
                $pNode = [
                    '@type' => 'Product',
                    '@id' => $pUrl . '#product',
                    'name' => $p['title'],
                    'url' => $pUrl,
                    'image' => !empty($p['image_url']) ? url($p['image_url']) : url('assets/images/logo.png'),
                    'description' => strip_tags($p['short_desc'] ?? ''),
                    'sku' => $p['sku'] ?: ('OPX-' . str_pad($p['id'], 4, '0', STR_PAD_LEFT)),
                    'offers' => [
                        '@type' => 'Offer',
                        'url' => $pUrl,
                        'priceCurrency' => 'INR',
                        'price' => number_format((float)($p['starting_price'] ?? 0), 2, '.', ''),
                        'availability' => 'https://schema.org/InStock',
                        'seller' => ['@id' => url('#organization')]
                    ]
                ];
                $listItems[] = [
                    '@type' => 'ListItem',
                    'position' => $idx + 1,
                    'name' => $p['title'],
                    'item' => ['@id' => $pUrl . '#product']
                ];
                $nodes[] = $pNode;
            }
            $nodes[] = [
                '@type' => 'CollectionPage',
                '@id' => $productsUrl . '#catalog',
                'url' => $productsUrl,
                'name' => 'Commercial Print Product Catalog | OrderPrintex',
                'description' => 'Browse all OrderPrintex manufactured products: DTF gang rolls, UV DTF decals, apparel, and corporate swag kits.',
                'mainEntity' => ['@id' => $productsUrl . '#itemlist']
            ];
            $nodes[] = [
                '@type' => 'ItemList',
                '@id' => $productsUrl . '#itemlist',
                'name' => 'OrderPrintex Print Products Catalog',
                'itemListElement' => $listItems
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $productsUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => $productsUrl]
                ]
            ];
            break;

        case 'product-details':
            $product = $data['product'] ?? null;
            if (!$product && !empty($_GET['slug'])) {
                $product = get_catalog_product_by_slug($_GET['slug']);
            }
            if (!$product) {
                $product = db_fetch_one("SELECT * FROM products WHERE is_active = 1 ORDER BY id ASC LIMIT 1");
            }
            if ($product) {
                $pUrl = product_url($product['slug']);
                $sku = $product['sku'] ?? ('OPX-' . str_pad($product['id'] ?? 1, 4, '0', STR_PAD_LEFT));
                $pNode = [
                    '@type' => 'Product',
                    '@id' => $pUrl . '#product',
                    'name' => $product['title'],
                    'image' => [!empty($product['image_url']) ? url($product['image_url']) : url('assets/images/logo.png')],
                    'description' => strip_tags($product['short_desc'] ?? ''),
                    'sku' => $sku,
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => 'OrderPrintex'
                    ],
                    'manufacturer' => ['@id' => url('#organization')],
                    'offers' => [
                        '@type' => 'Offer',
                        'url' => $pUrl,
                        'priceCurrency' => 'INR',
                        'price' => (float)preg_replace('/[^0-9.]/', '', $product['starting_price'] ?? '249'),
                        'priceValidUntil' => '2027-12-31',
                        'availability' => 'https://schema.org/InStock',
                        'itemCondition' => 'https://schema.org/NewCondition',
                        'seller' => ['@id' => url('#organization')]
                    ]
                ];
                if (!empty($data['reviewCount']) && $data['reviewCount'] > 0) {
                    $pNode['aggregateRating'] = [
                        '@type' => 'AggregateRating',
                        'ratingValue' => (string)$data['avgRating'],
                        'reviewCount' => (string)$data['reviewCount']
                    ];
                }
                $nodes[] = $pNode;
                $nodes[] = [
                    '@type' => 'BreadcrumbList',
                    '@id' => $pUrl . '#breadcrumb',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => url('products')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $product['category_name'] ?? 'Custom Printing', 'item' => category_url($product['category_slug'] ?? '')],
                        ['@type' => 'ListItem', 'position' => 4, 'name' => $product['title'], 'item' => $pUrl]
                    ]
                ];
            }
            break;

        case 'gallery':
        case 'portfolio':
            $galleryUrl = url('gallery');
            $items = db_fetch_all("SELECT id, title, description, image_url FROM gallery WHERE is_published = 1 ORDER BY display_order ASC");
            $imageObjects = [];
            foreach ($items as $item) {
                $imageObjects[] = [
                    '@type' => 'ImageObject',
                    'contentUrl' => url($item['image_url']),
                    'name' => $item['title'],
                    'caption' => strip_tags($item['description'] ?? $item['title'])
                ];
            }
            $nodes[] = [
                '@type' => 'CollectionPage',
                '@id' => $galleryUrl . '#portfolio',
                'url' => $galleryUrl,
                'name' => 'Production Portfolio & Specimen Gallery | OrderPrintex',
                'description' => 'High-resolution photographs of commercial production runs manufactured at our New Delhi printing facility.',
                'mainEntity' => ['@id' => $galleryUrl . '#imagegallery']
            ];
            $nodes[] = [
                '@type' => 'ImageGallery',
                '@id' => $galleryUrl . '#imagegallery',
                'name' => 'OrderPrintex Commercial Print Showcase',
                'description' => 'Specimens of DTF fabric transfers, UV DTF decals, and promotional items.',
                'image' => $imageObjects
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $galleryUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Portfolio', 'item' => $galleryUrl]
                ]
            ];
            break;

        case 'blog':
            $blogUrl = blog_url();
            $posts = db_fetch_all("SELECT id, title, slug, excerpt, featured_image, author, created_at, updated_at FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC");
            $blogPostNodes = [];
            foreach ($posts as $p) {
                $postUrl = blog_url($p['slug']);
                $blogPostNodes[] = [
                    '@type' => 'BlogPosting',
                    '@id' => $postUrl . '#article',
                    'headline' => $p['title'],
                    'url' => $postUrl,
                    'description' => strip_tags($p['excerpt'] ?? ''),
                    'image' => !empty($p['featured_image']) ? url($p['featured_image']) : url('assets/images/logo.png'),
                    'datePublished' => date('c', strtotime($p['created_at'])),
                    'dateModified' => date('c', strtotime($p['updated_at'] ?? $p['created_at'])),
                    'author' => [
                        '@type' => 'Person',
                        'name' => $p['author'] ?: 'OrderPrintex Editorial Team'
                    ],
                    'publisher' => ['@id' => url('#organization')]
                ];
            }
            $nodes[] = [
                '@type' => 'Blog',
                '@id' => $blogUrl . '#blog',
                'url' => $blogUrl,
                'name' => 'Printing Industry Knowledge Base & Prepress Guides | OrderPrintex',
                'description' => 'Educational guides, heat press charts, DTF vs UV DTF comparisons, and commercial printing insights.',
                'publisher' => ['@id' => url('#organization')],
                'blogPost' => $blogPostNodes
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $blogUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => $blogUrl]
                ]
            ];
            break;

        case 'blog-details':
            $post = $data['post'] ?? null;
            if (!$post && !empty($_GET['slug'])) {
                $post = db_fetch_one("SELECT * FROM blog_posts WHERE slug = :slug AND status = 'published'", ['slug' => $_GET['slug']]);
            }
            if (!$post) {
                $post = db_fetch_one("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY id ASC LIMIT 1");
            }
            if ($post) {
                $canonical = blog_url($post['slug']);
                $nodes[] = [
                    '@type' => 'BlogPosting',
                    '@id' => $canonical . '#article',
                    'headline' => $post['title'],
                    'description' => strip_tags($post['excerpt'] ?? ''),
                    'image' => [!empty($post['featured_image']) ? url($post['featured_image']) : url('assets/images/logo.png')],
                    'datePublished' => date('c', strtotime($post['created_at'])),
                    'dateModified' => date('c', strtotime($post['updated_at'] ?? $post['created_at'])),
                    'author' => [
                        '@type' => 'Person',
                        'name' => $post['author'] ?: 'OrderPrintex Editorial Team'
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        '@id' => url('#organization'),
                        'name' => 'OrderPrintex',
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => url('assets/images/logo.png')
                        ]
                    ],
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $canonical
                    ]
                ];
                $nodes[] = [
                    '@type' => 'BreadcrumbList',
                    '@id' => $canonical . '#breadcrumb',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => blog_url()],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $post['title'], 'item' => $canonical]
                    ]
                ];
            }
            break;

        case 'contact':
            $contactUrl = url('contact');
            $nodes[] = [
                '@type' => 'ContactPage',
                '@id' => $contactUrl . '#contactpage',
                'url' => $contactUrl,
                'name' => 'Contact OrderPrintex Delhi Production Studio',
                'description' => 'Get in touch with our New Delhi printing facility. Phone, WhatsApp, email, business hours, and directions to Pankha Road.',
                'mainEntity' => ['@id' => url('#organization')],
                'breadcrumb' => ['@id' => $contactUrl . '#breadcrumb']
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $contactUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Contact Us', 'item' => $contactUrl]
                ]
            ];
            break;

        case 'faq':
            $faqUrl = url('faq');
            $faqs = seo_get_curated_faqs();
            $questions = [];
            foreach ($faqs as $f) {
                $questions[] = [
                    '@type' => 'Question',
                    'name' => $f['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $f['answer']
                    ]
                ];
            }
            $nodes[] = [
                '@type' => 'FAQPage',
                '@id' => $faqUrl . '#faqpage',
                'url' => $faqUrl,
                'name' => 'Frequently Asked Questions | OrderPrintex',
                'description' => 'Answers to questions about artwork uploads, DTF transfer settings, wash care, turnaround, and volume pricing.',
                'mainEntity' => $questions
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $faqUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'FAQ', 'item' => $faqUrl]
                ]
            ];
            break;

        case 'pricing':
            $pricingUrl = url('pricing');
            $nodes[] = [
                '@type' => 'WebPage',
                '@id' => $pricingUrl . '#pricingpage',
                'url' => $pricingUrl,
                'name' => 'Trade Pricing & Volume Discount Tiers | OrderPrintex',
                'description' => 'Transparent commercial pricing for DTF gang sheets, UV decals, and apparel.',
                'breadcrumb' => ['@id' => $pricingUrl . '#breadcrumb']
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $pricingUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Pricing', 'item' => $pricingUrl]
                ]
            ];
            break;

        case 'get-quote':
            $quoteUrl = url('get-quote');
            $nodes[] = [
                '@type' => 'WebPage',
                '@id' => $quoteUrl . '#quotepage',
                'url' => $quoteUrl,
                'name' => 'Request a Custom Print Quotation | OrderPrintex',
                'description' => 'Upload design files, specify dimensions and quantities, and receive an itemized quotation.',
                'breadcrumb' => ['@id' => $quoteUrl . '#breadcrumb']
            ];
            $nodes[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $quoteUrl . '#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Get a Quote', 'item' => $quoteUrl]
                ]
            ];
            break;

        default:
            if (str_starts_with($cleanKey, 'services-') || str_ends_with($cleanKey, '-printing') || in_array($cleanKey, ['dtf-printing', 'uv-dtf-printing', 't-shirt-printing', 'corporate-printing'])) {
                $cleanSlug = str_replace('services-', '', $cleanKey);
                $srv = db_fetch_one("SELECT * FROM services WHERE slug = ? LIMIT 1", [$cleanSlug]);
                if ($srv) {
                    $srvUrl = service_url($srv['slug']);
                    $desc = !empty($srv['short_description']) ? strip_tags($srv['short_description']) : strip_tags($srv['full_description'] ?? '');
                    $sNode = [
                        '@type' => 'Service',
                        '@id' => $srvUrl . '#service',
                        'name' => $srv['title'],
                        'url' => $srvUrl,
                        'serviceType' => 'Commercial Printing',
                        'description' => $desc,
                        'provider' => ['@id' => url('#organization')],
                        'areaServed' => ['@type' => 'Country', 'name' => 'India']
                    ];
                    if (!empty($srv['image_url'])) {
                        $sNode['image'] = url($srv['image_url']);
                    }
                    $nodes[] = $sNode;
                    $nodes[] = [
                        '@type' => 'BreadcrumbList',
                        '@id' => $srvUrl . '#breadcrumb',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Printing Services', 'item' => url('services')],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $srv['title'], 'item' => $srvUrl]
                        ]
                    ];
                }
            }
            break;
    }
    return $nodes;
}

/**
 * Return the Master Unified Schema.org Graph for any page.
 * Guarantees Organization, LocalBusiness, and WebSite at top level of @graph,
 * plus all page-specific top-level entities (Service, Product, ItemList, etc.)
 */
function seo_get_unified_page_graph(string $key = '', array $data = []): array {
    $base = seo_get_base_graph_nodes();
    $page = seo_get_page_nodes($key, $data);
    return [
        '@context' => 'https://schema.org',
        '@graph' => array_merge($base, $page)
    ];
}

/**
 * Unified Builders (Retained for backwards compatibility & CMS sync)
 */
function seo_build_home_schema(): array {
    return seo_get_unified_page_graph('home');
}

function seo_build_about_schema(): array {
    return seo_get_unified_page_graph('about');
}

function seo_build_service_schema(string $slug): ?array {
    return seo_get_unified_page_graph('services-' . $slug);
}

function seo_build_services_catalog_schema(): array {
    return seo_get_unified_page_graph('services');
}

function seo_build_products_catalog_schema(): array {
    return seo_get_unified_page_graph('products');
}

function seo_build_gallery_schema(): array {
    return seo_get_unified_page_graph('gallery');
}

function seo_build_blog_index_schema(): array {
    return seo_get_unified_page_graph('blog');
}

function seo_build_contact_schema(): array {
    return seo_get_unified_page_graph('contact');
}

function seo_build_faq_schema(): array {
    return seo_get_unified_page_graph('faq');
}

function seo_build_pricing_schema(): array {
    return seo_get_unified_page_graph('pricing');
}

function seo_build_quote_schema(): array {
    return seo_get_unified_page_graph('get-quote');
}

/**
 * Master Dynamic Schema.org Generator: maps $pageSchemaKey to the exact connected JSON-LD graph.
 */
function seo_render_dynamic_schema(string $key, array $data = []): ?array {
    return seo_get_unified_page_graph($key, $data);
}

/**
 * Render Schema.org JSON-LD tag from dynamic unified graph
 */
function seo_render_schema(string $key, array $data = []): string {
    $dynamic = seo_render_dynamic_schema($key, $data);
    if (!empty($dynamic)) {
        return "\n  <!-- Schema.org Structured Data (" . htmlspecialchars($key) . ") -->\n" .
               "  <script type=\"application/ld+json\">\n" .
               json_encode($dynamic, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n" .
               "  </script>\n";
    }
    return '';
}

/**
 * Safe slug generator preventing filename collisions or invalid characters
 */
function seo_safe_slug(string $slug): string {
    $slug = strtolower(trim($slug));
    $slug = preg_replace('/[^a-z0-9_-]+/', '-', $slug);
    $slug = trim($slug, '-_');
    return $slug ?: 'item';
}

/**
 * Safe atomic file write preventing incomplete or corrupted files
 */
function seo_atomic_write(string $filePath, string $content): bool {
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    $tmpFile = $filePath . '.' . uniqid('seo_', true) . '.tmp';
    if (@file_put_contents($tmpFile, $content) === false) {
        return (@file_put_contents($filePath, $content) !== false);
    }
    if (file_exists($filePath)) {
        @unlink($filePath);
    }
    if (@rename($tmpFile, $filePath)) {
        return true;
    }
    $res = (@file_put_contents($filePath, $content) !== false);
    @unlink($tmpFile);
    return $res;
}

/**
 * Convert HTML content to clean, readable Markdown
 */
function seo_html_to_markdown(string $html): string {
    $text = trim($html);
    if ($text === '') {
        return '';
    }
    // Headings
    $text = preg_replace('/<h1[^>]*>(.*?)<\/h1>/is', "\n# $1\n", $text);
    $text = preg_replace('/<h2[^>]*>(.*?)<\/h2>/is', "\n## $1\n", $text);
    $text = preg_replace('/<h3[^>]*>(.*?)<\/h3>/is', "\n### $1\n", $text);
    $text = preg_replace('/<h4[^>]*>(.*?)<\/h4>/is', "\n#### $1\n", $text);
    // Bold / Italic
    $text = preg_replace('/<(strong|b)[^>]*>(.*?)<\/(strong|b)>/is', '**$2**', $text);
    $text = preg_replace('/<(em|i)[^>]*>(.*?)<\/(em|i)>/is', '*$2*', $text);
    // Links
    $text = preg_replace('/<a\s+[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)<\/a>/is', '[$2]($1)', $text);
    // Lists
    $text = preg_replace('/<li[^>]*>(.*?)<\/li>/is', "\n- $1", $text);
    // Paragraphs and line breaks
    $text = preg_replace('/<br\s*\/?>/is', "\n", $text);
    $text = preg_replace('/<p[^>]*>(.*?)<\/p>/is', "\n$1\n", $text);
    // Blockquotes
    $text = preg_replace('/<blockquote[^>]*>(.*?)<\/blockquote>/is', "\n> $1\n", $text);
    // Strip remaining tags
    $text = strip_tags($text);
    // Decode HTML entities
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Normalize excess blank lines
    $text = preg_replace("/[\r\n]{3,}/", "\n\n", $text);
    return trim($text);
}

/**
 * Explicitly delete content-specific SEO files (used on unpublish / delete)
 */
function seo_delete_content_files(string $type, string $slug): void {
    $rootDir = realpath(__DIR__ . '/..') ?: 'C:/Movies/Internship/OrderPrintex';
    $safeSlug = seo_safe_slug($slug);
    $mdFiles = [
        $rootDir . '/seo/markdown/' . $type . '-' . $safeSlug . '.md',
        $rootDir . '/seo/markdown/' . $safeSlug . '.md'
    ];
    $jsonFiles = [
        $rootDir . '/seo/schema/' . $type . '-' . $safeSlug . '.json',
        $rootDir . '/seo/schema/' . $safeSlug . '.json'
    ];
    foreach ($mdFiles as $f) {
        if (file_exists($f)) @unlink($f);
    }
    foreach ($jsonFiles as $f) {
        if (file_exists($f)) @unlink($f);
    }
}

/**
 * Generate all 14 core page Markdowns + Product, Category, Service, and Blog Markdowns into /seo/markdown/
 * Uses clean canonical URLs without trailing slash.
 */
function seo_generate_page_markdowns(string $rootDir, array $ctx): array {
    $mdDir = $rootDir . '/seo/markdown';
    if (!is_dir($mdDir)) {
        @mkdir($mdDir, 0777, true);
    }

    $created = [];
    $today = date('Y-m-d');

    // 1. Home / Index Markdown
    $homeMd = "# OrderPrintex — Commercial DTF & UV DTF Printing Studio Delhi\n\n";
    $homeMd .= "- **Canonical URL**: <" . seo_prod_url('') . ">\n";
    $homeMd .= "- **Meta Description**: " . $ctx['metaDesc'] . "\n";
    $homeMd .= "- **Operating Headquarters**: " . $ctx['siteAddress'] . "\n";
    $homeMd .= "- **Primary Contact**: " . $ctx['sitePhone'] . " | " . $ctx['siteEmail'] . "\n\n";
    $homeMd .= "## Executive Overview\n\n";
    $homeMd .= "OrderPrintex is a high-capacity industrial digital printing facility based in New Delhi, India. We deliver commercial-grade Direct-to-Film (DTF) apparel transfers, UV DTF 3D tactile decals, custom t-shirt manufacturing, and corporate swag kits. We operate on a bespoke B2B Enquiry-to-Quotation workflow with automated prepress inspection and pan-India express dispatch.\n\n";
    $homeMd .= "## Core Manufacturing Capabilities\n\n";
    $homeMd .= "1. **Direct-to-Film (DTF) Transfers**: Continuous 22-inch roll printing with dual Epson i3200 printheads, ultra-dense white ink backing, and automated TPU adhesive curing.\n";
    $homeMd .= "2. **UV DTF Decals**: Instant cold-peel multi-surface transfer stickers with 3D spot gloss varnish for drinkware, metal, glass, and acrylic.\n";
    $homeMd .= "3. **Custom T-Shirt Printing**: Premium 180 GSM bio-washed combed cotton, 240 GSM oversized streetwear, and sports polyester with photographic detail.\n";
    $homeMd .= "4. **Corporate Swag & Promotional Kits**: Turnkey client onboarding boxes, customized insulated drinkware, diaries, and executive presentation items.\n\n";
    $homeMd .= "## Machinery & Fleet\n\n";
    foreach ($ctx['equipment'] as $eq) {
        $homeMd .= "- **{$eq['name']}** ({$eq['category']}): {$eq['purpose']} — {$eq['impact']}\n";
    }
    $homeMd .= "\n## How to Order & Prepress Workflow\n\n";
    $homeMd .= "1. Visit <" . seo_prod_url('get-quote') . "> or upload artwork via the homepage.\n";
    $homeMd .= "2. Prepress engineers audit your resolution (300 DPI transparent PNG/PDF) and send a formal commercial quotation.\n";
    $homeMd .= "3. Approve the digital RIP proof in your customer dashboard.\n";
    $homeMd .= "4. Production runs within 24-48 hours with express nationwide tracking.\n";

    seo_atomic_write($mdDir . '/home.md', $homeMd);
    seo_atomic_write($mdDir . '/index.md', $homeMd);
    $created[] = 'home.md';
    $created[] = 'index.md';

    // 2. About Markdown
    $aboutMd = "# About OrderPrintex — Printing Facility & Prepress Studio\n\n";
    $aboutMd .= "- **Canonical URL**: <" . seo_prod_url('about') . ">\n";
    $aboutMd .= "- **Meta Description**: Learn about OrderPrintex: Delhi's leading commercial DTF and UV DTF printing studio with 10+ years of prepress expertise, serving 500+ brands across India.\n\n";
    $aboutMd .= "## Our Operational Heritage\n\n";
    $aboutMd .= "Founded with a mission to bring world-class digital garment and surface decoration technologies to Indian brands and print studios, OrderPrintex combines precision Japanese printheads, European TPU adhesives, and in-house prepress color calibration.\n\n";
    $aboutMd .= "## Factory Metrics & Infrastructure\n\n";
    $aboutMd .= "- **Heritage**: Over 10 years of prepress and textile printing experience.\n";
    $aboutMd .= "- **Technology Focus**: Industrial DTF roll-to-roll and UV DTF cold transfers.\n";
    $aboutMd .= "- **Prepress Guarantee**: Every customer file is manually audited for DPI, alpha channels, and color gamut before printing.\n";
    $aboutMd .= "- **Pan-India Dispatch**: Daily shipments via Blue Dart, DTDC, Delhivery, and express cargo.\n";
    $aboutMd .= "- **Facility Address**: " . $ctx['siteAddress'] . "\n";
    seo_atomic_write($mdDir . '/about.md', $aboutMd);
    $created[] = 'about.md';

    // 3. Services Directory Markdown
    $servicesMd = "# Industrial Printing & Manufacturing Services | OrderPrintex\n\n";
    $servicesMd .= "- **Canonical URL**: <" . seo_prod_url('services') . ">\n";
    $servicesMd .= "- **Meta Description**: Explore our end-to-end commercial printing services: DTF fabric transfers, UV DTF rigid decals, custom screen-quality t-shirts, and corporate swag kits.\n\n";
    $servicesMd .= "## Available Services\n\n";
    foreach ($ctx['services'] as $srv) {
        $servicesMd .= "### [{$srv['title']}](<" . seo_prod_service_url($srv['slug']) . ">)\n";
        $servicesMd .= "- **Service URL**: <" . seo_prod_service_url($srv['slug']) . ">\n";
        $desc = !empty($srv['full_description']) ? strip_tags($srv['full_description']) : (!empty($srv['short_description']) ? strip_tags($srv['short_description']) : 'High-definition industrial printing service.');
        $servicesMd .= "- **Overview**: {$desc}\n";
        $servicesMd .= "- **Turnaround**: 24-48 Hours | **Pan-India Dispatch**\n\n";
    }
    seo_atomic_write($mdDir . '/services.md', $servicesMd);
    $created[] = 'services.md';

    // 4-7. Individual Services Markdowns (both services-{slug}.md and {slug}.md)
    foreach ($ctx['services'] as $srv) {
        $slug = seo_safe_slug($srv['slug']);
        $sFile = 'services-' . $slug . '.md';
        $aliasFile = $slug . '.md';
        $sMd = "# {$srv['title']} | OrderPrintex\n\n";
        $sMd .= "- **Canonical URL**: <" . seo_prod_service_url($slug) . ">\n";
        $desc = !empty($srv['full_description']) ? strip_tags($srv['full_description']) : (!empty($srv['short_description']) ? strip_tags($srv['short_description']) : 'Commercial printing service.');
        $sMd .= "- **Description**: {$desc}\n\n";
        $sMd .= "## Service Specifications & Technical Capabilities\n\n";
        $sMd .= "- **Turnaround**: 24-48 Hours standard.\n";
        $sMd .= "- **File Formats**: 300 DPI Transparent PNG, vector PDF, AI, PSD.\n";
        $sMd .= "- **Quality Standard**: OEKO-TEX certified inks, 1440x2880 DPI resolution.\n";
        $sMd .= "- **Production Hub**: " . $ctx['siteAddress'] . "\n\n";
        $sMd .= "## Direct Quote Request\n\n";
        $sMd .= "Request a commercial quote for {$srv['title']} at <" . seo_prod_url('get-quote') . ">.\n";
        
        seo_atomic_write($mdDir . '/' . $sFile, $sMd);
        seo_atomic_write($mdDir . '/' . $aliasFile, $sMd);
        $created[] = $sFile;
        $created[] = $aliasFile;
    }

    // 8. Products Catalog Markdown
    $prodCatalogMd = "# Commercial Print Product Catalog | OrderPrintex\n\n";
    $prodCatalogMd .= "- **Canonical URL**: <" . seo_prod_url('products') . ">\n";
    $prodCatalogMd .= "- **Meta Description**: Browse all OrderPrintex manufactured products: DTF gang rolls, UV DTF sticker sheets, blank apparel, corporate swag kits, and custom packaging boxes.\n\n";
    $prodCatalogMd .= "## Product Categories\n\n";
    foreach ($ctx['categories'] as $cat) {
        $prodCatalogMd .= "- **[{$cat['name']}](<" . seo_prod_category_url($cat['slug']) . ">)**: " . strip_tags($cat['description'] ?? '') . "\n";
    }
    $prodCatalogMd .= "\n## Products Directory\n\n";
    foreach ($ctx['products'] as $p) {
        $price = $p['sale_price'] ?? $p['regular_price'] ?? $p['starting_price'] ?? 0;
        $priceStr = $price > 0 ? "₹" . number_format($price, 2) : "Custom Quoted";
        $sku = $p['sku'] ?: ('OP-PRD-' . $p['id']);
        $prodCatalogMd .= "### [{$p['title']}](<" . seo_prod_product_url($p['slug']) . ">)\n";
        $prodCatalogMd .= "- **SKU**: `{$sku}` | **Category**: " . ($p['category_name'] ?: 'Printing') . " | **Base Price**: {$priceStr}\n";
        $pDesc = !empty($p['short_desc']) ? strip_tags($p['short_desc']) : (!empty($p['description']) ? substr(strip_tags($p['description']), 0, 160) . '...' : '');
        $prodCatalogMd .= "- **Summary**: {$pDesc}\n\n";
    }
    seo_atomic_write($mdDir . '/products.md', $prodCatalogMd);
    $created[] = 'products.md';

    // 9. Pricing Markdown
    $pricingMd = "# Trade Pricing & Volume Discount Tiers | OrderPrintex\n\n";
    $pricingMd .= "- **Canonical URL**: <" . seo_prod_url('pricing') . ">\n";
    $pricingMd .= "- **Meta Description**: Transparent commercial pricing for DTF gang sheets, UV decals, and apparel. Wholesale discount tiers for brands, screen printers, and volume resellers.\n\n";
    $pricingMd .= "## B2B Commercial Pricing Structure\n\n";
    $pricingMd .= "OrderPrintex offers transparent per-meter and per-unit tiered pricing. As print volume increases, per-unit rates decrease significantly.\n\n";
    $pricingMd .= "### DTF Transfer Rolls (22-Inch Width)\n";
    $pricingMd .= "- **1 to 5 Linear Meters**: Standard retail/sample tier.\n";
    $pricingMd .= "- **6 to 20 Linear Meters**: Brand & studio discount tier (10% - 15% off).\n";
    $pricingMd .= "- **21 to 50 Linear Meters**: Bulk trade tier (20% - 25% off).\n";
    $pricingMd .= "- **50+ Linear Meters**: Master wholesale roll pricing (up to 35% off).\n\n";
    $pricingMd .= "### Quotation Details\n";
    $pricingMd .= "- All invoices comply with 18% GST.\n";
    $pricingMd .= "- Transparent linear yield calculations with zero hidden plate or setup charges.\n";
    seo_atomic_write($mdDir . '/pricing.md', $pricingMd);
    $created[] = 'pricing.md';

    // 10. Gallery Markdown
    $galleryMd = "# Production Portfolio & Specimen Gallery | OrderPrintex\n\n";
    $galleryMd .= "- **Canonical URL**: <" . seo_prod_url('gallery') . ">\n";
    $galleryMd .= "- **Meta Description**: High-resolution photographs of commercial production runs: DTF prints on dark garments, UV DTF on glassware, luxury unboxing packaging, and merchandise.\n\n";
    $galleryMd .= "## Showcase Specimens\n\n";
    foreach ($ctx['portfolioItems'] as $item) {
        $galleryMd .= "### {$item['title']} (" . strtoupper($item['category']) . ")\n";
        $galleryMd .= "- **Description**: {$item['description']}\n";
        $galleryMd .= "- **Image Asset**: <" . seo_prod_url($item['image_url']) . ">\n\n";
    }
    seo_atomic_write($mdDir . '/gallery.md', $galleryMd);
    $created[] = 'gallery.md';

    // 11. Blog Markdown
    $blogMd = "# Printing Knowledge Base & Prepress Guides | OrderPrintex\n\n";
    $blogMd .= "- **Canonical URL**: <" . seo_prod_url('blog') . ">\n";
    $blogMd .= "- **Meta Description**: Educational guides, heat press temperature charts, DTF vs UV DTF comparisons, file preparation tutorials, and commercial printing insights.\n\n";
    $blogMd .= "## Published Industry Guides\n\n";
    foreach ($ctx['blogPosts'] as $post) {
        $blogMd .= "### [{$post['title']}](<" . seo_prod_blog_url($post['slug']) . ">)\n";
        $blogMd .= "- **Author**: " . ($post['author'] ?: 'OrderPrintex Editorial Team') . " | **Read Time**: " . ($post['read_time'] ?: '5 min read') . "\n";
        $excerpt = !empty($post['excerpt']) ? strip_tags($post['excerpt']) : 'Printing industry technical guide.';
        $blogMd .= "- **Summary**: {$excerpt}\n\n";
    }
    seo_atomic_write($mdDir . '/blog.md', $blogMd);
    $created[] = 'blog.md';

    // 12. FAQ Markdown
    $faqMd = "# Frequently Asked Questions | OrderPrintex\n\n";
    $faqMd .= "- **Canonical URL**: <" . seo_prod_url('faq') . ">\n";
    $faqMd .= "- **Meta Description**: Comprehensive answers covering artwork upload formats, DTF press settings, wash care instructions, UV DTF adhesion, minimum order quantities, and turnaround.\n\n";
    $faqMd .= "## Knowledge Base Q&As\n\n";
    foreach ($ctx['faqs'] as $idx => $f) {
        $num = $idx + 1;
        $faqMd .= "### Q{$num}. [{$f['category']}] {$f['question']}\n\n";
        $faqMd .= "{$f['answer']}\n\n";
    }
    seo_atomic_write($mdDir . '/faq.md', $faqMd);
    $created[] = 'faq.md';

    // 13. Contact Markdown
    $contactMd = "# Contact & Factory Location | OrderPrintex\n\n";
    $contactMd .= "- **Canonical URL**: <" . seo_prod_url('contact') . ">\n";
    $contactMd .= "- **Meta Description**: Get in touch with our New Delhi printing facility. Phone, WhatsApp, email, business hours, and directions to Pankha Road, Sitapuri Part 1.\n\n";
    $contactMd .= "## Manufacturing Facility & Direct Channels\n\n";
    $contactMd .= "- **Company Name**: " . $ctx['siteName'] . "\n";
    $contactMd .= "- **Address**: " . $ctx['siteAddress'] . "\n";
    $contactMd .= "- **Phone / WhatsApp**: " . $ctx['sitePhone'] . " (<https://wa.me/919354028199>)\n";
    $contactMd .= "- **Email**: " . $ctx['siteEmail'] . "\n";
    $contactMd .= "- **Working Hours**: " . $ctx['siteHours'] . "\n";
    $contactMd .= "- **Coordinates**: 28.6180° N, 77.0805° E (Near 40 Feet Road, Pankha Road, Sitapuri Part 1, Rani Bagh, Delhi 110059)\n";
    seo_atomic_write($mdDir . '/contact.md', $contactMd);
    $created[] = 'contact.md';

    // 14. Get Quote Markdown
    $quoteMd = "# Request a Custom Print Quotation | OrderPrintex\n\n";
    $quoteMd .= "- **Canonical URL**: <" . seo_prod_url('get-quote') . ">\n";
    $quoteMd .= "- **Meta Description**: Upload your design files, specify dimensions and quantities, and receive an itemized commercial quotation with volume discounts within hours.\n\n";
    $quoteMd .= "## Quotation Request Guidelines\n\n";
    $quoteMd .= "1. Provide artwork in 300 DPI transparent PNG, PDF, or AI format.\n";
    $quoteMd .= "2. State your required dimensions (in inches or linear meters) and required volume.\n";
    $quoteMd .= "3. Prepress technicians verify print feasibility, calculate optimal nesting yield, and issue a formal GST invoice quote.\n";
    $quoteMd .= "4. Direct sales hotline: " . $ctx['sitePhone'] . " (WhatsApp available).\n";
    seo_atomic_write($mdDir . '/get-quote.md', $quoteMd);
    $created[] = 'get-quote.md';

    // Product Category Markdowns (category-{slug}.md and product-category-{slug}.md)
    foreach ($ctx['categories'] as $cat) {
        $cSlug = seo_safe_slug($cat['slug']);
        $cFile = 'category-' . $cSlug . '.md';
        $aliasCFile = 'product-category-' . $cSlug . '.md';
        $cUrl = seo_prod_category_url($cSlug);
        $cDesc = !empty($cat['description']) ? trim(strip_tags($cat['description'])) : "Commercial {$cat['name']} printing products and services.";

        $cMd = "# {$cat['name']} | Commercial Printing Products | OrderPrintex\n\n";
        $cMd .= "- **Canonical URL**: <{$cUrl}>\n";
        $cMd .= "- **Category Name**: {$cat['name']}\n";
        $cMd .= "- **Description**: {$cDesc}\n\n";
        $cMd .= "## Available Products in this Category\n\n";

        $matchedProducts = 0;
        foreach ($ctx['products'] as $p) {
            if ((int)($p['category_id'] ?? 0) === (int)$cat['id'] || (isset($p['category_slug']) && $p['category_slug'] === $cat['slug'])) {
                $matchedProducts++;
                $price = $p['sale_price'] ?? $p['regular_price'] ?? $p['starting_price'] ?? 0;
                $priceStr = $price > 0 ? "₹" . number_format($price, 2) : "Custom Quoted";
                $sku = $p['sku'] ?: ('OP-PRD-' . $p['id']);
                $cMd .= "### [{$p['title']}](<" . seo_prod_product_url($p['slug']) . ">)\n";
                $cMd .= "- **Product URL**: <" . seo_prod_product_url($p['slug']) . ">\n";
                $cMd .= "- **SKU**: `{$sku}` | **Base Price**: {$priceStr}\n";
                $cMd .= "- **Summary**: " . strip_tags($p['short_desc'] ?? '') . "\n\n";
            }
        }
        if ($matchedProducts === 0) {
            $cMd .= "Products currently being manufactured. Request a custom quote at <" . seo_prod_url('get-quote') . ">.\n\n";
        }
        $cMd .= "---\n\nPublished by [OrderPrintex Commercial Printing Studio](<" . seo_prod_url('') . ">).\n";

        seo_atomic_write($mdDir . '/' . $cFile, $cMd);
        seo_atomic_write($mdDir . '/' . $aliasCFile, $cMd);
        $created[] = $cFile;
        $created[] = $aliasCFile;
    }

    // Blog Category Markdowns (blog-category-{slug}.md)
    foreach ($ctx['blogCategories'] as $bcat) {
        $bcSlug = seo_safe_slug($bcat['slug']);
        $bcFile = 'blog-category-' . $bcSlug . '.md';
        $bcUrl = seo_prod_blog_category_url($bcSlug);
        $bcDesc = !empty($bcat['description']) ? trim(strip_tags($bcat['description'])) : "Industry guides and tutorials for {$bcat['name']}.";

        $bcMd = "# {$bcat['name']} | Printing Knowledge Base | OrderPrintex\n\n";
        $bcMd .= "- **Canonical URL**: <{$bcUrl}>\n";
        $bcMd .= "- **Category**: {$bcat['name']}\n";
        $bcMd .= "- **Description**: {$bcDesc}\n\n";
        $bcMd .= "## Published Articles in this Category\n\n";

        $matchedPosts = 0;
        foreach ($ctx['blogPosts'] as $post) {
            if ((int)($post['category_id'] ?? 0) === (int)$bcat['id'] || (isset($post['category_slug']) && $post['category_slug'] === $bcat['slug'])) {
                $matchedPosts++;
                $bcMd .= "### [{$post['title']}](<" . seo_prod_blog_url($post['slug']) . ">)\n";
                $bcMd .= "- **Author**: " . ($post['author'] ?: 'OrderPrintex Editorial Team') . " | **Read Time**: " . ($post['read_time'] ?: '5 min read') . "\n";
                $bcMd .= "- **Summary**: " . strip_tags($post['excerpt'] ?? '') . "\n\n";
            }
        }
        if ($matchedPosts === 0) {
            $bcMd .= "New articles coming soon. Explore the full blog at <" . seo_prod_url('blog') . ">.\n\n";
        }
        $bcMd .= "---\n\nPublished by [OrderPrintex Commercial Printing Studio](<" . seo_prod_url('') . ">).\n";

        seo_atomic_write($mdDir . '/' . $bcFile, $bcMd);
        $created[] = $bcFile;
    }

    // Product Markdowns (product-{slug}.md)
    foreach ($ctx['products'] as $p) {
        $pSlug = seo_safe_slug($p['slug']);
        $pFile = 'product-' . $pSlug . '.md';
        $price = $p['sale_price'] ?? $p['regular_price'] ?? $p['starting_price'] ?? 0;
        $priceStr = $price > 0 ? "₹" . number_format($price, 2) : "Custom Quoted";
        $sku = $p['sku'] ?: ('OP-PRD-' . $p['id']);

        $pMd = "# {$p['title']} | OrderPrintex\n\n";
        $pMd .= "- **Canonical URL**: <" . seo_prod_url('product/' . $pSlug) . ">\n";
        $pMd .= "- **SKU**: `{$sku}`\n";
        $pMd .= "- **Category**: " . ($p['category_name'] ?: 'Printing') . "\n";
        $pMd .= "- **Base Price**: {$priceStr} " . (!empty($p['price_unit']) ? "({$p['price_unit']})" : "") . "\n";
        $pMd .= "- **Lead Time**: " . ($p['lead_time'] ?: '24-48 Hours') . "\n";
        $pMd .= "- **Minimum Order Quantity**: " . ($p['min_order_qty'] ?: ($p['min_order'] ?: '1 Unit')) . "\n\n";
        
        $pDesc = !empty($p['description']) ? strip_tags($p['description']) : (!empty($p['short_desc']) ? strip_tags($p['short_desc']) : '');
        if ($pDesc) {
            $pMd .= "## Description\n\n{$pDesc}\n\n";
        }

        if (!empty($p['specifications'])) {
            $pMd .= "## Technical Specifications\n\n" . strip_tags($p['specifications']) . "\n\n";
        }
        if (!empty($p['artwork_guidelines'])) {
            $pMd .= "## Artwork Submission Guidelines\n\n" . strip_tags($p['artwork_guidelines']) . "\n\n";
        }
        if (!empty($p['application_instructions'])) {
            $pMd .= "## Application Instructions\n\n" . strip_tags($p['application_instructions']) . "\n\n";
        }

        $pMd .= "## Order & Inquiries\n\n";
        $pMd .= "To order {$p['title']}, request a quote at <" . seo_prod_url('get-quote') . "> or contact WhatsApp: " . $ctx['sitePhone'] . ".\n";

        seo_atomic_write($mdDir . '/' . $pFile, $pMd);
        $created[] = $pFile;
    }

    // Blog Post Markdowns (blog-{slug}.md) — Fully Detailed as Required
    foreach ($ctx['blogPosts'] as $post) {
        $bSlug = seo_safe_slug($post['slug']);
        $bFile = 'blog-' . $bSlug . '.md';
        $canonicalUrl = seo_prod_blog_url($bSlug);
        $pubDate = !empty($post['created_at']) ? substr($post['created_at'], 0, 10) : $today;
        $modDate = !empty($post['updated_at']) ? substr($post['updated_at'], 0, 10) : $pubDate;
        $author = !empty($post['author']) ? trim($post['author']) : 'OrderPrintex Editorial Team';
        $readTime = !empty($post['read_time']) ? trim($post['read_time']) : '5 min read';
        $metaTitle = !empty($post['seo_title']) ? trim($post['seo_title']) : $post['title'] . ' | OrderPrintex';
        $metaDesc = !empty($post['seo_description']) ? trim($post['seo_description']) : (!empty($post['excerpt']) ? strip_tags($post['excerpt']) : substr(strip_tags($post['content']), 0, 160));
        $catName = !empty($post['category_name']) ? trim($post['category_name']) : 'Printing & Manufacturing';
        $catSlug = !empty($post['category_slug']) ? trim($post['category_slug']) : '';
        $catUrl = $catSlug ? seo_prod_blog_category_url($catSlug) : seo_prod_blog_url();
        $featImg = !empty($post['featured_image']) ? seo_prod_url($post['featured_image']) : seo_prod_url('assets/images/logo.png');
        $imgAlt = !empty($post['title']) ? htmlspecialchars($post['title']) : 'OrderPrintex Blog Article';

        $bMd = "# {$post['title']}\n\n";
        $bMd .= "- **Meta Title**: {$metaTitle}\n";
        $bMd .= "- **Meta Description**: {$metaDesc}\n";
        $bMd .= "- **Canonical URL**: <{$canonicalUrl}>\n";
        $bMd .= "- **Category**: [{$catName}](<{$catUrl}>)\n";
        $bMd .= "- **Author**: {$author}\n";
        $bMd .= "- **Published Date**: {$pubDate}\n";
        $bMd .= "- **Last Modified Date**: {$modDate}\n";
        $bMd .= "- **Reading Time**: {$readTime}\n\n";

        if ($featImg) {
            $bMd .= "![{$imgAlt}](<{$featImg}>)\n\n";
        }

        if (!empty($post['excerpt'])) {
            $bMd .= "> " . trim(strip_tags($post['excerpt'])) . "\n\n";
        }

        $bMd .= "## Article Content\n\n";
        $bMd .= seo_html_to_markdown($post['content']) . "\n\n";

        $bMd .= "## Related Printing Services & Fast Links\n\n";
        $bMd .= "- [Direct-to-Film (DTF) Transfers](<" . seo_prod_service_url('dtf-printing') . ">)\n";
        $bMd .= "- [UV DTF 3D Hard Surface Decals](<" . seo_prod_service_url('uv-dtf-printing') . ">)\n";
        $bMd .= "- [Custom Screen & T-Shirt Studio](<" . seo_prod_service_url('t-shirt-printing') . ">)\n";
        $bMd .= "- [Corporate Merchandise & Gifting](<" . seo_prod_service_url('corporate-printing') . ">)\n";
        $bMd .= "- [Commercial Products Catalog](<" . seo_prod_url('products') . ">)\n";
        $bMd .= "- [Request a Commercial Quotation](<" . seo_prod_url('get-quote') . ">)\n\n";
        $bMd .= "---\n\nPublished by [OrderPrintex Commercial Printing Studio](<" . seo_prod_url('') . ">).\n";

        seo_atomic_write($mdDir . '/' . $bFile, $bMd);
        $created[] = $bFile;
    }

    // Static Pages from Database (page-{slug}.md)
    foreach ($ctx['staticPages'] as $sp) {
        $spSlug = seo_safe_slug($sp['slug']);
        $spFile = 'page-' . $spSlug . '.md';
        $spUrl = seo_prod_url($spSlug);

        $spMd = "# {$sp['title']} | OrderPrintex\n\n";
        $spMd .= "- **Canonical URL**: <{$spUrl}>\n";
        $spMd .= "- **Published Date**: " . (!empty($sp['created_at']) ? substr($sp['created_at'], 0, 10) : $today) . "\n\n";
        if (!empty($sp['content'])) {
            $spMd .= "## Page Content\n\n" . seo_html_to_markdown($sp['content']) . "\n\n";
        }
        $spMd .= "---\n\nPublished by [OrderPrintex Commercial Printing Studio](<" . seo_prod_url('') . ">).\n";

        seo_atomic_write($mdDir . '/' . $spFile, $spMd);
        $created[] = $spFile;
        // Also write direct clean alias if not already a core file
        if (!in_array($spSlug . '.md', ['about.md', 'contact.md', 'products.md', 'services.md', 'blog.md', 'faq.md', 'pricing.md', 'gallery.md', 'get-quote.md', 'home.md', 'index.md'], true)) {
            seo_atomic_write($mdDir . '/' . $spSlug . '.md', $spMd);
            $created[] = $spSlug . '.md';
        }
    }

    // Clean up stale markdown files for deleted or unpublished content
    $existingMdFiles = glob($mdDir . '/*.md') ?: [];
    foreach ($existingMdFiles as $filePath) {
        $baseName = basename($filePath);
        // Only sweep dynamically managed content files
        if (preg_match('/^(blog|product|category|blog-category|services|page)-.+\.md$/', $baseName) || in_array($baseName, ['about-us.md', 'privacy-policy.md', 'terms-and-conditions.md', 'shipping-policy.md'], true)) {
            if (!in_array($baseName, $created, true)) {
                @unlink($filePath);
            }
        }
    }

    return $created;
}

/**
 * Generate all 14 core page Schema JSONs + Product Schema JSONs + Blog Schema JSONs into /seo/schema/
 * Uses clean canonical URLs without trailing slash.
 */
function seo_generate_page_schemas(string $rootDir, array $ctx): array {
    $schemaDir = $rootDir . '/seo/schema';
    if (!is_dir($schemaDir)) {
        @mkdir($schemaDir, 0777, true);
    }

    $created = [];
    $nowIso = date('c');

    $orgReference = [
        '@type' => 'ProfessionalService',
        '@id' => seo_prod_url('#organization'),
        'name' => $ctx['siteName'],
        'url' => seo_prod_url(''),
        'telephone' => $ctx['sitePhone'],
        'email' => $ctx['siteEmail'],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $ctx['siteAddress'],
            'addressLocality' => 'New Delhi',
            'addressRegion' => 'Delhi',
            'postalCode' => '110059',
            'addressCountry' => 'IN'
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => 28.6180,
            'longitude' => 77.0805
        ],
        'sameAs' => [
            'https://www.facebook.com/orderprintex',
            'https://www.instagram.com/orderprintex',
            'https://www.youtube.com/@orderprintex',
            'https://www.linkedin.com/company/orderprintex'
        ]
    ];

    // Helper writer
    $writeSchema = function(string $name, array $data) use ($schemaDir, &$created) {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        file_put_contents($schemaDir . '/' . $name, $json);
        $created[] = $name;
    };

    // 1. Home Schema (home.json & index.json)
    $homeSchema = seo_build_home_schema();
    $writeSchema('home.json', $homeSchema);
    $writeSchema('index.json', $homeSchema);

    // 2. About Schema
    $writeSchema('about.json', seo_build_about_schema());

    // 3. Services Directory Schema
    $writeSchema('services.json', seo_build_services_catalog_schema());

    // 4-7. Individual Services Schemas (both services-{slug}.json and {slug}.json)
    foreach ($ctx['services'] as $srv) {
        $slug = $srv['slug'];
        $sSchema = seo_build_service_schema($slug);
        if ($sSchema) {
            $writeSchema('services-' . $slug . '.json', $sSchema);
            $writeSchema($slug . '.json', $sSchema);
        }
    }

    // 8. Products Catalog Schema
    $writeSchema('products.json', seo_build_products_catalog_schema());

    // 9. Pricing Schema
    $writeSchema('pricing.json', seo_build_pricing_schema());

    // 10. Gallery Schema
    $writeSchema('gallery.json', seo_build_gallery_schema());

    // 11. Blog Schema
    $writeSchema('blog.json', seo_build_blog_index_schema());

    // 12. FAQ Schema
    $writeSchema('faq.json', seo_build_faq_schema());

    // 13. Contact Schema
    $writeSchema('contact.json', seo_build_contact_schema());

    // 14. Get Quote Schema
    $writeSchema('get-quote.json', seo_build_quote_schema());

    // Product Schemas (product-{slug}.json) - Unified Graph
    foreach ($ctx['products'] as $p) {
        $pSlug = $p['slug'];
        $pFile = 'product-' . $pSlug . '.json';
        $revs = $ctx['reviewsByProduct'][$p['id']] ?? [];
        $avgRating = 0;
        if (!empty($revs)) {
            $sum = array_sum(array_column($revs, 'rating'));
            $avgRating = round($sum / count($revs), 1);
        }
        $pSchema = seo_get_unified_page_graph('product-details', [
            'product' => $p,
            'reviews' => $revs,
            'avgRating' => $avgRating,
            'reviewCount' => count($revs)
        ]);
        $writeSchema($pFile, $pSchema);
    }

    // Blog Post Schemas (blog-{slug}.json) - Unified Graph
    foreach ($ctx['blogPosts'] as $post) {
        $bSlug = $post['slug'];
        $bFile = 'blog-' . $bSlug . '.json';
        $bSchema = seo_get_unified_page_graph('blog-details', [
            'post' => $post
        ]);
        $writeSchema($bFile, $bSchema);
    }

    return $created;
}

/**
 * Synchronize all SEO, AI & Documentation artifacts:
 * 1. sitemap.xml & sitemap_index.xml
 * 2. llms.txt & llms-full.txt
 * 3. schema.json
 * 4. ORDERPRINTEX.md & SITE_OVERVIEW.md
 * 5. /seo/markdown/ (all 14 core markdowns + products + blogs + service aliases)
 * 6. /seo/schema/ (all 14 core schemas + products + blogs + service aliases)
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
        $siteEmail = get_setting('email', 'sales@orderprintex.com');
        $sitePhone = get_setting('phone', '+91 93540 28199');
        $siteAddress = get_setting('address', 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059');
        $siteHours = get_setting('business_hours', 'Mon - Sat: 9:30 AM - 8:00 PM');
        $metaDesc = get_setting('meta_description', "OrderPrintex is India's premier bulk manufacturer of DTF apparel transfers, UV DTF decals, custom streetwear t-shirts, and corporate merchandise with pan-India express dispatch.");

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

        // Blog Categories
        $blogCategories = db_fetch_all("SELECT * FROM blog_categories ORDER BY id ASC") ?: [];

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

        $ctx = compact(
            'siteName', 'siteEmail', 'sitePhone', 'siteAddress', 'siteHours', 'metaDesc',
            'products', 'productSizes', 'pricingTiers', 'reviewsByProduct',
            'categories', 'blogCategories', 'services', 'blogPosts',
            'equipment', 'staticPages', 'faqs', 'portfolioItems'
        );

        // -------------------------------------------------------------
        // 1. GENERATE SITEMAP.XML (AND SITEMAP_INDEX.XML)
        // -------------------------------------------------------------
        $urls = [];

        // Core Static Landing Pages (clean URLs without trailing slash)
        $corePages = [
            ['loc' => seo_prod_url(''),             'lastmod' => $today, 'changefreq' => 'daily',   'priority' => '1.0'],
            ['loc' => seo_prod_url('products'),     'lastmod' => $today, 'changefreq' => 'daily',   'priority' => '0.95'],
            ['loc' => seo_prod_url('services'),     'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.95'],
            ['loc' => seo_prod_url('pricing'),      'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.90'],
            ['loc' => seo_prod_url('get-quote'),    'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.90'],
            ['loc' => seo_prod_url('gallery'),      'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.85'],
            ['loc' => seo_prod_url('blog'),         'lastmod' => $today, 'changefreq' => 'daily',   'priority' => '0.90'],
            ['loc' => seo_prod_url('about'),        'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['loc' => seo_prod_url('faq'),          'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.80'],
            ['loc' => seo_prod_url('contact'),      'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.80'],
        ];
        foreach ($corePages as $cp) {
            $urls[] = $cp;
        }

        // Individual Service Pages
        foreach ($services as $srv) {
            $modTime = !empty($srv['updated_at']) ? substr($srv['updated_at'], 0, 10) : (!empty($srv['created_at']) ? substr($srv['created_at'], 0, 10) : $today);
            $urls[] = [
                'loc'        => seo_prod_service_url($srv['slug']),
                'lastmod'    => $modTime,
                'changefreq' => 'weekly',
                'priority'   => '0.90'
            ];
        }

        // Product Category Pages
        foreach ($categories as $cat) {
            $urls[] = [
                'loc'        => seo_prod_category_url($cat['slug']),
                'lastmod'    => $today,
                'changefreq' => 'weekly',
                'priority'   => '0.85'
            ];
        }

        // Blog Category Pages
        foreach ($blogCategories as $bcat) {
            $urls[] = [
                'loc'        => seo_prod_blog_category_url($bcat['slug']),
                'lastmod'    => $today,
                'changefreq' => 'weekly',
                'priority'   => '0.85'
            ];
        }

        // Active Product Pages
        foreach ($products as $p) {
            $modTime = !empty($p['updated_at']) ? substr($p['updated_at'], 0, 10) : (!empty($p['created_at']) ? substr($p['created_at'], 0, 10) : $today);
            $urls[] = [
                'loc'        => seo_prod_product_url($p['slug']),
                'lastmod'    => $modTime,
                'changefreq' => 'weekly',
                'priority'   => '0.85'
            ];
        }

        // Published Blog Articles
        foreach ($blogPosts as $post) {
            $modTime = !empty($post['updated_at']) ? substr($post['updated_at'], 0, 10) : (!empty($post['created_at']) ? substr($post['created_at'], 0, 10) : $today);
            $urls[] = [
                'loc'        => seo_prod_blog_url($post['slug']),
                'lastmod'    => $modTime,
                'changefreq' => 'monthly',
                'priority'   => '0.80'
            ];
        }

        // Static Policy / Legal Pages
        foreach ($staticPages as $sp) {
            if (in_array($sp['slug'], ['privacy-policy', 'terms-and-conditions', 'shipping-policy'])) {
                $urls[] = [
                    'loc'        => seo_prod_url($sp['slug']),
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
        $indexXml .= "    <loc>https://orderprintex.com/sitemap.xml</loc>\n";
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
        // 2. GENERATE LLMS.TXT & LLMS-FULL.TXT
        // -------------------------------------------------------------
        $llms = "# {$siteName} — Commercial Printing & Custom Transfer Manufacturing\n\n";
        $llms .= "> {$siteName} is a premier industrial printing facility and digital transfer manufacturer headquartered at Pankha Road, New Delhi, India. We specialize in Direct-to-Film (DTF) apparel transfers, UV DTF 3D tactile hard-surface decals, custom streetwear and corporate t-shirt printing, bespoke packaging, and corporate merchandise.\n\n";

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
            $llms .= "### [{$srv['title']}](" . seo_prod_service_url($srv['slug']) . ")\n";
            $llms .= "- **URL**: " . seo_prod_service_url($srv['slug']) . "\n";
            $desc = !empty($srv['description']) ? strip_tags($srv['description']) : (!empty($srv['short_desc']) ? strip_tags($srv['short_desc']) : 'High-definition industrial printing service.');
            $llms .= "- **Overview**: {$desc}\n";
            $llms .= "- **Standard Turnaround**: 24-48 Hours (Express same-day dispatch available on request)\n\n";
        }

        $llms .= "## Product Categories\n\n";
        foreach ($categories as $cat) {
            $catDesc = !empty($cat['description']) ? strip_tags($cat['description']) : 'Commercial print product category.';
            $llms .= "- **[{$cat['name']}](" . seo_prod_category_url($cat['slug']) . ")**: {$catDesc}\n";
        }
        $llms .= "\n";

        $llms .= "## Blog Categories\n\n";
        foreach ($blogCategories as $bcat) {
            $bcatDesc = !empty($bcat['description']) ? strip_tags($bcat['description']) : 'Printing education category.';
            $llms .= "- **[{$bcat['name']}](" . seo_prod_blog_category_url($bcat['slug']) . ")**: {$bcatDesc}\n";
        }
        $llms .= "\n";

        $llms .= "## Exhaustive Product Catalog\n\n";
        foreach ($products as $p) {
            $price = $p['sale_price'] ?? $p['regular_price'] ?? $p['starting_price'] ?? 0;
            $priceStr = $price > 0 ? "₹" . number_format($price, 2) : "Custom Quoted";
            $sku = $p['sku'] ?: ('OP-PRD-' . $p['id']);
            $lead = $p['lead_time'] ?: '24-48 Hours';
            $moq = $p['min_order_qty'] ?: ($p['min_order'] ?: '1 Unit');

            $llms .= "### [{$p['title']}](" . seo_prod_product_url($p['slug']) . ")\n";
            $llms .= "- **Product URL**: " . seo_prod_product_url($p['slug']) . "\n";
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
            $llms .= "  - Image Asset: " . seo_prod_url($item['image_url']) . "\n";
        }
        $llms .= "\n";

        $llms .= "## Knowledge Base & Printing Guides\n\n";
        foreach ($blogPosts as $post) {
            $excerpt = !empty($post['excerpt']) ? strip_tags($post['excerpt']) : 'Comprehensive printing industry guide.';
            $llms .= "### [{$post['title']}](" . seo_prod_blog_url($post['slug']) . ")\n";
            $llms .= "- **URL**: " . seo_prod_blog_url($post['slug']) . "\n";
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
        $llms .= "- **Direct Quote Request**: " . seo_prod_url('get-quote') . "\n\n";

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

        // Duplicate to llms-full.txt
        $llmsFullPath = $rootDir . '/llms-full.txt';
        @copy($llmsPath, $llmsFullPath);

        // -------------------------------------------------------------
        // 3. GENERATE SCHEMA.JSON (ENTERPRISE JSON-LD STRUCTURED GRAPH)
        // -------------------------------------------------------------
        $graph = [];

        // Organization / LocalBusiness / ProfessionalService
        $orgNode = [
            '@type'             => 'ProfessionalService',
            '@id'               => seo_prod_url('#organization'),
            'name'              => $siteName,
            'legalName'         => 'OrderPrintex Commercial Printing Studio',
            'url'               => seo_prod_url(''),
            'logo'              => seo_prod_url('assets/images/logo.png'),
            'image'             => seo_prod_url('assets/images/logo.png'),
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
                'postalCode'      => '110059',
                'addressCountry'  => 'IN'
            ],
            'geo'               => [
                '@type'           => 'GeoCoordinates',
                'latitude'        => 28.6180,
                'longitude'       => 77.0805
            ],
            'openingHoursSpecification' => [
                [
                    '@type'       => 'OpeningHoursSpecification',
                    'dayOfWeek'   => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    'opens'       => '09:30',
                    'closes'      => '20:00'
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
                'https://www.youtube.com/@orderprintex',
                'https://www.linkedin.com/company/orderprintex'
            ]
        ];
        $graph[] = $orgNode;

        // WebSite Node
        $siteNode = [
            '@type'             => 'WebSite',
            '@id'               => seo_prod_url('#website'),
            'name'              => $siteName,
            'url'               => seo_prod_url(''),
            'description'       => $metaDesc,
            'publisher'         => ['@id' => seo_prod_url('#organization')],
            'potentialAction'   => [
                '@type'       => 'SearchAction',
                'target'      => seo_prod_url('products') . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ]
        ];
        $graph[] = $siteNode;

        // WebPage Nodes for Main Pages
        foreach ($corePages as $cp) {
            $pageTitle = $siteName;
            if (str_contains($cp['loc'], '/products'))       $pageTitle = "Commercial Print Product Catalog | {$siteName}";
            elseif (str_contains($cp['loc'], '/services'))   $pageTitle = "Printing & Manufacturing Services | {$siteName}";
            elseif (str_contains($cp['loc'], '/pricing'))    $pageTitle = "Trade Pricing & Volume Discount Tiers | {$siteName}";
            elseif (str_contains($cp['loc'], '/get-quote'))  $pageTitle = "Request a Custom Print Quotation | {$siteName}";
            elseif (str_contains($cp['loc'], '/gallery'))    $pageTitle = "Production Portfolio & Showcase | {$siteName}";
            elseif (str_contains($cp['loc'], '/blog'))       $pageTitle = "Printing Knowledge Base & Prepress Guides | {$siteName}";
            elseif (str_contains($cp['loc'], '/about'))      $pageTitle = "About Our Facility & Printing Studio | {$siteName}";
            elseif (str_contains($cp['loc'], '/faq'))        $pageTitle = "Frequently Asked Questions | {$siteName}";
            elseif (str_contains($cp['loc'], '/contact'))    $pageTitle = "Contact & Factory Location | {$siteName}";

            $graph[] = [
                '@type'           => 'WebPage',
                '@id'             => $cp['loc'] . '#webpage',
                'url'             => $cp['loc'],
                'name'            => $pageTitle,
                'isPartOf'        => ['@id' => seo_prod_url('#website')],
                'breadcrumb'      => [
                    '@type'       => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => seo_prod_url('')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $pageTitle, 'item' => $cp['loc']]
                    ]
                ]
            ];
        }

        // Service Nodes
        foreach ($services as $srv) {
            $srvUrl = seo_prod_service_url($srv['slug']);
            $srvDesc = !empty($srv['description']) ? strip_tags($srv['description']) : (!empty($srv['short_desc']) ? strip_tags($srv['short_desc']) : 'Industrial printing service.');
            $graph[] = [
                '@type'         => 'Service',
                '@id'           => $srvUrl . '#service',
                'name'          => $srv['title'],
                'url'           => $srvUrl,
                'serviceType'   => 'Commercial Printing',
                'provider'      => ['@id' => seo_prod_url('#organization')],
                'description'   => $srvDesc,
                'areaServed'    => ['@type' => 'Country', 'name' => 'India']
            ];
        }

        // Product Nodes
        foreach ($products as $p) {
            $pUrl = seo_prod_product_url($p['slug']);
            $price = $p['sale_price'] ?? $p['regular_price'] ?? $p['starting_price'] ?? 0;
            $sku = $p['sku'] ?: ('OP-PRD-' . $p['id']);
            $pDesc = !empty($p['short_desc']) ? strip_tags($p['short_desc']) : (!empty($p['description']) ? substr(strip_tags($p['description']), 0, 200) : 'Industrial transfer product.');
            $img = !empty($p['image_url']) ? seo_prod_url($p['image_url']) : seo_prod_url('assets/images/logo.png');

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
            $bUrl = seo_prod_blog_url($post['slug']);
            $bImg = !empty($post['featured_image']) ? seo_prod_url($post['featured_image']) : seo_prod_url('assets/images/logo.png');
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
                'publisher'         => ['@id' => seo_prod_url('#organization')],
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
                'image'             => seo_prod_url($item['image_url']),
                'description'       => $item['description'],
                'creator'           => ['@id' => seo_prod_url('#organization')]
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
            '@id'               => seo_prod_url('faq') . '#faqpage',
            'name'              => 'OrderPrintex Frequently Asked Questions',
            'url'               => seo_prod_url('faq'),
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
        $md .= "*Generated Automatically on " . date('d F Y, H:i:s T') . " | Production Version: 2.5.0*\n\n";

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
        $md .= "1. **Discovery & Specification**: The customer visits [orderprintex.com](https://orderprintex.com/), browses the product catalogue or service descriptions, and determines required transfer formats (e.g. 22\" DTF gang sheets, UV DTF decals, or finished garments).\n";
        $md .= "2. **Artwork Upload & Enquiry Submission**: The customer uploads high-resolution artwork (transparent PNG, vector PDF, or AI) through the online interactive workbench or the [Get a Quote](https://orderprintex.com/get-quote) form.\n";
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
            $md .= "### [{$srv['title']}](" . seo_prod_service_url($srv['slug']) . ")\n\n";
            $md .= "- **Canonical URL**: <" . seo_prod_service_url($srv['slug']) . ">\n";
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

                $md .= "#### [{$p['title']}](" . seo_prod_product_url($p['slug']) . ")\n\n";
                $md .= "- **Direct URL**: <" . seo_prod_product_url($p['slug']) . ">\n";
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
            $md .= "- **Image Asset**: <" . seo_prod_url($item['image_url']) . ">\n";
            $md .= "- **Technical Summary**: {$item['description']}\n\n";
        }

        // Section 8
        $md .= "## 8. Industry Knowledge Base & Educational Guides\n\n";
        foreach ($blogPosts as $post) {
            $md .= "### [{$post['title']}](" . seo_prod_blog_url($post['slug']) . ")\n";
            $md .= "- **Canonical Route**: <" . seo_prod_blog_url($post['slug']) . ">\n";
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
            elseif (str_contains($u['loc'], '/blog')) $catLabel = 'Knowledge Base';
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
        // 5. GENERATE /seo/markdown/ AND /seo/schema/
        // -------------------------------------------------------------
        $markdownFiles = seo_generate_page_markdowns($rootDir, $ctx);
        $schemaFiles = seo_generate_page_schemas($rootDir, $ctx);

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

        log_activity(null, 'seo_sync_all', "Synchronized sitemap.xml ({$urlCount} URLs), llms.txt, schema.json, ORDERPRINTEX.md, " . count($markdownFiles) . " markdowns, and " . count($schemaFiles) . " schemas.");

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
            'markdown_files'   => count($markdownFiles),
            'schema_files'     => count($schemaFiles),
            'counts'           => [
                'urls'       => $urlCount,
                'products'   => count($products),
                'services'   => count($services),
                'categories' => count($categories),
                'articles'   => count($blogPosts),
                'portfolio'  => count($portfolioItems),
                'faqs'       => count($faqs)
            ],
            'message'          => "Successfully synchronized all SEO & AI discovery assets: sitemap.xml ({$urlCount} URLs), llms.txt, schema.json ({$urlCount} entities), ORDERPRINTEX.md, " . count($markdownFiles) . " Markdown pages, and " . count($schemaFiles) . " Schema JSONs."
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
 * Return current status and file metadata for all SEO & AI discovery files.
 */
function seo_get_status(): array {
    $rootDir = realpath(__DIR__ . '/..') ?: 'C:/Movies/Internship/OrderPrintex';

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
    $siteEmail = get_setting('contact_email', 'sales@orderprintex.com');
    $sitePhone = get_setting('contact_phone', '+91 93540 28199');
    $siteAddress = get_setting('contact_address', 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059');

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        '@id' => seo_prod_url('#organization'),
        'name' => $siteName,
        'url' => seo_prod_url(''),
        'logo' => seo_prod_url('assets/images/logo.png'),
        'description' => 'Commercial DTF printing, UV DTF gang sheets, and custom merchandise manufacturer in New Delhi.',
        'email' => $siteEmail,
        'telephone' => $sitePhone,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $siteAddress,
            'addressLocality' => 'New Delhi',
            'addressRegion' => 'Delhi',
            'postalCode' => '110059',
            'addressCountry' => 'IN'
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => 28.6180,
            'longitude' => 77.0805
        ],
        'sameAs' => [
            'https://www.facebook.com/orderprintex',
            'https://www.instagram.com/orderprintex',
            'https://www.youtube.com/@orderprintex',
            'https://www.linkedin.com/company/orderprintex'
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
        'image' => !empty($product['image_url']) ? seo_prod_url($product['image_url']) : seo_prod_url('assets/images/logo.png'),
        'description' => $desc,
        'sku' => $sku,
        'brand' => [
            '@type' => 'Brand',
            'name' => 'OrderPrintex'
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => seo_prod_product_url($product['slug'] ?? ''),
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
        'image' => !empty($post['featured_image']) ? seo_prod_url($post['featured_image']) : seo_prod_url('assets/images/logo.png'),
        'author' => [
            '@type' => 'Person',
            'name' => $post['author'] ?? 'OrderPrintex Editorial Team'
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'OrderPrintex',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => seo_prod_url('assets/images/logo.png')
            ]
        ],
        'datePublished' => !empty($post['created_at']) ? date('c', strtotime($post['created_at'])) : date('c'),
        'dateModified' => !empty($post['updated_at']) ? date('c', strtotime($post['updated_at'])) : date('c'),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => seo_prod_blog_url($post['slug'] ?? '')
        ]
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}
