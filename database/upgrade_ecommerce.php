<?php
/**
 * OrderPrintex - Safe E-Commerce Database Upgrade Migration
 * Adds Product Attributes, Variations, Section Content fields, and Settings.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

try {
    $db = Database::getInstance();
    $db->beginTransaction();

    echo "1. Checking and creating product attribute & variation tables...\n";

    // 1. attributes table
    $db->exec("CREATE TABLE IF NOT EXISTS attributes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // 2. attribute_values table
    $db->exec("CREATE TABLE IF NOT EXISTS attribute_values (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        attribute_id INTEGER NOT NULL,
        value TEXT NOT NULL,
        color_code TEXT DEFAULT NULL,
        display_order INTEGER DEFAULT 0,
        FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
    );");

    // 3. product_attributes table (assigned attributes to products)
    $db->exec("CREATE TABLE IF NOT EXISTS product_attributes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        attribute_id INTEGER NOT NULL,
        display_order INTEGER DEFAULT 0,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
    );");

    // 4. product_variations table
    $db->exec("CREATE TABLE IF NOT EXISTS product_variations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        sku TEXT NOT NULL UNIQUE,
        combination_key TEXT NOT NULL,
        regular_price REAL NOT NULL DEFAULT 0,
        sale_price REAL DEFAULT NULL,
        stock_quantity INTEGER NOT NULL DEFAULT 50,
        is_in_stock INTEGER NOT NULL DEFAULT 1,
        image_url TEXT DEFAULT NULL,
        is_active INTEGER NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    );");

    // 5. product_variation_values table
    $db->exec("CREATE TABLE IF NOT EXISTS product_variation_values (
        variation_id INTEGER NOT NULL,
        attribute_value_id INTEGER NOT NULL,
        PRIMARY KEY (variation_id, attribute_value_id),
        FOREIGN KEY (variation_id) REFERENCES product_variations(id) ON DELETE CASCADE,
        FOREIGN KEY (attribute_value_id) REFERENCES attribute_values(id) ON DELETE CASCADE
    );");

    // 6. Check columns on products table
    echo "2. Checking products table columns...\n";
    $productCols = $db->query("PRAGMA table_info(products)")->fetchAll(PDO::FETCH_ASSOC);
    $colNames = array_column($productCols, 'name');

    $neededCols = [
        'application_guide' => 'TEXT',
        'shipping_info' => 'TEXT',
        'meta_title' => 'TEXT',
        'meta_description' => 'TEXT',
        'regular_price' => 'REAL DEFAULT 0',
        'sale_price' => 'REAL DEFAULT NULL',
        'stock_quantity' => 'INTEGER DEFAULT 100',
        'stock_status' => "TEXT DEFAULT 'in_stock'"
    ];

    foreach ($neededCols as $c => $def) {
        if (!in_array($c, $colNames)) {
            $db->exec("ALTER TABLE products ADD COLUMN {$c} {$def}");
            echo " - Added column {$c} to products\n";
        }
    }

    // 7. Seed Settings for Social & Contact
    echo "3. Seeding site_settings for social and contact bar...\n";
    $settingsToEnsure = [
        'whatsapp' => ['+91 93540 28199', 'contact'],
        'whatsapp_raw' => ['919354028199', 'contact'],
        'business_hours' => ['Mon - Sat: 9:30 AM - 8:00 PM', 'contact'],
        'social_instagram' => ['https://www.instagram.com/orderprintex', 'social'],
        'social_facebook' => ['https://www.facebook.com/orderprintex', 'social'],
        'social_youtube' => ['https://www.youtube.com/@orderprintex', 'social'],
        'social_linkedin' => ['https://www.linkedin.com/company/orderprintex', 'social'],
        'top_bar_enabled' => ['1', 'general'],
        'meta_title' => ['OrderPrintex — Commercial Printing Studio & Direct Manufacturer Delhi', 'seo'],
        'meta_description' => ['OrderPrintex is India\'s premier bulk manufacturer of DTF apparel transfers, UV DTF decals, custom streetwear t-shirts, and corporate merchandise with pan-India express dispatch.', 'seo']
    ];

    foreach ($settingsToEnsure as $k => $info) {
        $exists = $db->query("SELECT id FROM site_settings WHERE setting_key = " . $db->quote($k))->fetch();
        if (!$exists) {
            $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value, group_name) VALUES (:k, :v, :g)");
            $stmt->execute(['k' => $k, 'v' => $info[0], 'g' => $info[1]]);
            echo " - Added setting {$k}\n";
        }
    }

    // 8. Seed Default Core Attributes (Size, Colour, Material, Finish, Length)
    echo "4. Seeding attributes and values...\n";
    $attrDefs = [
        'size' => [
            'name' => 'Size',
            'values' => [
                ['value' => 'Small (S)', 'color' => null],
                ['value' => 'Medium (M)', 'color' => null],
                ['value' => 'Large (L)', 'color' => null],
                ['value' => 'X-Large (XL)', 'color' => null],
                ['value' => 'XXL', 'color' => null],
                ['value' => '500ml', 'color' => null],
                ['value' => '750ml', 'color' => null],
                ['value' => '1000ml (1L)', 'color' => null],
                ['value' => '2" x 6"', 'color' => null],
                ['value' => '2" x 7"', 'color' => null],
                ['value' => '2" x 2" (Pack of 50)', 'color' => null],
                ['value' => '3" x 3" (Pack of 50)', 'color' => null],
                ['value' => '1 Meter Roll', 'color' => null],
                ['value' => '2 Meter Roll', 'color' => null],
                ['value' => '5 Meter Roll', 'color' => null],
                ['value' => 'A3 Sheet (10 Sheets)', 'color' => null],
                ['value' => 'A3 Sheet (25 Sheets)', 'color' => null]
            ]
        ],
        'colour' => [
            'name' => 'Colour',
            'values' => [
                ['value' => 'Phantom Black', 'color' => '#18181B'],
                ['value' => 'Bright White', 'color' => '#FFFFFF'],
                ['value' => 'Midnight Navy', 'color' => '#14233C'],
                ['value' => 'Crimson Red', 'color' => '#A83E3C'],
                ['value' => 'Steel Grey', 'color' => '#6B7280']
            ]
        ],
        'material' => [
            'name' => 'Material / Finish',
            'values' => [
                ['value' => 'Rainbow Holographic Foil', 'color' => null],
                ['value' => 'Waterproof Matte Vinyl', 'color' => null],
                ['value' => 'Clear Transparent Gloss', 'color' => null],
                ['value' => '350 GSM Velvet Soft-Touch', 'color' => null],
                ['value' => '400 GSM Gold Foil Accented', 'color' => null]
            ]
        ]
    ];

    foreach ($attrDefs as $slug => $def) {
        $attrId = $db->query("SELECT id FROM attributes WHERE slug = " . $db->quote($slug))->fetchColumn();
        if (!$attrId) {
            $stmt = $db->prepare("INSERT INTO attributes (name, slug) VALUES (:n, :s)");
            $stmt->execute(['n' => $def['name'], 's' => $slug]);
            $attrId = $db->lastInsertId();
            echo " - Created attribute {$def['name']}\n";
        }
        foreach ($def['values'] as $idx => $v) {
            $valExists = $db->query("SELECT id FROM attribute_values WHERE attribute_id = {$attrId} AND value = " . $db->quote($v['value']))->fetchColumn();
            if (!$valExists) {
                $stmt = $db->prepare("INSERT INTO attribute_values (attribute_id, value, color_code, display_order) VALUES (:aid, :val, :col, :ord)");
                $stmt->execute(['aid' => $attrId, 'val' => $v['value'], 'col' => $v['color'], 'ord' => $idx + 1]);
            }
        }
    }

    // 9. Update default scroll content sections for existing products if empty
    echo "5. Updating detailed scroll sections on existing products...\n";
    $products = $db->query("SELECT id, slug, sku, title FROM products")->fetchAll(PDO::FETCH_ASSOC);

    $defaultSpecs = [
        'custom-dtf-gang-sheet' => [
            'specifications' => "Print Width: 22 inches continuous roll\nFilm Type: Premium Double-Matte Cold Peel PET\nInk Configuration: Dual-Head CMYK + High-Density Opaque White Underbase\nAdhesive Powder: High-elasticity Thermoplastic Polyurethane (TPU)\nResolution: 1440 x 2880 DPI Photorealistic Micro-Piezo\nWash Durability: 50+ commercial wash cycles guaranteed",
            'artwork_notes' => "File Formats: Vector PDF, AI, SVG, or 300 DPI Transparent PNG.\nColor Profile: CMYK or sRGB with transparent alpha background.\nSafety Margins: 0.25 inch spacing between separate gang-arranged designs.\nMinimum Line Weight: 0.5pt for positive text, 1pt for negative reversed text.",
            'application_guide' => "Step 1: Preheat garment for 5 seconds at 160°C (320°F) to eliminate residual fabric moisture.\nStep 2: Position DTF transfer sheet design side down on garment.\nStep 3: Press firmly with medium-to-heavy pressure (50–60 PSI) for 15 seconds.\nStep 4: Allow transfer to cool completely (approx. 20–30 seconds) before peeling PET film away smoothly.\nStep 5: Post-press for 5 seconds with Teflon or silicone parchment sheet for ultra-soft hand feel.",
            'shipping_info' => "Turnaround: Same-day dispatch for prepress-ready files uploaded before 1:00 PM IST.\nPackaging: Moisture-sealed inner poly wrap with rigid double-wall core tubes.\nCarrier Partners: Bluedart, Delhivery, DTDC express air courier across all India pin codes.\nTracking: Real-time SMS and email tracking links provided immediately upon shipment dispatch."
        ],
        'custom-uv-dtf-decal-sheet' => [
            'specifications' => "Sheet Size: A3+ Sheet (11.7 x 16.5 inches)\nInks: UV LED Curable Inks (White, CMYK, Clear Gloss Varnish)\nAdhesion Type: Industrial Pressure-Sensitive Acrylic Bond (Cold Transfer)\nFinish: 3D Embossed Spot Gloss Tactile Finish\nCompatibility: Glass, Acrylic, Stainless Steel, Wood, Ceramics, Electronics\nResistance: 100% Waterproof, Dishwasher Safe & Scratch Proof",
            'artwork_notes' => "Resolution: 300 DPI minimum at 100% scale.\nTransparency: 100% transparent PNG or vector PDF with vector paths.\nSpot Gloss: Spot gloss varnish is automatically applied over all ink elements.",
            'application_guide' => "Step 1: Thoroughly clean the target hard surface with isopropyl alcohol and dry.\nStep 2: Slowly peel the white backing carrier film from the clear application tape.\nStep 3: Align and press the decal firmly onto the target surface.\nStep 4: Burnish down with a squeegee or thumb firmly across all edges for 10–15 seconds.\nStep 5: Slowly peel the top transparent transfer film at a 45-degree angle. No heat press required!",
            'shipping_info' => "Turnaround: 24 to 48 hours production turnaround.\nPackaging: Rigid flatboard mailers with moisture protection.\nDispatch: Express courier delivery across PAN-India."
        ],
        'streetwear-oversized-tee' => [
            'specifications' => "Fabric: 100% Super-Combed Bio-Washed Cotton Single Jersey\nFabric Weight: 240 GSM Heavyweight Premium Streetwear\nFit: Relaxed drop-shoulder boxy fit with ribbed crew collar\nSeams: Double-needle topstitching on neck, sleeves, and hem\nShrinkage: Pre-shrunk fabric with less than 2% dimensional change\nPrinting: Industrial DTF transfer with soft hand feel",
            'artwork_notes' => "Placement: Chest center (up to 12 x 14 in), Back center (up to 12 x 16 in), or left chest pocket (4 x 4 in).\nFormat: High-resolution PNG or PDF with transparent background.",
            'application_guide' => "Washing Instructions: Machine wash inside out with cold water and mild detergent.\nDrying: Tumble dry low or line dry in shade for best longevity.\nIroning: Do not iron directly on the printed graphic; iron on reverse side.",
            'shipping_info' => "Turnaround: 2–3 business days for custom printed orders.\nPackaging: Individual poly-bagged garments inside tamper-proof OrderPrintex courier mailer.\nDelivery: PAN-India express delivery within 3–5 working days."
        ],
        'thermal-sipper-bottle' => [
            'specifications' => "Capacity: 750ml / 25 oz (Also available in 500ml & 1000ml)\nMaterial: Food-Grade 304 Stainless Steel (BPA Free)\nInsulation: Double-Wall Vacuum Insulated (12h Hot / 24h Cold)\nLid: Leakproof spill-resistant flip sports spout with carry handle\nPrinting: High-Build UV DTF Decal with permanent adhesion",
            'artwork_notes' => "Artwork size: Maximum 3.5 inches width x 6 inches height for single-sided wrap.\nHigh contrast artwork recommended for dark powder-coated bottles.",
            'application_guide' => "Daily Care: Wash with warm soapy water and non-abrasive sponge.\nDishwasher: Hand wash recommended for maximum vacuum insulation life.",
            'shipping_info' => "Turnaround: 2 business days production.\nPackaging: Individual retail presentation box inside outer shipping carton."
        ],
        'holographic-stickers' => [
            'specifications' => "Material: Metallized Rainbow Prism Diffraction Vinyl\nThickness: 120 Micron Heavyweight Vinyl Stock\nAdhesive: Permanent Solvent Acrylic (Waterproof & Outdoor Rated)\nFinish: High-Gloss UV protective laminate\nDurability: 2+ Years outdoor UV sunlight and weather resistance",
            'artwork_notes' => "White ink underbase is applied under colors that require opacity. Non-printed areas let the rainbow holographic effect shine through.",
            'application_guide' => "Apply onto any clean, dry, flat surface. Firmly press from center outwards to remove any air pockets.",
            'shipping_info' => "Turnaround: 24 to 48 hours dispatch. Rigid non-bend cardboard packaging."
        ],
        'bookmark-03' => [
            'specifications' => "Size: Standard 2 x 6 inches (Optional 2 x 7 inches)\nCardstock: 350 GSM Heavyweight Velvet Art Card\nFinish: Matte Soft-Touch Lamination with Optional Gold Metallic Foil\nCorners: Straight Precision Cut or 3mm Rounded Corners",
            'artwork_notes' => "Include 2mm bleed on all sides. Keep critical text 4mm inside trim edge.",
            'application_guide' => "Ready-to-use premium printed bookmarks for publishing, events, and gift retail.",
            'shipping_info' => "Turnaround: 24–48 hours dispatch. Packaged in moisture-resistant bundles."
        ]
    ];

    foreach ($products as $p) {
        $slug = $p['slug'];
        $defaults = $defaultSpecs[$slug] ?? null;
        if ($defaults) {
            $stmt = $db->prepare("UPDATE products SET 
                specifications = COALESCE(NULLIF(specifications, ''), :specs),
                artwork_notes = COALESCE(NULLIF(artwork_notes, ''), :art),
                application_guide = COALESCE(NULLIF(application_guide, ''), :app),
                shipping_info = COALESCE(NULLIF(shipping_info, ''), :ship)
                WHERE id = :id");
            $stmt->execute([
                'specs' => $defaults['specifications'],
                'art' => $defaults['artwork_notes'],
                'app' => $defaults['application_guide'],
                'ship' => $defaults['shipping_info'],
                'id' => $p['id']
            ]);
            echo " - Configured scroll sections for {$p['title']}\n";
        }
    }

    // 10. Seed Sample Product Variations for verified products
    echo "6. Seeding realistic product variations...\n";

    // Helper function to link product to attribute
    $linkAttr = function($prodId, $attrSlug) use ($db) {
        $attrId = $db->query("SELECT id FROM attributes WHERE slug = " . $db->quote($attrSlug))->fetchColumn();
        if ($attrId) {
            $exists = $db->query("SELECT id FROM product_attributes WHERE product_id = {$prodId} AND attribute_id = {$attrId}")->fetchColumn();
            if (!$exists) {
                $db->exec("INSERT INTO product_attributes (product_id, attribute_id) VALUES ({$prodId}, {$attrId})");
            }
        }
        return $attrId;
    };

    // Helper to get attr value id
    $getValId = function($attrSlug, $val) use ($db) {
        return $db->query("SELECT av.id FROM attribute_values av 
            JOIN attributes a ON av.attribute_id = a.id 
            WHERE a.slug = " . $db->quote($attrSlug) . " AND av.value = " . $db->quote($val))->fetchColumn();
    };

    // Helper to insert variation
    $addVar = function($prodId, $sku, $key, $regPrice, $salePrice, $stock, $img = null, $valIds = []) use ($db) {
        $exists = $db->query("SELECT id FROM product_variations WHERE sku = " . $db->quote($sku))->fetchColumn();
        if (!$exists) {
            $stmt = $db->prepare("INSERT INTO product_variations 
                (product_id, sku, combination_key, regular_price, sale_price, stock_quantity, is_in_stock, image_url) 
                VALUES (:pid, :sku, :ck, :rp, :sp, :stock, :st, :img)");
            $stmt->execute([
                'pid' => $prodId,
                'sku' => $sku,
                'ck' => $key,
                'rp' => $regPrice,
                'sp' => $salePrice,
                'stock' => $stock,
                'st' => ($stock > 0 ? 1 : 0),
                'img' => $img
            ]);
            $varId = $db->lastInsertId();
            foreach ($valIds as $vid) {
                if ($vid) {
                    $db->exec("INSERT OR IGNORE INTO product_variation_values (variation_id, attribute_value_id) VALUES ({$varId}, {$vid})");
                }
            }
            return $varId;
        }
        return $exists;
    };

    // 10.1 Streetwear Tee (STR-001) -> Sizes: S, M, L, XL, XXL; Colours: Phantom Black, Bright White, Midnight Navy
    $tee = $db->query("SELECT id FROM products WHERE sku = 'STR-001'")->fetch(PDO::FETCH_ASSOC);
    if ($tee) {
        $tId = $tee['id'];
        $linkAttr($tId, 'size');
        $linkAttr($tId, 'colour');

        $sizes = ['Small (S)' => 'S', 'Medium (M)' => 'M', 'Large (L)' => 'L', 'X-Large (XL)' => 'XL', 'XXL' => 'XXL'];
        $colours = ['Phantom Black' => 'BLK', 'Bright White' => 'WHT', 'Midnight Navy' => 'NVY'];

        foreach ($sizes as $sName => $sCode) {
            foreach ($colours as $cName => $cCode) {
                $sku = "STR-{$sCode}-{$cCode}";
                $key = "size:{$sName}|colour:{$cName}";
                $regPrice = 699.00;
                $salePrice = 549.00;
                $stock = ($sCode === 'XXL' && $cCode === 'WHT') ? 8 : 45;
                $valIds = [$getValId('size', $sName), $getValId('colour', $cName)];
                $addVar($tId, $sku, $key, $regPrice, $salePrice, $stock, 'assets/images/streetwear_tee.jpg', $valIds);
            }
        }
        echo " - Generated 15 variations for Streetwear Tee (STR-001)\n";
    }

    // 10.2 Thermal Bottle (BTL-001) -> Sizes: 500ml, 750ml, 1000ml (1L); Colours: Phantom Black, Bright White, Midnight Navy
    $btl = $db->query("SELECT id FROM products WHERE sku = 'BTL-001'")->fetch(PDO::FETCH_ASSOC);
    if ($btl) {
        $bId = $btl['id'];
        $linkAttr($bId, 'size');
        $linkAttr($bId, 'colour');

        $bSizes = ['500ml' => ['code' => '500', 'reg' => 549, 'sale' => 449], '750ml' => ['code' => '750', 'reg' => 699, 'sale' => 599], '1000ml (1L)' => ['code' => '1L', 'reg' => 849, 'sale' => 749]];
        $bColours = ['Phantom Black' => 'BLK', 'Bright White' => 'WHT', 'Midnight Navy' => 'NVY'];

        foreach ($bSizes as $sName => $sData) {
            foreach ($bColours as $cName => $cCode) {
                $sku = "BTL-{$sData['code']}-{$cCode}";
                $key = "size:{$sName}|colour:{$cName}";
                $stock = 30;
                $valIds = [$getValId('size', $sName), $getValId('colour', $cName)];
                $addVar($bId, $sku, $key, $sData['reg'], $sData['sale'], $stock, 'assets/images/uv_dtf_drinkware.jpg', $valIds);
            }
        }
        echo " - Generated 9 variations for Thermal Bottle (BTL-001)\n";
    }

    // 10.3 Holographic Stickers (STK-001) -> Sizes: 2" x 2", 3" x 3"; Materials: Rainbow Holographic Foil, Waterproof Matte Vinyl
    $stk = $db->query("SELECT id FROM products WHERE sku = 'STK-001'")->fetch(PDO::FETCH_ASSOC);
    if ($stk) {
        $sId = $stk['id'];
        $linkAttr($sId, 'size');
        $linkAttr($sId, 'material');

        $stkSizes = ['2" x 2" (Pack of 50)' => ['code' => '2IN', 'price' => 299], '3" x 3" (Pack of 50)' => ['code' => '3IN', 'price' => 499]];
        $stkMats = ['Rainbow Holographic Foil' => 'HOLO', 'Waterproof Matte Vinyl' => 'VINYL'];

        foreach ($stkSizes as $szName => $szData) {
            foreach ($stkMats as $mName => $mCode) {
                $sku = "STK-{$szData['code']}-{$mCode}";
                $key = "size:{$szName}|material:{$mName}";
                $reg = $szData['price'] + ($mCode === 'HOLO' ? 50 : 0);
                $sale = $reg - 40;
                $valIds = [$getValId('size', $szName), $getValId('material', $mName)];
                $addVar($sId, $sku, $key, $reg, $sale, 100, 'assets/images/custom_stickers.jpg', $valIds);
            }
        }
        echo " - Generated 4 variations for Holographic Stickers (STK-001)\n";
    }

    // 10.4 Bookmark (BKM-001) -> Sizes: 2" x 6", 2" x 7"; Materials: 350 GSM Velvet Soft-Touch, 400 GSM Gold Foil Accented
    $bkm = $db->query("SELECT id FROM products WHERE sku = 'BKM-001'")->fetch(PDO::FETCH_ASSOC);
    if ($bkm) {
        $bkId = $bkm['id'];
        $linkAttr($bkId, 'size');
        $linkAttr($bkId, 'material');

        $bkmSizes = ['2" x 6"' => '2X6', '2" x 7"' => '2X7'];
        $bkmFinishes = ['350 GSM Velvet Soft-Touch' => ['code' => 'VLV', 'reg' => 199, 'sale' => 150], '400 GSM Gold Foil Accented' => ['code' => 'GOLD', 'reg' => 299, 'sale' => 240]];

        foreach ($bkmSizes as $sz => $szCode) {
            foreach ($bkmFinishes as $fName => $fData) {
                $sku = "BKM-{$szCode}-{$fData['code']}";
                $key = "size:{$sz}|material:{$fName}";
                $valIds = [$getValId('size', $sz), $getValId('material', $fName)];
                $addVar($bkId, $sku, $key, $fData['reg'], $fData['sale'], 80, 'assets/images/branding_stationery.jpg', $valIds);
            }
        }
        echo " - Generated 4 variations for Bookmark (BKM-001)\n";
    }

    // 10.5 DTF Gang Sheet (DTF-001) -> Lengths: 1 Meter Roll, 2 Meter Roll, 5 Meter Roll
    $dtf = $db->query("SELECT id FROM products WHERE sku = 'DTF-001'")->fetch(PDO::FETCH_ASSOC);
    if ($dtf) {
        $dId = $dtf['id'];
        $linkAttr($dId, 'size');

        $rolls = [
            '1 Meter Roll' => ['code' => '1M', 'reg' => 399, 'sale' => 299],
            '2 Meter Roll' => ['code' => '2M', 'reg' => 749, 'sale' => 580],
            '5 Meter Roll' => ['code' => '5M', 'reg' => 1750, 'sale' => 1390]
        ];

        foreach ($rolls as $rName => $rData) {
            $sku = "DTF-{$rData['code']}";
            $key = "size:{$rName}";
            $valIds = [$getValId('size', $rName)];
            $addVar($dId, $sku, $key, $rData['reg'], $rData['sale'], 200, 'assets/images/dtf_fabric_print.jpg', $valIds);
        }
        echo " - Generated 3 variations for DTF Gang Sheet (DTF-001)\n";
    }

    // 10.6 UV DTF Decal Sheet (UV-001) -> Sizes: A3 Sheet (10 Sheets), A3 Sheet (25 Sheets)
    $uv = $db->query("SELECT id FROM products WHERE sku = 'UV-001'")->fetch(PDO::FETCH_ASSOC);
    if ($uv) {
        $uvId = $uv['id'];
        $linkAttr($uvId, 'size');

        $uvPacks = [
            'A3 Sheet (10 Sheets)' => ['code' => '10S', 'reg' => 1899, 'sale' => 1599],
            'A3 Sheet (25 Sheets)' => ['code' => '25S', 'reg' => 4499, 'sale' => 3799]
        ];

        foreach ($uvPacks as $pName => $pData) {
            $sku = "UV-{$pData['code']}";
            $key = "size:{$pName}";
            $valIds = [$getValId('size', $pName)];
            $addVar($uvId, $sku, $key, $pData['reg'], $pData['sale'], 150, 'assets/images/uv_dtf_drinkware.jpg', $valIds);
        }
        echo " - Generated 2 variations for UV DTF Decals (UV-001)\n";
    }

    $db->commit();
    echo "\nE-Commerce Upgrade Migration COMPLETED successfully!\n";

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo "ERROR during migration: " . $e->getMessage() . "\n";
    exit(1);
}

