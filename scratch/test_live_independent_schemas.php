<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo_helper.php';

$allowedSubTypes = [
    'PostalAddress', 'ImageObject', 'Offer', 'GeoCoordinates', 
    'OpeningHoursSpecification', 'ListItem', 'Question', 'Answer', 
    'Brand', 'Country', 'SearchAction', 'EntryPoint', 'AggregateRating', 'Person'
];

function check_nested_entities($data, $path, &$violations, $allowedSubTypes) {
    if (!is_array($data)) return;
    foreach ($data as $k => $v) {
        $currPath = $path ? "$path.$k" : $k;
        if (is_array($v)) {
            if (isset($v['@type'])) {
                $subType = $v['@type'];
                if (!in_array($subType, $allowedSubTypes)) {
                    $violations[] = "Nested entity at $currPath with @type: $subType";
                }
            }
            check_nested_entities($v, $currPath, $violations, $allowedSubTypes);
        }
    }
}

// 1. Direct unit verification on seo_get_page_schema_entities()
$keys = [
    'home' => 'Home',
    'about' => 'About',
    'services' => 'Services overview',
    'services-dtf-printing' => 'DTF Printing',
    'services-uv-dtf-printing' => 'UV DTF Printing',
    'services-t-shirt-printing' => 'T-Shirt Printing',
    'services-corporate-printing' => 'Corporate Printing',
    'services-custom-hoodie-printing' => 'Custom Hoodie',
    'services-uv-printing' => 'UV Printing',
    'services-mug-cup-printing' => 'Mug Printing',
    'products' => 'Products catalog',
    'product-details' => 'Product details',
    'gallery' => 'Gallery',
    'blog' => 'Blog listing',
    'blog-details' => 'Blog details',
    'contact' => 'Contact',
    'faq' => 'FAQ',
    'pricing' => 'Pricing',
    'get-quote' => 'Get Quote'
];

echo "=== SECTION 1: UNIT TEST OF seo_get_page_schema_entities() ===\n";
$unitPassed = true;
foreach ($keys as $k => $title) {
    $entities = seo_get_page_schema_entities($k);
    $violations = [];
    foreach ($entities as $idx => $entity) {
        $rootType = $entity['@type'] ?? 'Unknown';
        check_nested_entities($entity, "$k.[#$idx $rootType]", $violations, $allowedSubTypes);
    }
    $types = array_map(function($e) { return $e['@type']; }, $entities);
    if (!empty($violations)) {
        $unitPassed = false;
        echo "FAIL: $k -> " . implode(', ', $violations) . "\n";
    } else {
        echo sprintf("PASS: %-30s | [%d blocks] => %s\n", $k, count($entities), implode(', ', $types));
    }
}

if ($unitPassed) {
    echo "\nAll 19 keys passed unit testing with 0 nested full entities!\n\n";
}

// 2. Integration verification on Live HTTP Server (http://127.0.0.1:8080)
echo "=== SECTION 2: LIVE HTTP ENDPOINT VERIFICATION ===\n";

// Gather all public pages
$urls = [
    ['label' => 'Home', 'url' => 'http://127.0.0.1:8080/'],
    ['label' => 'About Us', 'url' => 'http://127.0.0.1:8080/about'],
    ['label' => 'Services Overview', 'url' => 'http://127.0.0.1:8080/services'],
    ['label' => 'Service: DTF Printing', 'url' => 'http://127.0.0.1:8080/services/dtf-printing'],
    ['label' => 'Service: UV DTF Printing', 'url' => 'http://127.0.0.1:8080/services/uv-dtf-printing'],
    ['label' => 'Service: T-Shirt Printing', 'url' => 'http://127.0.0.1:8080/services/t-shirt-printing'],
    ['label' => 'Service: Corporate Printing', 'url' => 'http://127.0.0.1:8080/services/corporate-printing'],
    ['label' => 'Service: Custom Hoodie Printing', 'url' => 'http://127.0.0.1:8080/services/custom-hoodie-printing'],
    ['label' => 'Service: UV Printing', 'url' => 'http://127.0.0.1:8080/services/uv-printing'],
    ['label' => 'Service: Mug & Cup Printing', 'url' => 'http://127.0.0.1:8080/services/mug-cup-printing'],
    ['label' => 'Products Catalog', 'url' => 'http://127.0.0.1:8080/products'],
];

