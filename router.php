<?php
/**
 * OrderPrintex - Local Development Server Router
 * Clean URLs without trailing slash:
 *   /product/category/uv-dtf-decals
 *   /product/bio-washed-cotton-tee
 *   /services/dtf-printing
 *   /blog
 *   /blog/category/packaging-labels
 *   /blog/different-t-shirt-printing-techniques
 *   /about
 *   /services
 *   /contact
 *   /pricing
 *   /gallery
 *   /faq
 *   /get-quote
 *   /schema/{name}.json
 *   /{name}.md
 */

$rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$decodedUri = rawurldecode($rawUri);

// If running with built-in server and request path has /OrderPrintex prefix, strip it
if (preg_match('~^/OrderPrintex(/.*)?$~i', $decodedUri, $m)) {
    $decodedUri = !empty($m[1]) ? $m[1] : '/';
}

$uri = $decodedUri;
$trimmedUri = rtrim($uri, '/');

// 0. Trailing Slash 301 Redirect: Remove trailing slash from all URLs except root '/'
if ($uri !== '/' && substr($uri, -1) === '/') {
    $noSlash = rtrim($uri, '/');
    $qs = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $noSlash . $qs);
    exit;
}

// 1. Robots.txt
if ($uri === '/robots.txt') {
    $file = __DIR__ . '/robots.txt';
    if (file_exists($file)) {
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: inline');
        readfile($file);
        exit;
    }
}

// 1b. LLMS.txt & LLMS-FULL.txt
if ($uri === '/llms.txt' || $uri === '/llms-full.txt') {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: inline');
        readfile($file);
        exit;
    }
}

// 2. Sitemap XML & Sitemap Index
if ($uri === '/sitemap.xml' || $uri === '/sitemap_index.xml') {
    $file = __DIR__ . $uri;
    if (!file_exists($file)) {
        $file = __DIR__ . '/sitemap.xml';
    }
    if (file_exists($file)) {
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: inline');
        readfile($file);
        exit;
    }
}

// 2b. Central Schema.org Graph JSON
if ($uri === '/schema.json') {
    $file = __DIR__ . '/schema.json';
    if (file_exists($file)) {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: inline');
        readfile($file);
        exit;
    }
}

// 2c. Central Markdown Discovery Files: /ORDERPRINTEX.md and /SITE_OVERVIEW.md
if (in_array(strtolower($uri), ['/orderprintex.md', '/site_overview.md'])) {
    $targetName = (strtolower($uri) === '/site_overview.md') ? '/SITE_OVERVIEW.md' : '/ORDERPRINTEX.md';
    $file = __DIR__ . $targetName;
    if (file_exists($file)) {
        header('Content-Type: text/markdown; charset=utf-8');
        header('Content-Disposition: inline');
        readfile($file);
        exit;
    }
}

// 2d. Direct Access to /seo/schema/{filename}.json
if (preg_match('#^/seo/schema/([a-zA-Z0-9_.-]+)\.json$#i', $uri, $matches)) {
    $file = __DIR__ . '/seo/schema/' . $matches[1] . '.json';
    if (file_exists($file) && !is_dir($file)) {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: inline');
        header('Cache-Control: public, max-age=3600');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        http_response_code(404);
        require __DIR__ . '/404.php';
        exit;
    }
}

