<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$_GET['slug'] = 'dtf-printing-technology-custom-apparel';
$_SERVER['REQUEST_URI'] = '/blog/dtf-printing-technology-custom-apparel';

ob_start();
require __DIR__ . '/../blog-details.php';
$html = ob_get_clean();

echo "Rendered length: " . strlen($html) . " bytes\n";

// Count independent ld+json script blocks
preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);
echo "Total JSON-LD script blocks: " . count($matches[1]) . "\n";
foreach ($matches[1] as $idx => $block) {
    $data = json_decode($block, true);
    $type = $data['@type'] ?? 'Unknown';
    $id = $data['@id'] ?? 'No @id';
    echo "  Block " . ($idx + 1) . ": Type = {$type}, ID = {$id}\n";
    if ($type === 'FAQPage') {
        echo "    FAQ Count: " . count($data['mainEntity'] ?? []) . "\n";
        foreach ($data['mainEntity'] as $q) {
            echo "      - Q: " . $q['name'] . "\n";
        }
    }
}

// Check for alt text on featured image
if (preg_match('/<img[^>]*class="[^"]*featured[^"]*"[^>]*alt="([^"]*)"/i', $html, $altMatch) || preg_match('/<div class="blog-featured-image-container"[^>]*>\s*<img[^>]*alt="([^"]*)"/i', $html, $altMatch)) {
    echo "Featured Image Alt: " . $altMatch[1] . "\n";
} else {
    echo "Featured Image Alt not matched by regex\n";
}

// Check for Tags
if (strpos($html, 'Related Topics:') !== false || strpos($html, 'blog-tags-container') !== false) {
    echo "Tags section successfully rendered!\n";
} else {
    echo "Tags section NOT found\n";
}

// Check for FAQs Accordion
if (strpos($html, 'blog-faqs-wrapper') !== false && strpos($html, 'faq-accordion') !== false) {
    echo "FAQ Accordion successfully rendered!\n";
} else {
    echo "FAQ Accordion NOT found\n";
}
