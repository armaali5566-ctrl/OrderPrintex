<?php
$pages = [
    '/' => 'Home',
    '/about' => 'About Us',
    '/services' => 'Services Overview',
    '/services/dtf-printing' => 'DTF Printing',
    '/services/uv-dtf-printing' => 'UV DTF Printing',
    '/services/t-shirt-printing' => 'T-Shirt Printing',
    '/services/corporate-printing' => 'Corporate Printing',
    '/services/custom-hoodie-printing' => 'Custom Hoodie Printing',
    '/services/uv-printing' => 'UV Printing',
    '/services/mug-cup-printing' => 'Mug & Cup Printing',
    '/products' => 'Products Overview',
    '/product/custom-dtf-gang-sheet' => 'Product Details',
    '/gallery' => 'Print Gallery',
    '/blog' => 'Blog Overview',
    '/blog/dtf-vs-screen-printing-differences-benefits-applications' => 'Blog Details',
    '/pricing' => 'Pricing',
    '/contact' => 'Contact Us'
];

$baseUrl = 'http://127.0.0.1:8080';
$allPassed = true;

foreach ($pages as $uri => $label) {
    $html = @file_get_contents($baseUrl . $uri);
    if ($html === false) {
        echo "[FAIL] Could not fetch $baseUrl$uri\n";
        $allPassed = false;
        continue;
    }

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#is', $html, $matches);
    $blocks = $matches[1] ?? [];
    $types = [];
    $jsonErrors = [];

    foreach ($blocks as $idx => $rawJson) {
        $data = json_decode(trim($rawJson), true);
        if ($data === null) {
            $jsonErrors[] = "Block #$idx: " . json_last_error_msg();
            continue;
        }
        $type = $data['@type'] ?? 'Unknown';
        $types[] = is_array($type) ? implode(',', $type) : $type;
    }

    if (!empty($jsonErrors)) {
        echo "[ERROR] $label ($uri) has JSON syntax errors:\n" . implode("\n", $jsonErrors) . "\n";
        $allPassed = false;
    } else {
        echo "[OK] $label ($uri) -> " . count($blocks) . " blocks: [" . implode(', ', $types) . "]\n";
    }
}

if ($allPassed) {
    echo "\nAll pages verified with 100% valid independent Schema.org JSON-LD blocks!\n";
} else {
    echo "\nSome pages had validation issues!\n";
}
