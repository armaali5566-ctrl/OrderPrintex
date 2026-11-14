<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/catalog_helper.php';
require_once __DIR__ . '/../includes/seo_helper.php';

function get_base_nodes() {
    $siteUrl = url('');
    $logoUrl = url('assets/images/logo.png');
    $productionImg = url('assets/images/dtf_fabric_print.jpg');

    return [
        [
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
        ],
        [
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
        ],
        [
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
        ]
    ];
}

function get_page_nodes($key, $data = []) {
    $cleanKey = strtolower(trim($key));
    $nodes = [];

    switch ($cleanKey) {
        case 'home':
        case 'index':
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
                    'description' => strip_tags($s['short_description'] ?? ''),
                    'provider' => ['@id' => url('#organization')]
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

function test_unified_graph($key, $data = []) {
    $base = get_base_nodes();
    $page = get_page_nodes($key, $data);
    return [
        '@context' => 'https://schema.org',
        '@graph' => array_merge($base, $page)
    ];
}

$expectedTable = [
    'home' => ['LocalBusiness', 'WebSite', 'ItemList', 'Service', 'Organization'],
    'about' => ['AboutPage', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Service', 'Organization'],
    'services-dtf-printing' => ['Service', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'services-uv-dtf-printing' => ['Service', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'services-t-shirt-printing' => ['Service', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'services-corporate-printing' => ['Service', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'services' => ['CollectionPage', 'ItemList', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Service', 'Organization'],
    'products' => ['CollectionPage', 'ItemList', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Product', 'Organization'],
    'product-details' => ['Product', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'gallery' => ['CollectionPage', 'ImageGallery', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'blog' => ['Blog', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'blog-details' => ['BlogPosting', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'contact' => ['ContactPage', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
    'faq' => ['FAQPage', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization']
];

$allPassed = true;
foreach ($expectedTable as $key => $expected) {
    $graph = test_unified_graph($key);
    $detected = array_unique(array_map(function($node) {
        $t = $node['@type'];
        return is_array($t) ? implode('+', $t) : $t;
    }, $graph['@graph']));

    $missing = array_diff($expected, $detected);
    if (empty($missing)) {
        echo "[PASS] $key: Detected (" . implode(', ', $detected) . ")\n";
    } else {
        echo "[FAIL] $key: Missing (" . implode(', ', $missing) . ")! Detected: " . implode(', ', $detected) . "\n";
        $allPassed = false;
    }
}

if ($allPassed) {
    echo "\n>>> ALL 14 PAGE TYPES PERFECTLY MATCH EXPECTED TOP-LEVEL SCHEMAS! <<<\n";
}
