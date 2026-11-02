<?php
/**
 * OrderPrintex - Local Development Server Router
 * Enables clean SEO URLs:
 *   /product/bookmark-03/
 *   /services/dtf-printing/
 *   /blog/
 *   /blog/complete-guide-to-business-card-printing/
 *   /about/
 *   /contact/
 *   /gallery/
 *   /get-quote/
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$trimmedUri = rtrim($uri, '/');

// 1. Robots.txt
if ($uri === '/robots.txt') {
    $file = __DIR__ . '/robots.txt';
    if (file_exists($file)) {
        header('Content-Type: text/plain; charset=utf-8');
        readfile($file);
        exit;
    }
}

// 2. Sitemap.xml
if ($uri === '/sitemap.xml') {
    $file = __DIR__ . '/sitemap.xml';
    if (file_exists($file)) {
        header('Content-Type: application/xml; charset=utf-8');
        readfile($file);
        exit;
    }
}

// 3. Clean Product URL: /product/{slug}/ or /product/{slug}
if (preg_match('#^/product/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/product-details.php';
    exit;
}

// 4. Legacy Product URL: /products/{slug}/ -> 301 redirect to /product/{slug}/
if (preg_match('#^/products/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /product/' . $matches[1] . '/');
    exit;
}

// 5. Clean Products Catalog: /products or /products/
if ($trimmedUri === '/products') {
    require __DIR__ . '/products.php';
    exit;
}

// 6. Clean Services Subpages: /services/{slug}/ or /services/{slug}
if (preg_match('#^/services/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $svcFile = __DIR__ . '/services/' . $matches[1] . '.php';
    if (file_exists($svcFile)) {
        require $svcFile;
        exit;
    }
}

// 7. Clean Services Overview: /services or /services/
if ($trimmedUri === '/services') {
    require __DIR__ . '/services.php';
    exit;
}

// 8. Clean Blog Details: /blog/{slug}/ or /blog/{slug}
if (preg_match('#^/blog/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/blog-details.php';
    exit;
}

// 9. Clean Blog Listing: /blog or /blog/
if ($trimmedUri === '/blog') {
    require __DIR__ . '/blog.php';
    exit;
}

// 10. Clean About Us: /about or /about/
if ($trimmedUri === '/about') {
    require __DIR__ . '/about.php';
    exit;
}

// 11. Clean Contact: /contact or /contact/
if ($trimmedUri === '/contact') {
    require __DIR__ . '/contact.php';
    exit;
}

// 12. Clean Gallery: /gallery or /gallery/
if ($trimmedUri === '/gallery') {
    require __DIR__ . '/gallery.php';
    exit;
}

// 13. Clean Get Quote: /get-quote or /get-quote/
if ($trimmedUri === '/get-quote') {
    require __DIR__ . '/get-quote.php';
    exit;
}

// 14. Clean FAQ: /faq or /faq/
if ($trimmedUri === '/faq') {
    require __DIR__ . '/faq.php';
    exit;
}

// 15. Direct Static Files or Existing PHP Scripts
$targetPath = __DIR__ . $uri;

if ($uri !== '/' && file_exists($targetPath)) {
    if (!is_dir($targetPath)) {
        return false; // Let PHP built-in server serve static file or direct php script
    }
    if (file_exists($targetPath . '/index.php')) {
        require $targetPath . '/index.php';
        exit;
    }
}

// 16. Root URL: /
if ($uri === '' || $uri === '/') {
    require __DIR__ . '/index.php';
    exit;
}

// 17. 404 Fallback for unhandled routes
http_response_code(404);
require __DIR__ . '/404.php';
exit;
