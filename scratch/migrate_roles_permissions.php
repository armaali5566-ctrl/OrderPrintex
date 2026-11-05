<?php
/**
 * OrderPrintex - Database Migration for Granular Roles & Permissions
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

echo "=== Starting Roles & Permissions Migration ===\n";

// 1. Add department column to users table if not exists
try {
    $colCheck = db_fetch_one("SHOW COLUMNS FROM users LIKE 'department'");
    if (!$colCheck) {
        db_query("ALTER TABLE users ADD COLUMN department VARCHAR(100) DEFAULT NULL AFTER company_name");
        echo "[+] Added 'department' column to users table.\n";
    } else {
        echo "[.] 'department' column already exists on users table.\n";
    }
} catch (Exception $e) {
    echo "[!] Error checking department column: " . $e->getMessage() . "\n";
}

// 2. Create admin_roles table
db_query("
    CREATE TABLE IF NOT EXISTS admin_roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(60) NOT NULL UNIQUE,
        name VARCHAR(100) NOT NULL,
        department VARCHAR(100) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        is_system TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "[+] Ensured 'admin_roles' table exists.\n";

// 3. Create admin_permissions table
db_query("
    CREATE TABLE IF NOT EXISTS admin_permissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(80) NOT NULL UNIQUE,
        module VARCHAR(50) NOT NULL,
        action VARCHAR(50) NOT NULL,
        name VARCHAR(100) NOT NULL,
        description VARCHAR(255) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "[+] Ensured 'admin_permissions' table exists.\n";

// 4. Create admin_role_permissions table
db_query("
    CREATE TABLE IF NOT EXISTS admin_role_permissions (
        role_id INT NOT NULL,
        permission_id INT NOT NULL,
        PRIMARY KEY (role_id, permission_id),
        INDEX (role_id),
        INDEX (permission_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "[+] Ensured 'admin_role_permissions' table exists.\n";

// 5. Create admin_user_roles table
db_query("
    CREATE TABLE IF NOT EXISTS admin_user_roles (
        user_id INT NOT NULL,
        role_id INT NOT NULL,
        PRIMARY KEY (user_id, role_id),
        INDEX (user_id),
        INDEX (role_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "[+] Ensured 'admin_user_roles' table exists.\n";

// 6. Create admin_user_permissions table (for explicit per-user overrides)
db_query("
    CREATE TABLE IF NOT EXISTS admin_user_permissions (
        user_id INT NOT NULL,
        permission_id INT NOT NULL,
        is_granted TINYINT(1) DEFAULT 1,
        PRIMARY KEY (user_id, permission_id),
        INDEX (user_id),
        INDEX (permission_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "[+] Ensured 'admin_user_permissions' table exists.\n";

// 7. Seed Permissions
$permissionsList = [
    // Blog Articles
    ['blog.view',        'blog', 'view',          'View Blog Articles',        'View published and draft blog posts'],
    ['blog.create',      'blog', 'create',        'Create Blog Articles',      'Write new blog posts and drafts'],
    ['blog.edit',        'blog', 'edit',          'Edit Blog Articles',        'Edit existing blog posts and content'],
    ['blog.publish',     'blog', 'publish',       'Publish / Unpublish Blog',  'Toggle blog post published status'],
    ['blog.delete',      'blog', 'delete',        'Delete Blog Articles',      'Delete blog posts permanently'],

    // Customer Enquiries
    ['enquiries.view',   'enquiries', 'view',     'View Customer Enquiries',   'View customer enquiries and specifications'],
    ['enquiries.status', 'enquiries', 'manage_status', 'Manage Enquiry Status', 'Update status of enquiries (quoted, review, etc.)'],
    ['enquiries.notes',  'enquiries', 'edit',     'Add Internal Notes',        'Attach internal staff notes to enquiries'],
    ['enquiries.export', 'enquiries', 'export',   'Export Enquiries',          'Export enquiry data and customer reports'],
    ['enquiries.delete', 'enquiries', 'delete',   'Delete Enquiries',          'Delete spam or obsolete enquiries'],

    // Quotations
    ['quotations.view',   'quotations', 'view',   'View Quotations',           'View itemized quotation desk and history'],
    ['quotations.create', 'quotations', 'create', 'Create Quotations',         'Draft new commercial printing proposals'],
    ['quotations.edit',   'quotations', 'edit',   'Edit Quotations',           'Modify line items, quantities, and pricing'],
    ['quotations.send',   'quotations', 'publish','Send Quotations to Client', 'Dispatch quotations to client portal and email'],
    ['quotations.status', 'quotations', 'manage_status', 'Manage Quote Status','Update quote lifecycle status (approved/rejected)'],
    ['quotations.delete', 'quotations', 'delete', 'Delete Quotations',         'Remove draft or obsolete quotations'],

    // Customer Accounts
    ['customers.view',   'customers', 'view',     'View Customer Accounts',    'View customer profiles, contacts, and order logs'],
    ['customers.edit',   'customers', 'edit',     'Edit Customer Accounts',    'Update customer profile information or status'],

    // Products
    ['products.view',    'products', 'view',      'View Products',             'Browse product catalog and specifications'],
    ['products.create',  'products', 'create',    'Create Products',           'Add new printing products to catalog'],
    ['products.edit',    'products', 'edit',      'Edit Products',             'Update descriptions, pricing, and images'],
    ['products.delete',  'products', 'delete',    'Delete Products',           'Remove products from catalog'],
    ['products.variations','products','manage_settings','Manage Variations & SKU','Configure variations, attributes, and SKU rules'],

    // Categories
    ['categories.view',  'categories', 'view',    'View Categories',           'Browse product categories'],
    ['categories.create','categories', 'create',  'Create Categories',         'Add new product categories'],
    ['categories.edit',  'categories', 'edit',    'Edit Categories',           'Modify category names, slugs, and sequence'],
    ['categories.delete','categories', 'delete',  'Delete Categories',         'Delete product categories'],

    // Portfolio Management
    ['portfolio.view',   'portfolio', 'view',     'View Portfolio Projects',   'Browse studio portfolio projects'],
    ['portfolio.create', 'portfolio', 'create',   'Create Portfolio Projects', 'Upload new print specimens and projects'],
    ['portfolio.edit',   'portfolio', 'edit',     'Edit Portfolio Projects',   'Update project descriptions, tags, and photos'],
    ['portfolio.publish','portfolio', 'publish',  'Publish / Unpublish Project','Toggle portfolio visibility'],
    ['portfolio.delete', 'portfolio', 'delete',   'Delete Portfolio Projects', 'Remove portfolio specimens'],

    // Printing Services
    ['services.view',    'services', 'view',      'View Printing Services',    'Browse commercial printing services'],
    ['services.create',  'services', 'create',    'Create Printing Services',  'Add new printing services'],
    ['services.edit',    'services', 'edit',      'Edit Printing Services',    'Update service capabilities and equipment'],
    ['services.delete',  'services', 'delete',    'Delete Printing Services',  'Remove printing services'],

    // Customer Reviews
    ['reviews.view',     'reviews', 'view',       'View Customer Reviews',     'Browse client testimonials and reviews'],
    ['reviews.approve',  'reviews', 'approve',    'Approve / Reject Reviews',  'Moderate and publish customer reviews'],
    ['reviews.delete',   'reviews', 'delete',     'Delete Reviews',            'Permanently remove reviews'],

    // Website Content
    ['content.view',     'content', 'view',       'View Website Content',      'View CMS homepage and landing pages'],
    ['content.edit',     'content', 'edit',       'Edit Website Content',      'Update CMS text, announcements, and banners'],

    // Website / SEO
    ['seo.view',         'seo', 'view',           'View SEO & Discovery',      'View sitemap, llms.txt, and schema status'],
    ['seo.manage',       'seo', 'manage_settings','Manage SEO Synchronization','Run SEO sync and modify metadata'],

    // Settings
    ['settings.view',    'settings', 'view',      'View Website Settings',     'View contact, company, and studio settings'],
    ['settings.edit',    'settings', 'manage_settings','Edit Website Settings','Update company details and studio configuration'],

    // Personnel & Security
    ['personnel.view',   'personnel', 'view',     'View Admin Personnel',      'Browse staff directory and assigned roles'],
    ['personnel.manage', 'personnel', 'manage_settings','Manage Personnel & Roles','Create, edit, reset passwords, and assign roles'],

    // Security & Activity Logs
    ['activity.view',    'activity', 'view',      'View Security & Logs',      'Inspect system audit trails and login events'],
];

foreach ($permissionsList as $p) {
    $existing = db_fetch_one("SELECT id FROM admin_permissions WHERE code = :code", ['code' => $p[0]]);
    if (!$existing) {
        db_insert('admin_permissions', [
            'code'        => $p[0],
            'module'      => $p[1],
            'action'      => $p[2],
            'name'        => $p[3],
            'description' => $p[4]
        ]);
    }
}
echo "[+] Seeded " . count($permissionsList) . " permission records.\n";

// Cache permission IDs by code
$permMap = [];
$allPerms = db_fetch_all("SELECT id, code FROM admin_permissions");
foreach ($allPerms as $ap) {
    $permMap[$ap['code']] = (int) $ap['id'];
}

// 8. Seed Predefined Roles & Map Default Permissions
$rolesList = [
    [
        'slug'        => 'super_admin',
        'name'        => 'Super Admin',
        'department'  => 'Executive Administration',
        'description' => 'Full administrative access across all modules, personnel management, website settings, and security audit logs.',
        'is_system'   => 1,
        'permissions' => array_keys($permMap) // ALL permissions
    ],
    [
        'slug'        => 'blog_manager',
        'name'        => 'Blog Manager',
        'department'  => 'Content & Editorial',
        'description' => 'Manage editorial articles, drafting, editing, publishing, and blog media assets.',
        'is_system'   => 1,
        'permissions' => ['blog.view', 'blog.create', 'blog.edit', 'blog.publish', 'blog.delete']
    ],
    [
        'slug'        => 'enquiry_executive',
        'name'        => 'Enquiry Executive',
        'department'  => 'Sales & Customer Inquiries',
        'description' => 'Review incoming client print specifications, update enquiry status, add internal notes, and follow up.',
        'is_system'   => 1,
        'permissions' => ['enquiries.view', 'enquiries.status', 'enquiries.notes', 'enquiries.export']
    ],
    [
        'slug'        => 'quotation_executive',
        'name'        => 'Quotation Executive',
        'department'  => 'Commercial Prepress & Pricing',
        'description' => 'Draft, calculate, itemize, and issue formal quotations and pricing proposals to customers.',
        'is_system'   => 1,
        'permissions' => ['enquiries.view', 'quotations.view', 'quotations.create', 'quotations.edit', 'quotations.send', 'quotations.status']
    ],
    [
        'slug'        => 'customer_support',
        'name'        => 'Customer Support',
        'department'  => 'Client Services',
        'description' => 'Assist clients with account profiles, review enquiries, and check linked quotation statuses.',
        'is_system'   => 1,
        'permissions' => ['customers.view', 'enquiries.view', 'quotations.view']
    ],
    [
        'slug'        => 'product_manager',
        'name'        => 'Product Manager',
        'department'  => 'Catalogue & Merchandise',
        'description' => 'Manage printing products, custom specifications, SKU codes, pricing, and variations.',
        'is_system'   => 1,
        'permissions' => ['products.view', 'products.create', 'products.edit', 'products.delete', 'products.variations']
    ],
    [
        'slug'        => 'category_manager',
        'name'        => 'Category Manager',
        'department'  => 'Catalogue Structure',
        'description' => 'Organize product collections, category slugs, descriptive content, and display sequences.',
        'is_system'   => 1,
        'permissions' => ['categories.view', 'categories.create', 'categories.edit', 'categories.delete']
    ],
    [
        'slug'        => 'portfolio_manager',
        'name'        => 'Portfolio Manager',
        'department'  => 'Creative Studio Showcase',
        'description' => 'Curate studio printing portfolio, upload print specimen galleries, and manage project spotlights.',
        'is_system'   => 1,
        'permissions' => ['portfolio.view', 'portfolio.create', 'portfolio.edit', 'portfolio.publish', 'portfolio.delete']
    ],
    [
        'slug'        => 'services_manager',
        'name'        => 'Printing Services Manager',
        'department'  => 'Production & Capabilities',
        'description' => 'Manage commercial printing services, machinery capabilities, and process overviews.',
        'is_system'   => 1,
        'permissions' => ['services.view', 'services.create', 'services.edit', 'services.delete']
    ],
    [
        'slug'        => 'reviews_manager',
        'name'        => 'Reviews Manager',
        'department'  => 'Brand Reputation',
        'description' => 'Moderate, approve, and curate verified customer testimonials and print ratings.',
        'is_system'   => 1,
        'permissions' => ['reviews.view', 'reviews.approve', 'reviews.delete']
    ],
    [
        'slug'        => 'content_manager',
        'name'        => 'Website Content Manager',
        'department'  => 'Marketing & CMS',
        'description' => 'Manage homepage sections, announcements, editorial text, and public landing pages.',
        'is_system'   => 1,
        'permissions' => ['content.view', 'content.edit']
    ],
    [
        'slug'        => 'seo_manager',
        'name'        => 'Website / SEO Manager',
        'department'  => 'Search & AI Discovery',
        'description' => 'Manage sitemap.xml, llms.txt, schema metadata, and SEO synchronization.',
        'is_system'   => 1,
        'permissions' => ['seo.view', 'seo.manage']
    ],
];

foreach ($rolesList as $r) {
    $existingRole = db_fetch_one("SELECT id FROM admin_roles WHERE slug = :slug", ['slug' => $r['slug']]);
    if (!$existingRole) {
        $roleId = db_insert('admin_roles', [
            'slug'        => $r['slug'],
            'name'        => $r['name'],
            'department'  => $r['department'],
            'description' => $r['description'],
            'is_system'   => $r['is_system']
        ]);
    } else {
        $roleId = (int) $existingRole['id'];
        db_update('admin_roles', [
            'name'        => $r['name'],
            'department'  => $r['department'],
            'description' => $r['description'],
            'is_system'   => $r['is_system']
        ], 'id = :id', ['id' => $roleId]);
    }

    // Assign permissions to role
    foreach ($r['permissions'] as $pCode) {
        if (isset($permMap[$pCode])) {
            $pId = $permMap[$pCode];
            $hasMapping = db_fetch_one("SELECT 1 FROM admin_role_permissions WHERE role_id = :rid AND permission_id = :pid", [
                'rid' => $roleId,
                'pid' => $pId
            ]);
            if (!$hasMapping) {
                db_insert('admin_role_permissions', [
                    'role_id'       => $roleId,
                    'permission_id' => $pId
                ]);
            }
        }
    }
}
echo "[+] Seeded " . count($rolesList) . " predefined system roles and their permission mappings.\n";

// 9. Assign Super Admin role to existing admin users
$superAdminRole = db_fetch_one("SELECT id FROM admin_roles WHERE slug = 'super_admin'");
if ($superAdminRole) {
    $adminUsers = db_fetch_all("SELECT id, email, role FROM users WHERE role = 'admin'");
    foreach ($adminUsers as $au) {
        $hasRole = db_fetch_one("SELECT 1 FROM admin_user_roles WHERE user_id = :uid AND role_id = :rid", [
            'uid' => $au['id'],
            'rid' => $superAdminRole['id']
        ]);
        if (!$hasRole) {
            db_insert('admin_user_roles', [
                'user_id' => $au['id'],
                'role_id' => $superAdminRole['id']
            ]);
            echo "[+] Linked Super Admin role to user {$au['email']} (ID: {$au['id']}).\n";
        }
    }
}

echo "=== Migration Finished Successfully! ===\n";

