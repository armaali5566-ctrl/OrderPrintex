<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo_helper.php';

$baseUrl = 'http://127.0.0.1:8080';

$testUrls = [
    '/' => 'Home',
    '/about' => 'About',
    '/services' => 'Services overview',
    '/products' => 'Products catalog',
    '/gallery' => 'Gallery',
    '/blog' => 'Blog listing',
    '/contact' => 'Contact',
    '/faq' => 'FAQ',
    '/pricing' => 'Pricing',
    '/get-quote' => 'Get Quote'
];

// Add all active services
$services = db_fetch_all("SELECT slug, title FROM services WHERE is_active = 1 ORDER BY display_order ASC");
foreach ($services as $s) {
    $testUrls['/services/' . $s['slug']] = 'Service: ' . $s['title'];
}

// Add all active products
$products = db_fetch_all("SELECT slug, title FROM products WHERE is_active = 1 ORDER BY display_order ASC");
foreach ($products as $p) {
    $testUrls['/product/' . $p['slug']] = 'Product: ' . $p['title'];
}

// Add all published blog posts
$posts = db_fetch_all("SELECT slug, title FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC");
foreach ($posts as $b) {
    $testUrls['/blog/' . $b['slug']] = 'Blog: ' . $b['title'];
}

$summaryTable = [];

foreach ($testUrls as $path => $name) {
    $html = @file_get_contents($baseUrl . $path);
    if ($html === false) {
        $summaryTable[] = [
            'url' => $path,
            'title' => $name,
            'status' => 'HTTP_FAIL',
            'blocks' => 0,
            'types' => [],
            'valid' => 'NO',
            'errors' => ['Failed to fetch page']
        ];
        continue;
    }
    
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);
    $blocks = $matches[1] ?? [];
    $types = [];
    $validJson = true;
    $errors = [];
    
    foreach ($blocks as $idx => $block) {
        $data = json_decode(trim($block), true);
        if ($data === null) {
            $validJson = false;
            $types[] = 'INVALID_JSON';
            $errors[] = "Block #{$idx}: JSON syntax error: " . json_last_error_msg();
        } else {
            $type = $data['@type'] ?? 'NoType';
            $types[] = $type;
            if (empty($data['@context']) || $data['@context'] !== 'https://schema.org') {
                $errors[] = "Block #{$idx} ({$type}): Missing or incorrect @context";
            }
            if (empty($data['@id'])) {
                $errors[] = "Block #{$idx} ({$type}): Missing @id";
            }
        }
    }
    
    $summaryTable[] = [
        'url' => $path,
        'title' => $name,
        'status' => '200 OK',
        'blocks' => count($blocks),
        'types' => $types,
        'valid' => $validJson ? 'YES' : 'NO',
        'errors' => $errors
    ];
}

echo "TOTAL TESTED URLS: " . count($summaryTable) . "\n\n";
echo sprintf("%-42s | %-6s | %-6s | %s\n", "URL", "BLOCKS", "VALID", "DETECTED SCHEMA TYPES");
echo str_repeat("-", 115) . "\n";
foreach ($summaryTable as $row) {
    echo sprintf("%-42s | %-6d | %-6s | %s\n", 
        $row['url'], 
        $row['blocks'], 
        $row['valid'], 
        implode(', ', $row['types'])
    );
    if (!empty($row['errors'])) {
        foreach ($row['errors'] as $err) {
            echo "   !! ERROR: $err\n";
        }
    }
}
