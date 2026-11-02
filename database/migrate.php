<?php
/**
 * OrderPrintex - Database Migration & Seeder
 * Creates tables and populates seed data in SQLite database.
 */

$dbPath = __DIR__ . '/orderprintex.sqlite';
$isNew = !file_exists($dbPath);

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("PRAGMA foreign_keys = ON;");

    // 1. site_settings
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        setting_key TEXT NOT NULL UNIQUE,
        setting_value TEXT,
        group_name TEXT DEFAULT 'general',
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // 2. users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        phone TEXT,
        company_name TEXT,
        address TEXT,
        city TEXT,
        state TEXT,
        pincode TEXT,
        role TEXT DEFAULT 'customer',
        status TEXT DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // 3. categories
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        description TEXT,
        display_order INTEGER DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // 4. services
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT NOT NULL UNIQUE,
        title TEXT NOT NULL,
        short_description TEXT NOT NULL,
        full_description TEXT NOT NULL,
        hero_tagline TEXT,
        image_url TEXT,
        badge TEXT DEFAULT 'Popular',
        specifications TEXT,
        artwork_guidelines TEXT,
        is_active INTEGER DEFAULT 1,
        display_order INTEGER DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // 5. products (with SKU and rich fields)
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category_id INTEGER,
        service_id INTEGER,
        sku TEXT NOT NULL UNIQUE,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        short_desc TEXT,
        description TEXT,
        specifications TEXT,
        image_url TEXT,
        badge TEXT DEFAULT 'Popular',
        starting_price TEXT DEFAULT '₹249',
        price_unit TEXT DEFAULT '/ Unit',
        min_order TEXT DEFAULT 'Min. 1',
        artwork_notes TEXT,
        is_featured INTEGER DEFAULT 0,
        is_active INTEGER DEFAULT 1,
        display_order INTEGER DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
    );");

    // 6. product_sizes (for Dynamic Size-Based Pricing)
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_sizes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        size_label TEXT NOT NULL,
        dimensions TEXT,
        base_price REAL NOT NULL DEFAULT 0.00,
        display_order INTEGER DEFAULT 0,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    );");

    // 7. product_pricing_tiers (Quantity discounts)
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_pricing_tiers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        min_quantity INTEGER NOT NULL,
        max_quantity INTEGER,
        discount_percent REAL DEFAULT 0.00,
        unit_price REAL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    );");

    // 8. product_options (Paper, Finishing, Add-ons)
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_options (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        option_type TEXT NOT NULL, -- 'paper', 'material', 'finishing'
        option_name TEXT NOT NULL,
        additional_price REAL NOT NULL DEFAULT 0.00,
        display_order INTEGER DEFAULT 0,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    );");

    // 9. product_reviews (Genuine review system)
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        user_id INTEGER,
        customer_name TEXT NOT NULL,
        customer_email TEXT,
        rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
        title TEXT NOT NULL,
        review_text TEXT NOT NULL,
        is_verified_purchase INTEGER DEFAULT 0,
        status TEXT DEFAULT 'approved', -- 'pending', 'approved', 'rejected'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    );");

    // 10. blog_categories
    $pdo->exec("CREATE TABLE IF NOT EXISTS blog_categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // 11. blog_posts
    $pdo->exec("CREATE TABLE IF NOT EXISTS blog_posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category_id INTEGER,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        excerpt TEXT NOT NULL,
        content TEXT NOT NULL,
        featured_image TEXT NOT NULL,
        author TEXT DEFAULT 'OrderPrintex Editorial Team',
        status TEXT DEFAULT 'published', -- 'published', 'draft'
        is_sample INTEGER DEFAULT 1,
        read_time TEXT DEFAULT '5 min read',
        views INTEGER DEFAULT 0,
        seo_title TEXT,
        seo_description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL
    );");

    // 12. equipment_tools (About Us actual tools & machinery)
    $pdo->exec("CREATE TABLE IF NOT EXISTS equipment_tools (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        category TEXT NOT NULL,
        image_url TEXT,
        purpose TEXT NOT NULL,
        impact TEXT NOT NULL,
        display_order INTEGER DEFAULT 0
    );");

    // 13. announcement_bar (Editable promotional messages)
    $pdo->exec("CREATE TABLE IF NOT EXISTS announcement_bar (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        message_text TEXT NOT NULL,
        link_url TEXT,
        is_active INTEGER DEFAULT 1,
        display_order INTEGER DEFAULT 0
    );");

    // 14. enquiries
    $pdo->exec("CREATE TABLE IF NOT EXISTS enquiries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ref_number TEXT NOT NULL UNIQUE,
        user_id INTEGER,
        customer_name TEXT NOT NULL,
        customer_email TEXT NOT NULL,
        customer_phone TEXT NOT NULL,
        company_name TEXT,
        service_id INTEGER,
        product_id INTEGER,
        service_name TEXT,
        width_inches REAL,
        height_inches REAL,
        quantity INTEGER NOT NULL DEFAULT 1,
        material_options TEXT,
        customer_notes TEXT,
        status TEXT DEFAULT 'received',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
    );");

    // 15. quotations
    $pdo->exec("CREATE TABLE IF NOT EXISTS quotations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        quote_number TEXT NOT NULL UNIQUE,
        enquiry_id INTEGER NOT NULL,
        user_id INTEGER,
        customer_name TEXT NOT NULL,
        customer_email TEXT NOT NULL,
        subtotal REAL NOT NULL DEFAULT 0.00,
        tax_rate REAL NOT NULL DEFAULT 18.00,
        tax_amount REAL NOT NULL DEFAULT 0.00,
        delivery_charge REAL NOT NULL DEFAULT 0.00,
        grand_total REAL NOT NULL DEFAULT 0.00,
        validity_days INTEGER DEFAULT 15,
        valid_until TEXT,
        admin_notes TEXT,
        customer_feedback TEXT,
        status TEXT DEFAULT 'draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (enquiry_id) REFERENCES enquiries(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    );");

    // 16. orders
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_number TEXT NOT NULL UNIQUE,
        quotation_id INTEGER,
        enquiry_id INTEGER,
        user_id INTEGER,
        customer_name TEXT NOT NULL,
        customer_email TEXT NOT NULL,
        customer_phone TEXT NOT NULL,
        shipping_address TEXT NOT NULL,
        city TEXT NOT NULL,
        state TEXT NOT NULL,
        pincode TEXT NOT NULL,
        total_amount REAL NOT NULL DEFAULT 0.00,
        payment_status TEXT DEFAULT 'pending',
        payment_method TEXT DEFAULT 'Bank Transfer / UPI',
        order_status TEXT DEFAULT 'quotation_approved',
        courier_name TEXT,
        tracking_number TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE SET NULL,
        FOREIGN KEY (enquiry_id) REFERENCES enquiries(id) ON DELETE SET NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    );");

    echo "Tables created successfully.\n";

    // Seed Admin Account
    $adminPass = password_hash('Admin@Printex2026!', PASSWORD_DEFAULT);
    $customerPass = password_hash('Customer@123', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO users (id, name, email, password_hash, phone, role, status) VALUES 
        (1, 'OrderPrintex Administrator', 'admin@orderprintex.com', ?, '+91 93540 28199', 'admin', 'active'),
        (2, 'Demo Customer', 'customer@orderprintex.com', ?, '+91 98765 43210', 'customer', 'active');");
    $stmt->execute([$adminPass, $customerPass]);

    // Seed Categories
    $pdo->exec("INSERT OR IGNORE INTO categories (id, name, slug, description, display_order) VALUES
        (1, 'DTF Gang Sheets', 'dtf-gang-sheets', 'Direct-to-Film high-density apparel transfers', 1),
        (2, 'UV DTF Decals', 'uv-dtf-decals', 'Scratchproof 3D embossed decals for hard surfaces', 2),
        (3, 'Custom T-Shirts', 'custom-tshirts', 'Ready-to-wear printed apparel and streetwear', 3),
        (4, 'Corporate Merchandise', 'corporate-merchandise', 'Branded drinkware, stationery, and swag', 4),
        (5, 'Packaging & Boxes', 'packaging', 'Custom rigid and corrugated shipping mailer boxes', 5),
        (6, 'Custom Stickers', 'custom-stickers', 'Holographic, die-cut, and vinyl labels', 6),
        (7, 'Custom Stationery', 'custom-stationery', 'Bookmarks, flyers, brochures, and business cards', 7);");

    // Seed Services
    $pdo->exec("INSERT OR IGNORE INTO services (id, slug, title, short_description, full_description, hero_tagline, image_url, badge, specifications, display_order) VALUES
        (1, 'dtf-printing', 'DTF Printing', 'Direct-to-Film transfer printing with ultra soft-hand feel and 50+ wash cycles.', 'Direct-to-Film (DTF) transfer printing produces vibrant photorealistic graphics with opaque white underbase on cotton, polyester, and synthetics.', 'Ultra Soft Hand-Feel & 50+ Wash Cycles', 'assets/images/dtf_fabric_print.jpg', 'Flagship Service', 'Max Width: 24 inches | Inks: Premium Water-based Textile Pigment | Press Temp: 160°C for 15s', 1),
        (2, 'uv-dtf-printing', 'UV DTF Printing', 'Industrial-grade UV-cured transfer decals for glass, metal, wood, and acrylic.', 'UV DTF technology prints high-gloss 3D embossed decals with spot varnish that bond permanently without requiring heat pressing.', 'No Heat Press Required — Peel, Stick & Rub', 'assets/images/uv_dtf_drinkware.jpg', 'High Demand', 'Resolution: 2880 DPI | Layers: White + CMYK + Gloss Varnish | 100% Dishwasher Safe', 2),
        (3, 't-shirt-printing', 'T-Shirt Printing', 'Ready-to-wear custom printed apparel from streetwear tees to corporate polos.', 'Premium 180 to 240 GSM bio-washed combed cotton with high-density DTF graphics.', 'Premium Combed Cotton & High-Density Prints', 'assets/images/custom_tshirt_apparel.jpg', 'Bulk Supplier', 'Garments: 180–240 GSM Cotton, Poly-Rich, Dry-Fit | Sizes: XS to 3XL', 3),
        (4, 'corporate-printing', 'Corporate Printing', 'End-to-end branded merchandise, drinkware, and executive employee welcome kits.', 'Turnkey corporate promotional printing including thermal bottles, ceramic mugs, and custom packaging boxes.', 'Complete Branded Merchandise & Business Swag', 'assets/images/corporate_swag_kit.jpg', 'B2B Solutions', 'Products: Custom Drinkware, Executive Stationery, Event Giveaways', 4);");

    // Seed Products (including bookmark-03)
    $pdo->exec("INSERT OR IGNORE INTO products (id, category_id, service_id, sku, title, slug, short_desc, description, specifications, image_url, badge, starting_price, price_unit, min_order, artwork_notes, is_featured, is_active, display_order) VALUES
        (1, 1, 1, 'DTF-001', 'Commercial DTF Transfer Gang Sheet (22\" Full Roll)', 'custom-dtf-gang-sheet', 'High-density commercial DTF gang sheets printed on cold-peel PET film. 50+ wash durability with vibrant CMYK and high-opacity white ink.', 'Engineered for print-on-demand brands, screen printers, and garment manufacturers across India. Print as many graphics as you can fit on a continuous 22-inch wide roll with calibrated color profiling.', 'Roll Width: 22 inches | Film: Double-Matte Cold Peel PET | Inks: Eco-Pigment Textile Inks | Pressing: 160°C for 15s at medium pressure', 'assets/images/dtf_fabric_print.jpg', 'Bestseller', '₹249', '/ Foot', 'Min. 1 Meter', 'Submit artwork in 300 DPI transparent PNG, PDF, or AI. Group designs with at least 0.25 inch spacing.', 1, 1, 1),
        (2, 2, 2, 'UV-001', 'High-Gloss 3D UV DTF Decal Transfer Sheet', 'custom-uv-dtf-decal-sheet', 'Waterproof, scratch-resistant 3D embossed stickers with spot varnish. Bonds instantly to glass, metal, plastic, wood, and acrylic with zero heat pressing.', 'Our UV DTF transfer sheets give hard surfaces an ultra-premium embossed texture with high gloss varnish. Perfect for drinkware branding, electronic gadgets, packaging boxes, and automotive decals.', 'Sheet Sizes: A4, A3, or 24-inch continuous gang sheet | Finish: High-Build Spot Gloss Varnish | Adhesion: High-Tack Industrial Acrylic', 'assets/images/uv_dtf_drinkware.jpg', 'Trending', '₹349', '/ Sheet', 'Min. 1 Sheet', 'Vector artwork preferred. Ensure 100% vector outlines for spot gloss channels.', 1, 1, 2),
        (3, 3, 3, 'STR-001', 'Heavyweight 240 GSM Oversized Streetwear T-Shirt', 'streetwear-oversized-tee', 'Luxury heavyweight drop-shoulder streetwear t-shirt printed with high-density DTF graphics. Super-combed pre-shrunk cotton single jersey.', 'Tailored for independent streetwear labels, creative studios, and premium brand merchandise. Combines ultra-heavyweight cotton drape with vibrant, wash-tested DTF artwork that stretches without cracking.', 'Fabric: 100% Combed Cotton | Weight: 240 GSM Heavyweight | Fit: Relaxed Drop-Shoulder Oversized | Collar: 1.25\" Lycra Ribbed | Sizes: S to 3XL', 'assets/images/custom_tshirt_apparel.jpg', 'Streetwear Luxury', '₹449', '/ Piece', 'Min. 5 Pcs', 'Front Chest, Back, or Sleeve placements. Provide mockup and 300 DPI PNG.', 1, 1, 3),
        (4, 4, 4, 'BTL-001', 'Laser-Engraved Vacuum Thermal Sipper Bottle (750ml)', 'thermal-sipper-bottle', 'Double-wall stainless steel thermal bottle with laser-etched or full-color UV DTF company branding. 12hr hot / 24hr cold retention.', 'Premium corporate drinkware for employee onboarding, VIP clientele, and high-end marketing campaigns. Keeps beverages ice-cold for 24 hours or steaming hot for 12 hours.', 'Capacity: 750ml / 25oz | Material: Food-Grade SS 304 | Insulation: Double-Wall Vacuum Insulated | Finish: Matte Powder-Coat with Laser/UV DTF branding', 'assets/images/corporate_swag_kit.jpg', 'Drinkware & Gifts', '₹399', '/ Unit', 'Min. 10 Units', 'Vector logo in AI/EPS or high-resolution PNG with transparent background.', 1, 1, 4),
        (5, 5, 4, 'BOX-001', 'Custom Branded Matte Black Apparel Mailer Box', 'apparel-mailer-box', 'Rigid corrugated e-commerce shipping box with metallic gold foil logo and UV spot gloss. Luxury unboxing for apparel brands.', 'Deliver an unforgettable unboxing experience for your fashion or corporate merchandise. Precision die-cut with reinforced crush-resistant sidewalls.', 'Board: 3-Ply E-Flute Corrugated | Finish: Matte Soft-Touch Lamination + Gold Foil Stamping | Dimensions: 12x9x3 inches | Closure: Self-locking tab', 'assets/images/packaging_box.jpg', 'Luxury Packaging', '₹65', '/ Box', 'Min. 50 Boxes', 'Custom dieline provided upon order confirmation. Vector artwork mandatory.', 1, 1, 5),
        (6, 6, 2, 'STK-001', 'Die-Cut Holographic & UV DTF Sticker Pack', 'holographic-stickers', 'Die-cut rainbow holographic vinyl decals and 3D textured UV DTF stickers. 100% weatherproof and UV-resistant for laptops & bottles.', 'Eye-catching promotional stickers for events, lifestyle brands, and creator merchandise. The iridescent rainbow sheen catches ambient light with stunning prismatic effects.', 'Material: Rainbow Holographic Vinyl + 3D UV Varnish | Cut: Precision Kiss-Cut or Die-Cut | Adhesion: Extra-strength permanent adhesive | Outdoor Durability: 2+ years', 'assets/images/custom_stickers.jpg', 'Weatherproof', '₹299', '/ Pack', '50 Pcs Pack', 'Include cut contour line or outline vector art in 100% magenta.', 1, 1, 6),
        (7, 3, 3, 'TEE-001', '180 GSM Bio-Washed Combed Cotton Printed T-Shirt', 'bio-washed-cotton-tee', 'Breathable everyday crewneck t-shirt with soft-hand DTF breast and back prints. Ideal for team uniforms and retail brand drops.', 'Our foundational garment printing choice. Super-soft bio-washed fabric ensures maximum wearer comfort while DTF inks maintain edge sharpness and color saturation.', 'Material: 100% Bio-Washed Combed Cotton | Weight: 180 GSM Pre-Shrunk | Fit: Regular Unisex | Seams: Double-needle reinforced | Colors: Black, Navy, White, Sage', 'assets/images/streetwear_tee.jpg', 'Classic Combed', '₹299', '/ Piece', 'Min. 10 Pcs', 'Max print area: 12x16 inches front/back. Transparent 300 DPI PNG.', 1, 1, 7),
        (8, 7, 4, 'HMP-001', 'Executive Welcome Gift Hamper & Corporate Swag Kit', 'executive-welcome-hamper', 'Curated VIP gift bundle featuring custom ceramic mug, hardbound diary, metal pen, and branded apparel inside a presentation box.', 'An end-to-end corporate gifting suite coordinated to your precise brand standards. Perfect for company milestones, executive meetings, and new employee orientation.', 'Contents: 1 Ceramic Mug (11oz), 1 Hardbound Diary, 1 Metal Stylus Pen, 1 Branded Polo | Packaging: Rigid luxury presentation box with magnetic lid', 'assets/images/branding_stationery.jpg', 'Corporate Swag', '₹899', '/ Kit', 'Min. 10 Kits', 'Vector brand guidelines and Pantone color codes required.', 1, 1, 8),
        (9, 7, 4, 'BKM-001', 'Custom Printed Promotional Paper Bookmarks', 'bookmark-03', 'Premium double-sided printed bookmarks with velvet soft-touch lamination, foil accents, and rounded corner options for authors and brands.', 'Enhance your brand recall with custom-printed bookmarks on rigid 350-400 GSM art card. Ideal for book publishers, educational institutions, creative merchandise, and corporate giveaways.', 'Cardstock: 350 GSM / 400 GSM Art Card | Sizes: 2x6, 2x7, 2.5x8 inches | Finish: Gloss, Matte, Soft-Touch, Gold Foil Stamping | Corner Cut: Square or Rounded', 'assets/images/branding_stationery.jpg', 'Popular Stationery', '₹1.50', '/ Piece', 'Min. 100 Pcs', 'Submit PDF or AI with 3mm bleed on all sides. CMYK color mode.', 1, 1, 9);");

    // Seed Product Sizes
    $pdo->exec("INSERT OR IGNORE INTO product_sizes (id, product_id, size_label, dimensions, base_price, display_order) VALUES
        -- Bookmarks
        (1, 9, 'Standard (2 x 6 inches)', '2\" x 6\"', 1.50, 1),
        (2, 9, 'Medium (2 x 7 inches)', '2\" x 7\"', 2.00, 2),
        (3, 9, 'Large (2.5 x 8 inches)', '2.5\" x 8\"', 2.80, 3),
        -- DTF Gang Sheets
        (4, 1, '1 Foot (22\" x 12\")', '22\" x 12\"', 249.00, 1),
        (5, 1, '2 Feet (22\" x 24\")', '22\" x 24\"', 450.00, 2),
        (6, 1, '1 Meter (22\" x 39.3\")', '22\" x 39.3\"', 599.00, 3),
        (7, 1, '5 Feet (22\" x 60\")', '22\" x 60\"', 1099.00, 4),
        -- UV DTF
        (8, 2, 'A4 Sheet (8.25\" x 11.75\")', '8.25\" x 11.75\"', 349.00, 1),
        (9, 2, 'A3 Sheet (11.75\" x 16.5\")', '11.75\" x 16.5\"', 599.00, 2),
        (10, 2, 'Full Roll (24\" x 36\")', '24\" x 36\"', 999.00, 3),
        -- Streetwear Tee
        (11, 3, 'Small (S)', 'Chest 40\"', 449.00, 1),
        (12, 3, 'Medium (M)', 'Chest 42\"', 449.00, 2),
        (13, 3, 'Large (L)', 'Chest 44\"', 449.00, 3),
        (14, 3, 'X-Large (XL)', 'Chest 46\"', 479.00, 4),
        (15, 3, '2X-Large (2XL)', 'Chest 48\"', 499.00, 5),
        -- Thermal Bottle
        (16, 4, '500ml Flask', '500ml', 349.00, 1),
        (17, 4, '750ml Vacuum Bottle', '750ml', 399.00, 2),
        (18, 4, '1000ml Heavy Duty', '1000ml', 489.00, 3),
        -- Mailer Box
        (19, 5, 'Small (9\" x 6\" x 2\")', '9x6x2\"', 45.00, 1),
        (20, 5, 'Medium (12\" x 9\" x 3\")', '12x9x3\"', 65.00, 2),
        (21, 5, 'Large (14\" x 10\" x 4\")', '14x10x4\"', 85.00, 3),
        -- Stickers
        (22, 6, 'Pack of 50 Pcs', '2\" x 2\"', 299.00, 1),
        (23, 6, 'Pack of 100 Pcs', '2\" x 2\"', 499.00, 2),
        (24, 6, 'Pack of 250 Pcs', '2\" x 2\"', 999.00, 3),
        -- Bio Cotton Tee
        (25, 7, 'Small (S)', 'Chest 38\"', 299.00, 1),
        (26, 7, 'Medium (M)', 'Chest 40\"', 299.00, 2),
        (27, 7, 'Large (L)', 'Chest 42\"', 299.00, 3),
        (28, 7, 'X-Large (XL)', 'Chest 44\"', 329.00, 4),
        -- Hamper
        (29, 8, 'Executive Standard (3 Items)', 'Box 10x8x4\"', 899.00, 1),
        (30, 8, 'VIP Deluxe Suite (5 Items)', 'Box 14x11x5\"', 1399.00, 2);");

    // Seed Quantity Tiers (discounts for bulk orders)
    $pdo->exec("INSERT OR IGNORE INTO product_pricing_tiers (id, product_id, min_quantity, max_quantity, discount_percent) VALUES
        -- Generic tiers for all products
        (1, 9, 100, 249, 0.00),
        (2, 9, 250, 499, 10.00),
        (3, 9, 500, 999, 20.00),
        (4, 9, 1000, 99999, 35.00),
        -- DTF Tiers
        (5, 1, 1, 4, 0.00),
        (6, 1, 5, 9, 8.00),
        (7, 1, 10, 24, 15.00),
        (8, 1, 25, 9999, 25.00),
        -- Streetwear Tee
        (9, 3, 5, 19, 0.00),
        (10, 3, 20, 49, 10.00),
        (11, 3, 50, 99, 18.00),
        (12, 3, 100, 9999, 28.00);");

    // Seed Product Options (Paper, Materials, Finishings)
    $pdo->exec("INSERT OR IGNORE INTO product_options (id, product_id, option_type, option_name, additional_price, display_order) VALUES
        -- Bookmark Paper Types
        (1, 9, 'paper', '350 GSM Art Card (Standard)', 0.00, 1),
        (2, 9, 'paper', '400 GSM Extra-Rigid Card', 0.40, 2),
        (3, 9, 'paper', '300 GSM Eco Kraft Recycled', 0.25, 3),
        -- Bookmark Finishing Options
        (4, 9, 'finishing', 'Standard Matte Lamination', 0.20, 1),
        (5, 9, 'finishing', 'Gloss High-Shine Lamination', 0.20, 2),
        (6, 9, 'finishing', 'Velvet Soft-Touch Luxury', 0.60, 3),
        (7, 9, 'finishing', 'Metallic Gold Foil Accents', 1.20, 4),
        -- DTF Finishing
        (8, 1, 'finishing', 'Standard Cold-Peel Finish', 0.00, 1),
        (9, 1, 'finishing', 'Ultra-Matte Post-Press Treatment', 25.00, 2),
        -- Tee Placement
        (10, 3, 'finishing', 'Front Chest Print Only', 0.00, 1),
        (11, 3, 'finishing', 'Front Chest + Full Back Print', 120.00, 2),
        (12, 3, 'finishing', 'Front + Back + Sleeve Print', 180.00, 3);");

    // Seed Blog Categories
    $pdo->exec("INSERT OR IGNORE INTO blog_categories (id, name, slug, description) VALUES
        (1, 'Printing Guides', 'printing-guides', 'In-depth commercial and technical printing tutorials'),
        (2, 'Garment & Apparel', 'garment-apparel', 'DTF transfers, screen printing, and streetwear fashion tips'),
        (3, 'Corporate & Branding', 'corporate-branding', 'Promotional merchandise, corporate gifting, and unboxing ideas'),
        (4, 'Packaging & Labels', 'packaging-labels', 'Packaging boxes, adhesive stickers, and retail presentation');");

    // Seed 6 Demo Blog Articles (Phase 4 requirement)
    $pdo->exec("INSERT OR IGNORE INTO blog_posts (id, category_id, title, slug, excerpt, content, featured_image, author, status, is_sample, read_time, seo_title, seo_description) VALUES
        (1, 1, 'Complete Guide to Business Card Printing: Paper Weights, Finishes & Design Tips', 'complete-guide-to-business-card-printing', 
        'Discover everything you need to know about professional business card printing in India — from 350 vs 400 GSM cardstocks to soft-touch velvet lamination and gold foil stamping.',
        '<h2>Why Business Cards Still Matter in a Digital World</h2>
        <p>In an age dominated by LinkedIn QR codes and digital contacts, a tangible, beautifully printed business card leaves an indelible first impression. For entrepreneurs, executives, and creative agencies, hand-feel is synonymous with credibility.</p>
        <h3>1. Choosing the Right Paper Weight (GSM)</h3>
        <p>Paper weight is measured in GSM (Grams per Square Meter). For business cards, we never recommend dropping below 350 GSM:</p>
        <ul>
            <li><strong>350 GSM Art Card:</strong> The commercial gold standard. Stiff, resilient, and economical for high-volume distributions.</li>
            <li><strong>400 GSM Super-Heavy Card:</strong> Premium stiffness that resists dog-earing and flexing in wallets.</li>
            <li><strong>Kraft & Textured Linen Stocks:</strong> Perfect for organic, artisanal, and sustainable brand identities.</li>
        </ul>
        <h3>2. Selecting Your Protective & Decorative Lamination</h3>
        <p>Raw ink on cardstock is vulnerable to edge scuffing and moisture. Applying an ultra-thin polymer film elevates the tactile feel:</p>
        <ul>
            <li><strong>Matte Lamination:</strong> Elegant, glare-free, and smooth under the fingers.</li>
            <li><strong>Gloss Lamination:</strong> Intensifies color saturation and provides high-reflectance pop.</li>
            <li><strong>Velvet Soft-Touch:</strong> Mimics peach skin or luxury suede. Highly recommended for executive cards.</li>
        </ul>
        <h3>3. Special Finishing: Spot UV & Foil Stamping</h3>
        <p>Add contrast by highlighting your company logo with raised 3D spot gloss UV or hot foil stamping in metallic gold, rose gold, or silver.</p>',
        'assets/images/branding_stationery.jpg', 'OrderPrintex Prepress Team', 'published', 1, '6 min read',
        'Complete Guide to Business Card Printing | OrderPrintex Delhi',
        'Master business card printing: 350 vs 400 GSM, matte vs velvet lamination, spot UV, and bleed guidelines from Delhi print experts.'),

        (2, 2, 'DTF Printing Explained: Everything You Need to Know About Direct-to-Film Transfers', 'dtf-printing-explained-guide',
        'A comprehensive guide to Direct-to-Film (DTF) apparel printing: how it works, washability testing, pressing temperatures, and why it surpasses vinyl and screen printing.',
        '<h2>The Technological Revolution of Direct-to-Film</h2>
        <p>Direct-to-Film (DTF) transfer printing has fundamentally disrupted garment decoration across India. Apparel entrepreneurs no longer need to pay exorbitant screen separation charges for multi-color designs, nor do they need to weed intricate vinyl decals.</p>
        <h3>How Does DTF Printing Actually Work?</h3>
        <ol>
            <li><strong>Digital Printing:</strong> Precision micro-piezo heads print CMYK pigments onto a clear release film, immediately backed with a solid white ink underbase.</li>
            <li><strong>Powder Application:</strong> Thermoplastic polyurethane (TPU) adhesive powder is distributed uniformly across wet white ink and melted in an infrared tunnel oven.</li>
            <li><strong>Heat Pressing:</strong> The cured film is pressed onto garments at 160°C for 15 seconds, cold peeled, and post-pressed for soft fabric hand-feel.</li>
        </ol>
        <h3>Durability & Washability Standards</h3>
        <p>OrderPrintex calibrated DTF transfers withstand 50+ commercial washing cycles with zero cracking, flaking, or pigment wash-out when cared for correctly (wash inside-out with cold water).</p>',
        'assets/images/dtf_fabric_print.jpg', 'Rahul Sharma (Senior Prepress Engineer)', 'published', 1, '7 min read',
        'DTF Printing Explained: Everything You Need to Know | OrderPrintex',
        'Learn how DTF transfers work, pressing temperatures, TPU adhesive powder properties, and why DTF is ideal for streetwear labels.'),

        (3, 1, 'How to Choose the Right Paper for Your Printing Project: GSM, Texture & Coatings', 'how-to-choose-the-right-paper',
        'Confused between Art Card, Maplitho, Bond, and Textured Paper? Here is an expert breakdown of paper weights, fiber direction, and coating selections.',
        '<h2>Understanding Paper Weights (GSM Explained)</h2>
        <p>Paper selection is the single most critical tactile factor in any commercial printing run. Using lightweight stock for a high-end corporate brochure cheapens the brand, while using rigid board for multi-fold brochures causes spine cracking.</p>
        <h3>The Spectrum of Commercial Paper Stocks</h3>
        <ul>
            <li><strong>80–100 GSM Bond/Maplitho:</strong> Standard for letterheads, invoices, and multi-page contract documents.</li>
            <li><strong>130–170 GSM Art Paper:</strong> Gloss or matte coated paper used for vibrant promotional flyers, magazine inserts, and folded leaflets.</li>
            <li><strong>250–300 GSM Card:</strong> Sturdy cardstock ideal for booklet covers, presentation folders, and table tent cards.</li>
            <li><strong>350–400 GSM Board:</strong> Rigid packaging board and business cards engineered for structural stability.</li>
        </ul>
        <h3>Coated vs. Uncoated Stocks</h3>
        <p>Coated art papers hold ink droplets on the surface, producing crisp photograph reproduction. Uncoated stocks absorb ink into the pulp, yielding warm, muted, bookish tones ideal for reading long text.</p>',
        'assets/images/packaging_box.jpg', 'OrderPrintex Editorial Team', 'published', 1, '5 min read',
        'How to Choose Paper for Printing: GSM & Texture Guide | OrderPrintex',
        'Detailed comparison of paper weights (80 to 400 GSM), coated vs uncoated papers, and best choices for flyers, books, and boxes.'),

        (4, 1, 'Offset Printing vs Digital Printing: Key Differences, Costs & Turnaround Times', 'offset-vs-digital-printing',
        'When should you choose digital printing versus offset lithography? Understand the break-even volume, color consistency, and turnaround advantages of each method.',
        '<h2>The Two Pillars of Commercial Printing</h2>
        <p>One of the most frequent questions our Delhi workshop receives is: <em>Should I print this digitally or on an offset press?</em> The answer always comes down to quantity, turnaround urgency, and budget.</p>
        <h3>What is Digital Printing?</h3>
        <p>Digital presses print directly from computer design files using toner or liquid electrostatic ink. There are zero plate setup fees, making it cost-effective for 1 to 500 units and allowing instantaneous same-day turnaround.</p>
        <h3>What is Offset Lithography?</h3>
        <p>Offset printing uses aluminum plates to transfer ink onto a rubber blanket, then rolls it onto paper. It carries an upfront plate and calibration cost, but once configured, high-speed offset runs deliver exceptionally low cost per unit for runs of 1,000+ copies.</p>
        <h3>The Decision Rule</h3>
        <p>Need 100 corporate flyers today? Choose <strong>Digital</strong>. Need 10,000 retail product brochures with spot Pantone inks? Choose <strong>Offset</strong>.</p>',
        'assets/images/corporate_swag_kit.jpg', 'Vikas Verma (Production Manager)', 'published', 1, '6 min read',
        'Offset vs Digital Printing: Key Differences & Cost Analysis | OrderPrintex',
        'Compare offset lithography and commercial digital printing for volume pricing, turnaround, color accuracy, and minimum runs.'),

        (5, 4, 'Custom Stickers and Labels: A Complete Guide to Materials, Adhesives & UV Decals', 'custom-stickers-and-labels-guide',
        'From waterproof vinyl decals to prismatic holographic foils and 3D embossed UV DTF transfers — pick the ideal sticker material for your product packaging.',
        '<h2>Stickers as High-ROI Brand Ambassadors</h2>
        <p>Stickers convert customers into enthusiastic brand ambassadors. Whether slapped onto MacBook lids, hydro flasks, or shipping boxes, high-quality stickers create persistent visual impressions.</p>
        <h3>Sticker Substrates Explained</h3>
        <ul>
            <li><strong>Waterproof White Vinyl:</strong> 100-micron polymeric vinyl with UV-stable inks and permanent solvent acrylic adhesive. Dishwasher and outdoor-safe.</li>
            <li><strong>Rainbow Holographic:</strong> Metallized rainbow diffraction film that gleams with iridescent pastel prisms under daylight.</li>
            <li><strong>Clear Transparent Vinyl:</strong> See-through background with opaque white ink underbase, perfect for glass jar labels.</li>
            <li><strong>UV DTF Decals:</strong> The next-generation transfer sticker with 3D tactile spot gloss varnish, requiring no cutting or weeding.</li>
        </ul>
        <h3>Kiss-Cut vs. Die-Cut</h3>
        <p>Die-cut stickers are sliced through both the vinyl and backing paper to match the exact shape of your design. Kiss-cut stickers are cut through the vinyl only, leaving a rectangular paper border that makes them easier to peel in fast-paced retail settings.</p>',
        'assets/images/custom_stickers.jpg', 'OrderPrintex Editorial Team', 'published', 1, '5 min read',
        'Custom Stickers & Labels: Materials & Adhesive Guide | OrderPrintex',
        'Complete guide to waterproof vinyl stickers, holographic decals, UV DTF transfers, kiss-cut vs die-cut for product packaging.'),

        (6, 4, 'Packaging Printing Ideas for Growing E-Commerce & DTC Brands in India', 'packaging-printing-ideas-for-brands',
        'How custom shipping mailer boxes, branded tissue wrap, and spot-UV thank you cards transform standard package delivery into an unforgettable unboxing event.',
        '<h2>The Magic of the Unboxing Experience</h2>
        <p>In direct-to-consumer (DTC) e-commerce, the parcel arrival is often the very first physical touchpoint a customer has with your brand. A generic brown carton signals a commodity; a custom-printed rigid mailer signals a luxury investment.</p>
        <h3>1. Custom Corrugated Mailer Boxes (E-Flute)</h3>
        <p>E-flute corrugated board provides crush resistance while supporting razor-sharp offset and digital printing. Printing inside the lid with a brand mission statement or QR code increases social media unboxing shares by over 40%.</p>
        <h3>2. Branded Butter Paper & Tissue Wrap</h3>
        <p>Delicate 28–35 GSM semi-translucent tissue paper printed with repeating brand monogram patterns protects apparel and accessories from friction scuffs while building sensory anticipation.</p>
        <h3>3. Foil-Stamped Thank-You Cards</h3>
        <p>A personalized postcard inserted into every order thanking the customer by name with an exclusive discount code drives repeat order rates significantly.</p>',
        'assets/images/packaging_box.jpg', 'Pooja Mehta (Brand Specialist)', 'published', 1, '6 min read',
        'Packaging Printing Ideas for E-Commerce Brands | OrderPrintex',
        'Discover high-impact packaging printing: custom corrugated mailer boxes, branded tissue wrap, and unboxing design for Indian DTC brands.');");

    // Seed Equipment & Tools (for About Us page)
    $pdo->exec("INSERT OR IGNORE INTO equipment_tools (id, name, category, image_url, purpose, impact, display_order) VALUES
        (1, 'Industrial Micro-Piezo Dual-Head DTF Printers', 'Textile Printing', 'assets/images/dtf_fabric_print.jpg', 'Dual Epson i3200 micro-piezo heads laying down high-density white underbase and CMYK textile pigments simultaneously.', 'Photorealistic 1440x2880 DPI resolution with ultra-smooth gradient transitions and flawless solid color fill on dark garments.', 1),
        (2, 'Automatic Powder Shaker & Infrared Conveyor Oven', 'Transfer Curing', 'assets/images/dtf_fabric_print.jpg', 'Automated roll-to-roll powdering unit with electrostatic vibration and calibrated infrared heat tunnels.', 'Guarantees uniform TPU adhesive distribution and complete polyurethane polymerization for 50+ wash longevity.', 2),
        (3, 'Multi-Axis UV DTF Hybrid Flatbed & Roll System', 'UV Hard Surfaces', 'assets/images/uv_dtf_drinkware.jpg', 'UV LED curing system printing opaque white, CMYK, and high-build clear spot varnish onto release film.', 'Creates waterproof, scratchproof 3D tactile embossed decals for glass, tumblers, wood, and electronics.', 3),
        (4, 'Digital Precision Optical Contour Cutting Plotters', 'Finishing & Decals', 'assets/images/custom_stickers.jpg', 'High-speed cutting plotters with optical registration cameras for precision blade contouring.', 'Flawless kiss-cut and die-cut borders with sub-millimeter registration accuracy on vinyl and holographic stocks.', 4),
        (5, 'Industrial Pneumatic Auto-Open Heat Presses', 'Garment Pressing', 'assets/images/custom_tshirt_apparel.jpg', 'Digital dual-platen pneumatic presses with microprocessor temperature control and consistent pneumatic pressure.', 'Ensures 100% mechanical fiber bonding of transfers without hot spots, scorching, or uneven adhesion.', 5),
        (6, 'X-Rite Prepress Color Spectrophotometers', 'Prepress & Quality', 'assets/images/branding_stationery.jpg', 'Spectrophotometric color management instruments calibrating ICC profiles for CMYK and Pantone reproduction.', 'Guarantees consistent color fidelity between digital client mockups and physical press-ready output.', 6);");

    // Seed Announcement Bar
    $pdo->exec("INSERT OR IGNORE INTO announcement_bar (id, message_text, link_url, is_active, display_order) VALUES
        (1, 'Free Express Delivery on Bulk Orders Across India', 'services.php', 1, 1),
        (2, 'Upload Your Artwork — Free Prepress & Print Proof Analysis', 'get-quote.php', 1, 2),
        (3, 'Need Bulk DTF or UV DTF Transfers? Get Factory Direct Pricing', 'contact.php', 1, 3);");

    // Seed Site Settings
    $pdo->exec("INSERT OR IGNORE INTO site_settings (setting_key, setting_value, group_name) VALUES
        ('site_name', 'OrderPrintex', 'general'),
        ('tagline', 'Industrial Printing Studio — Delhi, India', 'general'),
        ('phone', '+91 93540 28199', 'contact'),
        ('phone_raw', '919354028199', 'contact'),
        ('email', 'sales@orderprintex.com', 'contact'),
        ('address', 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059', 'contact'),
        ('announcement_enabled', '1', 'announcement'),
        ('announcement_speed', '4500', 'announcement');");

    echo "Migration completed successfully! SQLite database initialized at: " . $dbPath . "\n";

} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
    exit(1);
}
