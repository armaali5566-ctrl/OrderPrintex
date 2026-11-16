<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

echo "Populating default metadata and FAQs for existing 5 posts...\n";

$initialData = [
    1 => [
        'alt' => 'DTF printing technology machine printing gang sheet on PET transfer film',
        'tags' => 'DTF Printing, Custom Apparel, Heat Press, Textile Transfers, Gang Sheet',
        'keywords' => 'dtf printing, direct to film transfers, garment printing delhi, custom apparel printing',
        'faqs' => [
            [
                'q' => 'What is DTF printing and how does it work?',
                'a' => 'Direct-to-Film (DTF) printing prints full-color pigment ink onto a PET film, backed by hot-melt adhesive powder and heat-cured. It is then transferred to garments using a heat press.'
            ],
            [
                'q' => 'How durable are DTF transfers on fabrics?',
                'a' => 'OrderPrintex DTF transfers withstand 50+ commercial wash cycles without cracking, peeling, or fading when applied according to heat-press specifications.'
            ],
            [
                'q' => 'Can DTF transfers be applied to all fabric types?',
                'a' => 'Yes, DTF prints bond flawlessly to 100% cotton, polyester, poly-blends, nylon, canvas, denim, and fleece in both light and dark colors.'
            ]
        ]
    ],
    2 => [
        'alt' => 'UV DTF decal printing on drinkware and promotional merchandise',
        'tags' => 'UV DTF, Decals, Product Branding, Stickers, Hard Surface Printing',
        'keywords' => 'uv dtf printing, 3d decals, promotional branding, custom stickers delhi',
        'faqs' => [
            [
                'q' => 'What surfaces are compatible with UV DTF decals?',
                'a' => 'UV DTF decals adhere permanently to non-porous hard surfaces including glass, stainless steel, ceramic, acrylic, wood, leather, and rigid plastic without requiring heat.'
            ],
            [
                'q' => 'Are UV DTF decals waterproof and scratch resistant?',
                'a' => 'Yes, cured with dual LED-UV lamps and sealed with spot gloss varnish, UV DTF decals are 100% waterproof, dish-washable, and resistant to everyday abrasion.'
            ]
        ]
    ],
    3 => [
        'alt' => 'Overview of commercial t-shirt printing techniques and equipment',
        'tags' => 'T-Shirt Printing, Screen Printing, DTF, Apparel Manufacturing, Business Guide',
        'keywords' => 't-shirt printing techniques, bulk t-shirt printing, custom t-shirts delhi',
        'faqs' => [
            [
                'q' => 'Which T-shirt printing technique is best for small bulk orders?',
                'a' => 'DTF printing is ideal for small to medium batch runs (1 to 500 pcs) because it eliminates setup screen charges and prints unlimited colors.'
            ],
            [
                'q' => 'What file resolution is needed for T-shirt printing?',
                'a' => 'We recommend 300 DPI vector PDF, AI, or transparent PNG files scaled to 100% of final print dimensions.'
            ]
        ]
    ],
    4 => [
        'alt' => 'DTF printing versus traditional screen printing comparison',
        'tags' => 'DTF vs Screen Printing, Printing Comparison, Cost Analysis, Apparel Production',
        'keywords' => 'dtf vs screen printing, screen printing comparison, textile printing costs',
        'faqs' => [
            [
                'q' => 'When is screen printing cheaper than DTF?',
                'a' => 'Screen printing becomes more economical at very large volume runs (500+ identical garments) with 1 or 2 solid ink colors due to rapid per-impression printing speeds.'
            ],
            [
                'q' => 'Does DTF have a heavy rubbery hand feel?',
                'a' => 'No, modern calibrated DTF printing with ultra-fine TPU powder delivers a soft, breathable hand feel that flexes naturally with the fabric.'
            ]
        ]
    ],
    5 => [
        'alt' => 'Corporate team wearing branded custom printed t-shirts',
        'tags' => 'Brand Identity, Corporate T-Shirts, Merchandising, Employee Swag, Team Wear',
        'keywords' => 'corporate t-shirt printing, custom brand apparel, employee swag merchandise',
        'faqs' => [
            [
                'q' => 'How do corporate printed T-shirts improve brand visibility?',
                'a' => 'Branded T-shirts turn employees and event attendees into walking brand advocates, projecting cohesive professionalism and creating memorable brand recall.'
            ],
            [
                'q' => 'What turnaround time does OrderPrintex offer for bulk corporate T-shirts?',
                'a' => 'Standard turnaround for bulk corporate orders is 2 to 4 business days with PAN-India express courier dispatch from our New Delhi production facility.'
            ]
        ]
    ]
];

foreach ($initialData as $postId => $info) {
    db_update('blog_posts', [
        'featured_image_alt' => $info['alt'],
        'tags' => $info['tags'],
        'seo_keywords' => $info['keywords'],
        'published_at' => date('Y-m-d H:i:s')
    ], 'id = :id', ['id' => $postId]);

    // Check if FAQs already in blog_post_faqs
    $existingFaqs = db_fetch_all("SELECT id FROM blog_post_faqs WHERE post_id = :pid", ['pid' => $postId]);
    if (empty($existingFaqs)) {
        foreach ($info['faqs'] as $order => $f) {
            db_insert('blog_post_faqs', [
                'post_id' => $postId,
                'question' => $f['q'],
                'answer' => $f['a'],
                'display_order' => $order + 1
            ]);
        }
        echo "Inserted " . count($info['faqs']) . " FAQs for post {$postId}.\n";
    }
}

echo "Default blog metadata and FAQs populated successfully!\n";
