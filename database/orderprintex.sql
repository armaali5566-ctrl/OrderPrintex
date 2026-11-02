-- ==========================================================
-- ORDERPRINTEX — Complete MySQL Database Schema
-- Version: 1.0.0
-- Compatible: MySQL 5.7+ / 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `orderprintex` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `orderprintex`;

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------
-- Table: site_settings
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `group_name` VARCHAR(50) DEFAULT 'general',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: users
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(30) NULL,
  `company_name` VARCHAR(150) NULL,
  `address` TEXT NULL,
  `city` VARCHAR(100) NULL,
  `state` VARCHAR(100) NULL,
  `pincode` VARCHAR(20) NULL,
  `role` ENUM('customer', 'admin', 'staff') DEFAULT 'customer',
  `status` ENUM('active', 'suspended') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_email` (`email`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: categories
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `display_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: services
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `title` VARCHAR(150) NOT NULL,
  `short_description` TEXT NOT NULL,
  `full_description` LONGTEXT NOT NULL,
  `hero_tagline` VARCHAR(255) NULL,
  `image_url` VARCHAR(255) NULL,
  `badge` VARCHAR(50) DEFAULT 'Popular',
  `specifications` TEXT NULL,
  `artwork_guidelines` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `display_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: products
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NULL,
  `service_id` INT NULL,
  `title` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(200) NOT NULL UNIQUE,
  `short_desc` TEXT NULL,
  `description` LONGTEXT NULL,
  `specifications` TEXT NULL,
  `image_url` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `display_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_product_slug` (`slug`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: product_images
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(200) NULL,
  `display_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: enquiries
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `enquiries`;
CREATE TABLE `enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ref_number` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` INT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `customer_email` VARCHAR(191) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `company_name` VARCHAR(150) NULL,
  `service_id` INT NULL,
  `product_id` INT NULL,
  `service_name` VARCHAR(150) NULL,
  `width_inches` DECIMAL(8,2) NULL,
  `height_inches` DECIMAL(8,2) NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `material_options` VARCHAR(255) NULL,
  `customer_notes` TEXT NULL,
  `status` ENUM('received', 'under_review', 'quoted', 'converted', 'rejected', 'closed') DEFAULT 'received',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_ref_number` (`ref_number`),
  INDEX `idx_enq_status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: enquiry_files
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `enquiry_files`;
CREATE TABLE `enquiry_files` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `enquiry_id` INT NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `stored_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` BIGINT NOT NULL,
  `mime_type` VARCHAR(100) NOT NULL,
  `file_extension` VARCHAR(20) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: quotations
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `quotations`;
CREATE TABLE `quotations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `quote_number` VARCHAR(50) NOT NULL UNIQUE,
  `enquiry_id` INT NOT NULL,
  `user_id` INT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `customer_email` VARCHAR(191) NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 18.00,
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `delivery_charge` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `validity_days` INT DEFAULT 15,
  `valid_until` DATE NULL,
  `admin_notes` TEXT NULL,
  `customer_feedback` TEXT NULL,
  `status` ENUM('draft', 'sent', 'accepted', 'rejected', 'change_requested') DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_quote_number` (`quote_number`),
  INDEX `idx_quote_status` (`status`),
  FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: quotation_items
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `quotation_items`;
CREATE TABLE `quotation_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `quotation_id` INT NOT NULL,
  `item_description` VARCHAR(255) NOT NULL,
  `dimensions` VARCHAR(100) NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: orders
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `quotation_id` INT NULL,
  `enquiry_id` INT NULL,
  `user_id` INT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `customer_email` VARCHAR(191) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `pincode` VARCHAR(20) NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` ENUM('pending', 'partial', 'paid', 'refunded') DEFAULT 'pending',
  `payment_method` VARCHAR(50) DEFAULT 'Bank Transfer / UPI',
  `order_status` ENUM(
    'enquiry_received',
    'under_review',
    'quotation_sent',
    'quotation_approved',
    'payment_pending',
    'artwork_review',
    'proof_approval',
    'production',
    'quality_check',
    'dispatched',
    'delivered',
    'cancelled'
  ) DEFAULT 'quotation_approved',
  `courier_name` VARCHAR(100) NULL,
  `tracking_number` VARCHAR(100) NULL,
  `dispatch_date` DATE NULL,
  `delivery_date` DATE NULL,
  `internal_notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_order_num` (`order_number`),
  INDEX `idx_order_status` (`order_status`),
  FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: order_status_history
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `order_status_history`;
CREATE TABLE `order_status_history` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `previous_status` VARCHAR(50) NULL,
  `new_status` VARCHAR(50) NOT NULL,
  `remarks` TEXT NULL,
  `changed_by_user_id` INT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: artwork_proofs
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `artwork_proofs`;
CREATE TABLE `artwork_proofs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `proof_file_path` VARCHAR(255) NOT NULL,
  `version` INT DEFAULT 1,
  `customer_approval` ENUM('pending', 'approved', 'revision_requested') DEFAULT 'pending',
  `customer_comments` TEXT NULL,
  `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `approved_at` DATETIME NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: gallery
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `category` ENUM('dtf', 'uv_dtf', 'tshirt', 'corporate') NOT NULL DEFAULT 'dtf',
  `image_url` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) DEFAULT 1,
  `display_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: faqs
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` VARCHAR(50) DEFAULT 'General',
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `display_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Table: activity_logs
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `action` VARCHAR(100) NOT NULL,
  `details` TEXT NULL,
  `ip_address` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_log_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================================
