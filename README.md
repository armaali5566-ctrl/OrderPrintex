# OrderPrintex — Premium Printing Studio Website & Business Management System

A high-end, modern, and fully functional multi-page website, custom quotation engine, artwork upload pipeline, customer account portal, and secure administrative dashboard for **OrderPrintex** (Delhi, India).

Designed with editorial typography, sophisticated color palettes (Midnight Navy, Warm Ivory, Champagne Gold, Royal Blue, and Deep Crimson), native CSS animations, and Vanilla JavaScript. Built strictly using **PHP 8+**, **MySQL (PDO)**, **HTML5**, **CSS3**, and **Vanilla JavaScript** with **zero external CDN dependencies or JavaScript frameworks**.

---

## 📋 Table of Contents
1. [Core Features & System Architecture](#core-features--system-architecture)
2. [Technology Stack & Compliance](#technology-stack--compliance)
3. [Verified Business Identity & Contact Details](#verified-business-identity--contact-details)
4. [Prerequisites & Software Requirements](#prerequisites--software-requirements)
5. [Installation & Setup with XAMPP](#installation--setup-with-xampp)
6. [Database Setup & Importing SQL](#database-setup--importing-sql)
7. [Database Credentials Configuration](#database-credentials-configuration)
8. [Default Admin & Customer Credentials](#default-admin--customer-credentials)
9. [Step-by-Step Testing & Verification Walkthrough](#step-by-step-testing--verification-walkthrough)
10. [Directory Structure](#directory-structure)
11. [Security & Protection Mechanisms](#security--protection-mechanisms)

---

## 🌟 Core Features & System Architecture

### 1. Customer-Facing Showcase & Public Pages
- **Split Hero Section**: Editorial typography, introductory badge, dual call-to-actions, and high-impact visual imagery.
- **Interactive Drag-and-Drop Artwork Uploader**: Real-time client-side preview, file size/type validation, print service selection, dimensions area calculator, and customer notes.
- **Verified Printing Services**:
  - `services/dtf-printing.php`: Industrial Direct-to-Film transfers (2400x1200 DPI, Japanese dual-head specs, 160°C heat press recommendations, wash durability).
  - `services/uv-dtf-printing.php`: Permanent peel-and-stick 3D varnish decals for tumblers, acrylic, glass, and wood.
  - `services/t-shirt-printing.php`: 100% premium combed cotton (180–240 GSM) screen-quality garments.
  - `services/corporate-printing.php`: Executive onboarding kits, promotional packages, and event merch.
- **Dynamic Catalog & Single Product Pages** (`products.php` & `product-details.php`): Filterable by category, searchable, displaying technical specifications and integrated quotation triggers.
- **Visual Work Showcase** (`gallery.php`): Masonry grid, category filtering tabs (DTF, UV DTF, T-Shirts, Corporate), and full-size modal viewer.
- **Seamless Infinite Marquee**: Pure CSS keyframes running continuously without visible jumps or lag.
- **Help Center & Interactive FAQs** (`faq.php`): Technical categories with accessible Vanilla JS accordions.
- **Verified Contact Page** (`contact.php`): Official Delhi address, phone, direct WhatsApp link, operating hours, and interactive enquiry submission form.

### 2. Customer Portal & Self-Service
- **Secure Authentication**: Registration (`account/register.php`), Login (`account/login.php`), and Logout (`account/logout.php`) using `password_hash()` and `password_verify()`.
- **Session Regeneration**: Protects against session fixation attacks.
- **Customer Dashboard** (`account/dashboard.php`): Live metrics, recent requests, active orders, and notifications.
- **Artwork Enquiries** (`account/enquiries.php`): Track submitted artwork requests and pipeline statuses (`Received`, `Under Review`, `Quoted`, `Converted to Order`).
- **Interactive Quotation Review** (`account/quotations.php`): View formal itemized quotations with unit pricing, dimensions, GST breakdown, and shipping charges. Customers can **Accept** (which immediately converts the quote into an active production order), **Request Revisions** with custom feedback notes, or **Decline**.
- **Order Tracking & Milestone Timeline** (`account/orders.php` & `account/order-details.php`): Live visual milestone tracker from Prepress Review to Production, Quality Inspection, Dispatch, and Courier Delivery.
- **Profile & Address Manager** (`account/profile.php`): Update shipping address, phone, and password.

### 3. Comprehensive Administrative Control Center (`/admin/`)
- **Executive Dashboard** (`admin/dashboard.php`): Live database metrics (Active Orders, Total Enquiries, Pending Quotations, Converted Orders, Store Revenue, Registered Clients) and recent security audit logs.
- **Production Order Management** (`admin/orders.php`): Search and filter orders by status, update stages (`Enquiry Received` &rarr; `Proof Approval` &rarr; `Production` &rarr; `Quality Check` &rarr; `Dispatched` &rarr; `Delivered`), assign courier details & tracking numbers, add internal notes, and maintain an automated status change audit trail in `order_status_history`.
- **Artwork & Enquiry Manager** (`admin/enquiries.php`): Inspect customer uploads, preview image files, download vector artwork, update status, and convert any enquiry directly into a formal quotation with one click.
- **Quotation Generator & Editor** (`admin/quotations.php`): Dynamic itemized line repeater with automatic subtotal calculation, GST (18%) computation, delivery fees, quotation validity periods, client revision feedback logs, and instant publishing.
- **Product Catalog Management** (`admin/products.php`): Add, edit, delete products, manage categories, upload product images, write specifications, and toggle storefront visibility.
- **Printing Services Editor** (`admin/services.php`): Update service titles, press temperature guidelines, prepress specs, badges, and images.
- **Customer Account Inspector** (`admin/customers.php`): Search client accounts, view customer spending metrics, view their linked orders, quotations, and uploaded files, and toggle active/suspended status.
- **Portfolio & Gallery Manager** (`admin/gallery.php`): Upload production photos, assign categories, and toggle homepage spotlight feature.
- **Website Content Editor** (`admin/content.php`): Edit homepage headlines, intro paragraphs, QA badges, and editorial value pillars directly from MySQL without editing PHP source code.
- **FAQ Management** (`admin/faqs.php`): Add, edit, and reorder categorized FAQs.
- **Studio & Business Settings** (`admin/settings.php`): Update official telephone numbers, WhatsApp integration, Delhi studio address, email, social URLs, and default GST rates.
- **Admin Users & Permissions** (`admin/users.php`): Add administrators and production staff with BCrypt hashing and role management.

---

## 🛠 Technology Stack & Compliance

| Layer | Technology | Details |
| :--- | :--- | :--- |
| **Markup** | HTML5 | Semantic markup, accessible forms, meta descriptions, unique test IDs |
| **Styling** | Native CSS3 | Custom variables, Flexbox, CSS Grid masonry, Keyframe marquee, No Tailwind/Bootstrap |
| **Interactivity** | Vanilla JavaScript | Native DOM manipulation, IntersectionObserver, No jQuery/GSAP/Swiper |
| **Backend** | PHP 8.0+ | Modern object-oriented PHP, PDO Prepared Statements, Session security |
| **Database** | MySQL 5.7+ / 8.0+ | Normalized InnoDB schema, foreign key constraints, indexes, cascade rules |
| **Dependencies**| 100% Self-Contained | Zero NPM packages, zero Composer libraries, zero CDN dependencies |

---

## 🏢 Verified Business Identity & Contact Details

All data reflects verified, authentic information from the original OrderPrintex business:

- **Brand Name**: OrderPrintex
- **Tagline**: Manufacturer & Bulk Supplier of DTF and UV DTF in India
- **Delhi Studio Address**: Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059
- **Phone / WhatsApp**: `+91 93540 28199`
- **Sales Email**: `sales@orderprintex.com`
- **Google Maps**: [View on Google Maps](https://share.google/MWOVYne70asGLsYXn)
- **Instagram**: `@order_printex`
- **YouTube**: `@PRINTEX_Delhi`

---

## 💻 Prerequisites & Software Requirements

To run this application locally, ensure you have:
1. **XAMPP** (recommended) with **PHP 8.0+** and **MySQL / MariaDB**.
   - Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Alternatively: Standalone **PHP 8+** and **MySQL Server 8.0+**.
3. Enabled PHP Extensions in `php.ini`:
   - `extension=pdo_mysql`
   - `extension=fileinfo`
   - `extension=gd`
   - `extension=mbstring`
   - `extension=openssl`
   - `extension=session`

---

## 🚀 Installation & Setup with XAMPP

### Step 1: Place Project Files into XAMPP
Copy the `OrderPrintex` project directory into your XAMPP web root:
```
C:\xampp\htdocs\OrderPrintex
```
Ensure the folder name is capitalized exactly as `OrderPrintex`.

### Step 2: Start Apache and MySQL
1. Launch the **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

---

## 🗄 Database Setup & Importing SQL

### Option A: Using phpMyAdmin (Recommended)
1. Open your browser and navigate to:
   ```
   http://localhost/phpmyadmin/
   ```
2. Click on **Databases** tab.
3. In "Create database", enter `orderprintex` with collation `utf8mb4_unicode_ci` and click **Create**.
4. Select the newly created `orderprintex` database from the left sidebar.
5. Click on the **Import** tab in the top navigation bar.
6. Click **Choose File** and select:
   ```
   C:\xampp\htdocs\OrderPrintex\database\orderprintex.sql
   ```
7. Click **Import** (or **Go**) at the bottom of the page.
8. You should see a green success banner indicating that all 16 tables and verified seed records have been created.

### Option B: Using MySQL Command Line
Run the following command in terminal or command prompt:
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS orderprintex CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p orderprintex < C:\xampp\htdocs\OrderPrintex\database\orderprintex.sql
```

---

## ⚙ Database Credentials Configuration

Open `includes/config.php` in any text editor. By default, it is configured for standard XAMPP:

```php
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'orderprintex');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
```

- If your local MySQL `root` user has a password (e.g. `MySecretPassword`), simply edit line 27:
  ```php
  define('DB_PASS', 'MySecretPassword');
  ```
- Or set the environment variable:
  ```powershell
  $env:DB_PASS="MySecretPassword"
  ```

---

## 🔑 Default Admin & Customer Credentials

The database comes pre-seeded with an initial Administrator account:

### Super Administrator Account
- **Portal URL**: `http://localhost/OrderPrintex/admin/login.php`
- **Email**: `admin@orderprintex.com`
- **Password**: `AdminPassword@2026`
- **Role**: Super Administrator (full permissions)

### Creating Additional Admin Accounts
1. Log in to the Admin Dashboard.
2. Navigate to **Admin Users & Permissions** (`admin/users.php`).
3. Fill in the user's name, email, role (`admin` or `staff`), and a secure password (min. 8 characters).
4. The system automatically hashes the password using `password_hash($password, PASSWORD_BCRYPT)` and logs the activity.

### Customer Registration
- New customers can register at `http://localhost/OrderPrintex/account/register.php`.
- Customer accounts have isolated access to only their personal enquiries, quotations, orders, and addresses.

---

## 🧪 Step-by-Step Testing & Verification Walkthrough

Follow this step-by-step test flow to verify every feature:

### 1. Public Website & Navigation
1. Visit `http://localhost/OrderPrintex/`.
2. Observe the sticky navigation, smooth transparent-to-solid transition on scroll, and infinite marquee banner.
3. Test all service links: `/services/dtf-printing.php`, `/services/uv-dtf-printing.php`, `/services/t-shirt-printing.php`, `/services/corporate-printing.php`.
4. Test the Products catalog (`products.php`), category filters, search box, and click into a product (`product-details.php`).
5. Open the Portfolio (`gallery.php`), switch category tabs, and click on an image to view the modal showcase.
6. Open FAQs (`faq.php`) and click accordion headers to expand/collapse answers.

### 2. Artwork Upload & Quotation Enquiry
1. On the homepage (Section 3) or at `get-quote.php`:
   - Drag and drop or browse to select an artwork file (`.png`, `.jpg`, `.pdf`, etc.).
   - Notice the instant image preview, file name, and file size displayed.
   - Enter your name, email, phone number, dimensions (e.g. `22` x `40` inches), quantity (`10`), and special notes.
   - Click **Submit Print Request / Get Formal Quotation**.
2. You will be redirected to an order confirmation screen showing your unique Reference Number (e.g., `OP-2026-XXXXX`).

### 3. Customer Portal
1. Register or log in at `http://localhost/OrderPrintex/account/login.php`.
2. Visit **My Enquiries** (`account/enquiries.php`) to see the submitted request with status `Received` and uploaded files.

### 4. Admin Workflow: Review, Price, & Quote
1. Log in to the Admin Dashboard at `http://localhost/OrderPrintex/admin/login.php` using `admin@orderprintex.com` / `AdminPassword@2026`.
2. Navigate to **Enquiries & Artwork** (`admin/enquiries.php`).
3. Click **Inspect** next to the newly submitted enquiry:
   - Preview the uploaded artwork and download the file.
   - Change pipeline status to `Under Review`.
   - Click **Quote** (or **Generate Formal Quotation for this Enquiry**).
4. In the Quotation Builder (`admin/quotations.php`):
   - Notice customer details and line items pre-populated from the enquiry.
   - Set unit price (e.g., `₹250.00`).
   - Notice the dynamic Vanilla JS repeater automatically recalculating subtotal, 18% GST, and grand total.
   - Add delivery fee (e.g., `₹150.00`).
   - Set status to **Sent / Published to Client** and click **Publish & Save Quotation**.

### 5. Customer Quotation Acceptance & Order Creation
1. Switch back to the customer portal at `account/quotations.php`.
2. Inspect the newly published quotation `QP-2026-XXXXX`.
3. Click **Accept & Proceed to Order Confirmation**.
4. The system immediately converts the quotation into an active production order `ORD-2026-XXXXX` and redirects the customer to their order tracker.

### 6. Order Tracking & Status Updates
1. In the customer portal (`account/order-details.php?id=...`), observe the visual status timeline.
2. In the Admin Dashboard (`admin/orders.php`):
   - Open the order.
   - Update status from `Proof Approval` to `Production` and then `Dispatched`.
   - Enter courier name (e.g., `BlueDart / Delhivery`) and tracking number (e.g., `BLU982341209`).
   - Click **Save Order Updates**.
3. Refresh the customer order page to see the updated milestone timeline, delivery details, and tracking number.

### 7. Content Management & Storefront Updates
1. Navigate to `admin/content.php` in the admin panel.
2. Change the **Main Editorial Headline** or **Quality Assurance Badges**.
3. Click **Publish Website Content Updates**.
4. Visit the public homepage at `http://localhost/OrderPrintex/` and verify the live headline reflects your database update without modifying any PHP files.

---

## 📂 Directory Structure

```
OrderPrintex/
│
├── index.php                     # 12-section flagship studio homepage
├── about.php                     # Studio story, 4 pillars of QA & prepress checks
├── services.php                  # Overview of all 4 industrial printing capabilities
├── products.php                  # Filterable & searchable product catalog
├── product-details.php           # Individual product specs & embedded quote form
├── gallery.php                   # Masonry portfolio showcase & image modal viewer
├── get-quote.php                 # Dedicated custom quotation builder & file uploader
├── contact.php                   # Verified Delhi workshop details, hours & contact form
├── faq.php                       # Categorized help center accordions
│
├── services/                     # Individual Verified Service Pages
│   ├── dtf-printing.php          # 24" dual-head gang roll specs & press settings
│   ├── uv-dtf-printing.php       # Hard-surface permanent 3D varnish transfer specs
│   ├── t-shirt-printing.php      # 180-240 GSM combed cotton garment printing specs
│   └── corporate-printing.php    # Executive kits, mugs & promotional merch packages
│
├── account/                      # Customer Portal Pages
│   ├── register.php              # Customer account registration
│   ├── login.php                 # Customer authentication with session regeneration
│   ├── logout.php                # Secure session termination
│   ├── dashboard.php             # Real database-driven client dashboard metrics
│   ├── enquiries.php             # Customer's submitted artwork enquiries
│   ├── quotations.php            # Quotation review (Accept / Revise / Decline)
│   ├── orders.php                # Customer's orders list & status badges
│   ├── order-details.php         # Visual milestone timeline & courier tracking
│   └── profile.php               # Address management & password update
│
├── admin/                        # Secure Administrative Control Center
│   ├── login.php                 # Dedicated dark admin authentication
│   ├── dashboard.php             # DB metrics, orders breakdown & audit logs
│   ├── orders.php                # Order pipeline manager & status history logger
│   ├── enquiries.php             # Artwork inspector, file downloader & status pipeline
│   ├── quotations.php            # Itemized quotation builder with dynamic calculations
│   ├── products.php              # Product catalog CRUD with photo uploads
│   ├── services.php              # Service specifications & prepress guidelines editor
│   ├── customers.php             # Client account inspector & lifetime spend metrics
│   ├── gallery.php               # Showcase manager with category filters
│   ├── content.php               # Website copy, headlines & QA badges manager
│   ├── faqs.php                  # FAQ help center CRUD & ordering
│   ├── settings.php              # Studio contact, WhatsApp, address & GST settings
│   ├── users.php                 # Admin users management & permission controls
│   ├── header.php                # Admin navigation bar & live badge counters
│   └── footer.php                # Admin scripts & closing tags
│
├── includes/                     # Backend Core & Architecture
│   ├── config.php                # Dynamic BASE_URL, DB credentials & upload limits
│   ├── database.php              # Singleton PDO database wrapper
│   ├── auth.php                  # Role-based auth guards & password hashing
│   ├── functions.php             # XSS escaping, CSRF protection, file upload handler
│   ├── header.php                # Public HTML <head>, SEO meta tags & stylesheets
│   ├── navbar.php                # Responsive sticky header & mobile drawer menu
│   └── footer.php                # Dynamic database-driven footer & verified links
│
├── api/                          # Backend API & Form Processors
│   └── submit-enquiry.php        # Secure multipart artwork uploader & DB processor
│
├── assets/                       # Public Frontend Assets
│   ├── css/
│   │   ├── style.css             # Design system tokens, editorial typography & components
│   │   ├── responsive.css        # Breakpoints for desktop, tablet, and mobile drawers
│   │   ├── animations.css        # Pure CSS marquee, badge pulses & reveal transitions
│   │   └── admin.css             # Admin dashboard layout, metrics & data tables
│   ├── js/
│   │   ├── main.js               # Sticky navbar, mobile menu, accordions & tabs
│   │   ├── animations.js         # IntersectionObserver scroll animations & counters
│   │   ├── upload.js             # Drag-and-drop artwork uploader & dimension calculator
│   │   └── admin.js              # Admin sidebar toggle & quotation repeater calculations
│   └── images/                   # Local verified photography & brand assets
│       ├── logo.png              # Official OrderPrintex studio logo
│       ├── gallery_1.jpg         # DTF transfer demonstration
│       ├── gallery_2.jpg         # Multi-color gang sheet printing
│       ├── gallery_3.jpg         # UV DTF embossed decals
│       ├── gallery_4.jpg         # Custom apparel pressing
│       ├── gallery_5.jpg         # Workshop production roll
│       ├── badge.png             # Official quality seal badge
│       └── shipping.png          # All-India delivery icon
│
├── database/
│   └── orderprintex.sql          # Complete MySQL schema, foreign keys & verified seeds
│
├── uploads/                      # Secure File Storage
│   ├── .htaccess                 # Disables PHP script execution in uploads
│   ├── artwork/                  # Customer uploaded design files
│   └── proofs/                   # Prepress digital proofs
│
└── README.md                     # Comprehensive documentation & installation guide
```

---

## 🔒 Security & Protection Mechanisms

1. **SQL Injection Defense**: 100% of database interactions execute through PDO prepared statements with strict parameter binding. No raw SQL concatenation.
2. **Cross-Site Scripting (XSS) Prevention**: All user inputs and database outputs are filtered using the `e()` helper (`htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`).
3. **Cross-Site Request Forgery (CSRF) Protection**: Cryptographically secure 32-byte hexadecimal tokens generated per session, validated on every POST request via `csrf_verify()`.
4. **Session Fixation Defense**: Sessions are regenerated (`session_regenerate_id(true)`) immediately upon authentication and destroyed on logout.
5. **Secure Password Hashing**: Passwords are encrypted using BCrypt (`PASSWORD_BCRYPT` or `PASSWORD_DEFAULT`) via PHP's native `password_hash()` and verified with `password_verify()`.
6. **Hardened File Upload Validation**:
   - Validation performed strictly server-side using native PHP `mime_content_type()` / `finfo`.
   - File extensions checked against an explicit whitelist (`png`, `jpg`, `jpeg`, `pdf`, `ai`, `psd`, `svg`, `eps`).
   - File sizes enforced up to 25MB.
   - Uploaded files are renamed using randomized SHA-256 hashes (`bin2hex(random_bytes(16))`) to prevent directory traversal and file overwrite attacks.
   - The `uploads/.htaccess` file explicitly disables execution of all executable and PHP scripts inside the upload directory.
7. **Role-Based Authorization**: Protected areas use `require_auth()` for customer accounts and `require_admin()` for the administrative dashboard, preventing unauthorized horizontal or vertical privilege escalation.
8. **Audit Trail Logging**: Administrative updates and critical events are recorded in the `activity_logs` table with user IDs, IP addresses, and timestamps.

---

## 📄 License & Attribution
Developed specifically for **OrderPrintex** (Delhi, India). All branding, service specifications, and contact details are authentic to the business. Designed and engineered without third-party frameworks or external libraries.