// 2e. Markdown Page Routing: Canonical URL + .md -> seo/markdown/{file}.md
// Supports all public pages: /blog/{slug}.md, /product/{slug}.md,
// /services/{slug}.md, /product/category/{slug}.md, /about-us.md, /contact.md, /products.md, etc.
if (preg_match('#^/(.+)\.md$#i', $uri, $matches)) {
    $rawPath = $matches[1];
    $mdDir = __DIR__ . '/seo/markdown';
    $found = null;

    // Direct /seo/markdown/ path
    if (str_starts_with($rawPath, 'seo/markdown/')) {
        $candidate = __DIR__ . '/' . $rawPath . '.md';
        if (file_exists($candidate) && !is_dir($candidate)) {
            $found = $candidate;
        }
    }
    // Blog article: /blog/{slug}.md
    elseif (preg_match('#^blog/([a-zA-Z0-9_-]+)$#i', $rawPath, $pm)) {
        $slug = strtolower($pm[1]);
        $cands = [
            $mdDir . '/blog-' . $slug . '.md',
            $mdDir . '/' . $slug . '.md'
        ];
        foreach ($cands as $c) {
            if (file_exists($c) && !is_dir($c)) { $found = $c; break; }
        }
    }
    // Blog category: /blog/category/{slug}.md
    elseif (preg_match('#^blog/category/([a-zA-Z0-9_-]+)$#i', $rawPath, $pm)) {
        $slug = strtolower($pm[1]);
        $cands = [
            $mdDir . '/blog-category-' . $slug . '.md',
            $mdDir . '/' . $slug . '.md'
        ];
        foreach ($cands as $c) {
            if (file_exists($c) && !is_dir($c)) { $found = $c; break; }
        }
    }
    // Product category: /product/category/{slug}.md or /category/{slug}.md
    elseif (preg_match('#^(?:product/)?category/([a-zA-Z0-9_-]+)$#i', $rawPath, $pm)) {
        $slug = strtolower($pm[1]);
        $cands = [
            $mdDir . '/category-' . $slug . '.md',
            $mdDir . '/product-category-' . $slug . '.md',
            $mdDir . '/' . $slug . '.md'
        ];
        foreach ($cands as $c) {
            if (file_exists($c) && !is_dir($c)) { $found = $c; break; }
        }
    }
    // Product details: /product/{slug}.md or /products/{slug}.md
    elseif (preg_match('#^products?/([a-zA-Z0-9_-]+)$#i', $rawPath, $pm)) {
        $slug = strtolower($pm[1]);
        $cands = [
            $mdDir . '/product-' . $slug . '.md',
            $mdDir . '/' . $slug . '.md'
        ];
        foreach ($cands as $c) {
            if (file_exists($c) && !is_dir($c)) { $found = $c; break; }
        }
    }
    // Service details: /services/{slug}.md
    elseif (preg_match('#^services/([a-zA-Z0-9_-]+)$#i', $rawPath, $pm)) {
        $slug = strtolower($pm[1]);
        $cands = [
            $mdDir . '/services-' . $slug . '.md',
            $mdDir . '/' . $slug . '.md'
        ];
        foreach ($cands as $c) {
            if (file_exists($c) && !is_dir($c)) { $found = $c; break; }
        }
    }

    // Generic / single-segment fallback (e.g. /about-us.md, /contact.md, /products.md, /services.md)
    if (!$found) {
        $slug = strtolower($rawPath);
        $safeSlug = preg_replace('/[^a-zA-Z0-9_-]/', '', $slug);
        $cands = [
            $mdDir . '/' . $safeSlug . '.md',
            $mdDir . '/page-' . $safeSlug . '.md',
            $mdDir . '/blog-' . $safeSlug . '.md',
            $mdDir . '/product-' . $safeSlug . '.md',
            $mdDir . '/services-' . $safeSlug . '.md',
            $mdDir . '/category-' . $safeSlug . '.md',
            $mdDir . '/blog-category-' . $safeSlug . '.md',
            __DIR__ . '/' . strtoupper($safeSlug) . '.md',
            __DIR__ . '/' . $safeSlug . '.md'
        ];
        if ($safeSlug === 'about-us' || $safeSlug === 'about') {
            array_unshift($cands, $mdDir . '/about.md', $mdDir . '/page-about-us.md', $mdDir . '/about-us.md');
        }
        foreach ($cands as $c) {
            if (file_exists($c) && !is_dir($c)) {
                $found = $c;
                break;
            }
        }
    }

    if ($found) {
        header('Content-Type: text/markdown; charset=utf-8');
        header('Content-Disposition: inline');
        header('Cache-Control: public, max-age=3600');
        header('Content-Length: ' . filesize($found));
        readfile($found);
        exit;
    } else {
        http_response_code(404);
        require __DIR__ . '/404.php';
        exit;
    }
}

