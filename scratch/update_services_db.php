<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$data = [
    1 => [
        'title' => '1.DTF Printing in Delhi',
        'badge' => '1.DTF Printing in Delhi',
        'hero_tagline' => 'Prints on Fabric with Bright, Soft, and Long Lasting Results',
        'full_description' => "Our DTF (Direct-to-Film) printing is perfect for cotton, polyester and blended fabrics. It prints a white base under the colours so your designs stay bright even on black. Want to get started ? You can order ready-to-press transfers and gang sheets for your own pressing or we can do it for you. This service is a hit with apparel brands, resellers and print shops that need reliable bulk DTF printing.",
        'specifications' => 'Works on: Cotton, Polyester, Blends | Sheet width: 24 inches - Let us know if you need anything else | Order: Gang sheets and bulk rolls'
    ],
    2 => [
        'title' => '2. UV DTF Printing - A Popular Choice',
        'badge' => '2. UV DTF Printing - A Popular Choice',
        'hero_tagline' => 'UV DTF Printing & Stickers - Add a 3D Glossy Finish to Your Products',
        'full_description' => 'Peel and stick stickers that just need to be applied - no heat press needed. The glossy layer gives them a raised 3D look, making them waterproof and scratch resistant. They work on glass, steel, acrylic, plastic, and wood, so they are super popular for mugs, tumblers, bottles, gift items and packaging. We make UV DTF stickers in custom sizes and bulk quantities.',
        'specifications' => 'Layers: Colour + White + Gloss | Surfaces: Glass, Steel, Acrylic, Plastic, Wood | No heat press needed'
    ],
    3 => [
        'title' => '3. T-Shirt Printing for Bulk Orders',
        'badge' => '3. T-Shirt Printing for Bulk Orders',
        'hero_tagline' => 'T-Shirt Printing in Delhi - Custom T-Shirts for Brands, Teams and Events',
        'full_description' => 'We print custom t-shirts for brands, offices, colleges, events and teams in Delhi. We use DTF printing, so your full-colour designs and fine text come out sharp. Just send us your design along with the quantity and we will suggest the right fabric and print placement. Price of bulk orders are based on the quantity.',
        'specifications' => 'Fabrics: Cotton, Poly-Rich, Dry-Fit | Sizes: XS to 3XL | Orders: Single sample to bulk'
    ],
    4 => [
        'title' => '4. Corporate Printing Services for Businesses',
        'badge' => '4. Corporate Printing Services for Businesses',
        'hero_tagline' => 'Corporate T-Shirt & Merchandise Printing - For Offices, Events and Brands',
        'full_description' => 'Need branded merchandise for your company or event ? Printex is here to help. We print uniform t-shirts, branded mugs and bottles, and giveaways with your logo, in the same colours across the whole batch. GST invoice available on request.',
        'specifications' => 'Products: T-Shirts, Mugs, Bottles, Giveaways | Invoice: GST on request | Delivery: Delhi and pan-India'
    ]
];

foreach ($data as $id => $fields) {
    db_update('services', $fields, 'id = :id', ['id' => $id]);
}
echo "Services table updated successfully!\n";

