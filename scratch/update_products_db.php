<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$products = [
    [
        'id' => 1,
        'slug' => 'custom-dtf-gang-sheet',
        'title' => 'DTF Gang Sheet (22" Wide)',
        'short_desc' => 'Ready-to-press transfers with bright color and a soft feel. Works on cotton, polyester and blends.',
        'description' => 'Ready-to-press transfers with bright color and a soft feel. Works on cotton, polyester and blends.',
        'starting_price' => 249.00,
        'price_unit' => '/ sheet',
        'min_order' => '',
        'badge' => 'DTF & UV DTF',
        'is_active' => 1,
        'display_order' => 1
    ],
    [
        'id' => 2,
        'slug' => 'custom-uv-dtf-decal-sheet',
        'title' => '3D Gloss UV DTF Decal Sheet',
        'short_desc' => 'Raised, glossy decals that peel and stick on glass, steel, acrylic and plastic. Waterproof once applied.',
        'description' => 'Raised, glossy decals that peel and stick on glass, steel, acrylic and plastic. Waterproof once applied.',
        'starting_price' => 349.00,
        'price_unit' => '/ sheet',
        'min_order' => '',
        'badge' => 'DTF & UV DTF',
        'is_active' => 1,
        'display_order' => 2
    ],
    [
        'id' => 3,
        'slug' => 'streetwear-oversized-tee',
        'title' => 'Oversized Streetwear T-Shirt (240 GSM)',
        'short_desc' => 'A heavy drop-shoulder tee with your design printed in DTF. Roomy fit and a soft feel.',
        'description' => 'A heavy drop-shoulder tee with your design printed in DTF. Roomy fit and a soft feel.',
        'starting_price' => 449.00,
        'price_unit' => '/ piece',
        'min_order' => '',
        'badge' => 'T-Shirts',
        'is_active' => 1,
        'display_order' => 3
    ],
    [
        'id' => 4,
        'slug' => 'thermal-sipper-bottle',
        'title' => 'Custom Steel Thermal Bottle (750 ml)',
        'short_desc' => 'A double-wall steel bottle with your logo printed on it. Good for gifts and team kits.',
        'description' => 'A double-wall steel bottle with your logo printed on it. Good for gifts and team kits.',
        'starting_price' => 599.00,
        'price_unit' => '/ bottle',
        'min_order' => '',
        'badge' => 'Corporate Gifts',
        'is_active' => 1,
        'display_order' => 4
    ],
    [
        'id' => 5,
        'slug' => 'apparel-mailer-box',
        'title' => 'Branded Apparel Mailer Box',
        'short_desc' => 'A sturdy matte black box with your logo in gold foil and gloss detail. Made for shipping apparel.',
        'description' => 'A sturdy matte black box with your logo in gold foil and gloss detail. Made for shipping apparel.',
        'starting_price' => 65.00,
        'price_unit' => '/ box',
        'min_order' => '',
        'badge' => 'Packaging',
        'is_active' => 1,
        'display_order' => 5
    ],
    [
        'id' => 6,
        'slug' => 'holographic-stickers',
        'title' => 'Custom Die-Cut Sticker Pack',
        'short_desc' => 'Stickers cut to your shape, in holographic and UV DTF finishes. Great for brands and giveaways.',
        'description' => 'Stickers cut to your shape, in holographic and UV DTF finishes. Great for brands and giveaways.',
        'starting_price' => 299.00,
        'price_unit' => '/ pack',
        'min_order' => '',
        'badge' => 'Stickers',
        'is_active' => 1,
        'display_order' => 6
    ],
    [
        'id' => 7,
        'slug' => 'bio-washed-cotton-tee',
        'title' => 'Everyday Cotton T-Shirt (180 GSM)',
        'short_desc' => 'A breathable combed cotton crewneck with a soft printed design. Easy to wear all day.',
        'description' => 'A breathable combed cotton crewneck with a soft printed design. Easy to wear all day.',
        'starting_price' => 299.00,
        'price_unit' => '/ piece',
        'min_order' => '',
        'badge' => 'T-Shirts',
        'is_active' => 1,
        'display_order' => 7
    ],
    [
        'id' => 8,
        'slug' => 'executive-welcome-hamper',
        'title' => 'Corporate Welcome Gift Kit',
        'short_desc' => 'A ready gift set with branded items for new joiners and clients, packed neatly.',
        'description' => 'A ready gift set with branded items for new joiners and clients, packed neatly.',
        'starting_price' => 599.00,
        'price_unit' => '/ kit',
        'min_order' => '',
        'badge' => 'Corporate Gifts',
        'is_active' => 1,
        'display_order' => 8
    ],
    [
        'id' => 9,
        'slug' => 'bookmark-03',
        'title' => 'Custom Printed Bookmarks',
        'short_desc' => 'Double-sided bookmarks on thick card, with a soft-touch finish. Good for brands, bookstores and event giveaways.',
        'description' => 'Double-sided bookmarks on thick card, with a soft-touch finish. Good for brands, bookstores and event giveaways.',
        'specifications' => '350 GSM card • Double-sided print • Custom sizes',
        'starting_price' => 1.50,
        'price_unit' => '/ piece',
        'min_order' => 'Min. 100 pcs',
        'badge' => 'CUSTOM STATIONERY',
        'is_active' => 1,
        'display_order' => 9
    ]
];

foreach ($products as $p) {
    $existing = db_fetch_one("SELECT id FROM products WHERE slug = :s OR id = :id", ['s' => $p['slug'], 'id' => $p['id']]);
    if ($existing) {
        db_update('products', [
            'title' => $p['title'],
            'short_desc' => $p['short_desc'],
            'description' => $p['description'],
            'starting_price' => $p['starting_price'],
            'price_unit' => $p['price_unit'],
            'min_order' => $p['min_order'],
            'badge' => $p['badge'],
            'is_active' => 1,
            'display_order' => $p['display_order']
        ] + (isset($p['specifications']) ? ['specifications' => $p['specifications']] : []), 'id = :id', ['id' => $existing['id']]);
        echo "Updated product ID {$existing['id']} ({$p['slug']})\n";
    } else {
        db_insert('products', [
            'id' => $p['id'],
            'slug' => $p['slug'],
            'title' => $p['title'],
            'short_desc' => $p['short_desc'],
            'description' => $p['description'],
            'starting_price' => $p['starting_price'],
            'price_unit' => $p['price_unit'],
            'min_order' => $p['min_order'],
            'badge' => $p['badge'],
            'image_url' => 'assets/images/dtf_fabric_print.jpg',
            'is_active' => 1,
            'display_order' => $p['display_order']
        ]);
        echo "Inserted product ID {$p['id']} ({$p['slug']})\n";
    }
}
echo "Products table updated successfully!\n";

