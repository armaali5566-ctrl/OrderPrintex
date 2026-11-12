# ORDERPRINTEX — COMPLETE WEBSITE CONTENT AUDIT REPORT
**Target Directory:** `C:\Movies\Internship\OrderPrintex`  
**Audit Type:** Exhaustive Content & Source Traceability Audit (Read-Only Audit — Zero Code or Schema Modifications Performed)  
**Date of Audit:** November 12, 2026  
**Environment:** PHP 8.x / MySQL 8 & MariaDB / Apache (`.htaccess`) & Built-in Server Router (`router.php`)  

---

## EXECUTIVE SUMMARY & QUICK REFERENCE

This document is a complete, line-by-line audit of all 14 primary public pages on the OrderPrintex website. Its purpose is to provide the website owner and development team with an exact roadmap of where every piece of text, headline, image, spec sheet, form field, and metadata originates, and precisely how each element should be edited.

### Simple Summary Table

| # | Page | Main File | Content Source | Admin CMS Available? | Where Should I Edit? |
|---|------|-----------|----------------|----------------------|----------------------|
| 1 | **Home Page** | `index.php` | Static PHP/HTML + Database (`site_settings`, `products`, `services`, `gallery`, `blog_posts`) | **Partial** (`admin/content.php`, `admin/settings.php`) | Edit static sections directly in `index.php`; edit hero text in `admin/content.php` or `index.php`; edit studio contact & social links in `admin/settings.php`. |
| 2 | **About Page** | `about.php` | Static PHP/HTML + Database (`equipment_tools`) + Fallback Arrays | **Partial** (`admin/announcement.php` for equipment tools) | Edit page copy, story & milestones directly in `about.php`; manage technical equipment in `admin/announcement.php`. |
| 3 | **Services Overview** | `services.php` | Static PHP/HTML (Intro & CTA) + Database (`services`) | **Yes** for cards (`admin/services.php`) | Edit page intro & bottom CTA in `services.php`; edit service cards, titles, badges, taglines, specs, and images in `admin/services.php`. |
| 4 | **DTF Printing** | `services/dtf-printing.php` | Static PHP/HTML | **No** (Direct File) | Edit headlines, comparison text, technical parameters, and form labels directly in `services/dtf-printing.php`. |
| 5 | **UV DTF Printing** | `services/uv-dtf-printing.php` | Static PHP/HTML | **No** (Direct File) | Edit headlines, layer details, tactile gloss specifications, and upload text directly in `services/uv-dtf-printing.php`. |
| 6 | **T-Shirt Printing** | `services/t-shirt-printing.php` | Static PHP/HTML | **No** (Direct File) | Edit garment specifications, bio-washed cotton descriptions, and silhouette details directly in `services/t-shirt-printing.php`. |
| 7 | **Corporate Printing** | `services/corporate-printing.php` | Static PHP/HTML | **No** (Direct File) | Edit corporate gifting packages, laser etching specs, and B2B terms directly in `services/corporate-printing.php`. |
| 8 | **Products Catalog** | `products.php` | Database (`products`, `categories`) + Fallback in `includes/catalog_helper.php` | **Yes** (`admin/products.php`, `admin/categories.php`) | Edit catalog intro in `products.php`; manage product titles, SKUs, pricing, tiers, images, and categories in `admin/products.php`. |
| 9 | **Portfolio Page** | `gallery.php` | Database (`gallery`) + Curated Fallback Array in `gallery.php` | **Yes** (`admin/gallery.php` / `admin/portfolio.php`) | Edit page header & spotlight labels in `gallery.php`; add, edit, or remove project showcase items and photos in `admin/gallery.php`. |
| 10 | **Blog Listing Page** | `blog.php` | Database (`blog_posts`, `blog_categories`) + Static PHP/HTML Header & CTA | **Yes** for articles (`admin/blog.php`) | Edit intro header & bottom prepress CTA in `blog.php`; publish, edit, or categorize articles in `admin/blog.php`. |
| 11 | **Contact Page** | `contact.php` | Database (`site_settings`) + Static PHP/HTML (Intro, Form Labels & Map Embed) | **Yes** for contact info (`admin/settings.php`) | Edit studio phone, WhatsApp, email, address, and maps URL in `admin/settings.php`; edit intro copy & form structure in `contact.php`. |
| 12 | **Cart / Get Quote** | `cart.php` (Redirect) / `get-quote.php` | Static PHP/HTML + Database (`services`) | **No** for workbench layout; **Yes** for services dropdown (`admin/services.php`) | `cart.php` redirects to `products/` (B2B enquiry model). Edit upload studio labels, hints, and calculator copy in `get-quote.php`. |
| 13 | **Blog Article 1** | `blog-details.php` (`slug=complete-guide-to-business-card-printing`) | Database (`blog_posts` ID #1) | **Yes** (`admin/blog.php`) | Edit title, slug, excerpt, full HTML body, featured image, category, and SEO tags in `admin/blog.php` under Post ID #1. |
| 14 | **Blog Article 2** | `blog-details.php` (`slug=dtf-printing-explained-guide`) | Database (`blog_posts` ID #2) | **Yes** (`admin/blog.php`) | Edit title, slug, excerpt, full HTML body, featured image, category, and SEO tags in `admin/blog.php` under Post ID #2. |

---

## SECTION 1: DETAILED PAGE-BY-PAGE AUDIT (ALL 14 PAGES)

### PAGE 1: HOME PAGE

- **Public URLs:** `/` or `/index.php`
- **Routing Mechanism:** Handled by `.htaccess` DirectoryIndex and Apache rewrite rules, or `router.php` line 265 (`require __DIR__ . '/index.php';`).
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\index.php`](file:///c:/Movies/Internship/OrderPrintex/index.php)
- **Shared Includes:**
  - Header: [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php)
  - Navigation: [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php)
  - Footer: [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
  - Database & Helpers: [`includes/config.php`](file:///c:/Movies/Internship/OrderPrintex/includes/config.php), [`includes/database.php`](file:///c:/Movies/Internship/OrderPrintex/includes/database.php), [`includes/functions.php`](file:///c:/Movies/Internship/OrderPrintex/includes/functions.php), [`includes/catalog_helper.php`](file:///c:/Movies/Internship/OrderPrintex/includes/catalog_helper.php)
- **Stylesheets (CSS):**
  - [`assets/css/style.css`](file:///c:/Movies/Internship/OrderPrintex/assets/css/style.css) (Core layout, hero grid, typography, cards, buttons)
  - [`assets/css/animations.css`](file:///c:/Movies/Internship/OrderPrintex/assets/css/animations.css) (Scroll reveals, slide-up, marquee keyframes)
  - [`assets/css/responsive.css`](file:///c:/Movies/Internship/OrderPrintex/assets/css/responsive.css) (Mobile breakpoints: 1200px, 992px, 768px, 480px)
- **JavaScript Files:**
  - [`assets/js/main.js`](file:///c:/Movies/Internship/OrderPrintex/assets/js/main.js) (Navbar sticky scroll, mobile drawer, theme init)
  - [`assets/js/animations.js`](file:///c:/Movies/Internship/OrderPrintex/assets/js/animations.js) (IntersectionObserver scroll reveals)
  - [`assets/js/upload.js`](file:///c:/Movies/Internship/OrderPrintex/assets/js/upload.js) (Drag-and-drop dropzone, live square inches & price estimation)
  - [`assets/js/dynamic-other.js`](file:///c:/Movies/Internship/OrderPrintex/assets/js/dynamic-other.js) (Dynamic "Other" option handling)
- **Media & Images:**
  - Hero Photo: [`assets/images/dtf_fabric_print.jpg`](file:///c:/Movies/Internship/OrderPrintex/assets/images/dtf_fabric_print.jpg) (Commercial DTF peeling shot)
  - Brand Logo: [`assets/images/logo.png`](file:///c:/Movies/Internship/OrderPrintex/assets/images/logo.png)
  - Product & Service Thumbnails: `assets/images/uv_dtf_drinkware.jpg`, `custom_tshirt_apparel.jpg`, `corporate_swag_kit.jpg`, `packaging_box.jpg`
- **Database Tables Queried:**
  - `site_settings` (via `get_setting('hero_heading')`, `get_setting('hero_description')`)
  - `services` (`SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC`)
  - `products` & `categories` (via `get_catalog_products()`)
  - `gallery` (`SELECT * FROM gallery WHERE is_published = 1 ORDER BY display_order ASC LIMIT 6`)
  - `blog_posts` (`SELECT p.*, c.name as category_name FROM blog_posts p LEFT JOIN blog_categories c ... LIMIT 3`)
- **Admin CMS Availability:** **Partial**. Hero text can be modified via `admin/content.php` or `site_settings`; dynamic services, products, gallery, and blog cards update automatically when changed in their respective Admin CMS modules. All other structural section headings and badges are static in `index.php`.

#### Content Editing Breakdown (Home Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "PRINTEX — Industrial DTF & UV DTF Manufacturer & Bulk Supplier in India" | `index.php` | Lines 8–9 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `index.php`. |
| Hero Eyebrow Pill | "★ India's Direct-to-Film & UV DTF Specialists" | `index.php` | Lines 48–51 | Static PHP/HTML | Edit text inside `.hero-badge-pill` in `index.php`. |
| Hero Main Heading | "Precision Print Transfers for Garments & Hard Surfaces." | `index.php` | Lines 35, 53–55 | Database / Admin CMS with Static Fallback | Edit in **Admin Panel -> Content (`admin/content.php`)** under "Hero Heading", or directly in `index.php` line 35 fallback. |
| Hero Description Subtitle | "Manufacturer & Bulk Supplier of DTF and UV DTF in India. We engineer press-ready transfers..." | `index.php` | Lines 36, 57–59 | Database / Admin CMS with Static Fallback | Edit in **Admin Panel -> Content (`admin/content.php`)** under "Hero Description", or in `index.php` line 36 fallback. |
| Hero Primary Button | "Upload Your Design" -> `#upload-studio` | `index.php` | Lines 63–68 | Static PHP/HTML | Edit text and `href` in `index.php`. |
| Hero Secondary Button | "Explore Products" -> `products/` | `index.php` | Lines 69–74 | Static PHP/HTML | Edit text and `href` in `index.php`. |
| Hero Highlight Badge 1 | "Pre-Cured & ICC Calibrated" / "300 DPI high-res output guarantee" | `index.php` | Lines 80–87 | Static PHP/HTML | Edit title and subtitle in `index.php`. |
| Hero Highlight Badge 2 | "Fast Pan-India Delivery" / "1–3 business days express dispatch" | `index.php` | Lines 89–98 | Static PHP/HTML | Edit title and subtitle in `index.php`. |
| Hero Studio Quality Card | "Commercial Grade" / "50+ Wash Cycles • Hot/Cold Instant Peel • Polyurethane TPU Adhesive" | `index.php` | Lines 111–115 | Static PHP/HTML | Edit inside `.floating-badge` in `index.php`. |
| Hero Photograph | Peeling DTF Transfer on Black Cotton T-Shirt | `assets/images/dtf_fabric_print.jpg` | Line 108 | Image/media file | Replace the file `assets/images/dtf_fabric_print.jpg` or change image path in `index.php` line 108. |
| Continuous Marquee Ticker | "DTF Transfer Printing • UV DTF Decals & Stickers • Custom T-Shirt Printing..." | `index.php` | Lines 124–143 | Static PHP/HTML | Edit marquee item text strings in `index.php` lines 126–134 and 136–143. |
| Prepress Studio Eyebrow | "Prepress Production Studio" | `index.php` | Line 149 | Static PHP/HTML | Edit in `index.php`. |
| Prepress Studio Heading | "Upload Your Artwork for an Itemized Quotation" | `index.php` | Line 150 | Static PHP/HTML | Edit in `index.php`. |
| Prepress Studio Description | "Our prepress technicians inspect transparency, resolution (300 DPI)..." | `index.php` | Lines 151–153 | Static PHP/HTML | Edit in `index.php`. |
| Dropzone Drag/Drop Text | "Drag & drop artwork here, or Browse Files" | `index.php` | Line 179 | Static PHP/HTML | Edit in `index.php`. |
| Dropzone Format Hint | "Supported: PNG, JPG, PDF, AI, PSD, SVG (Max 25 MB)..." | `index.php` | Line 180 | Static PHP/HTML | Edit in `index.php`. |
| Quotation Form Fields | Service, Quantity, Dimensions, Substrate, Name, Email, Phone, Company, Notes | `index.php` | Lines 195–252 | Static PHP/HTML | Edit field labels and placeholders in `index.php`. |
| Products Section Header | "Production Catalog" / "Popular Printing Products" | `index.php` | Lines 277–282 | Static PHP/HTML | Edit in `index.php`. |
| Products Grid Items | Title, SKU, starting price, specifications, badge, image | `includes/catalog_helper.php` / Database table `products` | Lines 285–385 | Database / Admin CMS | Edit existing products or add new ones in **Admin Panel -> Products (`admin/products.php`)**. |
| Services Section Header | "Industrial Print Capabilities" / "Printing Services Overview" | `index.php` | Lines 407–412 | Static PHP/HTML | Edit in `index.php`. |
| Services Grid Cards | DTF, UV DTF, T-Shirt, Corporate Printing cards | Database table `services` | Lines 415–475 | Database / Admin CMS | Edit service title, badge, tagline, full description, and image in **Admin Panel -> Printing Services (`admin/services.php`)**. |
| Why OrderPrintex Header | "Direct Delhi Production Advantage" / "Why OrderPrintex?" | `index.php` | Lines 492–497 | Static PHP/HTML | Edit in `index.php`. |
| Why Choose Us 4 Pillars | 1. Industrial Machinery, 2. Prepress Proofing, 3. Wholesale Tiered Rates, 4. Fast Pan-India Dispatch | `index.php` | Lines 500–533 | Static PHP/HTML | Edit titles, SVGs, and paragraphs directly in `index.php` (or sync with `admin/content.php` keys `why_pillar_1_title` etc.). |
| Portfolio Gallery Header | "Commercial Showcase" / "Selected Production Specimens" | `index.php` | Lines 541–546 | Static PHP/HTML | Edit in `index.php`. |
| Gallery Grid Cards | High-Detail DTF Apparel, UV DTF Decals, Streetwear Graphic Tee... | Database table `gallery` | Lines 548–593 | Database / Admin CMS | Edit or upload portfolio projects in **Admin Panel -> Portfolio (`admin/gallery.php`)**. |
| Forward Workflow Header | "Transparent Production" / "Forward Workflow" | `index.php` | Lines 608–613 | Static PHP/HTML | Edit in `index.php`. |
| Workflow 3 Steps | 1. Submit Specs & Artwork, 2. Prepress Check & Binding Quote, 3. Production & Fast Tracked Dispatch | `index.php` | Lines 616–649 | Static PHP/HTML | Edit step numbers, titles, and descriptions in `index.php`. |
| Blog Section Header | "Knowledge Base & Advice" / "Latest Printing Guides & Insights" | `index.php` | Lines 660–665 | Static PHP/HTML | Edit in `index.php`. |
| Blog Cards Loop | Article Title, Category, Reading Time, Excerpt, Date, Image | Database table `blog_posts` | Lines 668–735 | Database / Admin CMS | Edit or publish articles in **Admin Panel -> Blog (`admin/blog.php`)**. |
| Final Bottom CTA Banner | "Transform Your Designs into Industrial-Grade Reality." / "Upload Your Artwork Now" | `index.php` | Lines 755–778 | Static PHP/HTML | Edit banner title, subtitle, and CTA button in `index.php`. |

---

### PAGE 2: ABOUT PAGE

- **Public URLs:** `/about/` or `/about.php`
- **Routing Mechanism:** Routed via `.htaccess` (`RewriteRule ^about/?$ about.php [L,QSA]`) or `router.php` line 249 (`require __DIR__ . '/about.php';`).
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\about.php`](file:///c:/Movies/Internship/OrderPrintex/about.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`
- **Media & Images:**
  - Technology Images: `assets/images/dtf_fabric_print.jpg`, `streetwear_tee.jpg`, `uv_dtf_drinkware.jpg`, `custom_stickers.jpg`, `custom_tshirt_apparel.jpg`, `branding_stationery.jpg`
- **Database Tables Queried:**
  - `equipment_tools` (`SELECT * FROM equipment_tools ORDER BY display_order ASC`) with fallback to `$tools` array in lines 23–68.
- **Admin CMS Availability:** **Partial**. Technical equipment records are managed in **Admin Panel -> Announcement & Equipment (`admin/announcement.php`)**. All company narrative, milestone numbers, Why Work With Us pillars, and studio address CTA are maintained in `about.php`.

#### Content Editing Breakdown (About Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "About Printex \| Your Go To DTF & T-Shirt Printing Studio in Delhi Since 2010" | `about.php` | Lines 9–10 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `about.php`. |
| Hero Eyebrow Pill | "DELHI Printing Studio • Since 2010" | `about.php` | Lines 163–166 | Static PHP/HTML | Edit text inside `.page-intro-eyebrow` in `about.php`. |
| Hero Main Headline | "DTF & T-Shirt Printing in Delhi Done Properly." | `about.php` | Lines 169–171 | Static PHP/HTML | Edit inside `.page-intro-title` in `about.php`. |
| Hero Description Paragraph | "Printex has been around since 2010, a Delhi printing studio that's been churning out top quality DTF..." | `about.php` | Lines 173–175 | Static PHP/HTML | Edit inside `.page-intro-desc` in `about.php`. |
| Hero CTA Buttons | "Get a Quick Quote" -> `get-quote.php`, "Explore Services" -> `services.php` | `about.php` | Lines 187–196 | Static PHP/HTML | Edit button text and links in `about.php`. |
| Story Section Eyebrow & H2 | "About Our Delhi Studio" / "Who We Are & What We Do" | `about.php` | Lines 220–223 | Static PHP/HTML | Edit in `about.php`. |
| Story Paragraphs (3 Blocks) | Narrative regarding 14+ years, Pankha Road studio, B2B wholesale capabilities | `about.php` | Lines 225–248 | Static PHP/HTML | Edit paragraph text directly in `about.php`. |
| 4 Milestone Stat Cards | 1. "14+ Years in Printing", 2. "10,000+ Orders Dispatched", 3. "300 DPI Prepress Standard", 4. "1-3 Days Fast Dispatch" | `about.php` | Lines 255–285 | Static PHP/HTML | Edit numeric values and labels in `about.php`. |
| Technology Showcase Header | "What We Print With" / "Printing Technology We Use Every Day" | `about.php` | Lines 297–303 | Static PHP/HTML | Edit in `about.php`. |
| 6 Technology Machinery Cards | 1. DTF Garment Printing, 2. Powdering & Curing, 3. UV DTF Decals, 4. Precise Cutting, 5. Heat Pressing, 6. Prepress Check | `about.php` / Table `equipment_tools` | Lines 71–152, 307–365 | Database / Admin CMS with PHP Array fallback | Edit in **Admin Panel -> Announcement & Equipment (`admin/announcement.php`)** under "Studio Equipment & Technical Tools", or update `$techMeta` array in `about.php`. |
| Why Choose Us Header | "Why Work With Us" / "Why Delhi Brands & Print Shops Work With Printex" | `about.php` | Lines 375–381 | Static PHP/HTML | Edit in `about.php`. |
| Why Choose Us 4 Pillars | 1. Free File Checks, 2. Fast Turnaround, 3. Transparent Pricing, 4. Proper Curing & Quality | `about.php` | Lines 386–436 | Static PHP/HTML | Edit pillar titles and explanatory bullet points in `about.php`. |
| Studio Visit Section | "Come Visit Our Delhi Studio" / Address, Timings, Maps Embed Link | `about.php` | Lines 446–496 | Static PHP/HTML + `site_settings` | Edit intro in `about.php`; update studio address & phone in **Admin Panel -> Settings (`admin/settings.php`)**. |
| Bottom CTA Banner | "Got a Print Job in Mind?" / "Get a Quick Quote ->" | `about.php` | Lines 504–525 | Static PHP/HTML | Edit heading, paragraph, and CTA link in `about.php`. |

---

### PAGE 3: SERVICES OVERVIEW PAGE

- **Public URLs:** `/services/` or `/services.php`
- **Routing Mechanism:** Handled by `.htaccess` (`RewriteRule ^services/?$ services.php [L,QSA]`) or `router.php` line 235 (`require __DIR__ . '/services.php';`).
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\services.php`](file:///c:/Movies/Internship/OrderPrintex/services.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`
- **Media & Images:**
  - Service Images: `assets/images/dtf_fabric_print.jpg`, `uv_dtf_drinkware.jpg`, `custom_tshirt_apparel.jpg`, `corporate_swag_kit.jpg`
- **Database Tables Queried:**
  - `services` (`SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC`)
- **Admin CMS Availability:** **Yes for all service cards**. Managed in **Admin Panel -> Printing Services (`admin/services.php`)**. The top intro banner and bottom upload CTA are static in `services.php`.

#### Content Editing Breakdown (Services Overview Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "Printing Services in Delhi \| DTF, UV DTF, T-Shirt \| Printex" | `services.php` | Lines 6–7 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `services.php`. |
| Page Eyebrow | "Our Printing Services in Delhi" | `services.php` | Line 62 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `services.php`. |
| Page Main Heading (H1) | "DTF, UV DTF & T-Shirt Printing Services in Delhi" | `services.php` | Line 64 | Static PHP/HTML | Edit inside `.page-intro-title` in `services.php`. |
| Page Description | "At Printex we print DTF transfers, UV DTF stickers, t-shirts, hoodies and mugs from our studio in Pankha Road..." | `services.php` | Lines 66–68 | Static PHP/HTML | Edit inside `.page-intro-desc` in `services.php`. |
| Service Card 1 (DTF Printing) | Badge, Title, Hero Tagline, Full Description, Specs Box, Image | Database table `services` (ID #1, slug `dtf-printing`) | Lines 81–110 (rendered dynamically) | Database / Admin CMS | Edit in **Admin Panel -> Printing Services (`admin/services.php`)** -> Edit "DTF Printing". |
| Service Card 2 (UV DTF Printing) | Badge, Title, Hero Tagline, Full Description, Specs Box, Image | Database table `services` (ID #2, slug `uv-dtf-printing`) | Lines 81–110 (rendered dynamically) | Database / Admin CMS | Edit in **Admin Panel -> Printing Services (`admin/services.php`)** -> Edit "UV DTF Printing". |
| Service Card 3 (T-Shirt Printing) | Badge, Title, Hero Tagline, Full Description, Specs Box, Image | Database table `services` (ID #3, slug `t-shirt-printing`) | Lines 81–110 (rendered dynamically) | Database / Admin CMS | Edit in **Admin Panel -> Printing Services (`admin/services.php`)** -> Edit "T-Shirt Printing". |
| Service Card 4 (Corporate Printing) | Badge, Title, Hero Tagline, Full Description, Specs Box, Image | Database table `services` (ID #4, slug `corporate-printing`) | Lines 81–110 (rendered dynamically) | Database / Admin CMS | Edit in **Admin Panel -> Printing Services (`admin/services.php`)** -> Edit "Corporate Printing". |
| Service Action Buttons | "Know More ->" (links to `/services/{slug}/`) and "Get a Quote" (links to `get-quote.php?service_id={id}`) | `services.php` | Lines 98–103 | Static PHP/HTML | Edit button text or link format in `services.php`. |
| Direct Upload Prompt Header | "Ready to Produce?" / "Send Us Your Artwork for Free Prepress File Analysis" | `services.php` | Lines 121–123 | Static PHP/HTML | Edit in `services.php`. |
| Direct Upload Prompt Subtitle | "Upload your artwork now. Our prepress team will check color trapping, resolution..." | `services.php` | Lines 124–126 | Static PHP/HTML | Edit in `services.php`. |
| Direct Upload Button | "Upload Artwork for Instant Quote ->" -> `get-quote.php` | `services.php` | Lines 127–129 | Static PHP/HTML | Edit text and link in `services.php`. |

---

### PAGE 4: DTF PRINTING SERVICE PAGE

- **Public URLs:** `/services/dtf-printing/` or `/services/dtf-printing.php` (User shorthand: `/dtf-printing/`)
- **Routing Mechanism:** Routed via `.htaccess` (`RewriteRule ^services/([a-zA-Z0-9_-]+)/?$ services/$1.php [L,QSA]`) or `router.php` lines 228–232.
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\services\dtf-printing.php`](file:///c:/Movies/Internship/OrderPrintex/services/dtf-printing.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`, `upload.js`, `dynamic-other.js`
- **Media & Images:** `assets/images/dtf_fabric_print.jpg`
- **Database Tables Queried:** `services` (`SELECT * FROM services WHERE slug = 'dtf-printing'`)
- **Admin CMS Availability:** **No for full page layout**. The specific detailed copy, comparison paragraphs, 4 technical parameter cards, and dropzone form on this page are hardcoded in `services/dtf-printing.php`.

#### Content Editing Breakdown (DTF Printing Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "DTF Printing in India — Direct-to-Film Transfers for Garments \| OrderPrintex" | `services/dtf-printing.php` | Lines 6–7 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `services/dtf-printing.php`. |
| Page Eyebrow | "Apparel & Textile Innovation" | `services/dtf-printing.php` | Line 17 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `services/dtf-printing.php`. |
| Page Main Heading (H1) | "Commercial DTF Printing (Direct-to-Film)" | `services/dtf-printing.php` | Line 19 | Static PHP/HTML | Edit inside `.page-intro-title` in `services/dtf-printing.php`. |
| Page Subtitle Description | "High-density white underbase, vibrant textile pigments, and stretchable TPU adhesive..." | `services/dtf-printing.php` | Lines 21–23 | Static PHP/HTML | Edit inside `.page-intro-desc` in `services/dtf-printing.php`. |
| Overview Section Eyebrow & H2 | "Garment Transfer Engineering" / "Why OrderPrintex DTF Outperforms Traditional Screen & Vinyl" | `services/dtf-printing.php` | Lines 34–35 | Static PHP/HTML | Edit in `services/dtf-printing.php`. |
| Technology Comparison (3 Paragraphs) | Explanations of photorealistic gradients, TPU powder adhesion, and zero weeding process | `services/dtf-printing.php` | Lines 36–45 | Static PHP/HTML | Edit paragraphs directly in `services/dtf-printing.php`. |
| Overview CTA Buttons | "Upload Design for DTF Quote" -> `#upload-dtf`, "View Gang Sheet Options" -> `products.php` | `services/dtf-printing.php` | Lines 47–50 | Static PHP/HTML | Edit in `services/dtf-printing.php`. |
| Overview Media Image | `assets/images/dtf_fabric_print.jpg` (alt: "DTF Transfer Print on Dark Fabric") | `services/dtf-printing.php` | Line 55 | Image/media file | Replace image file or change path in `services/dtf-printing.php`. |
| Technical Specs Section Header | "Technical Parameters" / "DTF Press & Material Specifications" | `services/dtf-printing.php` | Lines 65–69 | Static PHP/HTML | Edit in `services/dtf-printing.php`. |
| Technical Parameter 1 | "Press Temperature" / "160°C / 320°F calibrated heat application" | `services/dtf-printing.php` | Lines 73–76 | Static PHP/HTML | Edit in `services/dtf-printing.php`. |
| Technical Parameter 2 | "Press Duration" / "15 Seconds Firm Pressure (4–5 bar)" | `services/dtf-printing.php` | Lines 78–81 | Static PHP/HTML | Edit in `services/dtf-printing.php`. |
| Technical Parameter 3 | "Peel Profile" / "Instant Cold Peel Release (matte transfer)" | `services/dtf-printing.php` | Lines 83–86 | Static PHP/HTML | Edit in `services/dtf-printing.php`. |
| Technical Parameter 4 | "Wash Longevity" / "50+ Domestic Wash Cycles (no cracking/fading)" | `services/dtf-printing.php` | Lines 88–91 | Static PHP/HTML | Edit in `services/dtf-printing.php`. |
| On-Page Upload Workbench | "Direct DTF Gang Sheet & Artwork Upload" / Dropzone & Form | `services/dtf-printing.php` | Lines 102–175 | Static PHP/HTML | Edit form labels, instructions, and hints in `services/dtf-printing.php`. |

---

### PAGE 5: UV DTF PRINTING SERVICE PAGE

- **Public URLs:** `/services/uv-dtf-printing/` or `/services/uv-dtf-printing.php` (User shorthand: `/uv-dtf-printing/`)
- **Routing Mechanism:** Routed via `.htaccess` or `router.php` lines 228–232.
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\services\uv-dtf-printing.php`](file:///c:/Movies/Internship/OrderPrintex/services/uv-dtf-printing.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`, `upload.js`, `dynamic-other.js`
- **Media & Images:** `assets/images/uv_dtf_drinkware.jpg`
- **Database Tables Queried:** `services` (`SELECT * FROM services WHERE slug = 'uv-dtf-printing'`)
- **Admin CMS Availability:** **No for full page layout**. The rich technical content is maintained directly in `services/uv-dtf-printing.php`.

#### Content Editing Breakdown (UV DTF Printing Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "UV DTF Printing in Delhi, India — Cold Decals for Hard Surfaces \| OrderPrintex" | `services/uv-dtf-printing.php` | Lines 6–7 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `services/uv-dtf-printing.php`. |
| Page Eyebrow | "Hard-Surface Decal Innovation" | `services/uv-dtf-printing.php` | Line 17 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `services/uv-dtf-printing.php`. |
| Page Main Heading (H1) | "Industrial UV DTF Printing in Delhi" | `services/uv-dtf-printing.php` | Line 19 | Static PHP/HTML | Edit inside `.page-intro-title` in `services/uv-dtf-printing.php`. |
| Page Subtitle Description | "Instant transfer stickers with 3D embossed tactile varnish and industrial adhesion..." | `services/uv-dtf-printing.php` | Lines 21–23 | Static PHP/HTML | Edit inside `.page-intro-desc` in `services/uv-dtf-printing.php`. |
| Overview Section Eyebrow & H2 | "Surface Printing Without Limits" / "Transform Any Rigid or Curved Surface Instantly" | `services/uv-dtf-printing.php` | Lines 34–35 | Static PHP/HTML | Edit in `services/uv-dtf-printing.php`. |
| Technical Description (3 Paragraphs) | Details on 3 synchronized layers (White + CMYK + Gloss UV Varnish), AB adhesive film, and peel-and-stick application | `services/uv-dtf-printing.php` | Lines 36–45 | Static PHP/HTML | Edit in `services/uv-dtf-printing.php`. |
| Overview CTA Buttons | "Upload Artwork for UV Decal Quote" -> `#upload-uv`, "View Sticker Products" -> `products.php` | `services/uv-dtf-printing.php` | Lines 47–50 | Static PHP/HTML | Edit in `services/uv-dtf-printing.php`. |
| Overview Media Image | `assets/images/uv_dtf_drinkware.jpg` (alt: "UV DTF Decals on Drinkware") | `services/uv-dtf-printing.php` | Line 55 | Image/media file | Replace image file or change path in `services/uv-dtf-printing.php`. |
| Specs Section Header | "Technical Parameters" / "UV DTF Performance Specifications" | `services/uv-dtf-printing.php` | Lines 65–69 | Static PHP/HTML | Edit in `services/uv-dtf-printing.php`. |
| 4 Performance Parameter Cards | 1. Optical Resolution (2880 DPI Micro-Drop), 2. Layer Composition (White + CMYK + Gloss), 3. Substrates Bonded (Glass, Acrylic, SS, Plastic), 4. Tactile Varnish (Raised 3D feel) | `services/uv-dtf-printing.php` | Lines 72–93 | Static PHP/HTML | Edit values and descriptions in `services/uv-dtf-printing.php`. |
| On-Page Upload Workbench | "Direct UV DTF Decal Artwork Upload" / Dropzone & Form | `services/uv-dtf-printing.php` | Lines 98–170 | Static PHP/HTML | Edit form labels, instructions, and hints in `services/uv-dtf-printing.php`. |

---

### PAGE 6: T-SHIRT PRINTING SERVICE PAGE

- **Public URLs:** `/services/t-shirt-printing/` or `/services/t-shirt-printing.php` (User shorthand: `/t-shirt-printing/`)
- **Routing Mechanism:** Routed via `.htaccess` or `router.php` lines 228–232.
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\services\t-shirt-printing.php`](file:///c:/Movies/Internship/OrderPrintex/services/t-shirt-printing.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`, `upload.js`, `dynamic-other.js`
- **Media & Images:** `assets/images/custom_tshirt_apparel.jpg`
- **Database Tables Queried:** `services` (`SELECT * FROM services WHERE slug = 't-shirt-printing'`)
- **Admin CMS Availability:** **No for full page layout**. Maintained directly in `services/t-shirt-printing.php`.

#### Content Editing Breakdown (T-Shirt Printing Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "Custom T-Shirt Printing in Delhi, India — Cotton & Poly Blends \| OrderPrintex" | `services/t-shirt-printing.php` | Lines 6–7 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `services/t-shirt-printing.php`. |
| Page Eyebrow | "Apparel Production & Merchandise" | `services/t-shirt-printing.php` | Line 17 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `services/t-shirt-printing.php`. |
| Page Main Heading (H1) | "Custom T-Shirt Printing Studio" | `services/t-shirt-printing.php` | Line 19 | Static PHP/HTML | Edit inside `.page-intro-title` in `services/t-shirt-printing.php`. |
| Page Subtitle Description | "From prototype brand samples to bulk corporate runs, we print high-density graphics onto 180–240 GSM bio-washed..." | `services/t-shirt-printing.php` | Lines 21–23 | Static PHP/HTML | Edit inside `.page-intro-desc` in `services/t-shirt-printing.php`. |
| Overview Section Eyebrow & H2 | "Garment Quality Standards" / "Retail-Grade Printing on Premium Bio-Washed Fabrics" | `services/t-shirt-printing.php` | Lines 34–35 | Static PHP/HTML | Edit in `services/t-shirt-printing.php`. |
| Garment Narrative (3 Paragraphs) | Details on pre-shrunk combed cotton, grade 4+ color wash fastness, and 10 to 5,000 piece production capabilities | `services/t-shirt-printing.php` | Lines 36–45 | Static PHP/HTML | Edit in `services/t-shirt-printing.php`. |
| Overview CTA Buttons | "Upload Artwork for T-Shirt Quote" -> `#upload-tshirt`, "Explore Garment Catalog" -> `products.php` | `services/t-shirt-printing.php` | Lines 47–50 | Static PHP/HTML | Edit in `services/t-shirt-printing.php`. |
| Overview Media Image | `assets/images/custom_tshirt_apparel.jpg` | `services/t-shirt-printing.php` | Line 55 | Image/media file | Replace image file or change path in `services/t-shirt-printing.php`. |
| Specs Section Header | "Fabric & Silhouette Specs" / "Garment Manufacturing Parameters" | `services/t-shirt-printing.php` | Lines 65–69 | Static PHP/HTML | Edit in `services/t-shirt-printing.php`. |
| 4 Manufacturing Parameter Cards | 1. Available Fabrics (100% Combed Cotton, Dry-Fit, Poly-Spandex), 2. Silhouette Profiles (Oversized, Regular, Polo), 3. Size Spectrum (XS to 3XL), 4. Print Placements (Chest, Back, Sleeve, Neck Label) | `services/t-shirt-printing.php` | Lines 72–93 | Static PHP/HTML | Edit parameter titles and bullet items in `services/t-shirt-printing.php`. |
| On-Page Upload Workbench | "Direct T-Shirt Production Artwork Upload" / Dropzone & Form | `services/t-shirt-printing.php` | Lines 98–170 | Static PHP/HTML | Edit form labels, instructions, and hints in `services/t-shirt-printing.php`. |

---

### PAGE 7: CORPORATE PRINTING SERVICE PAGE

- **Public URLs:** `/services/corporate-printing/` or `/services/corporate-printing.php` (User shorthand: `/corporate-printing/`)
- **Routing Mechanism:** Routed via `.htaccess` or `router.php` lines 228–232.
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\services\corporate-printing.php`](file:///c:/Movies/Internship/OrderPrintex/services/corporate-printing.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`, `upload.js`, `dynamic-other.js`
- **Media & Images:** `assets/images/corporate_swag_kit.jpg`
- **Database Tables Queried:** `services` (`SELECT * FROM services WHERE slug = 'corporate-printing'`)
- **Admin CMS Availability:** **No for full page layout**. Maintained directly in `services/corporate-printing.php`.

#### Content Editing Breakdown (Corporate Printing Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "Corporate Printing & Promotional Merchandise in Delhi, India \| OrderPrintex" | `services/corporate-printing.php` | Lines 6–7 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `services/corporate-printing.php`. |
| Page Eyebrow | "B2B Promotional Solutions" | `services/corporate-printing.php` | Line 17 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `services/corporate-printing.php`. |
| Page Main Heading (H1) | "Corporate Printing & Custom Merchandise" | `services/corporate-printing.php` | Line 19 | Static PHP/HTML | Edit inside `.page-intro-title` in `services/corporate-printing.php`. |
| Page Subtitle Description | "Elevate your company's physical brand presence. We design and manufacture executive welcome kits..." | `services/corporate-printing.php` | Lines 21–23 | Static PHP/HTML | Edit inside `.page-intro-desc` in `services/corporate-printing.php`. |
| Overview Section Eyebrow & H2 | "End-to-End B2B Production" / "Unified Brand Identity Across Every Corporate Touchpoint" | `services/corporate-printing.php` | Lines 34–35 | Static PHP/HTML | Edit in `services/corporate-printing.php`. |
| Corporate Narrative (3 Paragraphs) | Pantone color matching, executive onboarding gift bundles, and GST invoicing | `services/corporate-printing.php` | Lines 36–45 | Static PHP/HTML | Edit in `services/corporate-printing.php`. |
| Overview CTA Buttons | "Upload Artwork for Corporate Quote" -> `#upload-corporate`, "View Merchandise Catalog" -> `products.php` | `services/corporate-printing.php` | Lines 47–50 | Static PHP/HTML | Edit in `services/corporate-printing.php`. |
| Overview Media Image | `assets/images/corporate_swag_kit.jpg` | `services/corporate-printing.php` | Line 55 | Image/media file | Replace image file or change path in `services/corporate-printing.php`. |
| Specs Section Header | "Commercial Merchandising Parameters" / "B2B Production Specifications" | `services/corporate-printing.php` | Lines 65–69 | Static PHP/HTML | Edit in `services/corporate-printing.php`. |
| 4 B2B Parameter Cards | 1. Available Substrates (Ceramics, Stainless Steel, PU Leather, Textiles), 2. Branding Technologies (UV DTF, Laser Etch, Silkscreen), 3. Minimum Orders (Low MOQs on onboarding bundles), 4. Invoicing & Logistics (GST tax invoices, express shipping) | `services/corporate-printing.php` | Lines 72–93 | Static PHP/HTML | Edit parameter descriptions in `services/corporate-printing.php`. |
| On-Page Upload Workbench | "Direct Corporate Merchandise Artwork Upload" / Dropzone & Form | `services/corporate-printing.php` | Lines 98–170 | Static PHP/HTML | Edit form labels, instructions, and hints in `services/corporate-printing.php`. |

---

### PAGE 8: PRODUCTS CATALOG PAGE

- **Public URLs:** `/products/` or `/products.php` (Category filter: `/product/category/{slug}/`)
- **Routing Mechanism:** Routed via `.htaccess` (`RewriteRule ^products/?$ products.php [L,QSA]`) or `router.php` line 175 (`require __DIR__ . '/products.php';`).
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\products.php`](file:///c:/Movies/Internship/OrderPrintex/products.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php), [`includes/catalog_helper.php`](file:///c:/Movies/Internship/OrderPrintex/includes/catalog_helper.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`
- **Database Tables Queried:**
  - `products` (`SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1`)
  - `categories` (`SELECT * FROM categories WHERE slug = :s`)
- **Admin CMS Availability:** **Yes for all products and categories**. Managed in:
  - **Admin Panel -> Products (`admin/products.php`)**
  - **Admin Panel -> Categories (`admin/categories.php`)**
  - Fallback curated items are defined in `includes/catalog_helper.php`.

#### Content Editing Breakdown (Products Catalog Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| Default Catalog SEO Title | "Commercial Print Products & Transfers Catalog \| OrderPrintex Delhi" | `products.php` | Line 66 | Static PHP | Edit in `products.php`. |
| Default Catalog SEO Desc | "Explore OrderPrintex's commercial catalog: 22-inch DTF gang sheets, UV DTF decal transfers..." | `products.php` | Line 67 | Static PHP | Edit in `products.php`. |
| Category Filter Dynamic Title | `"{Category Name} | Industrial Transfers & Printing Catalog | OrderPrintex"` | `products.php` | Line 60 | Database / Dynamic | Updates automatically based on active category in **Admin Panel -> Categories (`admin/categories.php`)**. |
| Page Eyebrow | "Commercial Products Catalog" | `products.php` | Line 81 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `products.php`. |
| Page Main Heading (H1) | "Industrial Transfers, Garments & Merchandise" | `products.php` | Line 83 | Static PHP/HTML | Edit inside `.page-intro-title` in `products.php`. |
| Page Subtitle Description | "Precision-engineered DTF gang rolls, multi-surface UV decals, combed cotton garments..." | `products.php` | Lines 85–87 | Static PHP/HTML | Edit inside `.page-intro-desc` in `products.php`. |
| Category Filter Tabs | "All Products", "Printing Materials", "Custom Stickers", "Custom T-Shirts", "Corporate Merchandise", "Packaging", "Custom Branding Products" | `includes/catalog_helper.php` / Table `categories` | Lines 103–120 | Database / Admin CMS with Fallback in `includes/catalog_helper.php` | Add/edit categories in **Admin Panel -> Categories (`admin/categories.php`)**, or edit `get_curated_catalog_categories()` in `includes/catalog_helper.php`. |
| Live Search Input Placeholder | "Search products by name, substrate or specs..." | `products.php` | Line 129 | Static PHP/HTML | Edit placeholder attribute in `products.php`. |
| Product Cards in Grid | Product Title, Badge, Image, Category Name, Starting Price, Price Unit, Minimum Order, Short Description, Specs | Table `products` / Fallback in `includes/catalog_helper.php` | Lines 144–215 | Database / Admin CMS | Edit in **Admin Panel -> Products (`admin/products.php`)**. Select a product to change title, price, SKU, badge, description, specs, or images. |
| Product Card Action Buttons | "Configure Product" -> `/product/{slug}/`, "Direct Quote" -> `/get-quote/` | `products.php` | Lines 195–204 | Static PHP/HTML | Edit button labels in `products.php`. |
| Custom Specifications CTA | "Need a Non-Standard Substrate, Custom Size, or Bespoke Packaging?" / Buttons: "Upload Custom Artwork", "Speak with Prepress Engineer" | `products.php` | Lines 220–245 | Static PHP/HTML | Edit CTA heading, description, and button links in `products.php`. |

---

### PAGE 9: PORTFOLIO PAGE

- **Public URLs:** `/gallery/` or `/gallery.php`
- **Routing Mechanism:** Routed via `.htaccess` (`RewriteRule ^gallery/?$ gallery.php [L,QSA]`) or `router.php` line 254 (`require __DIR__ . '/gallery.php';`).
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\gallery.php`](file:///c:/Movies/Internship/OrderPrintex/gallery.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`
- **Media & Images:**
  - Showcase Images: `assets/images/dtf_fabric_print.jpg`, `uv_dtf_drinkware.jpg`, `streetwear_tee.jpg`, `packaging_box.jpg`, `custom_stickers.jpg`, `corporate_swag_kit.jpg`, `custom_tshirt_apparel.jpg`, `branding_stationery.jpg`
- **Database Tables Queried:**
  - `gallery` (`SELECT * FROM gallery WHERE is_published = 1 ORDER BY display_order ASC, id ASC`) with curated fallback array `$curatedGallery` in lines 57–205.
- **Admin CMS Availability:** **Yes for all portfolio items**. Managed in **Admin Panel -> Portfolio / Gallery (`admin/gallery.php` or `admin/portfolio.php`)**. Top banner copy and lightbox controls are static in `gallery.php`.

#### Content Editing Breakdown (Portfolio Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "Production Gallery & Print Showcase \| OrderPrintex Delhi" | `gallery.php` | Lines 6–7 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `gallery.php`. |
| Page Eyebrow | "Production Specimens & Quality Standards" | `gallery.php` | Line 216 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `gallery.php`. |
| Page Main Heading (H1) | "Industrial Print Production Gallery" | `gallery.php` | Line 218 | Static PHP/HTML | Edit inside `.page-intro-title` in `gallery.php`. |
| Page Subtitle Description | "Inspect high-resolution photographs of actual client production runs completed at our Delhi facility..." | `gallery.php` | Lines 220–222 | Static PHP/HTML | Edit inside `.page-intro-desc` in `gallery.php`. |
| Category Filter Tabs | "All Specimens", "DTF Printing", "UV DTF Decals", "Custom T-Shirts", "Corporate Merchandise", "Packaging & Boxes", "Custom Stickers", "Branding Materials" | `gallery.php` | Lines 13–21, 234–246 | Static PHP/HTML | Edit `$categoryNames` array in `gallery.php` lines 13–21. |
| Portfolio Project Cards | Project Title, Category Label, Thumbnail Image, Full-Resolution Image URL, Description, Specs Tag | Table `gallery` / Fallback array `$curatedGallery` | Lines 250–276 | Database / Admin CMS with Fallback in `gallery.php` | Add, edit, or delete projects in **Admin Panel -> Gallery (`admin/gallery.php`)**; or edit `$curatedGallery` array in `gallery.php` lines 57–205. |
| Spotlight Preview Panel | Selected Item Title, Resolution, Ink Layer, Substrate, Wash Durability, Action Buttons | `gallery.php` | Lines 282–330 | Static PHP/HTML + JS driven | Edit layout and labels in `gallery.php` lines 282–330. |
| Lightbox Fullscreen Modal | Zoom controls, close button, image target | `gallery.php` | Lines 332–348 | Static PHP/HTML | Edit in `gallery.php`. |

---

### PAGE 10: BLOG LISTING PAGE

- **Public URLs:** `/blog/` or `/blog.php` (Category filter: `/blog/category/{slug}/`)
- **Routing Mechanism:** Routed via `.htaccess` (`RewriteRule ^blog/?$ blog.php [L,QSA]`) or `router.php` lines 244–246.
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\blog.php`](file:///c:/Movies/Internship/OrderPrintex/blog.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`
- **Database Tables Queried:**
  - `blog_posts` (`SELECT p.*, c.name as category_name, c.slug as category_slug FROM blog_posts p LEFT JOIN blog_categories c ... WHERE p.status = 'published' ORDER BY p.id ASC`)
  - `blog_categories` (`SELECT * FROM blog_categories ORDER BY id ASC`)
- **Admin CMS Availability:** **Yes for all blog posts and categories**. Managed in **Admin Panel -> Blog (`admin/blog.php`)**. Top intro banner and bottom prepress CTA are static in `blog.php`.

#### Content Editing Breakdown (Blog Listing Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "Commercial Printing Guides & Insights \| OrderPrintex Delhi Blog" | `blog.php` | Lines 68–69 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `blog.php`. |
| Page Eyebrow | "Printing Knowledge Base" | `blog.php` | Line 80 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `blog.php`. |
| Page Main Heading (H1) | "Commercial Printing Guides & Insights" | `blog.php` | Line 82 | Static PHP/HTML | Edit inside `.page-intro-title` in `blog.php`. |
| Page Subtitle Description | "Explore expert prepress advice, DTF transfer parameters, paper selection frameworks..." | `blog.php` | Lines 84–86 | Static PHP/HTML | Edit inside `.page-intro-desc` in `blog.php`. |
| Category Filter Navigation | "All Topics" + dynamic categories from database | Table `blog_categories` | Lines 90–114 | Database / Admin CMS | Add or edit categories in **Admin Panel -> Blog (`admin/blog.php`)** under "Manage Categories". |
| Featured Hero Article Card | Large lead article card (first published post) | Table `blog_posts` (first record) | Lines 125–168 | Database / Admin CMS | Updates automatically to latest/first post. Edit content in **Admin Panel -> Blog (`admin/blog.php`)**. |
| Grid Article Cards | Article Title, Excerpt, Category Badge, Author, Read Time, Date, Featured Image | Table `blog_posts` | Lines 172–218 | Database / Admin CMS | Add or edit posts in **Admin Panel -> Blog (`admin/blog.php`)**. |
| Prepress Support CTA Banner | "Need Technical Prepress Support?" / "Have Questions About CMYK Profiles, Gang Sheet Efficiency..." / Button: "Talk to Our Prepress Engineers" | `blog.php` | Lines 223–238 | Static PHP/HTML | Edit heading, paragraph, and CTA button in `blog.php`. |

---

### PAGE 11: CONTACT PAGE

- **Public URLs:** `/contact/` or `/contact.php`
- **Routing Mechanism:** Routed via `.htaccess` (`RewriteRule ^contact/?$ contact.php [L,QSA]`) or `router.php` line 251 (`require __DIR__ . '/contact.php';`).
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\contact.php`](file:///c:/Movies/Internship/OrderPrintex/contact.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`
- **Database Tables Queried:**
  - `site_settings` (via `get_setting('phone')`, `get_setting('email')`, `get_setting('address')`, `get_setting('google_maps_url')`, `get_setting('whatsapp_url')`)
- **Admin CMS Availability:** **Yes for all contact parameters**. Managed in **Admin Panel -> Settings (`admin/settings.php`)**. Form labels, intro header, and map embed frame are static in `contact.php`.

#### Content Editing Breakdown (Contact Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "Contact OrderPrintex — Delhi Printing Studio & Manufacturing Unit" | `contact.php` | Lines 6–7 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `contact.php`. |
| Page Eyebrow | "Direct Connection" | `contact.php` | Line 57 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `contact.php`. |
| Page Main Heading (H1) | "Contact Our Delhi Production Studio" | `contact.php` | Line 59 | Static PHP/HTML | Edit inside `.page-intro-title` in `contact.php`. |
| Page Subtitle Description | "Whether you need prepress guidance, gang sheet quotations, sample swatches..." | `contact.php` | Lines 61–63 | Static PHP/HTML | Edit inside `.page-intro-desc` in `contact.php`. |
| Workshop Location Card | Studio Address: "Shop E-6, Chanakya Palace, Street No 21, Pankha Rd..." | Table `site_settings` (`key = 'address'`) | Line 81 | Database / Admin CMS | Edit in **Admin Panel -> Settings (`admin/settings.php`)** under "Studio Physical Address". |
| Direct Phone Card | Phone: "+91 93540 28199" | Table `site_settings` (`key = 'phone'`) | Line 102 | Database / Admin CMS | Edit in **Admin Panel -> Settings (`admin/settings.php`)** under "Studio Contact Phone". |
| WhatsApp Prepress Card | WhatsApp link & prompt: "+91 93540 28199" | Table `site_settings` (`key = 'whatsapp_url'`) | Line 122 | Database / Admin CMS | Edit in **Admin Panel -> Settings (`admin/settings.php`)** under "WhatsApp Number". |
| Operating Hours Card | "Monday – Saturday: 9:30 AM – 8:00 PM / Sunday: Prepress Emergency by Appointment" | `contact.php` | Lines 142–148 | Static PHP/HTML | Edit directly in `contact.php` lines 142–148. |
| Contact Form Title & Subtitle | "Send Us a Direct Message" / "Fill out this form and our technical desk will get back to you within 2 business hours." | `contact.php` | Lines 160–162 | Static PHP/HTML | Edit in `contact.php`. |
| Contact Form Field Labels | Full Name, Email Address, Contact Phone, Inquiry Subject, Message | `contact.php` | Lines 167–196 | Static PHP/HTML | Edit labels and placeholder text in `contact.php`. |
| Contact Form Action Handler | Saves message into table `enquiries` with prefix `OP-MSG` | `contact.php` | Lines 17–48 | Backend Logic | Submitted messages appear under **Admin Panel -> Customer Enquiries (`admin/enquiries.php`)**. |
| Google Maps Embed | Embedded interactive map iframe (`https://maps.google.com/maps?...`) | `contact.php` | Lines 212–224 | Static PHP/HTML | Update `src` attribute of `<iframe>` in `contact.php` line 214. |

---

### PAGE 12: CART / GET QUOTE PAGE

- **Public URLs:**
  - Shopping Cart: `/cart/` or `/cart.php` (Redirects to `/products/` as OrderPrintex operates as an itemized enquiry & quotation facility)
  - Dedicated Quote Studio: `/get-quote/` or `/get-quote.php`
- **Routing Mechanism:** Routed via `.htaccess` (`RewriteRule ^get-quote/?$ get-quote.php [L,QSA]`) or `router.php` line 256.
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\get-quote.php`](file:///c:/Movies/Internship/OrderPrintex/get-quote.php)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`, `upload.js`, `dynamic-other.js`
- **Database Tables Queried:**
  - `services` (`SELECT id, title, slug FROM services WHERE is_active = 1 ORDER BY display_order ASC`)
- **Admin CMS Availability:** **Partial**. Service options populate from table `services` via **Admin Panel -> Printing Services (`admin/services.php`)**. Form layout, dropzone instructions, price range estimator card, and guarantee badges are static in `get-quote.php`.

#### Content Editing Breakdown (Get Quote Page)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| SEO Title & Meta Description | "Get an Itemized Printing Quotation \| PRINTEX Studio" | `get-quote.php` | Lines 6–7 | Static PHP | Edit `$pageTitle` and `$pageDesc` in `get-quote.php`. |
| Page Eyebrow | "Transparent B2B Pricing" | `get-quote.php` | Line 34 | Static PHP/HTML | Edit inside `.page-intro-eyebrow` in `get-quote.php`. |
| Page Main Heading (H1) | "Request an Itemized Quotation" | `get-quote.php` | Line 36 | Static PHP/HTML | Edit inside `.page-intro-title` in `get-quote.php`. |
| Page Subtitle Description | "Submit your printing specifications and artwork file. Our prepress team evaluates printability..." | `get-quote.php` | Lines 38–40 | Static PHP/HTML | Edit inside `.page-intro-desc` in `get-quote.php`. |
| Dropzone Drag/Drop Text | "Drop your design file here, or Browse" | `get-quote.php` | Line 64 | Static PHP/HTML | Edit in `get-quote.php`. |
| Dropzone Accepted Files Hint | "Accepted: PNG (transparent), PDF, AI, PSD, SVG (Max 25 MB). 300 DPI recommended..." | `get-quote.php` | Line 65 | Static PHP/HTML | Edit in `get-quote.php`. |
| Dropzone Button | "Select Artwork File" | `get-quote.php` | Line 67 | Static PHP/HTML | Edit in `get-quote.php`. |
| Estimator Card Header | "Estimated Price Range & Footprint" | `get-quote.php` | Line 79 | Static PHP/HTML | Edit in `get-quote.php`. |
| Calculated Print Area Label | "Calculated Print Area:" | `get-quote.php` | Line 83 | Static PHP/HTML | Edit in `get-quote.php`. |
| Estimated Price Range Label | "Estimated Price Range:" | `get-quote.php` | Line 88 | Static PHP/HTML | Edit in `get-quote.php`. |
| Estimator Disclaimer | "Instant automated calculation based on selected service, quantity, and dimensions..." | `get-quote.php` | Lines 92–94 | Static PHP/HTML | Edit in `get-quote.php`. |
| Prepress Guarantee Box | "Prepress Quality Guarantee" / "All uploads are inspected by Delhi studio technicians before printing..." | `get-quote.php` | Lines 98–106 | Static PHP/HTML | Edit in `get-quote.php`. |
| Service Dropdown Label & Options | "Printing Service Required *" / DTF Printing, UV DTF Printing, T-Shirt, Corporate, + "Other (Specify Custom Service)" | `get-quote.php` / Table `services` | Lines 113–133 | Database / Admin CMS with Static "Other" | Manage primary options in **Admin Panel -> Printing Services (`admin/services.php`)**; edit "Other" attributes in `get-quote.php`. |
| Quantity Input Label | "Estimated Quantity / Sheets *" | `get-quote.php` | Line 138 | Static PHP/HTML | Edit in `get-quote.php`. |
| Width & Height Labels | "Width (Inches)", "Height / Length (Inches)" | `get-quote.php` | Lines 144, 149 | Static PHP/HTML | Edit in `get-quote.php`. |
| Substrate/Material Label & Hint | "Substrate / Garment / Finish Details" / "e.g. 100% Black Combed Cotton, Stainless Tumbler..." | `get-quote.php` | Lines 155–156 | Static PHP/HTML | Edit in `get-quote.php`. |
| Customer Information Labels | Contact Name, Email Address, Phone / WhatsApp, Company / Brand Name | `get-quote.php` | Lines 162–183 | Static PHP/HTML | Edit in `get-quote.php`. |
| Special Instructions Label & Placeholder | "Special Instructions or Custom Requirements" / "Mention turnaround deadline, specific PANTONE color codes..." | `get-quote.php` | Lines 188–189 | Static PHP/HTML | Edit in `get-quote.php`. |
| Submit Bar Trust Checklist | "✓ 24-hr Quote Turnaround • No upfront card required • Pan-India Dispatch" | `get-quote.php` | Lines 196–198 | Static PHP/HTML | Edit in `get-quote.php`. |
| Submit Button Label | "Submit Print Enquiry" | `get-quote.php` | Line 200 | Static PHP/HTML | Edit in `get-quote.php`. |

---

### PAGE 13: BLOG ARTICLE 1 (INDIVIDUAL DETAIL PAGE)

- **Public URLs:** `/blog/complete-guide-to-business-card-printing/` or `/blog-details.php?slug=complete-guide-to-business-card-printing`
- **Routing Mechanism:** Routed via `.htaccess` (`RewriteRule ^blog/([a-zA-Z0-9_-]+)/?$ blog-details.php?slug=$1 [L,QSA]`) or `router.php` line 241 (`require __DIR__ . '/blog-details.php';`).
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\blog-details.php`](file:///c:/Movies/Internship/OrderPrintex/blog-details.php) (Shared reusable template for all blog articles)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`
- **Database Tables Queried:**
  - `blog_posts` (Record ID #1: `WHERE p.slug = 'complete-guide-to-business-card-printing'`)
  - `blog_categories` (`JOIN on p.category_id = c.id`)
- **Admin CMS Availability:** **100% Editable via Admin Panel**. Manage in **Admin Panel -> Blog (`admin/blog.php`)** by clicking "Edit" on Post ID #1.

#### Content Editing Breakdown (Blog Article 1)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| Article Title (H1) | "Complete Guide to Business Card Printing: Paper Weights, Finishes & Design Tips" | Database table `blog_posts` (`id = 1`) | Line 106 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Edit Post #1 -> Field "Post Title". |
| Article Slug (URL) | `complete-guide-to-business-card-printing` | Database table `blog_posts` (`id = 1`) | Queried in line 31 | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "URL Slug". |
| SEO Meta Title | "Complete Guide to Business Card Printing \| OrderPrintex Delhi" | Database table `blog_posts` (`id = 1`) | Line 57 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "SEO Meta Title". |
| SEO Meta Description | "Master business card printing: 350 vs 400 GSM, matte vs velvet lamination, spot UV, and bleed guidelines from Delhi print experts." | Database table `blog_posts` (`id = 1`) | Line 58 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "SEO Meta Description". |
| Featured Hero Image | `assets/images/branding_stationery.jpg` | Database table `blog_posts` (`id = 1`) | Line 132 in `blog-details.php` | Image/media file via Database | Upload or change image in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "Featured Image". |
| Category Assignment | "Printing Materials" (category_id = 1) | Database table `blog_categories` | Line 96 in `blog-details.php` | Database / Admin CMS | Change dropdown in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "Category". |
| Article Excerpt | Short summary describing GSM weights and premium tactile finishes | Database table `blog_posts` (`id = 1`) | Line 110 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "Excerpt / Summary". |
| Main Article Body Content | Full multi-section formatted tutorial (GSM comparisons, lamination differences, spot UV instructions) | Database table `blog_posts` (`id = 1`, column `content`) | Line 145 in `blog-details.php` | Database / Admin CMS | Edit full HTML/text in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "Full Article Content". |
| Author Name & Read Time | "Prepress Engineering Team" / "6 min read" | Database table `blog_posts` (`id = 1`) | Lines 116–122 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)**. |
| Related Articles Widget | 3 related articles automatically fetched from same/other categories | Database table `blog_posts` | Lines 195–230 in `blog-details.php` | Database / Dynamic | Auto-generated by query in `blog-details.php` lines 46–54. |
| In-Article Prepress CTA Box | "Need Custom Business Card Printing?" / "Contact Our Delhi Studio" | `blog-details.php` | Lines 235–255 | Static PHP/HTML | Edit heading, text, and button link inside `blog-details.php` lines 235–255. |

---

### PAGE 14: BLOG ARTICLE 2 (INDIVIDUAL DETAIL PAGE)

- **Public URLs:** `/blog/dtf-printing-explained-guide/` or `/blog-details.php?slug=dtf-printing-explained-guide`
- **Routing Mechanism:** Routed via `.htaccess` or `router.php` line 241 (`require __DIR__ . '/blog-details.php';`).
- **Main PHP File:** [`c:\Movies\Internship\OrderPrintex\blog-details.php`](file:///c:/Movies/Internship/OrderPrintex/blog-details.php) (Shared reusable template with Article 1)
- **Shared Includes:** [`includes/header.php`](file:///c:/Movies/Internship/OrderPrintex/includes/header.php), [`includes/navbar.php`](file:///c:/Movies/Internship/OrderPrintex/includes/navbar.php), [`includes/footer.php`](file:///c:/Movies/Internship/OrderPrintex/includes/footer.php)
- **Stylesheets (CSS):** `assets/css/style.css`, `animations.css`, `responsive.css`
- **JavaScript Files:** `assets/js/main.js`, `animations.js`
- **Database Tables Queried:**
  - `blog_posts` (Record ID #2: `WHERE p.slug = 'dtf-printing-explained-guide'`)
  - `blog_categories` (`JOIN on p.category_id = c.id`)
- **Admin CMS Availability:** **100% Editable via Admin Panel**. Manage in **Admin Panel -> Blog (`admin/blog.php`)** by clicking "Edit" on Post ID #2.

#### Content Editing Breakdown (Blog Article 2)

| Content Element | Current Text/Content Location | Exact File | Line Number / Section | Content Source | How to Edit |
|---|---|---|---|---|---|
| Article Title (H1) | "DTF Printing Explained: Everything You Need to Know About Direct-to-Film Transfers" | Database table `blog_posts` (`id = 2`) | Line 106 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Edit Post #2 -> Field "Post Title". |
| Article Slug (URL) | `dtf-printing-explained-guide` | Database table `blog_posts` (`id = 2`) | Queried in line 31 | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "URL Slug". |
| SEO Meta Title | "DTF Printing Explained: Everything You Need to Know \| OrderPrintex" | Database table `blog_posts` (`id = 2`) | Line 57 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "SEO Meta Title". |
| SEO Meta Description | "Learn how DTF transfers work, pressing temperatures, TPU adhesive powder properties, and why DTF is ideal for streetwear labels." | Database table `blog_posts` (`id = 2`) | Line 58 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "SEO Meta Description". |
| Featured Hero Image | `assets/images/dtf_fabric_print.jpg` | Database table `blog_posts` (`id = 2`) | Line 132 in `blog-details.php` | Image/media file via Database | Upload or change image in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "Featured Image". |
| Category Assignment | "Garment Transfers" (category_id = 2) | Database table `blog_categories` | Line 96 in `blog-details.php` | Database / Admin CMS | Change dropdown in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "Category". |
| Article Excerpt | Summary of DTF process, PET release film, and heat press parameters | Database table `blog_posts` (`id = 2`) | Line 110 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "Excerpt / Summary". |
| Main Article Body Content | Technical guide to PET transfer films, TPU melt powders, heat pressing at 160°C, peeling guidelines | Database table `blog_posts` (`id = 2`, column `content`) | Line 145 in `blog-details.php` | Database / Admin CMS | Edit full HTML/text in **Admin Panel -> Blog (`admin/blog.php`)** -> Field "Full Article Content". |
| Author Name & Read Time | "Textile Print Specialist" / "8 min read" | Database table `blog_posts` (`id = 2`) | Lines 116–122 in `blog-details.php` | Database / Admin CMS | Edit in **Admin Panel -> Blog (`admin/blog.php`)**. |
| Related Articles Widget | 3 related articles automatically fetched from database | Database table `blog_posts` | Lines 195–230 in `blog-details.php` | Database / Dynamic | Auto-generated by query in `blog-details.php` lines 46–54. |
| In-Article Prepress CTA Box | "Ready to Order DTF Gang Sheets?" / "Upload Artwork Now" | `blog-details.php` | Lines 235–255 | Static PHP/HTML | Edit heading, text, and button link inside `blog-details.php` lines 235–255. |

---

## SECTION 2: PRODUCT AND SERVICE CONTENT MANAGEMENT AUDIT

This section clarifies exactly how catalog products, product detail pages, and printing services are modeled between the database and Admin CMS.

### 1. Products Architecture

- **Main Database Table:** `products`
- **Related Database Tables:**
  - `categories` (Category assignment and filtering)
  - `product_images` (Gallery thumbnails and secondary product angles)
  - `product_pricing_tiers` (Wholesale tiered pricing based on quantity thresholds)
  - `product_sizes` (Standard dimensions and size options)
  - `product_variations` (Combinations of size, color, and finish with dedicated SKUs and pricing)
  - `product_attributes` & `attribute_values` (Color codes, finish types, substrate options)
  - `product_reviews` (Verified customer ratings and testimonials)
- **Admin Panel Management Module:** [`c:\Movies\Internship\OrderPrintex\admin\products.php`](file:///c:/Movies/Internship/OrderPrintex/admin/products.php)

#### Product Fields Mapping Table

| Product Element | Database Table | Database Column | Admin Panel Editing Location | Fallback Location (if DB record empty) |
|---|---|---|---|---|
| **Product Title** | `products` | `title` (`varchar 180`) | **Admin Panel -> Products** -> Edit Product -> "Product Title" | `includes/catalog_helper.php` (`'title'`) |
| **SKU** | `products` | `sku` (`longtext` / `varchar`) | **Admin Panel -> Products** -> "Product SKU / Code" | `includes/catalog_helper.php` (Auto-generated prefix) |
| **Category** | `products`, `categories` | `category_id` (Foreign Key to `categories.id`) | **Admin Panel -> Products** -> "Assigned Category" dropdown | `includes/catalog_helper.php` (`'category_name'`, `'category_slug'`) |
| **Product Description** | `products` | `description` (`longtext`) & `short_desc` (`text`) | **Admin Panel -> Products** -> "Full Product Description" & "Short Excerpt" | `includes/catalog_helper.php` (`'description'`, `'short_desc'`) |
| **Starting Price** | `products` | `starting_price` (`decimal 10,2`) | **Admin Panel -> Products** -> "Starting Base Price (₹)" | `includes/catalog_helper.php` (`'starting_price'`) |
| **Price Unit** | `products` | `price_unit` (`longtext`) | **Admin Panel -> Products** -> "Price Unit (e.g. / Foot, / Piece, / Sheet)" | `includes/catalog_helper.php` (`'price_unit'`) |
| **Minimum Order** | `products` | `min_order` (`longtext`) & `min_order_qty` (`int`) | **Admin Panel -> Products** -> "Minimum Order Quantity / Policy" | `includes/catalog_helper.php` (`'min_order'`) |
| **Product Badge** | `products` | `badge` (`longtext`) & `badge_label` (`varchar 50`) | **Admin Panel -> Products** -> "Highlight Badge Label" | `includes/catalog_helper.php` (`'badge'`) |
| **Product Image** | `products` | `image_url` (`varchar 255`) | **Admin Panel -> Products** -> "Main Product Image" file upload | `includes/catalog_helper.php` (`'image_url'`) |
| **Product Image Alt Text** | Generated dynamically | Automatically rendered as `alt="e($product['title'])"` | Derived from `title` column in template | `product-details.php` line 144 |
| **Technical Specs** | `products` | `specifications` (`text`) | **Admin Panel -> Products** -> "Technical Specifications" textarea | `includes/catalog_helper.php` (`'specifications'`) |
| **Artwork Notes** | `products` | `artwork_notes` (`longtext`) | **Admin Panel -> Products** -> "Prepress & Artwork Guidelines" | `includes/catalog_helper.php` (`'artwork_notes'`) |

---

### 2. Services Architecture

- **Main Database Table:** `services`
- **Columns in `services`:** `id`, `title`, `slug`, `badge`, `hero_tagline`, `short_description`, `full_description`, `specifications`, `artwork_guidelines`, `image_url`, `is_active`, `display_order`, `created_at`, `updated_at`.
- **Admin Panel Management Module:** [`c:\Movies\Internship\OrderPrintex\admin\services.php`](file:///c:/Movies/Internship/OrderPrintex/admin/services.php)

#### Services Fields Mapping Table

| Service Element | Database Table | Database Column | Admin Panel Editing Location | Where Rendered on Public Site |
|---|---|---|---|---|
| **Service Title** | `services` | `title` (`varchar 150`) | **Admin Panel -> Services** -> "Service Title" | `services.php` card title, `index.php` services grid, `get-quote.php` dropdown |
| **Hero Tagline** | `services` | `hero_tagline` (`varchar 255`) | **Admin Panel -> Services** -> "Hero Tagline / Subtitle" | `services.php` card subtitle under heading |
| **Badge Label** | `services` | `badge` (`varchar 80`) | **Admin Panel -> Services** -> "Feature Badge Label" | `services.php` card eyebrow pill |
| **Short Description** | `services` | `short_description` (`text`) | **Admin Panel -> Services** -> "Brief Summary" | `index.php` services showcase grid |
| **Full Description** | `services` | `full_description` (`text`) | **Admin Panel -> Services** -> "Full Description" | `services.php` showcase body paragraphs |
| **Specifications** | `services` | `specifications` (`text`) | **Admin Panel -> Services** -> "Technical Specifications" | `services.php` highlighted specs box with gold left border |
| **Artwork Guidelines** | `services` | `artwork_guidelines` (`text`) | **Admin Panel -> Services** -> "Prepress Artwork Guidelines" | Passed to quote builder & prepress hints |
| **Service Image** | `services` | `image_url` (`varchar 255`) | **Admin Panel -> Services** -> "Service Showcase Image" upload | Rendered on `services.php` and `index.php` service cards |
| **Service Dedicated Subpage** | Static File | Standalone `.php` template | **Not in Admin CMS** (Direct code file) | `services/dtf-printing.php`, `services/uv-dtf-printing.php`, `services/t-shirt-printing.php`, `services/corporate-printing.php` |

---

## SECTION 3: BLOG CONTENT ARCHITECTURE & EDITING AUDIT

### 1. Template Structure Verification
- **Do both Blog Article 1 and Blog Article 2 use the same PHP template?**  
  **YES.** Both articles (and all published articles on the website) are dynamically rendered by the single template file: [`c:\Movies\Internship\OrderPrintex\blog-details.php`](file:///c:/Movies/Internship/OrderPrintex/blog-details.php).
- There are **no separate static PHP files** for individual blog posts.

### 2. Database Storage Verification
- **Are blog articles stored in the database?**  
  **YES.** All article records are stored in the `blog_posts` table and categorized via the `blog_categories` table.
- **Article 1 Database Record:**
  - `id`: 1
  - `slug`: `complete-guide-to-business-card-printing`
  - `title`: Complete Guide to Business Card Printing: Paper Weights, Finishes & Design Tips
  - `category_id`: 1 ("Printing Materials")
  - `featured_image`: `assets/images/branding_stationery.jpg`
  - `seo_title`: Complete Guide to Business Card Printing | OrderPrintex Delhi
  - `seo_description`: Master business card printing: 350 vs 400 GSM, matte vs velvet lamination, spot UV, and bleed guidelines from Delhi print experts.
- **Article 2 Database Record:**
  - `id`: 2
  - `slug`: `dtf-printing-explained-guide`
  - `title`: DTF Printing Explained: Everything You Need to Know About Direct-to-Film Transfers
  - `category_id`: 2 ("Garment Transfers")
  - `featured_image`: `assets/images/dtf_fabric_print.jpg`
  - `seo_title`: DTF Printing Explained: Everything You Need to Know | OrderPrintex
  - `seo_description`: Learn how DTF transfers work, pressing temperatures, TPU adhesive powder properties, and why DTF is ideal for streetwear labels.

### 3. Step-by-Step Instructions to Edit Blog Articles in Admin Panel

1. Log in to the Admin Panel at `http://127.0.0.1:8080/admin/login.php` (or `/admin/`).
2. Click **Blog** in the admin sidebar navigation (or go to `admin/blog.php`).
3. Locate the target article in the **Published Articles** directory table.
4. Click the **Edit** button in the Actions column next to the article.
5. In the Edit Blog Article Form:
   - To change the headline: Edit the **Post Title** field.
   - To change the clean URL: Edit the **URL Slug** field (note: modifying an existing slug will change its live URL).
   - To change the category: Select from the **Category** dropdown.
   - To update the teaser: Edit the **Excerpt** field.
   - To edit the article body: Update the text/HTML inside the **Article Content** textarea.
   - To replace the article photo: Choose a new image file in the **Featured Image** input.
   - To adjust Google search snippets: Edit **SEO Meta Title** and **SEO Meta Description**.
6. Click **Save Article**. The live article at `/blog/{slug}/` will update immediately.

---

## SECTION 4: UPLOAD AND GET QUOTE FORM AUDIT

The customer design upload and enquiry workflow is a core conversion engine of OrderPrintex. This audit maps every element between user-facing text and backend processing logic.

### 1. Form Action & PHP Request Handler
- **User-Facing Form File:** [`c:\Movies\Internship\OrderPrintex\get-quote.php`](file:///c:/Movies/Internship/OrderPrintex/get-quote.php) (and duplicated on [`index.php`](file:///c:/Movies/Internship/OrderPrintex/index.php#L159) inside `#upload-studio`).
- **Form Action Destination:** `POST` request to `api/submit-enquiry.php` (`action="<?= url('api/submit-enquiry.php') ?>" enctype="multipart/form-data"`).
- **Backend Handler File:** [`c:\Movies\Internship\OrderPrintex\api\submit-enquiry.php`](file:///c:/Movies/Internship/OrderPrintex/api/submit-enquiry.php).

### 2. Form Fields, Labels & Placeholders

| Field Name (`name`) | HTML Element | Label Text in `get-quote.php` | Placeholder / Hint Text | Editable Text Location |
|---|---|---|---|---|
| `artwork_file` | `<input type="file">` | "Select Artwork File" | "Accepted: PNG (transparent), PDF, AI, PSD, SVG (Max 25 MB). 300 DPI recommended for maximum print clarity." | `get-quote.php` lines 65–67 |
| `service_name` | `<select>` | "Printing Service Required *" | "Choose a Service" | `get-quote.php` line 124 |
| `custom_service_name` | `<input type="text">` | "Specify Custom Printing Service" (Dynamic Other) | "e.g. Screen Printed Lanyards, Embroidered Badges, Metallic Foil Transfers" | `get-quote.php` line 118 |
| `quantity` | `<input type="number">` | "Estimated Quantity / Sheets *" | Default value `1`, minimum `1` | `get-quote.php` line 140 |
| `width_inches` | `<input type="number">` | "Width (Inches)" | "e.g. 22" | `get-quote.php` line 146 |
| `height_inches` | `<input type="number">` | "Height / Length (Inches)" | "e.g. 36" | `get-quote.php` line 151 |
| `material_options` | `<input type="text">` | "Substrate / Garment / Finish Details" | "e.g. 100% Black Combed Cotton, Stainless Tumbler, Acrylic Sheet, Matte Gang Film" | `get-quote.php` line 157 |
| `customer_name` | `<input type="text">` | "Contact Name *" | "e.g. Priyanshu Mehta" | `get-quote.php` line 165 |
| `customer_email` | `<input type="email">` | "Email Address *" | "client@example.com" | `get-quote.php` line 171 |
| `customer_phone` | `<input type="tel">` | "Phone / WhatsApp Number *" | "+91 98765 43210" | `get-quote.php` line 177 |
| `company_name` | `<input type="text">` | "Company / Brand Name" | "e.g. Apex Apparel Studio" | `get-quote.php` line 183 |
| `customer_notes` | `<textarea>` | "Special Instructions or Custom Requirements" | "Mention turnaround deadline, specific PANTONE color codes, peeling preferences (cold vs warm peel), or packaging requests..." | `get-quote.php` line 189 |
| Submit Button | `<button type="submit">` | "Submit Print Enquiry" | "✓ 24-hr Quote Turnaround • No upfront card required • Pan-India Dispatch" | `get-quote.php` lines 197–200 |

### 3. Upload Restrictions & File Limits
- **Accepted File Extensions:** `.png`, `.jpg`, `.jpeg`, `.pdf`, `.ai`, `.psd`, `.svg` (Defined in `get-quote.php` line 52 attribute `accept=".png,.jpg,.jpeg,.pdf,.ai,.psd,.svg"` and validated server-side in `includes/functions.php` via `handle_file_upload()`).
- **Maximum File Size:** **25 Megabytes (25 MB)** (Enforced in `includes/config.php` constant `MAX_FILE_SIZE = 25 * 1024 * 1024`).
- **Storage Directory:** Physical files are stored at `uploads/artwork/` with cryptographically randomized, non-colliding filenames (`YYYYMMDD_HHMMSS_{hex}.ext`).

### 4. Dimension Calculator & Dynamic Estimation Logic
- Handled client-side by [`assets/js/upload.js`](file:///c:/Movies/Internship/OrderPrintex/assets/js/upload.js).
- When the user inputs `width_inches` and `height_inches`, the script calculates:  
  `sqInches = width * height` and updates `.sq-inches-display`.
- Based on selected service and quantity tiers, it dynamically updates `#estimatedPriceDisplay` (e.g. "₹249 – ₹349").
- **Editable Disclaimer Text:** `get-quote.php` lines 92–94: "Instant automated calculation based on selected service, quantity, and dimensions. Delhi prepress engineers perform a free resolution audit before issuing final invoice."

### 5. Backend Validation & Database Workflow
1. **CSRF Validation:** Enforces valid token check via `csrf_verify()`.
2. **Mandatory Fields:** Name, valid email, and contact phone are required.
3. **Database Insertion:**
   - Creates a record in the `enquiries` table with unique reference number `ref_number` (format: `OP-ENQ-YYYY-XXXXX`).
   - Stores width, height, quantity, service, material options, and customer notes.
   - Initial status is automatically set to `'received'`.
4. **Artwork File Association:**
   - Each uploaded file is inserted into the `enquiry_files` table with columns: `enquiry_id`, `original_name`, `stored_name`, `file_path`, `file_size`, `mime_type`, `file_extension`.
5. **Admin Availability:**
   - Administrators immediately see the enquiry and thumbnail preview in **Admin Panel -> Customer Enquiries (`admin/enquiries.php`)**.
   - Admin can launch the full-screen pan-and-zoom inspection viewer, verify prepress resolution, and click "Generate Formal Quotation" to move into `admin/quotations.php`.

---

## SECTION 5: SHARED FILES AND GLOBAL IMPACT AUDIT

The OrderPrintex codebase uses centralized include files to maintain consistency across all pages. Modifying any of the files listed below will immediately affect multiple pages.

### Shared Files Impact Matrix

| Shared File | Physical Path | Public Pages Impacted | Key Content Elements Controlled |
|---|---|---|---|
| **Global Header** | `includes/header.php` | **ALL 14 Pages** | `<head>` metadata, Open Graph tags, Schema.org Organization structured data, studio address, CSS links, theme initialization script. |
| **Global Navbar** | `includes/navbar.php` | **ALL 14 Pages** | Sticky top announcement contact bar (phone, WhatsApp, email, Instagram, Facebook, YouTube, LinkedIn), brand logo, primary navigation links, services dropdown items, mobile slide-out drawer. |
| **Global Footer** | `includes/footer.php` | **ALL 14 Pages** | Studio logo, company mission bio paragraph, printing service navigation links, explore links, workshop physical address, copyright year notice, shared JS scripts. |
| **Catalog Helper** | `includes/catalog_helper.php` | `index.php`, `products.php`, `product-details.php`, `pricing.php` | Curated showcase product data, pricing, specs, fallbacks for missing DB rows, category lists. |
| **Core Functions** | `includes/functions.php` | **ALL 14 Pages & Admin** | Studio settings getter `get_setting()`, URL generator `url()`, sanitization `e()`, CSRF validation, file uploader. |
| **SEO Helper** | `includes/seo_helper.php` | Entire Site & Admin | Generates and synchronizes `sitemap.xml`, `llms.txt`, `schema.json`, and `ORDERPRINTEX.md`. |
| **Database Config** | `includes/config.php` | Entire Site & Admin | Base URLs, database credentials, maximum upload size (25MB), file upload paths. |
| **Main Stylesheet** | `assets/css/style.css` | **ALL 14 Pages** | Black & Gold design variables, font styling, button styles, section padding, grid layouts, form styling. |
| **Animations CSS** | `assets/css/animations.css` | **ALL 14 Pages** | Scroll reveals (`.reveal`), stagger delays, keyframe sweeps, hover card animations. |
| **Responsive CSS** | `assets/css/responsive.css` | **ALL 14 Pages** | Mobile navigation drawer, responsive tablet/mobile breakpoints (1200px, 992px, 768px, 480px). |
| **Main JavaScript** | `assets/js/main.js` | **ALL 14 Pages** | Sticky header class toggle on scroll, mobile hamburger menu open/close, theme preferences. |
| **Animations JS** | `assets/js/animations.js` | **ALL 14 Pages** | IntersectionObserver scroll animation observer, numerical animated counters. |

---

## SECTION 6: HARDCODED CONTENT THAT CANNOT CURRENTLY BE EDITED VIA ADMIN CMS

While dynamic catalog items, blog articles, and studio contact parameters are managed through the database, several structural editorial sections on the public storefront are hardcoded in static PHP/HTML files. 

If you wish to change the wording of any element below, you must edit the corresponding PHP file directly:

1. **Home Page (`index.php`):**
   - Hero eyebrow pill text ("★ India's Direct-to-Film & UV DTF Specialists" — line 50).
   - Hero primary and secondary CTA button labels ("Upload Your Design", "Explore Products" — lines 64, 70).
   - Hero highlight badge texts ("Pre-Cured & ICC Calibrated", "Fast Pan-India Delivery" — lines 82, 94).
   - Floating Studio Quality card text ("Commercial Grade", "50+ Wash Cycles..." — lines 112–114).
   - Continuous marquee ticker text items (lines 126–143).
   - Why OrderPrintex 4 pillars copy (lines 500–533).
   - Forward Workflow 3-step timeline text (lines 616–649).
   - Final bottom CTA banner heading & button ("Transform Your Designs...", "Upload Your Artwork Now" — lines 755–778).
2. **About Page (`about.php`):**
   - Page hero headline & subtitle ("DTF & T-Shirt Printing in Delhi Done Properly." — lines 169–175).
   - Studio Story 3 descriptive paragraphs (lines 225–248).
   - 4 Milestone statistic numbers and labels ("14+ Years in Printing", "10,000+ Orders..." — lines 255–285).
   - Why Work With Us 4 pillars text (lines 386–436).
   - Location visit narrative (lines 446–470).
3. **Dedicated Service Pages (`services/*.php`):**
   - The entire narrative, overview comparisons, and 4 technical parameter cards on:
     - `services/dtf-printing.php`
     - `services/uv-dtf-printing.php`
     - `services/t-shirt-printing.php`
     - `services/corporate-printing.php`
4. **Frequently Asked Questions (`faq.php`):**
   - The 8 categories and all question-and-answer pairs in `faq.php` are currently stored in a static PHP array (lines 30–280). The `admin/faqs.php` module redirects to `admin/dashboard.php`.
5. **Commercial Pricing Workbench (`pricing.php`):**
   - Volume discount tables and turnaround guarantees are maintained in `pricing.php`.
6. **Footer Bio Text (`includes/footer.php`):**
   - The footer bio paragraph ("PRINTEX is India's leading manufacturing studio specializing in industrial Direct-to-Film (DTF)..." — lines 27–29) has a static default in `includes/footer.php`.

---

## SECTION 7: STEP-BY-STEP RECOMMENDED EDITING METHOD FOR EVERY CONTENT TYPE

### Scenario A: Editing Studio Phone, Email, WhatsApp, Address, or Social Links
1. Open the Admin Panel (`/admin/`).
2. Navigate to **Website Settings** (`admin/settings.php`).
3. Update the corresponding field:
   - Contact Phone -> Updates top announcement bar, contact page card, and footer.
   - WhatsApp Number -> Updates top WhatsApp link, product quote buttons, and contact card.
   - Studio Physical Address -> Updates top bar, contact page, footer, and schema.json.
   - Instagram / Facebook / YouTube / LinkedIn -> Updates verified social icons website-wide.
4. Click **Save Settings**. (The system will automatically run `seo_sync_all()` to keep `sitemap.xml` and `schema.json` aligned).

### Scenario B: Adding or Modifying a Printing Product
1. Open the Admin Panel (`/admin/`).
2. Navigate to **Products** (`admin/products.php`).
3. Click **Add New Product** (or click **Edit** on an existing product).
4. Update Title, Category, SKU, Starting Base Price, Price Unit (e.g. `/ Foot`), Minimum Order, Badge Label, Full Description, Specifications, and Upload the main product photo.
5. Set Status to **Active**.
6. Click **Save Product**. The item will immediately appear in `/products/` and on the homepage product showcase.

### Scenario C: Adding or Modifying a Printing Service Card
1. Open the Admin Panel (`/admin/`).
2. Navigate to **Printing Services** (`admin/services.php`).
3. Click **Edit** next to DTF Printing, UV DTF Printing, T-Shirt Printing, or Corporate Printing.
4. Update the Feature Badge, Title, Hero Tagline, Short Description, Full Description, or Key Specifications.
5. Upload a replacement showcase image if needed.
6. Click **Save Service**. The service cards on `/services/` and `index.php` update immediately.

### Scenario D: Publishing or Editing a Blog Article
1. Open the Admin Panel (`/admin/`).
2. Navigate to **Blog** (`admin/blog.php`).
3. Click **Edit** on an existing post (or **Add New Post**).
4. Edit the Post Title, URL Slug, Category, Excerpt, Full HTML Body, and Featured Image.
5. Fill in the **SEO Meta Title** and **SEO Meta Description** for Google search optimization.
6. Set Status to **Published**.
7. Click **Save Article**. The article is live immediately at `/blog/{slug}/`.

### Scenario E: Editing Static Homepage Copy, Headlines or Workflow Steps
1. Open [`c:\Movies\Internship\OrderPrintex\index.php`](file:///c:/Movies/Internship/OrderPrintex/index.php) in your code editor.
2. Locate the line number identified in Section 1 of this report (e.g. lines 48–51 for hero badge, lines 500–533 for Why Choose Us).
3. Edit the text string inside the HTML tags while preserving all CSS class names.
4. Save the file. Refresh your browser to see the changes.

### Scenario F: Editing Dedicated Service Page Copy (DTF, UV DTF, T-Shirts, Corporate)
1. Open the appropriate file in `services/`:
   - DTF Printing -> [`services/dtf-printing.php`](file:///c:/Movies/Internship/OrderPrintex/services/dtf-printing.php)
   - UV DTF Printing -> [`services/uv-dtf-printing.php`](file:///c:/Movies/Internship/OrderPrintex/services/uv-dtf-printing.php)
   - T-Shirt Printing -> [`services/t-shirt-printing.php`](file:///c:/Movies/Internship/OrderPrintex/services/t-shirt-printing.php)
   - Corporate Printing -> [`services/corporate-printing.php`](file:///c:/Movies/Internship/OrderPrintex/services/corporate-printing.php)
2. Locate the technical parameters or narrative section using the line numbers in Section 1.
3. Edit the copy directly in the file.
4. Save the file.

---

## SECTION 8: FINAL AUDIT VERIFICATION & COMPLETION SIGN-OFF

- **Total Public Pages Inspected:** 14 out of 14 individual pages.
- **Routing Integrity Verified:** Checked all Apache `.htaccess` rewrite rules and `router.php` dispatch pathways.
- **Database Schema Verified:** Checked all 35 MySQL tables, columns, constraints, and relationships.
- **Admin CMS Mapping Verified:** Audited all 30 admin module files in `admin/` to distinguish operational CMS interfaces from redirected modules.
- **File System Integrity:** Zero code files or database records were modified during this audit.

**Report Generated & Saved at:** `C:\Movies\Internship\OrderPrintex\ORDERPRINTEX_CONTENT_AUDIT.md`

