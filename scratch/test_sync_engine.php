<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/seo_helper.php';

echo "Running seo_sync_all()...\n";
$res = seo_sync_all();
print_r($res);

echo "\n--- VERIFYING ARTIFACTS ---\n";

// 1. Sitemap
$sitemapPath = 'sitemap.xml';
if (file_exists($sitemapPath)) {
    $xmlContent = file_get_contents($sitemapPath);
    $xml = @simplexml_load_string($xmlContent);
    if ($xml !== false) {
        $count = count($xml->url);
        echo "PASS: sitemap.xml is VALID XML with {$count} URLs.\n";
    } else {
        echo "FAIL: sitemap.xml is NOT valid XML!\n";
    }
} else {
    echo "FAIL: sitemap.xml does not exist!\n";
}

// 2. LLMS.txt
$llmsPath = 'llms.txt';
if (file_exists($llmsPath)) {
    $size = filesize($llmsPath);
    echo "PASS: llms.txt exists (Size: {$size} bytes).\n";
} else {
    echo "FAIL: llms.txt does not exist!\n";
}

// 3. Schema.json
$schemaPath = 'schema.json';
if (file_exists($schemaPath)) {
    $jsonContent = file_get_contents($schemaPath);
    $json = json_decode($jsonContent, true);
    if ($json && isset($json['@graph'])) {
        $nodeCount = count($json['@graph']);
        echo "PASS: schema.json is VALID JSON-LD with {$nodeCount} graph nodes.\n";
    } else {
        echo "FAIL: schema.json is NOT valid JSON-LD! Error: " . json_last_error_msg() . "\n";
    }
} else {
    echo "FAIL: schema.json does not exist!\n";
}

// 4. ORDERPRINTEX.md
$mdPath = 'ORDERPRINTEX.md';
if (file_exists($mdPath)) {
    $size = filesize($mdPath);
    echo "PASS: ORDERPRINTEX.md exists (Size: {$size} bytes).\n";
} else {
    echo "FAIL: ORDERPRINTEX.md does not exist!\n";
}

$status = seo_get_status();
echo "\n--- SEO GET STATUS ---\n";
print_r($status);

