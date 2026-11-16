<?php
echo "Testing via HTTP live server...\n\n";

// 1. Test Product Page
$prodHtml = file_get_contents('http://127.0.0.1:8080/products/custom-dtf-gang-sheet');
echo "Product Page HTTP response length: " . strlen($prodHtml) . " bytes\n";
echo "Product Page Warnings: " . (strpos($prodHtml, '<b>Warning</b>') !== false ? 'YES' : 'NONE') . "\n";

// Check WhatsApp quote button specifically
if (preg_match('/<a\s+href="([^"]*wa\.me\/919354028199\?text=[^"]*)"[^>]*>\s*<span>WhatsApp Prepress Desk<\/span>/i', $prodHtml, $waMatch)) {
    echo "Product WhatsApp Button URL: " . $waMatch[1] . "\n";
    echo "Decoded: " . urldecode($waMatch[1]) . "\n";
} else {
    echo "Product WhatsApp button regex did not match, listing all wa.me links:\n";
    preg_match_all('/href="([^"]*wa\.me\/919354028199\?text=[^"]*)"/i', $prodHtml, $allWa);
    foreach ($allWa[1] as $idx => $link) {
        echo "Link {$idx}: " . urldecode($link) . "\n";
    }
}

// 2. Test Blog Details Page
$blogHtml = file_get_contents('http://127.0.0.1:8080/blog/dtf-printing-technology-custom-apparel');
echo "\nBlog Details HTTP response length: " . strlen($blogHtml) . " bytes\n";

preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $blogHtml, $jsonBlocks);
echo "Total JSON-LD blocks on Blog Details: " . count($jsonBlocks[1]) . "\n";
foreach ($jsonBlocks[1] as $idx => $jb) {
    $dec = json_decode($jb, true);
    echo "  " . ($idx + 1) . ". @type: " . ($dec['@type'] ?? 'unknown') . " | @id: " . ($dec['@id'] ?? 'none') . "\n";
}
