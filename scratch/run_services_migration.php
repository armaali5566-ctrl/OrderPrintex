<?php
require_once __DIR__ . '/../includes/database.php';

$newServices = [
    [
        'id' => 5,
        'slug' => 'custom-hoodie-printing',
        'title' => 'Custom Hoodie Printing in Delhi',
        'short_description' => 'Premium heavyweight hoodie and sweatshirt printing using high-stretch DTF transfers and screen printing. Custom pullover, zip-up, and oversized fleece printing with vibrant, crack-resistant finishes for streetwear brands, colleges, and corporate winterwear.',
        'full_description' => 'OrderPrintex provides industrial-grade custom hoodie printing engineered for fleece, French terry, and poly-cotton blends. Our high-elasticity DTF transfers and precision screen printing withstand repeated machine washes without cracking, peeling, or fading. Whether you need front chest prints, oversized back artwork, kangaroo pocket graphics, or sleeve designs, we deliver flawless results across all hoodie weights (280 to 450 GSM). Low minimums, express turnaround, and pan-India doorstep delivery.',
        'hero_tagline' => 'High-Density Fleece & Streetwear Hoodie Printing in Delhi',
        'image_url' => 'assets/images/custom_tshirt_apparel.jpg',
        'badge' => 'Winter Special',
        'specifications' => "GSM Range: 280 - 450 GSM\nCompatible Fabrics: 100% Combed Cotton, Cotton-Poly Fleece, French Terry\nPrint Techniques: High-Stretch DTF, Plastisol Screen Print, Puff Embroidery\nPlacements: Left Chest, Center Chest, Full Back, Kangaroo Pocket, Sleeves, Hood\nDurability: 50+ Machine Washes with Zero Cracking",
        'artwork_guidelines' => "Vector formats preferred (AI, EPS, PDF, SVG)\nRaster files must be min 300 DPI with transparent background (PNG, TIFF)\nColor Mode: CMYK for precise pigment matching\nMinimum line weight: 0.5pt for fine text and strokes",
        'is_active' => 1,
        'display_order' => 5
    ],
    [
        'id' => 6,
        'slug' => 'uv-printing',
        'title' => 'Direct UV Flatbed Printing Services',
        'short_description' => 'Industrial direct UV flatbed printing on rigid surfaces including acrylic, wood, glass, metal, leather, and foam boards. High-precision CMYK + White + Varnish printing with instant UV LED curing and tactile embossed textures.',
        'full_description' => 'Our direct UV printing service utilizes advanced flatbed printers to apply UV-curable inks directly onto rigid substrates. Unlike UV DTF stickers that are transferred via carrier film, flatbed UV printing deposits ink droplets directly onto the substrate, instantly cured by high-intensity UV LED lamps. Ideal for corporate awards, acrylic plaques, metal signage, wooden coasters, phone cases, promotional gifting, and industrial instrument panels with optional raised gloss varnish.',
        'hero_tagline' => 'Direct-to-Substrate Industrial UV Flatbed Printing in Delhi',
        'image_url' => 'assets/images/uv_dtf_drinkware.jpg',
        'badge' => 'Industrial Grade',
        'specifications' => "Substrates: Cast Acrylic, Natural Wood, Glass, Brushed Metal, Anodized Aluminum, Leather, PVC Foam\nMax Print Area: 60cm x 90cm up to 100mm substrate thickness\nInk System: CMYK + High-Opacity White + Spot Gloss Varnish\nResolution: 1440 x 1440 DPI photo-grade precision\nFinish Options: Matte, High-Gloss, Raised 3D Braille & Embossed Texture",
        'artwork_guidelines' => "Vector EPS, AI, PDF preferred; high-res raster (min 300 DPI) accepted\nInclude dedicated spot channels for White Underbase and Gloss Varnish\nBleed: 3mm on all exterior edges for cut-to-edge designs\nFonts: Convert all typography to outlines/curves",
        'is_active' => 1,
        'display_order' => 6
    ],
    [
        'id' => 7,
        'slug' => 'mug-cup-printing',
        'title' => 'Custom Mug & Cup Printing Studio',
        'short_description' => 'Full-color ceramic coffee mugs, stainless steel tumblers, travel cups, and enamel mugs with high-gloss sublimation and rotary UV printing. Microwave safe, dishwasher safe, and ideal for corporate gifting.',
        'full_description' => 'OrderPrintex produces custom printed mugs, insulated tumblers, ceramic coffee cups, and glassware for corporate kits, cafe merchandise, and promotional campaigns. We use 360-degree wrap sublimation and cylindrical rotary UV printing to achieve photorealistic full-color prints, metallic accents, and embossed gloss finishes. All drinkware prints are scratch-resistant, heat-resistant, and microwave/dishwasher safe.',
        'hero_tagline' => 'High-Gloss Ceramic Mugs & Tumblers Custom Printed in Delhi',
        'image_url' => 'assets/images/corporate_merch_box.jpg',
        'badge' => 'Top Gifting Choice',
        'specifications' => "Drinkware Styles: 11oz & 15oz Ceramic Mugs, Stainless Steel Insulated Tumblers, Enamel Camp Mugs, Frosted Beer Steins\nPrint Methods: 360° Dye-Sublimation Wrap & Cylindrical Rotary Direct UV\nCoating Quality: Premium AAA-grade Orca/Hard-coated ceramic\nDurability: Dishwasher safe (100+ cycles) & Microwave safe\nPackaging: Individual crash-lock thermocol gift boxes included",
        'artwork_guidelines' => "Standard Mug Dimensions: 200mm x 95mm (wrap layout)\nResolution: 300 DPI minimum at 1:1 print dimensions\nColor Profile: sRGB / CMYK standard\nAvoid hairline borders near top and bottom rims (5mm margin)",
        'is_active' => 1,
        'display_order' => 7
    ]
];

