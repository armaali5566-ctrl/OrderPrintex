<?php
/**
 * OrderPrintex - Local Development Server Router
 * Enables clean SEO URLs:
 *   /category/printing-materials/
 *   /product/bookmark-03/
 *   /services/dtf-printing/
 *   /blog/
 *   /blog/complete-guide-to-business-card-printing/
 *   /about/
 *   /services/
 *   /contact/
 *   /pricing/
 *   /cart/
 *   /checkout/
 *   /account/
 *   /account/orders/
 */

$rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$decodedUri = rawurldecode($rawUri);

// If running with built-in server and request path has /OrderPrintex prefix, strip it
if (preg_match('~^/OrderPrintex(/.*)?$~i', $decodedUri, $m)) {
    $decodedUri = !empty($m[1]) ? $m[1] : '/';
}

$uri = $decodedUri;
$trimmedUri = rtrim($uri, '/');

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

// 1b. LLMS.txt
if ($uri === '/llms.txt') {
    $file = __DIR__ . '/llms.txt';
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

// 2b. Schema.org JSON
if ($uri === '/schema.json') {
    $file = __DIR__ . '/schema.json';
    if (file_exists($file)) {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: inline');
        readfile($file);
        exit;
    }
}

// 2c. Main Markdown Discovery File
if ($uri === '/ORDERPRINTEX.md' || $uri === '/orderprintex.md') {
    $file = __DIR__ . '/ORDERPRINTEX.md';
    if (file_exists($file)) {
        header('Content-Type: text/markdown; charset=utf-8');
        header('Content-Disposition: inline');
        readfile($file);
        exit;
    }
}

// 3. 301 Canonical Redirects from legacy .php and query-string URLs
if ($uri === '/products.php') {
    if (!empty($_GET['category'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /product/category/' . rawurlencode($_GET['category']) . '/');
        exit;
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /products/');
    exit;
}

if ($uri === '/product-details.php') {
    if (!empty($_GET['slug'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /product/' . rawurlencode($_GET['slug']) . '/');
        exit;
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /products/');
    exit;
}

if ($uri === '/blog-details.php') {
    if (!empty($_GET['slug'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /blog/' . rawurlencode($_GET['slug']) . '/');
        exit;
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /blog/');
    exit;
}

$legacyRedirects = [
    '/about.php' => '/about/',
    '/services.php' => '/services/',
    '/contact.php' => '/contact/',
    '/blog.php' => '/blog/',
    '/pricing.php' => '/pricing/',
    '/cart.php' => '/cart/',
    '/checkout.php' => '/checkout/',
    '/gallery.php' => '/gallery/',
    '/faq.php' => '/faq/',
    '/get-quote.php' => '/get-quote/'
];
if (isset($legacyRedirects[$uri])) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $legacyRedirects[$uri]);
    exit;
}

// 4. Clean Category URL: /product/category/{slug}/ or /product/category/{slug}
if (preg_match('#^/product/category/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $_GET['category'] = $matches[1];
    require __DIR__ . '/products.php';
    exit;
}

// 4b. 301 Permanent Redirect for Legacy Category URL: /category/{slug}/ -> /product/category/{slug}/
if (preg_match('#^/category/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /product/category/' . $matches[1] . '/');
    exit;
}

// 5. Clean Product URL: /product/{slug}/ or /product/{slug}
if (preg_match('#^/product/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/product-details.php';
    exit;
}

// 6. Legacy Product URL: /products/{slug}/ -> 301 redirect to /product/{slug}/
if (preg_match('#^/products/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /product/' . $matches[1] . '/');
    exit;
}

// 7. Clean Products Catalog: /products or /products/
if ($trimmedUri === '/products') {
    require __DIR__ . '/products.php';
    exit;
}

// 8. Clean Shopping Pages: /pricing/, /cart/, /checkout/
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

// 9. Clean Customer Account Pages: /account/, /account/orders/, etc.
if (preg_match('#^/account/orders/([0-9]+)/?$#', $uri, $matches)) {
    $_GET['id'] = $matches[1];
    require __DIR__ . '/account/order-details.php';
    exit;
}

if ($trimmedUri === '/account/orders') {
    require __DIR__ . '/account/orders.php';
    exit;
}

if (preg_match('#^/account/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
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

// 10. Admin Panel Entry Point & Subroutes: /admin, /admin/, /admin/{page}
if ($trimmedUri === '/admin') {
    require __DIR__ . '/admin/index.php';
    exit;
}

if (preg_match('#^/admin/([a-zA-Z0-9_-]+)(?:\.php)?/?$#', $uri, $matches)) {
    $adminFile = __DIR__ . '/admin/' . $matches[1] . '.php';
    if (file_exists($adminFile)) {
        require $adminFile;
        exit;
    }
}

// 11. Clean Services Subpages: /services/{slug}/ or /services/{slug}
if (preg_match('#^/services/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $svcFile = __DIR__ . '/services/' . $matches[1] . '.php';
    if (file_exists($svcFile)) {
        require $svcFile;
        exit;
    }
}

// 11. Clean Services Overview: /services or /services/
if ($trimmedUri === '/services') {
    require __DIR__ . '/services.php';
    exit;
}

// 12. Clean Blog Category: /blog/category/{slug}/ or /blog/category/{slug}
if (preg_match('#^/blog/category/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $_GET['category'] = $matches[1];
    require __DIR__ . '/blog.php';
    exit;
}

// 13. Clean Blog Details: /blog/{slug}/ or /blog/{slug}
if (preg_match('#^/blog/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/blog-details.php';
    exit;
}

// 14. Clean Blog Listing: /blog or /blog/
if ($trimmedUri === '/blog') {
    require __DIR__ . '/blog.php';
    exit;
}

// 14. Clean About Us: /about or /about/
if ($trimmedUri === '/about') {
    require __DIR__ . '/about.php';
    exit;
}

// 15. Clean Contact: /contact or /contact/
if ($trimmedUri === '/contact') {
    require __DIR__ . '/contact.php';
    exit;
}

// 16. Clean Gallery: /gallery or /gallery/
if ($trimmedUri === '/gallery') {
    require __DIR__ . '/gallery.php';
    exit;
}

// 17. Clean Get Quote: /get-quote or /get-quote/
if ($trimmedUri === '/get-quote') {
    require __DIR__ . '/get-quote.php';
    exit;
}

// 18. Clean FAQ: /faq or /faq/
if ($trimmedUri === '/faq') {
    require __DIR__ . '/faq.php';
    exit;
}

// 19. Direct Static Files or Existing PHP Scripts
$targetPath = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $uri);

if ($uri !== '/') {
    // If it's a directory containing index.php
    if (is_dir($targetPath) && file_exists($targetPath . DIRECTORY_SEPARATOR . 'index.php')) {
        require $targetPath . DIRECTORY_SEPARATOR . 'index.php';
        exit;
    }

    // Check candidate static file locations (exact, spaces as underscores, underscores as spaces)
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
                'md'    => 'text/plain; charset=utf-8',
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

// 20. Root URL: /
if ($uri === '' || $uri === '/') {
    require __DIR__ . '/index.php';
    exit;
}

// 21. 404 Fallback for unhandled routes
http_response_code(404);
require __DIR__ . '/404.php';
exit;
