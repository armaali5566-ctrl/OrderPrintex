<?php
$file = __DIR__ . '/../includes/seo_helper.php';
$content = file_get_contents($file);

$startMarker = "/**\n * 1. Homepage Schema: ItemList, Service entities, Organization reference\n */";
$endMarker = "    return '';\n}";

$startPos = strpos($content, $startMarker);
if ($startPos === false) {
    die("Start marker not found!\n");
}

$endPos = strpos($content, $endMarker, $startPos);
if ($endPos === false) {
    die("End marker not found!\n");
}
$endPos += strlen($endMarker);

$newCode = <<<'CODE'
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
CODE;

$updated = substr($content, 0, $startPos) . $newCode . substr($content, $endPos);

// Test syntax with a temporary file before overwriting
$temp = $file . '.test.php';
file_put_contents($temp, $updated);

exec('php -l ' . escapeshellarg($temp), $output, $returnCode);
if ($returnCode !== 0) {
    unlink($temp);
    die("Syntax error in generated code:\n" . implode("\n", $output) . "\n");
}
unlink($temp);

file_put_contents($file, $updated);
echo "Successfully updated includes/seo_helper.php with unified graph architecture!\n";