// 2f. Page-Specific Schema JSON Route: /schema/{name}.json
if (preg_match('#^/schema/([a-zA-Z0-9_-]+)\.json$#i', $uri, $matches)) {
    $slug = strtolower($matches[1]);
    $candidates = [
        __DIR__ . '/seo/schema/' . $slug . '.json',
        __DIR__ . '/seo/schema/' . $matches[1] . '.json',
        __DIR__ . '/seo/schema/blog-' . $slug . '.json',
        __DIR__ . '/seo/schema/product-' . $slug . '.json',
        __DIR__ . '/seo/schema/category-' . $slug . '.json',
        __DIR__ . '/seo/schema/blog-category-' . $slug . '.json',
        __DIR__ . '/seo/schema/services-' . $slug . '.json',
        __DIR__ . '/seo/schema/page-' . $slug . '.json'
    ];
    $found = null;
    foreach ($candidates as $c) {
        if (file_exists($c) && !is_dir($c)) {
            $found = $c;
            break;
        }
    }
    if ($found) {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: inline');
        header('Cache-Control: public, max-age=3600');
        header('Content-Length: ' . filesize($found));
        readfile($found);
        exit;
    } else {
        http_response_code(404);
        require __DIR__ . '/404.php';
        exit;
    }
}

// 3. Application Core Bootstrap for Dynamic Page Routes
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