// Query all active products
$products = db_fetch_all("SELECT slug, title FROM products WHERE is_active = 1 ORDER BY display_order ASC");
foreach ($products as $p) {
    $urls[] = ['label' => 'Product: ' . $p['title'], 'url' => 'http://127.0.0.1:8080/products/' . $p['slug']];
}

// Gallery, Blog, Contact, FAQ, Pricing, Quote
$urls[] = ['label' => 'Portfolio Gallery', 'url' => 'http://127.0.0.1:8080/gallery'];
$urls[] = ['label' => 'Blog Index', 'url' => 'http://127.0.0.1:8080/blog'];

// Query all published blog posts
$posts = db_fetch_all("SELECT slug, title FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC");
foreach ($posts as $post) {
    $urls[] = ['label' => 'Blog: ' . substr($post['title'], 0, 30), 'url' => 'http://127.0.0.1:8080/blog/' . $post['slug']];
}

$urls[] = ['label' => 'Contact Us', 'url' => 'http://127.0.0.1:8080/contact'];
$urls[] = ['label' => 'FAQ', 'url' => 'http://127.0.0.1:8080/faq'];
$urls[] = ['label' => 'Pricing', 'url' => 'http://127.0.0.1:8080/pricing'];
$urls[] = ['label' => 'Get Quote', 'url' => 'http://127.0.0.1:8080/get-quote'];

$resultsTable = [];
$allLivePassed = true;

foreach ($urls as $item) {
    $html = @file_get_contents($item['url']);
    if ($html === false) {
        echo "ERROR fetching {$item['url']}\n";
        $allLivePassed = false;
        continue;
    }

    // Extract all <script type="application/ld+json"> blocks
    preg_match_all('/<script\s+type=["\']application\/ld\+json["\']\s*>(.*?)<\/script>/is', $html, $matches);
    $blocks = $matches[1] ?? [];
    
    $parsedEntities = [];
    $errors = [];
    $violations = [];

    foreach ($blocks as $idx => $rawJson) {
        $decoded = json_decode(trim($rawJson), true);
        if ($decoded === null) {
            $errors[] = "Block #$idx JSON syntax error: " . json_last_error_msg();
            continue;
        }

        // Check @graph violation
        if (isset($decoded['@graph'])) {
            $errors[] = "Block #$idx contains @graph wrapper";
        }

        // Check @context
        if (($decoded['@context'] ?? '') !== 'https://schema.org') {
            $errors[] = "Block #$idx invalid @context: " . ($decoded['@context'] ?? 'missing');
        }

        // Check @id
        if (empty($decoded['@id'])) {
            $errors[] = "Block #$idx missing @id";
        }

        $type = $decoded['@type'] ?? 'Unknown';
        $parsedEntities[] = $type;

        // Check for nested full entities
        check_nested_entities($decoded, "Block #$idx ($type)", $violations, $allowedSubTypes);
    }

    if (!empty($errors) || !empty($violations)) {
        $allLivePassed = false;
    }

    $resultsTable[] = [
        'page' => $item['label'],
        'url' => str_replace('http://127.0.0.1:8080', '', $item['url']),
        'count' => count($parsedEntities),
        'types' => implode(', ', $parsedEntities),
        'nested_violations' => count($violations),
        'errors' => count($errors),
        'status' => (empty($errors) && empty($violations)) ? 'PASS' : 'FAIL'
    ];
}

echo "\nTotal pages verified: " . count($resultsTable) . "\n\n";
echo sprintf("%-35s | %-30s | %-5s | %-50s | %-6s\n", "Page", "Path", "Count", "Root Entities", "Status");
echo str_repeat('-', 135) . "\n";
foreach ($resultsTable as $r) {
    echo sprintf("%-35s | %-30s | %-5d | %-50s | %-6s\n", 
        substr($r['page'], 0, 35), 
        $r['url'] ?: '/', 
        $r['count'], 
        substr($r['types'], 0, 50), 
        $r['status']
    );
}

if ($allLivePassed) {
    echo "\n=== ALL " . count($resultsTable) . " PAGES PASSED ZERO ERRORS, ZERO NESTED FULL OBJECTS! ===\n";
} else {
    echo "\n=== SOME PAGES FAILED VERIFICATION ===\n";
}

file_put_contents(__DIR__ . '/verification_results.json', json_encode($resultsTable, JSON_PRETTY_PRINT));
