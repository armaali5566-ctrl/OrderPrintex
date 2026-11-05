<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

// Check if enum needs 'packaging'
try {
    db_query("ALTER TABLE gallery MODIFY category ENUM('dtf','uv_dtf','tshirt','corporate','packaging') NOT NULL DEFAULT 'dtf'");
    echo "Altered gallery category enum.\n";
} catch (Exception $e) {
    echo "Note: " . $e->getMessage() . "\n";
}

$count = db_fetch_one("SELECT COUNT(*) as c FROM gallery");
if (($count['c'] ?? 0) === 0) {
    $curated = [
        [
            'title' => 'High-Detail DTF Apparel Print on Dark Garment',
            'category' => 'dtf',
            'image_url' => 'assets/images/dtf_fabric_print.jpg',
            'description' => 'Direct-to-Film transfer showcasing ultra-fine color gradients, opaque white underbase, and soft stretch elasticity without hand feel stiffness.',
            'display_order' => 1
        ],
        [
            'title' => 'UV DTF Decals on Glass & Tumbler Curved Surfaces',
            'category' => 'uv_dtf',
            'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
            'description' => 'Industrial-adhesion 3D tactile UV DTF stickers applied seamlessly onto curved stainless steel bottles and glassware with spot gloss varnish.',
            'display_order' => 2
        ],
        [
            'title' => 'Heavyweight 240 GSM Streetwear Graphic Tee',
            'category' => 'tshirt',
            'image_url' => 'assets/images/streetwear_tee.jpg',
            'description' => 'Vintage washed boxy drop-shoulder t-shirt with photographic DTF chest graphic, double-needle stitched seams, and tight retail ribbing.',
            'display_order' => 3
        ],
        [
            'title' => 'Luxury Apparel Unboxing Mailer Packaging Box',
            'category' => 'packaging',
            'image_url' => 'assets/images/packaging_box.jpg',
            'description' => 'Custom matte black corrugated mailer box with hot-stamped gold foil logo, spot UV accents, and inner branded tissue wrap.',
            'display_order' => 4
        ],
        [
            'title' => 'Custom Die-Cut & Holographic Decal Collection',
            'category' => 'uv_dtf',
            'image_url' => 'assets/images/custom_stickers.jpg',
            'description' => 'Multi-surface peel-and-stick vinyl decals with embossed gloss spot varnish, iridescent light reflection, and permanent acrylic bond.',
            'display_order' => 5
        ],
        [
            'title' => 'Executive Corporate Swag & Client Gifting Kit',
            'category' => 'corporate',
            'image_url' => 'assets/images/corporate_swag_kit.jpg',
            'description' => 'Turnkey corporate onboarding kit runs featuring customized insulated drinkware, embossed diary, and stylus pen in a bespoke gift hamper.',
            'display_order' => 6
        ],
        [
            'title' => 'Retail Brand Collection & Crimson Studio Apparel',
            'category' => 'tshirt',
            'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
            'description' => 'Studio fashion apparel featuring rich crimson red garment dyes, true-match ICC color profiling, and breathable photographic chest prints.',
            'display_order' => 7
        ],
        [
            'title' => 'Luxury Embossed Branding Collateral & Stationery',
            'category' => 'packaging',
            'image_url' => 'assets/images/branding_stationery.jpg',
            'description' => 'Thick crimson red and warm beige business cards with edge gilding, debossed linen corporate journal, and executive presentation folders.',
            'display_order' => 8
        ]
    ];

    foreach ($curated as $item) {
        db_insert('gallery', [
            'title' => $item['title'],
            'category' => $item['category'],
            'image_url' => $item['image_url'],
            'description' => $item['description'],
            'is_featured' => 1,
            'is_published' => 1,
            'display_order' => $item['display_order']
        ]);
    }
    echo "Seeded " . count($curated) . " gallery items.\n";
} else {
    echo "Gallery already has {$count['c']} items.\n";
}

