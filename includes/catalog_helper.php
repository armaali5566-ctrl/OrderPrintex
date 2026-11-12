<?php
/**
 * OrderPrintex - Commercial Catalog & Product Helper
 * Provides database queries with graceful curated fallback for seamless rendering.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

function get_curated_catalog_categories(): array {
    return [
        ['name' => 'All Products', 'slug' => ''],
        ['name' => 'DTF & UV DTF', 'slug' => 'dtf-uv-dtf'],
        ['name' => 'Stickers', 'slug' => 'stickers'],
        ['name' => 'T-Shirts', 'slug' => 't-shirts'],
        ['name' => 'Corporate Gifts', 'slug' => 'corporate-gifts'],
        ['name' => 'Packaging', 'slug' => 'packaging'],
        ['name' => 'Branded Merch', 'slug' => 'branded-merch']
    ];
}

function get_curated_catalog_products(): array {
    return [
        [
            'id' => 1,
            'title' => 'DTF Gang Sheet (22" Wide)',
            'slug' => 'custom-dtf-gang-sheet',
            'category_name' => 'DTF & UV DTF',
            'category_slug' => 'dtf-uv-dtf',
            'service_id' => 1,
            'service_slug' => 'dtf-printing',
            'service_title' => 'DTF Printing',
            'image_url' => 'assets/images/dtf_fabric_print.jpg',
            'badge' => 'DTF & UV DTF',
            'badge_class' => 'badge-gold',
            'theme' => 'card-theme-white',
            'starting_price' => '₹249',
            'price_unit' => '/ sheet',
            'min_order' => '',
            'short_desc' => 'Ready-to-press transfers with bright color and a soft feel. Works on cotton, polyester and blends.',
            'specifications' => 'Width: 22" (56cm) | Film: Double-matte premium PET | Inks: Calibrated textile pigment | Temp: 160°C / 15s | Cold peel release',
            'description' => 'Ready-to-press transfers with bright color and a soft feel. Works on cotton, polyester and blends.',
            'artwork_notes' => 'Transparent background PNG or vector PDF/AI. 300 DPI at 100% print scale.',
            'is_featured' => 1
        ],
        [
            'id' => 2,
            'title' => '3D Gloss UV DTF Decal Sheet',
            'slug' => 'custom-uv-dtf-decal-sheet',
            'category_name' => 'DTF & UV DTF',
            'category_slug' => 'dtf-uv-dtf',
            'service_id' => 2,
            'service_slug' => 'uv-dtf-printing',
            'service_title' => 'UV DTF Printing',
            'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
            'badge' => 'DTF & UV DTF',
            'badge_class' => 'badge-blue',
            'theme' => 'card-theme-ivory',
            'starting_price' => '₹349',
            'price_unit' => '/ sheet',
            'min_order' => '',
            'short_desc' => 'Raised, glossy decals that peel and stick on glass, steel, acrylic and plastic. Waterproof once applied.',
            'specifications' => 'Layers: White + CMYK + Spot Gloss Varnish | Adhesion: High-tack permanent acrylic | Waterproof & scratch-resistant',
            'description' => 'Raised, glossy decals that peel and stick on glass, steel, acrylic and plastic. Waterproof once applied.',
            'artwork_notes' => 'Minimum line thickness: 0.75pt. Provide transparent PNG or vector AI.',
            'is_featured' => 1
        ],
        [
            'id' => 3,
            'title' => 'Oversized Streetwear T-Shirt (240 GSM)',
            'slug' => 'streetwear-oversized-tee',
            'category_name' => 'T-Shirts',
            'category_slug' => 't-shirts',
            'service_id' => 3,
            'service_slug' => 't-shirt-printing',
            'service_title' => 'T-Shirt Printing',
            'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
            'badge' => 'T-Shirts',
            'badge_class' => 'badge-navy',
            'theme' => 'card-theme-navy',
            'starting_price' => '₹449',
            'price_unit' => '/ piece',
            'min_order' => '',
            'short_desc' => 'A heavy drop-shoulder tee with your design printed in DTF. Roomy fit and a soft feel.',
            'specifications' => 'Fabric: 100% Combed Cotton | Weight: 240 GSM Heavyweight | Fit: Relaxed Drop-Shoulder Oversized',
            'description' => 'A heavy drop-shoulder tee with your design printed in DTF. Roomy fit and a soft feel.',
            'artwork_notes' => 'Front Chest, Back, or Sleeve placements. Provide mockup and 300 DPI PNG.',
            'is_featured' => 1
        ],
        [
            'id' => 4,
            'title' => 'Custom Steel Thermal Bottle (750 ml)',
            'slug' => 'thermal-sipper-bottle',
            'category_name' => 'Corporate Gifts',
            'category_slug' => 'corporate-gifts',
            'service_id' => 4,
            'service_slug' => 'corporate-printing',
            'service_title' => 'Corporate Printing',
            'image_url' => 'assets/images/corporate_swag_kit.jpg',
            'badge' => 'Corporate Gifts',
            'badge_class' => 'badge-navy',
            'theme' => 'card-theme-white',
            'starting_price' => '₹599',
            'price_unit' => '/ bottle',
            'min_order' => '',
            'short_desc' => 'A double-wall steel bottle with your logo printed on it. Good for gifts and team kits.',
            'specifications' => 'Capacity: 750ml | Material: 304 Food-Grade Stainless Steel | Double-Wall Vacuum Insulated',
            'description' => 'A double-wall steel bottle with your logo printed on it. Good for gifts and team kits.',
            'artwork_notes' => 'Vector logo AI/EPS or high-res 300 DPI PNG.',
            'is_featured' => 1
        ],
        [
            'id' => 5,
            'title' => 'Branded Apparel Mailer Box',
            'slug' => 'apparel-mailer-box',
            'category_name' => 'Packaging',
            'category_slug' => 'packaging',
            'service_id' => 4,
            'service_slug' => 'corporate-printing',
            'service_title' => 'Corporate Printing',
            'image_url' => 'assets/images/apparel_mailer_box.jpg',
            'badge' => 'Packaging',
            'badge_class' => 'badge-gold',
            'theme' => 'card-theme-ivory',
            'starting_price' => '₹65',
            'price_unit' => '/ box',
            'min_order' => '',
            'short_desc' => 'A sturdy matte black box with your logo in gold foil and gloss detail. Made for shipping apparel.',
            'specifications' => 'Material: 350 GSM E-Flute Corrugated | Finish: Soft-Touch Matte Black | Hot Foil Stamping',
            'description' => 'A sturdy matte black box with your logo in gold foil and gloss detail. Made for shipping apparel.',
            'artwork_notes' => 'Dieline supplied upon order placement. Provide artwork in vector format.',
            'is_featured' => 1
        ],
        [
            'id' => 6,
            'title' => 'Custom Die-Cut Sticker Pack',
            'slug' => 'holographic-stickers',
            'category_name' => 'Stickers',
            'category_slug' => 'stickers',
            'service_id' => 2,
            'service_slug' => 'uv-dtf-printing',
            'service_title' => 'UV DTF Printing',
            'image_url' => 'assets/images/die_cut_stickers.jpg',
            'badge' => 'Stickers',
            'badge_class' => 'badge-blue',
            'theme' => 'card-theme-white',
            'starting_price' => '₹299',
            'price_unit' => '/ pack',
            'min_order' => '',
            'short_desc' => 'Stickers cut to your shape, in holographic and UV DTF finishes. Great for brands and giveaways.',
            'specifications' => 'Material: Rainbow Holographic Vinyl + 3D UV Varnish | Cut: Precision Kiss-Cut or Die-Cut',
            'description' => 'Stickers cut to your shape, in holographic and UV DTF finishes. Great for brands and giveaways.',
            'artwork_notes' => 'Include cut contour line or outline vector art in 100% magenta.',
            'is_featured' => 1
        ],
        [
            'id' => 7,
            'title' => 'Everyday Cotton T-Shirt (180 GSM)',
            'slug' => 'bio-washed-cotton-tee',
            'category_name' => 'T-Shirts',
            'category_slug' => 't-shirts',
            'service_id' => 3,
            'service_slug' => 't-shirt-printing',
            'service_title' => 'T-Shirt Printing',
            'image_url' => 'assets/images/streetwear_tee.jpg',
            'badge' => 'T-Shirts',
            'badge_class' => 'badge-gold',
            'theme' => 'card-theme-ivory',
            'starting_price' => '₹299',
            'price_unit' => '/ piece',
            'min_order' => '',
            'short_desc' => 'A breathable combed cotton crewneck with a soft printed design. Easy to wear all day.',
            'specifications' => 'Material: 100% Bio-Washed Combed Cotton | Weight: 180 GSM Pre-Shrunk | Fit: Regular Unisex',
            'description' => 'A breathable combed cotton crewneck with a soft printed design. Easy to wear all day.',
            'artwork_notes' => 'Max print area: 12x16 inches front/back. Transparent 300 DPI PNG.',
            'is_featured' => 1
        ],
        [
            'id' => 8,
            'title' => 'Corporate Welcome Gift Kit',
            'slug' => 'executive-welcome-hamper',
            'category_name' => 'Corporate Gifts',
            'category_slug' => 'corporate-gifts',
            'service_id' => 4,
            'service_slug' => 'corporate-printing',
            'service_title' => 'Corporate Printing',
            'image_url' => 'assets/images/branding_stationery.jpg',
            'badge' => 'Corporate Gifts',
            'badge_class' => 'badge-blue',
            'theme' => 'card-theme-white',
            'starting_price' => '₹599',
            'price_unit' => '/ kit',
            'min_order' => '',
            'short_desc' => 'A ready gift set with branded items for new joiners and clients, packed neatly.',
            'specifications' => 'Contents: Ceramic Mug, Hardbound Diary, Metal Stylus Pen, Branded Polo | Packaging: Rigid presentation box',
            'description' => 'A ready gift set with branded items for new joiners and clients, packed neatly.',
            'artwork_notes' => 'Vector brand guidelines and Pantone color codes required.',
            'is_featured' => 1
        ],
        [
            'id' => 9,
            'title' => 'Custom Printed Bookmarks',
            'slug' => 'bookmark-03',
            'category_name' => 'CUSTOM STATIONERY',
            'category_slug' => 'custom-stationery',
            'service_id' => 4,
            'service_slug' => 'corporate-printing',
            'service_title' => 'Corporate Printing',
            'image_url' => 'assets/images/specimen_art_prints.jpg',
            'badge' => 'CUSTOM STATIONERY',
            'badge_class' => 'badge-navy',
            'theme' => 'card-theme-ivory',
            'starting_price' => '₹1.50',
            'price_unit' => '/ piece',
            'min_order' => 'Min. 100 pcs',
            'short_desc' => 'Double-sided bookmarks on thick card, with a soft-touch finish. Good for brands, bookstores and event giveaways.',
            'specifications' => '350 GSM card • Double-sided print • Custom sizes',
            'description' => 'Double-sided bookmarks on thick card, with a soft-touch finish. Good for brands, bookstores and event giveaways.',
            'artwork_notes' => '350 GSM card • Double-sided print • Custom sizes. Provide 300 DPI PDF.',
            'is_featured' => 1
        ]
    ];
}

function get_catalog_products(?string $categorySlug = null, ?string $searchTerm = null): array {
    $curatedMap = [];
    foreach (get_curated_catalog_products() as $p) {
        $curatedMap[$p['slug']] = $p;
    }

    $dbProducts = [];
    if (Database::isConnected()) {
        try {
            $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.is_active = 1";
            $params = [];
            if (!empty($categorySlug)) {
                $sql .= " AND (c.slug = :cat OR p.badge = :cat2)";
                $params['cat'] = $categorySlug;
                $params['cat2'] = $categorySlug;
            }
            if (!empty($searchTerm)) {
                $sql .= " AND (p.title LIKE :s OR p.short_desc LIKE :s OR p.specifications LIKE :s)";
                $params['s'] = '%' . $searchTerm . '%';
            }
            $sql .= " ORDER BY p.display_order ASC";
            $rows = db_fetch_all($sql, $params);
            
            // Enrich with curated defaults if needed
            foreach ($rows as $row) {
                $slug = $row['slug'] ?? '';
                if (isset($curatedMap[$slug])) {
                    $row = array_merge($curatedMap[$slug], array_filter($row, fn($v) => $v !== null && $v !== ''));
                }
                $dbProducts[] = $row;
            }
        } catch (Exception $e) {
            $dbProducts = [];
        }
    }

    if (!empty($dbProducts)) {
        return $dbProducts;
    }

    // Fallback to curated showcase products
    $items = get_curated_catalog_products();

    if (!empty($categorySlug)) {
        $items = array_values(array_filter($items, function ($p) use ($categorySlug) {
            return ($p['category_slug'] ?? '') === $categorySlug || ($p['badge'] ?? '') === $categorySlug;
        }));
    }

    if (!empty($searchTerm)) {
        $term = strtolower($searchTerm);
        $items = array_values(array_filter($items, function ($p) use ($term) {
            return str_contains(strtolower($p['title']), $term) 
                || str_contains(strtolower($p['short_desc']), $term)
                || str_contains(strtolower($p['category_name']), $term)
                || str_contains(strtolower($p['specifications'] ?? ''), $term);
        }));
    }

    return $items;
}

function get_catalog_product_by_slug(string $slug): ?array {
    if (empty($slug)) {
        return null;
    }

    $curatedMap = [];
    foreach (get_curated_catalog_products() as $item) {
        $curatedMap[$item['slug']] = $item;
    }

    $fallback = $curatedMap[$slug] ?? null;

    if (Database::isConnected()) {
        try {
            $product = db_fetch_one("
                SELECT p.*, c.name as category_name, c.slug as category_slug, s.title as service_title, s.slug as service_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN services s ON p.service_id = s.id 
                WHERE p.slug = :slug AND p.is_active = 1
            ", ['slug' => $slug]);
            if ($product) {
                if ($fallback) {
                    return array_merge($fallback, array_filter($product, fn($v) => $v !== null && $v !== ''));
                }
                return $product;
            }
        } catch (Exception $e) {}
    }

    return $fallback;
}