function migrateDatabase($pdo, $driverName, $newServices) {
    echo "Processing $driverName...\n";
    foreach ($newServices as $s) {
        $check = $pdo->prepare("SELECT id FROM services WHERE slug = ?");
        $check->execute([$s['slug']]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            echo " - Service {$s['slug']} already exists with ID {$existing['id']}. Updating...\n";
            $sql = "UPDATE services SET 
                    title = ?, short_description = ?, full_description = ?, 
                    hero_tagline = ?, image_url = ?, badge = ?, specifications = ?, 
                    artwork_guidelines = ?, is_active = ?, display_order = ?
                    WHERE slug = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $s['title'], $s['short_description'], $s['full_description'],
                $s['hero_tagline'], $s['image_url'], $s['badge'], $s['specifications'],
                $s['artwork_guidelines'], $s['is_active'], $s['display_order'],
                $s['slug']
            ]);
        } else {
            echo " - Inserting new service {$s['slug']} (ID {$s['id']})...\n";
            $sql = "INSERT INTO services (id, slug, title, short_description, full_description, hero_tagline, image_url, badge, specifications, artwork_guidelines, is_active, display_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $s['id'], $s['slug'], $s['title'], $s['short_description'], $s['full_description'],
                $s['hero_tagline'], $s['image_url'], $s['badge'], $s['specifications'],
                $s['artwork_guidelines'], $s['is_active'], $s['display_order']
            ]);
        }
    }
}

// 1. Migrate active DB connection (MySQL in XAMPP)
$mainPdo = Database::getInstance();
if ($mainPdo) {
    migrateDatabase($mainPdo, 'Main Connection (' . Database::getActiveDriver() . ')', $newServices);
}

// 2. Migrate SQLite DB if exists
$sqlitePath = defined('DB_SQLITE_PATH') ? DB_SQLITE_PATH : (__DIR__ . '/../database/orderprintex.sqlite');
if (file_exists($sqlitePath)) {
    try {
        $sqlitePdo = new PDO('sqlite:' . $sqlitePath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        migrateDatabase($sqlitePdo, 'SQLite (' . $sqlitePath . ')', $newServices);
    } catch (Exception $e) {
        echo "SQLite migration error: " . $e->getMessage() . "\n";
    }
}

echo "Migration finished successfully.\n";
