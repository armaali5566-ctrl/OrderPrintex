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
        ['name' => 'Printing Materials', 'slug' => 'printing-materials'],
        ['name' => 'Custom Stickers', 'slug' => 'custom-stickers'],
        ['name' => 'Custom T-Shirts', 'slug' => 'custom-tshirts'],
        ['name' => 'Corporate Merchandise', 'slug' => 'corporate-merchandise'],
        ['name' => 'Packaging', 'slug' => 'packaging'],
        ['name' => 'Custom Branding Products', 'slug' => 'custom-branding']
    ];
}

function get_curated_catalog_products(): array {
    return [
        [
            'id' => 1,
            'title' => 'Custom DTF Gang Sheet (22" Width)',
            'slug' => 'custom-dtf-gang-sheet',
            'category_name' => 'Printing Materials',
            'category_slug' => 'printing-materials',
            'service_id' => 1,
            'service_slug' => 'dtf-printing',
            'service_title' => 'DTF Printing',
            'image_url' => 'assets/images/dtf_fabric_print.jpg',
            'badge' => 'Production Roll',
            'badge_class' => 'badge-gold',
            'theme' => 'card-theme-white',
            'starting_price' => '₹249',
            'price_unit' => '/ Foot',
            'min_order' => '1 Meter Min.',
            'short_desc' => 'Industrial-grade 22-inch continuous roll gang sheets. High white underbase opacity, ultra soft-hand feel, and 50+ wash longevity.',
            'specifications' => "Width: 22\" (56cm) | Film: Double-matte premium PET | Inks: Calibrated textile pigment | Temp: 160°C / 15s | Cold peel release",
            'description' => "Our 22-inch roll gang sheets allow you to arrange dozens of front chest graphics, back prints, sleeve emblems, and neck labels with zero color restrictions. Each sheet is printed with calibrated ICC color profiles and sealed with high-elasticity TPU hot-melt adhesive powder.",
            'artwork_notes' => 'Transparent background PNG or vector PDF/AI. 300 DPI at 100% print scale.',
            'is_featured' => 1
        ],
        [
            'id' => 2,
            'title' => 'Custom UV DTF Cold Transfer Decals',
            'slug' => 'custom-uv-dtf-decal-sheet',
            'category_name' => 'Custom Stickers',
            'category_slug' => 'custom-stickers',
            'service_id' => 2,
            'service_slug' => 'uv-dtf-printing',
            'service_title' => 'UV DTF Printing',
            'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
            'badge' => 'Multi-Surface',
            'badge_class' => 'badge-blue',
            'theme' => 'card-theme-ivory',
            'starting_price' => '₹349',
            'price_unit' => '/ Sheet',
            'min_order' => 'No Heat Required',
            'short_desc' => 'High-tack permanent UV transfer stickers with 3D gloss varnish. Peel, stick, and rub on glass, mugs, tumblers, and packaging.',
            'specifications' => "Resolution: 1440x2880 DPI | Layers: White + CMYK + Spot Gloss Varnish | Adhesion: High-tack permanent acrylic | Waterproof & scratch-resistant",
            'description' => "Engineered for non-porous hard surfaces without requiring a heat press or oven. Instant permanent adhesion on stainless steel tumblers, glass bottles, electronics, plastic packaging, and retail signage.",
            'artwork_notes' => 'Minimum line thickness: 0.75pt. Provide transparent PNG or vector AI.',
            'is_featured' => 1
        ],
        [
            'id' => 3,
            'title' => 'Heavyweight 240 GSM Oversized Streetwear T-Shirt',
            'slug' => 'streetwear-oversized-tee',
            'category_name' => 'Custom T-Shirts',
            'category_slug' => 'custom-tshirts',
            'service_id' => 3,
            'service_slug' => 't-shirt-printing',
            'service_title' => 'T-Shirt Printing',
            'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
            'badge' => 'Streetwear Luxury',
            'badge_class' => 'badge-navy',
            'theme' => 'card-theme-navy',
            'starting_price' => '₹449',
            'price_unit' => '/ Piece',
            'min_order' => 'Min. 5 Pcs',
            'short_desc' => 'Luxury heavyweight drop-shoulder streetwear t-shirt printed with high-density DTF graphics. Super-combed pre-shrunk cotton.',
            'specifications' => "Fabric: 100% Combed Cotton | Weight: 240 GSM Heavyweight | Fit: Relaxed Drop-Shoulder Oversized | Collar: 1.25\" Lycra Ribbed | Sizes: S to 3XL",
            'description' => "Tailored for independent streetwear labels, creative studios, and premium brand merchandise. Combines ultra-heavyweight cotton drape with vibrant, wash-tested DTF artwork that stretches without cracking.",
            'artwork_notes' => 'Front Chest, Back, or Sleeve placements. Provide mockup and 300 DPI PNG.',
            'is_featured' => 1
        ],
        [
            'id' => 4,
            'title' => 'Laser-Engraved Vacuum Thermal Sipper Bottle (750ml)',
            'slug' => 'thermal-sipper-bottle',
            'category_name' => 'Corporate Merchandise',
            'category_slug' => 'corporate-merchandise',
            'service_id' => 4,
            'service_slug' => 'corporate-printing',
            'service_title' => 'Corporate Printing',
            'image_url' => 'assets/images/corporate_swag_kit.jpg',
            'badge' => 'Drinkware & Gifts',
            'badge_class' => 'badge-gold',
            'theme' => 'card-theme-white',
            'starting_price' => '₹399',
            'price_unit' => '/ Unit',
            'min_order' => 'Min. 10 Units',
            'short_desc' => 'Double-wall stainless steel thermal bottle with laser-etched or full-color UV DTF company branding. 12hr hot / 24hr cold.',
            'specifications' => "Capacity: 750ml / 25oz | Material: Food-Grade SS 304 | Insulation: Double-Wall Vacuum Insulated | Finish: Matte Powder-Coat with Laser/UV DTF branding",
            'description' => "Premium corporate drinkware for employee onboarding, VIP clientele, and high-end marketing campaigns. Keeps beverages ice-cold for 24 hours or steaming hot for 12 hours.",
            'artwork_notes' => 'Vector logo in AI/EPS or high-resolution PNG with transparent background.',
            'is_featured' => 1
        ],
        [
            'id' => 5,
            'title' => 'Custom Branded Matte Black Apparel Mailer Box',
            'slug' => 'apparel-mailer-box',
            'category_name' => 'Packaging',
            'category_slug' => 'packaging',
            'service_id' => 4,
            'service_slug' => 'corporate-printing',
            'service_title' => 'Corporate Printing',
            'image_url' => 'assets/images/packaging_box.jpg',
            'badge' => 'Luxury Packaging',
            'badge_class' => 'badge-navy',
            'theme' => 'card-theme-navy',
            'starting_price' => '₹65',
            'price_unit' => '/ Box',
            'min_order' => 'Min. 50 Boxes',
            'short_desc' => 'Rigid corrugated e-commerce shipping box with metallic gold foil logo and UV spot gloss. Luxury unboxing for apparel brands.',
            'specifications' => "Board: 3-Ply E-Flute Corrugated | Finish: Matte Soft-Touch Lamination + Gold Foil Stamping | Dimensions: 12x9x3 inches | Closure: Self-locking tab",
            'description' => "Deliver an unforgettable unboxing experience for your fashion or corporate merchandise. Precision die-cut with reinforced crush-resistant sidewalls.",
            'artwork_notes' => 'Custom dieline provided upon order confirmation. Vector artwork mandatory.',
            'is_featured' => 1
        ],
        [
            'id' => 6,
            'title' => 'Die-Cut Holographic & UV DTF Sticker Pack',
            'slug' => 'holographic-stickers',
            'category_name' => 'Custom Stickers',
            'category_slug' => 'custom-stickers',
            'service_id' => 2,
            'service_slug' => 'uv-dtf-printing',
            'service_title' => 'UV DTF Printing',
            'image_url' => 'assets/images/custom_stickers.jpg',
            'badge' => 'Weatherproof',
            'badge_class' => 'badge-blue',
            'theme' => 'card-theme-white',
            'starting_price' => '₹299',
            'price_unit' => '/ Pack',
            'min_order' => '50 Pcs Pack',
            'short_desc' => 'Die-cut rainbow holographic vinyl decals and 3D textured UV DTF stickers. 100% weatherproof and UV-resistant for laptops & bottles.',
            'specifications' => "Material: Rainbow Holographic Vinyl + 3D UV Varnish | Cut: Precision Kiss-Cut or Die-Cut | Adhesion: Extra-strength permanent adhesive | Outdoor Durability: 2+ years",
            'description' => "Eye-catching promotional stickers for events, lifestyle brands, and creator merchandise. The iridescent rainbow sheen catches ambient light with stunning prismatic effects.",
            'artwork_notes' => 'Include cut contour line or outline vector art in 100% magenta.',
            'is_featured' => 1
        ],
        [
            'id' => 7,
            'title' => '180 GSM Bio-Washed Combed Cotton Printed T-Shirt',
            'slug' => 'bio-washed-cotton-tee',
            'category_name' => 'Custom T-Shirts',
            'category_slug' => 'custom-tshirts',
            'service_id' => 3,
            'service_slug' => 't-shirt-printing',
            'service_title' => 'T-Shirt Printing',
            'image_url' => 'assets/images/streetwear_tee.jpg',
            'badge' => 'Classic Combed',
            'badge_class' => 'badge-gold',
            'theme' => 'card-theme-ivory',
            'starting_price' => '₹299',
            'price_unit' => '/ Piece',
            'min_order' => 'Min. 10 Pcs',
            'short_desc' => 'Breathable everyday crewneck t-shirt with soft-hand DTF breast and back prints. Ideal for team uniforms and retail brand drops.',
            'specifications' => "Material: 100% Bio-Washed Combed Cotton | Weight: 180 GSM Pre-Shrunk | Fit: Regular Unisex | Seams: Double-needle reinforced | Colors: Black, Navy, White, Sage",
            'description' => "Our foundational garment printing choice. Super-soft bio-washed fabric ensures maximum wearer comfort while DTF inks maintain edge sharpness and color saturation.",
            'artwork_notes' => 'Max print area: 12x16 inches front/back. Transparent 300 DPI PNG.',
            'is_featured' => 1
        ],
        [
            'id' => 8,
            'title' => 'Executive Welcome Gift Hamper & Corporate Swag Kit',
            'slug' => 'executive-welcome-hamper',
            'category_name' => 'Custom Branding Products',
            'category_slug' => 'custom-branding',
            'service_id' => 4,
            'service_slug' => 'corporate-printing',
            'service_title' => 'Corporate Printing',
            'image_url' => 'assets/images/branding_stationery.jpg',
            'badge' => 'Corporate Swag',
            'badge_class' => 'badge-blue',
            'theme' => 'card-theme-white',
            'starting_price' => '₹899',
            'price_unit' => '/ Kit',
            'min_order' => 'Min. 10 Kits',
            'short_desc' => 'Curated VIP gift bundle featuring custom ceramic mug, hardbound diary, metal pen, and branded apparel inside a presentation box.',
            'specifications' => "Contents: 1 Ceramic Mug (11oz), 1 Hardbound Diary, 1 Metal Stylus Pen, 1 Branded Polo | Packaging: Rigid luxury presentation box with magnetic lid",
            'description' => "An end-to-end corporate gifting suite coordinated to your precise brand standards. Perfect for company milestones, executive meetings, and new employee orientation.",
            'artwork_notes' => 'Vector brand guidelines and Pantone color codes required.',
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
                $sql .= " AND c.slug = :cat";
                $params['cat'] = $categorySlug;
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
            return ($p['category_slug'] ?? '') === $categorySlug;
        }));
    }

    if (!empty($searchTerm)) {
        $term = strtolower($searchTerm);
        $items = array_values(array_filter($items, function ($p) use ($term) {
            return str_contains(strtolower($p['title']), $term) 
                || str_contains(strtolower($p['short_desc']), $term)
                || str_contains(strtolower($p['category_name']), $term)
                || str_contains(strtolower($p['specifications']), $term);
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
