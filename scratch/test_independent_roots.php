<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo_helper.php';

function build_independent_nodes(string $key, array $data = []): array {
    $cleanKey = strtolower(trim($key));
    $nodes = [];

    switch ($cleanKey) {
        case 'home':
        case 'index':
        case '':
            $homeUrl = url('');
            $services = db_fetch_all("SELECT slug, title, short_description, image_url FROM services WHERE is_active = 1 ORDER BY display_order ASC");
            $listItems = [];
            foreach ($services as $idx => $s) {
                $sUrl = service_url($s['slug']);
                $listItems[] = [
                    '@type' => 'ListItem',
                    'position' => $idx + 1,
                    'name' => $s['title'],
                    'item' => $sUrl
                ];
            }
            $nodes[] = [
                '@type' => 'WebPage',
                '@id' => $homeUrl . '#webpage',
                'url' => $homeUrl,
                'name' => 'OrderPrintex — Commercial DTF & UV DTF Printing Facility in Delhi',
                'description' => 'Direct-to-Film (DTF) transfers, UV DTF 3D tactile decals, custom apparel, and corporate merchandise manufactured at Pankha Road, New Delhi with nationwide delivery.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
                'mainEntity' => ['@id' => url('#services-list')]
            ];
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
                '@id' => $aboutUrl . '#webpage',
                'url' => $aboutUrl,
                'name' => 'About Our Printing Studio & Manufacturing Facility | OrderPrintex',
                'description' => 'Learn about OrderPrintex: Delhi\'s leading commercial DTF and UV DTF printing studio with 10+ years of prepress expertise.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
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
            break;

        case 'services':
            $servicesUrl = url('services');
            $services = db_fetch_all("SELECT slug, title, short_description, image_url FROM services WHERE is_active = 1 ORDER BY display_order ASC");
            $listItems = [];
            foreach ($services as $idx => $s) {
                $sUrl = service_url($s['slug']);
                $listItems[] = [
                    '@type' => 'ListItem',
                    'position' => $idx + 1,
                    'name' => $s['title'],
                    'item' => $sUrl
                ];
            }
            $nodes[] = [
                '@type' => 'CollectionPage',
                '@id' => $servicesUrl . '#webpage',
                'url' => $servicesUrl,
                'name' => 'Industrial Printing & Manufacturing Services | OrderPrintex',
                'description' => 'Explore our end-to-end commercial printing services: DTF fabric transfers, UV DTF decals, custom t-shirts, and corporate swag kits.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
                'breadcrumb' => ['@id' => $servicesUrl . '#breadcrumb'],
                'mainEntity' => ['@id' => $servicesUrl . '#itemlist']
            ];
            $nodes[] = [
                '@type' => 'ItemList',
                '@id' => $servicesUrl . '#itemlist',
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
                $listItems[] = [
                    '@type' => 'ListItem',
                    'position' => $idx + 1,
                    'name' => $p['title'],
                    'item' => $pUrl
                ];
            }
            $nodes[] = [
                '@type' => 'CollectionPage',
                '@id' => $productsUrl . '#webpage',
                'url' => $productsUrl,
                'name' => 'Commercial Print Product Catalog | OrderPrintex',
                'description' => 'Browse all OrderPrintex manufactured products: DTF gang rolls, UV DTF decals, apparel, and corporate swag kits.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
                'breadcrumb' => ['@id' => $productsUrl . '#breadcrumb'],
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
                
                // 1. Independent WebPage
                $nodes[] = [
                    '@type' => 'WebPage',
                    '@id' => $pUrl . '#webpage',
                    'url' => $pUrl,
                    'name' => $product['title'] . ' | OrderPrintex',
                    'description' => strip_tags($product['short_desc'] ?? ''),
                    'isPartOf' => ['@id' => url('#website')],
                    'about' => ['@id' => url('#organization')],
                    'breadcrumb' => ['@id' => $pUrl . '#breadcrumb'],
                    'mainEntity' => ['@id' => $pUrl . '#product']
                ];

                // 2. Independent Product
                $pNode = [
                    '@type' => 'Product',
                    '@id' => $pUrl . '#product',
                    'name' => $product['title'],
                    'url' => $pUrl,
                    'mainEntityOfPage' => ['@id' => $pUrl . '#webpage'],
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

                // 3. Independent BreadcrumbList
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
            // 1. Independent CollectionPage
            $nodes[] = [
                '@type' => 'CollectionPage',
                '@id' => $galleryUrl . '#webpage',
                'url' => $galleryUrl,
                'name' => 'Production Portfolio & Specimen Gallery | OrderPrintex',
                'description' => 'High-resolution photographs of commercial production runs manufactured at our New Delhi printing facility.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
                'breadcrumb' => ['@id' => $galleryUrl . '#breadcrumb'],
                'mainEntity' => ['@id' => $galleryUrl . '#imagegallery']
            ];
            // 2. Independent ImageGallery
            $nodes[] = [
                '@type' => 'ImageGallery',
                '@id' => $galleryUrl . '#imagegallery',
                'url' => $galleryUrl,
                'mainEntityOfPage' => ['@id' => $galleryUrl . '#webpage'],
                'name' => 'OrderPrintex Commercial Print Showcase',
                'description' => 'Specimens of DTF fabric transfers, UV DTF decals, and promotional items manufactured in our Delhi production studio.',
                'image' => $imageObjects
            ];
            // 3. Independent BreadcrumbList
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
            $blogPostRefs = [];
            foreach ($posts as $p) {
                $postUrl = blog_url($p['slug']);
                $blogPostRefs[] = ['@id' => $postUrl . '#article'];
            }
            $nodes[] = [
                '@type' => 'Blog',
                '@id' => $blogUrl . '#blog',
                'url' => $blogUrl,
                'mainEntityOfPage' => ['@id' => $blogUrl . '#webpage'],
                'name' => 'Printing Industry Knowledge Base & Prepress Guides | OrderPrintex',
                'description' => 'Educational guides, heat press charts, DTF vs UV DTF comparisons, and commercial printing insights.',
                'isPartOf' => ['@id' => url('#website')],
                'publisher' => ['@id' => url('#organization')],
                'blogPost' => $blogPostRefs
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
                
                // 1. Independent WebPage
                $nodes[] = [
                    '@type' => 'WebPage',
                    '@id' => $canonical . '#webpage',
                    'url' => $canonical,
                    'name' => $post['title'] . ' | OrderPrintex Blog',
                    'description' => strip_tags($post['excerpt'] ?? ''),
                    'isPartOf' => ['@id' => url('#website')],
                    'about' => ['@id' => url('#organization')],
                    'breadcrumb' => ['@id' => $canonical . '#breadcrumb'],
                    'mainEntity' => ['@id' => $canonical . '#article']
                ];

                // 2. Independent BlogPosting
                $nodes[] = [
                    '@type' => 'BlogPosting',
                    '@id' => $canonical . '#article',
                    'headline' => $post['title'],
                    'url' => $canonical,
                    'mainEntityOfPage' => ['@id' => $canonical . '#webpage'],
                    'description' => strip_tags($post['excerpt'] ?? ''),
                    'image' => [!empty($post['featured_image']) ? url($post['featured_image']) : url('assets/images/logo.png')],
                    'datePublished' => date('c', strtotime($post['created_at'])),
                    'dateModified' => date('c', strtotime($post['updated_at'] ?? $post['created_at'])),
                    'author' => [
                        '@type' => 'Person',
                        'name' => $post['author'] ?: 'OrderPrintex Editorial Team'
                    ],
                    'publisher' => ['@id' => url('#organization')]
                ];

                // 3. Independent BreadcrumbList
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
                '@id' => $contactUrl . '#webpage',
                'url' => $contactUrl,
                'name' => 'Contact OrderPrintex Delhi Production Studio',
                'description' => 'Get in touch with our New Delhi printing facility. Phone, WhatsApp, email, business hours, and directions to Pankha Road.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
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
                '@id' => $faqUrl . '#webpage',
                'url' => $faqUrl,
                'name' => 'Frequently Asked Questions | OrderPrintex',
                'description' => 'Answers to questions about artwork uploads, DTF transfer settings, wash care, turnaround, and volume pricing.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
                'breadcrumb' => ['@id' => $faqUrl . '#breadcrumb'],
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
                '@id' => $pricingUrl . '#webpage',
                'url' => $pricingUrl,
                'name' => 'Trade Pricing & Volume Discount Tiers | OrderPrintex',
                'description' => 'Transparent commercial pricing for DTF gang sheets, UV decals, and apparel.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
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
                '@id' => $quoteUrl . '#webpage',
                'url' => $quoteUrl,
                'name' => 'Request a Custom Print Quotation | OrderPrintex',
                'description' => 'Upload design files, specify dimensions and quantities, and receive an itemized quotation.',
                'isPartOf' => ['@id' => url('#website')],
                'about' => ['@id' => url('#organization')],
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
            if (str_starts_with($cleanKey, 'services-') || str_ends_with($cleanKey, '-printing') || in_array($cleanKey, ['dtf-printing', 'uv-dtf-printing', 't-shirt-printing', 'corporate-printing', 'custom-hoodie-printing', 'uv-printing', 'mug-cup-printing'])) {
                $cleanSlug = str_replace('services-', '', $cleanKey);
                $srv = db_fetch_one("SELECT * FROM services WHERE slug = ? LIMIT 1", [$cleanSlug]);
                if ($srv) {
                    $srvUrl = service_url($srv['slug']);
                    $desc = !empty($srv['short_description']) ? strip_tags($srv['short_description']) : strip_tags($srv['full_description'] ?? '');

                    $serviceType = 'Corporate Merchandise & Kits';
                    if ($srv['slug'] === 'dtf-printing') {
                        $serviceType = 'Direct-to-Film Transfer Printing';
                    } elseif ($srv['slug'] === 'uv-dtf-printing') {
                        $serviceType = 'UV DTF Decal & Sticker Printing';
                    } elseif ($srv['slug'] === 't-shirt-printing') {
                        $serviceType = 'Custom Apparel & T-Shirt Printing';
                    } elseif ($srv['slug'] === 'custom-hoodie-printing') {
                        $serviceType = 'Custom Hoodie & Heavyweight Fleece Printing';
                    } elseif ($srv['slug'] === 'uv-printing') {
                        $serviceType = 'Direct UV Flatbed Printing';
                    } elseif ($srv['slug'] === 'mug-cup-printing') {
                        $serviceType = 'Custom Mug & Drinkware Printing';
                    }

                    // 1. Independent WebPage
                    $nodes[] = [
                        '@type' => 'WebPage',
                        '@id' => $srvUrl . '#webpage',
                        'url' => $srvUrl,
                        'name' => $srv['title'] . ' | OrderPrintex',
                        'description' => $desc,
                        'isPartOf' => ['@id' => url('#website')],
                        'about' => ['@id' => url('#organization')],
                        'breadcrumb' => ['@id' => $srvUrl . '#breadcrumb'],
                        'mainEntity' => ['@id' => $srvUrl . '#service']
                    ];

                    // 2. Independent Service
                    $sNode = [
                        '@type' => 'Service',
                        '@id' => $srvUrl . '#service',
                        'name' => $srv['title'],
                        'url' => $srvUrl,
                        'mainEntityOfPage' => ['@id' => $srvUrl . '#webpage'],
                        'serviceType' => $serviceType,
                        'description' => $desc,
                        'provider' => ['@id' => url('#localbusiness')],
                        'areaServed' => ['@type' => 'Country', 'name' => 'India']
                    ];
                    if (!empty($srv['image_url'])) {
                        $sNode['image'] = url($srv['image_url']);
                    }
                    $nodes[] = $sNode;

                    // 3. Independent BreadcrumbList
                    $nodes[] = [
                        '@type' => 'BreadcrumbList',
                        '@id' => $srvUrl . '#breadcrumb',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Printing Services', 'item' => url('services')],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $srv['title'], 'item' => $srvUrl]
                        ]
                    ];

                    // Service FAQs
                    $serviceFaqs = [
                        'dtf-printing' => [
                            ['q' => 'How long do DTF prints last on clothes?', 'a' => 'Our DTF prints withstand 50+ commercial wash cycles without cracking, peeling, or fading when properly cured with European hot-melt adhesive.'],
                            ['q' => 'What fabrics can DTF transfers be applied to?', 'a' => 'DTF transfers adhere perfectly to 100% cotton, polyester, poly-cotton blends, denim, canvas, nylon, and performance sportswear.'],
                            ['q' => 'What file formats are accepted for DTF gang sheets?', 'a' => 'We accept 300 DPI transparent PNG files, vector PDF, AI, and EPS with CMYK color profiles.']
                        ],
                        'uv-dtf-printing' => [
                            ['q' => 'Do UV DTF stickers need a heat press to apply?', 'a' => 'No heat press is required. UV DTF decals are pressure-sensitive cold transfer stickers that peel and stick permanently onto any hard surface.'],
                            ['q' => 'What surfaces do UV DTF decals stick to?', 'a' => 'They bond permanently to glass, stainless steel, ceramic, acrylic, wood, hard plastics, and coated metal.'],
                            ['q' => 'Are UV DTF stickers waterproof and dishwasher safe?', 'a' => 'Yes, our UV cured ink and protective varnish layer make the decals scratch-resistant, waterproof, and durable for everyday hand washing.']
                        ],
                        't-shirt-printing' => [
                            ['q' => 'What fabrics and GSM do you offer for custom t-shirts?', 'a' => 'We stock 180 to 240 GSM bio-washed 100% combed cotton, oversized heavyweights, and moisture-wicking poly-spandex blends.'],
                            ['q' => 'What is the minimum order quantity for t-shirt printing?', 'a' => 'Orders start from just 5 pieces for sample drops up to 10,000+ units for corporate events and brand merchandise.']
                        ],
                        'corporate-printing' => [
                            ['q' => 'Can you match exact corporate Pantone brand colors?', 'a' => 'Yes, our prepress spectrophotometers calibrate ink outputs against physical Pantone Solid Coated swatch guides.'],
                            ['q' => 'Do you provide GST commercial invoices?', 'a' => 'Yes, official 18% GST tax invoices with input credit eligibility are provided for every business order.']
                        ],
                        'custom-hoodie-printing' => [
                            ['q' => 'What is the minimum order quantity for custom printed hoodies?', 'a' => 'We accept short-run orders starting from 5 hoodies up to 5,000+ units for university fests, corporate winterwear, and streetwear brand drops.'],
                            ['q' => 'Will the print crack or peel when the fleece stretches?', 'a' => 'No. We use specially formulated high-stretch TPU adhesive powder and flexible pigment inks engineered for elastic fleece that survive 50+ wash cycles without cracking.'],
                            ['q' => 'Can I send my own hoodie blanks for printing?', 'a' => 'Yes, brands courier blanks to our Delhi production studio where we calibrate heat and pressure to ensure flawless bonding.']
                        ],
                        'uv-printing' => [
                            ['q' => 'What is the difference between Direct UV printing and UV DTF?', 'a' => 'Direct UV printing prints ink straight onto the rigid object placed on our flatbed vacuum table with instant UV LED lamp curing and zero sticker borders. UV DTF prints onto a transfer film for application onto curved objects.'],
                            ['q' => 'What is the maximum object size and thickness for flatbed UV printing?', 'a' => 'Our flatbed machines accommodate sheets and objects up to 600mm x 900mm in area and up to 100mm in thickness.'],
                            ['q' => 'Is the direct flatbed print scratch-resistant and waterproof?', 'a' => 'Yes, UV LED curing creates an instantly cross-linked polymer shell that bonds permanently to acrylic, wood, glass, metal, and leather with specialized primers.']
                        ],
                        'mug-cup-printing' => [
                            ['q' => 'Are custom printed ceramic mugs safe for microwaves and dishwashers?', 'a' => 'Yes, our dye-sublimation process infuses ink into genuine Orca polymer coatings inside the ceramic glaze, rated for 1,000+ dishwasher cycles.'],
                            ['q' => 'What is the minimum order quantity for custom mugs?', 'a' => 'We accept short runs from 12 mugs for cafes and startups, and fulfill volume runs of 10,000+ units for corporate swag.'],
                            ['q' => 'What happens if any mugs break during delivery?', 'a' => 'Every mug is packaged in high-density thermocol clam-shells with a zero-breakage guarantee; any damaged units are reprinted and dispatched immediately.']
                        ]
                    ];

                    if (!empty($serviceFaqs[$srv['slug']])) {
                        $faqEntities = [];
                        foreach ($serviceFaqs[$srv['slug']] as $fItem) {
                            $faqEntities[] = [
                                '@type' => 'Question',
                                'name' => $fItem['q'],
                                'acceptedAnswer' => [
                                    '@type' => 'Answer',
                                    'text' => $fItem['a']
                                ]
                            ];
                        }
                        $nodes[] = [
                            '@type' => 'FAQPage',
                            '@id' => $srvUrl . '#faqpage',
                            'url' => $srvUrl,
                            'name' => $srv['title'] . ' FAQs',
                            'mainEntity' => $faqEntities
                        ];
                    }
                }
            }
            break;
    }
    return $nodes;
}

function build_independent_entities(string $key = '', array $data = []): array {
    $base = seo_get_base_graph_nodes();
    $page = build_independent_nodes($key, $data);

    // Desired output order per user specification:
    // 1. Page Entity: WebPage / AboutPage / CollectionPage / ContactPage / FAQPage / Blog
    // 2. Primary Content Entity: Service / Product / BlogPosting / ImageGallery
    // 3. Collection ItemList (for products/services catalog)
    // 4. Organization
    // 5. LocalBusiness
    // 6. WebSite
    // 7. BreadcrumbList
    // 8. Auxiliary: Home ItemList, FAQPage (on services)

    $pageEntities = [];
    $contentEntities = [];
    $collectionLists = [];
    $breadcrumbs = [];
    $auxiliary = [];

    $pageEntityTypes = ['WebPage', 'AboutPage', 'CollectionPage', 'ContactPage', 'FAQPage', 'Blog'];
    $contentEntityTypes = ['Service', 'Product', 'BlogPosting', 'ImageGallery'];

    foreach ($page as $node) {
        if (!is_array($node) || empty($node['@type'])) continue;
        $type = $node['@type'];
        if ($type === 'BreadcrumbList') {
            $breadcrumbs[] = $node;
        } elseif ($type === 'ItemList' && in_array($key, ['services', 'products'])) {
            $collectionLists[] = $node;
        } elseif (in_array($type, $contentEntityTypes)) {
            $contentEntities[] = $node;
        } elseif (in_array($type, $pageEntityTypes) && empty($pageEntities)) {
            $pageEntities[] = $node;
        } else {
            $auxiliary[] = $node;
        }
    }

    $all = array_merge($pageEntities, $contentEntities, $collectionLists, $base, $breadcrumbs, $auxiliary);

    $entities = [];
    foreach ($all as $item) {
        if (!is_array($item) || empty($item['@type'])) continue;
        $entity = ['@context' => 'https://schema.org'];
        foreach ($item as $k => $v) {
            if ($k !== '@context') {
                $entity[$k] = $v;
            }
        }
        $entities[] = $entity;
    }
    return $entities;
}

$keys = [
    'home' => 'Home',
    'about' => 'About',
    'services' => 'Services overview',
    'services-dtf-printing' => 'DTF Printing',
    'services-uv-dtf-printing' => 'UV DTF Printing',
    'services-t-shirt-printing' => 'T-Shirt Printing',
    'services-corporate-printing' => 'Corporate Printing',
    'services-custom-hoodie-printing' => 'Custom Hoodie',
    'services-uv-printing' => 'UV Printing',
    'services-mug-cup-printing' => 'Mug Printing',
    'products' => 'Products catalog',
    'product-details' => 'Product details',
    'gallery' => 'Gallery',
    'blog' => 'Blog listing',
    'blog-details' => 'Blog details',
    'contact' => 'Contact',
    'faq' => 'FAQ',
    'pricing' => 'Pricing',
    'get-quote' => 'Get Quote'
];

foreach ($keys as $k => $title) {
    $entities = build_independent_entities($k);
    $types = array_map(function($e) { return $e['@type']; }, $entities);
    echo sprintf("%-30s | %-20s | [%d blocks] => %s\n", $k, $title, count($entities), implode(', ', $types));
}