-- SEED DATA — Verified Business Information from OrderPrintex
-- ==========================================================

-- 1. Site Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `group_name`) VALUES
('site_name', 'OrderPrintex', 'general'),
('site_tagline', 'Manufacturer & Bulk Supplier of DTF and UV DTF in India', 'general'),
('site_logo', 'assets/images/logo.png', 'general'),
('phone', '+91 93540 28199', 'contact'),
('phone_raw', '919354028199', 'contact'),
('whatsapp', '+91 93540 28199', 'contact'),
('whatsapp_url', 'https://wa.me/919354028199', 'contact'),
('email', 'sales@orderprintex.com', 'contact'),
('address', 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059', 'contact'),
('google_maps_url', 'https://share.google/MWOVYne70asGLsYXn', 'contact'),
('instagram_url', 'https://www.instagram.com/order_printex/?hl=en', 'social'),
('youtube_url', 'https://www.youtube.com/@PRINTEX_Delhi', 'social'),
('hero_badge', 'India\'s Direct-to-Film & UV DTF Specialists', 'homepage'),
('hero_heading', 'Precision Print Transfers for Garments & Hard Surfaces', 'homepage'),
('hero_description', 'Manufacturer & Bulk Supplier of DTF and UV DTF in India. We engineer press-ready transfers with vivid color gamut, durable washability, and rapid turnaround for apparel brands, promotional studios, and print businesses.', 'homepage'),
('prepress_info', 'Pre-cured, color-calibrated ICC profiles, 300 DPI high-res output.', 'technical'),
('turnaround_info', '1–3 business days typical turnaround after proof approval.', 'shipping');

-- 2. Initial Admin User
-- Email: admin@orderprintex.com
-- Password: AdminPassword@2026 (BCrypt hashed)
INSERT INTO `users` (`name`, `email`, `password_hash`, `phone`, `role`, `status`) VALUES
('OrderPrintex Admin', 'admin@orderprintex.com', '$2y$10$UkyrFh8x9l59e8fD3/Y24u41bA.v4.0m1e.d.7RzO1c6s0.Uf7RWC', '+91 93540 28199', 'admin', 'active');

-- 3. Categories
INSERT INTO `categories` (`name`, `slug`, `description`, `display_order`) VALUES
('DTF Transfers', 'dtf-transfers', 'Direct-to-Film apparel transfers for cotton, polyester, and performance blends', 1),
('UV DTF Decals', 'uv-dtf-decals', 'High-adhesion peel-and-stick UV decals for mugs, bottles, glass, and acrylic', 2),
('Apparel Printing', 'apparel-printing', 'Custom screen-grade DTF printed t-shirts, hoodies, and corporate apparel', 3),
('Corporate & Promotional', 'corporate-promotional', 'Branded corporate gifts, promotional packages, and event merchandise', 4);

-- 4. Verified Services
INSERT INTO `services` (`slug`, `title`, `short_description`, `full_description`, `hero_tagline`, `image_url`, `badge`, `specifications`, `artwork_guidelines`, `is_active`, `display_order`) VALUES
('dtf-printing', 'DTF Printing', 
'Premium Direct-to-Film transfers engineered for cotton, poly-blends, and performance sportswear. Ultra soft-hand feel, high stretch elasticity, and proven wash durability.',
'Printex is a top manufacturer & bulk supplier of DTF transfers in India. Direct-to-Film (DTF) technology allows full-color photographic designs with gradients, fine lines, and white backing to be pressed seamlessly onto dark, light, or synthetic fabrics without weeding. Every sheet is printed with calibrated ICC color profiles and sealed with premium hot-melt polyurethane adhesive powder for stretch resistance and long-lasting wash cycles.',
'Ultra Soft Hand-Feel & 50+ Wash Cycles',
'assets/images/gallery_1.jpg',
'Flagship Service',
'Max Print Width: 24 inches (Gang Sheets up to 100 meters) | Inks: Premium Water-based Textile Pigment Inks | Powder: High-Grade TPU Hot Melt Adhesive | Press Temp: 160°C for 15 seconds | Peel: Cold / Warm peel release',
'Formats: PNG (transparent background mandatory), PDF, AI, PSD | Resolution: 300 DPI at 100% scale | Color Mode: CMYK or RGB (converted to calibrated profile) | Minimum Line Thickness: 0.5pt (0.18mm) | Maintain 0.125 inch bleed clearance from sheet edges',
1, 1),

('uv-dtf-printing', 'UV DTF Printing', 
'Revolutionary UV-cured decals for hard and irregular surfaces. Instant application on tumblers, acrylic, glass, wood, metals, and packaging without heat presses.',
'Our UV DTF printing service in Delhi provides industrial-grade UV cured transfer decals. UV DTF uses micro-piezo printheads to lay down white ink, vibrant CMYK, and high-gloss clear varnish topped with durable pressure-sensitive adhesive film. The result is a waterproof, scratch-resistant 3D embossed sticker that bonds permanently to hard surfaces like stainless steel mugs, glass bottles, electronics, plastic packaging, and signage without requiring a heat press or lamination machine.',
'No Heat Press Required — Peel, Stick & Rub',
'assets/images/gallery_5.jpg',
'High Demand',
'Print Resolution: Up to 1440x2880 DPI | Layers: White Underbase + CMYK + Spot Gloss Varnish | Adhesion: High-tack permanent acrylic adhesive | Water Resistance: 100% Waterproof & Dishwasher Resistant | Compatible Surfaces: Glass, Ceramic, Acrylic, Metal, Plastic, Wood, Leather',
'Formats: PNG (with transparency), PDF, AI, PSD | Minimum Resolution: 300 DPI | Outline all vector fonts | Minimum line weight: 0.75pt | Avoid semi-transparent gradients unless rasterized with solid base',
1, 2),

('t-shirt-printing', 'T-Shirt Printing', 
'Comprehensive garment printing for brands, creators, events, and bulk corporate merchandise. High-definition prints on round-neck, polo, and oversized tees.',
'From independent streetwear labels to bulk organizational uniforms, OrderPrintex delivers ready-to-wear printed t-shirts with exceptional print fidelity. We combine 100% combed cotton and breathable poly-rich fabrics with state-of-the-art DTF and digital print technology. Whether you need single sample prototypes or bulk runs for college fests, corporate teams, or brand merchandise, our Delhi production facility delivers crisp detail with vibrant pigments.',
'Premium Combed Cotton & High-Density Prints',
'assets/images/gallery_3.jpg',
'Bulk Supplier',
'Garment Types: 180–240 GSM Bio-Washed Combed Cotton, Dry-Fit Polyester, Oversized Streetwear Tees, Polo Collars | Sizes: XS, S, M, L, XL, XXL, 3XL | Placement: Front Chest, Back Full, Left Chest Pocket, Sleeve Badges | Color Fastness: Grade 4+ Wash Fastness',
'Artwork Dimensions: Max 12x16 inches front/back | Transparent PNG preferred | 300 DPI scale | Provide mockups indicating exact placement in inches below collar',
1, 3),

('corporate-printing', 'Corporate Printing', 
'End-to-end promotional solutions, business gifts, employee onboarding kits, printed drinkware, and branded corporate accessories.',
'Printex handles comprehensive corporate and promotional printing requirements across India. We produce high-impact branded merchandise including personalized ceramic and stainless mugs, premium corporate diaries, metal pens, branded ID lanyards, uniform polo shirts, and customized packaging boxes. With strict quality inspection and reliable door-to-door delivery across India, we empower businesses to present a unified, high-end brand identity.',
'Complete Branded Merchandise & Business Swag',
'assets/images/gallery_4.jpg',
'B2B Solutions',
'Product Categories: Custom Printed Drinkware (Mugs/Sipper Bottles), Executive Stationery, Event Giveaways, Custom Branded Packaging | Customization: UV DTF, Screen Print, Sublimation, Laser Engraving | Packaging: Individual gift boxes or bulk bulk carton packaging',
'Vector vector art (AI / PDF / EPS) strongly recommended for logos and corporate identity guidelines. Specify Pantone codes where brand color matching is critical.',
1, 4);

-- 5. Verified Products
INSERT INTO `products` (`category_id`, `service_id`, `title`, `slug`, `short_desc`, `description`, `specifications`, `image_url`, `is_featured`, `is_active`, `display_order`) VALUES
(1, 1, 'Custom DTF Gang Sheet (22" Width)', 'custom-dtf-gang-sheet', 
'Maximize print economy by placing multiple logos, neck tags, and t-shirt designs onto our 22-inch continuous roll.',
'Our custom DTF gang sheets are printed on premium matte PET release film using high-density white underbase and vibrant textile inks. Pack your designs closely to minimize unit costs. Ready to cut and heat-press onto any color garment in 15 seconds.',
'Width: 22 inches | Length: 1 meter to 50 meters continuous | Film Type: Double-sided matte premium PET | Adhesive: Premium TPU white powder | Press Settings: 160°C, 15s, medium pressure, cold peel',
'assets/images/gallery_1.jpg', 1, 1, 1),

(2, 2, 'Custom UV DTF Decal Sheet', 'custom-uv-dtf-decal-sheet', 
'Permanent transfer stickers for tumblers, glass mugs, tech items, acrylic awards, and hard packaging.',
'Pre-cured with high-strength UV inks and high-gloss 3D embossed varnish. Peel the transfer film, apply to your hard surface, burnish firmly with a squeegee or thumb, and peel off the carrier film for a flawless factory-finished look.',
'Decal Type: UV DTF Cold Transfer | Finish: Gloss varnish with 3D tactile texture | Adhesion: Permanent industrial grade | Waterproof: Yes | Dishwasher safe: Hand wash recommended for long life',
'assets/images/gallery_5.jpg', 1, 1, 2),

(3, 3, 'Bio-Washed 100% Cotton Printed T-Shirt', 'bio-washed-cotton-printed-tshirt', 
'180 GSM super-combed cotton t-shirt with soft-hand DTF print. Ideal for brand merchandising and team wear.',
'Crafted from premium 100% bio-washed combed cotton with double-needle stitching on sleeves and hem. Breathable, pre-shrunk fabric printed with vivid DTF graphics that stretch without cracking or peeling.',
'Material: 100% Combed Cotton | Weight: 180 GSM Bio-Washed | Fit: Regular Unisex Fit | Neck: Ribbed Round Neck | Sizes: S to 3XL | Print Area: Front & Back up to A3 size',
'assets/images/gallery_3.jpg', 1, 1, 3),

(4, 4, 'Custom Printed Ceramic Mug (11oz)', 'custom-printed-ceramic-mug', 
'High-grade glossy white ceramic mug customized with durable full-color print. Perfect for corporate and personal gifts.',
'Premium ceramic mug featuring vivid wrap-around full-color printing. Engineered to resist daily use, washing, and microwave heating without fading.',
'Capacity: 11 oz (325 ml) | Material: Ceramic grade AAA | Finish: High Gloss | Print: Full-color wrap-around UV DTF / Sublimation | Packaging: Safe corrugated shipping box',
'assets/images/gallery_2.jpg', 1, 1, 4),

(4, 4, 'Corporate Promotional Gift Kit', 'corporate-promotional-gift-kit', 
'Custom curated corporate gift bundle featuring branded polo, metallic water bottle, executive notebook, and pen.',
'An executive welcome kit tailored for employee onboarding, VIP clients, and corporate summits. All items are coordinated with your company logo and brand color specifications.',
'Kit Contents: 1 Branded Polo T-Shirt, 1 Stainless Steel Flask/Bottle, 1 Hardbound Diary, 1 Stylus Pen | Packaging: Customized rigid gift box with branded sleeve',
'assets/images/gallery_4.jpg', 1, 1, 5);

-- 6. Gallery Items (Using verified showcase photos from original site)
INSERT INTO `gallery` (`title`, `category`, `image_url`, `description`, `is_featured`, `display_order`) VALUES
('High-Detail DTF Apparel Print on Dark Garment', 'dtf', 'assets/images/gallery_1.jpg', 'Direct-to-Film transfer showcasing ultra-fine color gradients and solid white backing.', 1, 1),
('Custom Branded Corporate Ceramic Mugs', 'corporate', 'assets/images/gallery_2.jpg', 'Vibrant wrap-around print for corporate promotional merchandise.', 1, 2),
('Custom T-Shirt Printing Production Run', 'tshirt', 'assets/images/gallery_3.jpg', 'Finished bio-washed cotton t-shirts ready for retail brand distribution.', 1, 3),
('Custom Merchandise & Job Work Batch', 'corporate', 'assets/images/gallery_4.jpg', 'Commercial job work for corporate clients and retail promotional kits.', 1, 4),
('UV DTF Decals on Glass & Tumbler Surfaces', 'uv-dtf', 'assets/images/gallery_5.jpg', 'Industrial-adhesion 3D UV DTF stickers applied seamlessly onto curved surfaces.', 1, 5);

-- 7. Verified FAQs (From existing website content)
INSERT INTO `faqs` (`category`, `question`, `answer`, `display_order`, `is_active`) VALUES
('Technology', 'What’s the difference between DTF and UV DTF?', 
'DTF (Direct-to-Film) is specifically designed for apparel and textiles (cotton, polyester, poly-blends, canvas, and sportswear), providing a soft-hand feel with excellent stretch and washability. UV DTF is engineered for hard, non-porous surfaces (glass, stainless steel mugs, acrylic, plastic, wood, and metals) and requires no heat press—simply peel, stick, and rub.', 1, 1),

('Artwork', 'What file types and specifications should I upload for printing?', 
'We accept PNG, PDF, AI, PSD, and EPS files. For best print clarity, please ensure artwork is at 300 DPI at the intended physical print size. For DTF and UV DTF stickers, a transparent background is mandatory unless a solid background color is explicitly intended. Vector text must be outlined.', 2, 1),

('Turnaround & Shipping', 'How fast is your turnaround time and do you offer bulk pricing?', 
'Standard turnaround is typically 1–3 business days after artwork approval, depending on order volume. We offer tiered bulk discounts for continuous gang sheets and wholesale garment printing. All orders are packed in secure, moisture-resistant packaging with courier tracking across India.', 3, 1),

('Orders & Quotes', 'How does the quotation and ordering system work?', 
'You can upload your artwork and enter dimensions and quantities directly through our Get a Quote or Homepage tool. Our prepress team reviews resolution, color profiles, and printability, then generates an itemized quotation. Once you review and approve the quotation, production begins immediately.', 4, 1),

('Services', 'Do you provide job work for existing garment businesses and creators?', 
'Yes! OrderPrintex is a primary manufacturer and bulk job-work supplier for print shops, clothing labels, promotional agencies, and creators across India. You can order transfers only (gang sheets) or complete finished printed garments.', 5, 1);