// 3b. 301 Canonical Redirects from legacy .php and query-string URLs
if ($uri === '/products.php') {
    if (!empty($_GET['category'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /product/category/' . rawurlencode($_GET['category']));
        exit;
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /products');
    exit;
}

if ($uri === '/product-details.php') {
    if (!empty($_GET['slug'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /product/' . rawurlencode($_GET['slug']));
        exit;
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /products');
    exit;
}

if ($uri === '/blog-details.php') {
    if (!empty($_GET['slug'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /blog/' . rawurlencode($_GET['slug']));
        exit;
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /blog');
    exit;
}

$legacyRedirects = [
    '/about.php' => '/about',
    '/services.php' => '/services',
    '/contact.php' => '/contact',
    '/blog.php' => '/blog',
    '/pricing.php' => '/pricing',
    '/cart.php' => '/cart',
    '/checkout.php' => '/checkout',
    '/gallery.php' => '/gallery',
    '/faq.php' => '/faq',
    '/get-quote.php' => '/get-quote'
];
if (isset($legacyRedirects[$uri])) {
    $target = $legacyRedirects[$uri];
    if (!empty($_SERVER['QUERY_STRING'])) {
        $target .= '?' . $_SERVER['QUERY_STRING'];
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $target);
    exit;
}

// 4. Clean Category URL: /product/category/{slug}
if (preg_match('#^/product/category/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    $_GET['category'] = $matches[1];
    require __DIR__ . '/products.php';
    exit;
}

// 4b. 301 Permanent Redirect for Legacy Category URL: /category/{slug} -> /product/category/{slug}
if (preg_match('#^/category/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /product/category/' . $matches[1]);
    exit;
}

// 5. Clean Product URL: /product/{slug}
if (preg_match('#^/product/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/product-details.php';
    exit;
}

// 6. Legacy Product URL: /products/{slug} -> 301 redirect to /product/{slug}
if (preg_match('#^/products/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /product/' . $matches[1]);
    exit;
}

// 7. Clean Products Catalog: /products
if ($trimmedUri === '/products') {
    require __DIR__ . '/products.php';
    exit;
}

// 8. Clean Shopping Pages: /pricing, /cart, /checkout
if ($trimmedUri === '/pricing') {
    require __DIR__ . '/pricing.php';
    exit;
}

if ($trimmedUri === '/cart') {
    require __DIR__ . '/cart.php';
    exit;
}

if ($trimmedUri === '/checkout') {
    require __DIR__ . '/checkout.php';
    exit;
}

// 9. Clean Customer Account Pages: /account, /account/orders, etc.
if (preg_match('#^/account/orders/([0-9]+)$#', $uri, $matches)) {
    $_GET['id'] = $matches[1];
    require __DIR__ . '/account/order-details.php';
    exit;
}

if ($trimmedUri === '/account/orders') {
    require __DIR__ . '/account/orders.php';
    exit;
}

if (preg_match('#^/account/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    $accountFile = __DIR__ . '/account/' . $matches[1] . '.php';
    if (file_exists($accountFile)) {
        require $accountFile;
        exit;
    }
}

if ($trimmedUri === '/account') {
    require __DIR__ . '/account/dashboard.php';
    exit;
}

if ($trimmedUri === '/login') {
    require __DIR__ . '/account/login.php';
    exit;
}

if ($trimmedUri === '/register') {
    require __DIR__ . '/account/register.php';
    exit;
}

if ($trimmedUri === '/logout') {
    require __DIR__ . '/account/logout.php';
    exit;
}

// 10. Admin Panel Entry Point & Subroutes: /admin, /admin/{page}
if ($trimmedUri === '/admin') {
    require __DIR__ . '/admin/index.php';
    exit;
}

if (preg_match('#^/admin/([a-zA-Z0-9_-]+)(?:\.php)?$#', $uri, $matches)) {
    $adminFile = __DIR__ . '/admin/' . $matches[1] . '.php';
    if (file_exists($adminFile)) {
        require $adminFile;
        exit;
    }
}

// 11. Clean Services Subpages: /services/{slug}
if (preg_match('#^/services/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    $svcFile = __DIR__ . '/services/' . $matches[1] . '.php';
    if (file_exists($svcFile)) {
        require $svcFile;
        exit;
    }
}

// 11b. Clean Services Overview: /services
if ($trimmedUri === '/services') {
    require __DIR__ . '/services.php';
    exit;
}

// 12. Clean Blog Category: /blog/category/{slug}
if (preg_match('#^/blog/category/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    $_GET['category'] = $matches[1];
    require __DIR__ . '/blog.php';
    exit;
}

// 13. Clean Blog Details: /blog/{slug}
if (preg_match('#^/blog/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/blog-details.php';
    exit;
}

// 14. Clean Blog Listing: /blog
if ($trimmedUri === '/blog') {
    require __DIR__ . '/blog.php';
    exit;
}

// 15. Clean About Us: /about and /about-us
if ($trimmedUri === '/about' || $trimmedUri === '/about-us') {
    require __DIR__ . '/about.php';
    exit;
}

// 16. Clean Contact: /contact
if ($trimmedUri === '/contact') {
    require __DIR__ . '/contact.php';
    exit;
}

// 17. Clean Gallery: /gallery
if ($trimmedUri === '/gallery') {
    require __DIR__ . '/gallery.php';
    exit;
}

// 18. Clean Get Quote: /get-quote
if ($trimmedUri === '/get-quote') {
    require __DIR__ . '/get-quote.php';
    exit;
}

// 19. Clean FAQ: /faq
if ($trimmedUri === '/faq') {
    require __DIR__ . '/faq.php';
    exit;
}

// 20. Direct Static Files or Existing PHP Scripts
$targetPath = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $uri);

if ($uri !== '/') {
    // If it's a directory containing index.php
    if (is_dir($targetPath) && file_exists($targetPath . DIRECTORY_SEPARATOR . 'index.php')) {
        require $targetPath . DIRECTORY_SEPARATOR . 'index.php';
        exit;
    }

    // Check candidate static file locations
    $candidateFiles = [
        $targetPath,
        str_replace(' ', '_', $targetPath),
        str_replace('_', ' ', $targetPath)
    ];

    foreach ($candidateFiles as $file) {
        if (file_exists($file) && !is_dir($file)) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if ($ext === 'php') {
                require $file;
                exit;
            }

            $mimes = [
                'css'   => 'text/css; charset=utf-8',
                'js'    => 'application/javascript; charset=utf-8',
                'png'   => 'image/png',
                'jpg'   => 'image/jpeg',
                'jpeg'  => 'image/jpeg',
                'gif'   => 'image/gif',
                'webp'  => 'image/webp',
                'svg'   => 'image/svg+xml',
                'ico'   => 'image/x-icon',
                'woff2' => 'font/woff2',
                'woff'  => 'font/woff',
                'ttf'   => 'font/ttf',
                'pdf'   => 'application/pdf',
                'json'  => 'application/json; charset=utf-8',
                'txt'   => 'text/plain; charset=utf-8',
                'md'    => 'text/markdown; charset=utf-8',
                'xml'   => 'application/xml; charset=utf-8'
            ];
            $contentType = $mimes[$ext] ?? 'text/plain; charset=utf-8';
            header('Content-Type: ' . $contentType);
            header('Content-Disposition: inline');
            header('Content-Length: ' . filesize($file));
            header('Cache-Control: public, max-age=3600');
            readfile($file);
            exit;
        }
    }
}

// 21. Root URL: /
if ($uri === '' || $uri === '/') {
    require __DIR__ . '/index.php';
    exit;
}

// 22. 404 Fallback for unhandled routes
http_response_code(404);
require __DIR__ . '/404.php';
exit;
