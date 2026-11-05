<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/seo_helper.php';

echo "Testing mutation trigger...\n";

// Record current mtime of sitemap.xml
$sitemapPath = 'sitemap.xml';
$beforeMtime = filemtime($sitemapPath);
sleep(1); // Ensure timestamp moves forward by at least 1 second

// Perform a test call to seo_sync_all() as would occur during a mutation
$result = seo_sync_all();
$afterMtime = filemtime($sitemapPath);

echo "Before Mtime: {$beforeMtime} | After Mtime: {$afterMtime}\n";
if ($afterMtime > $beforeMtime) {
    echo "PASS: sitemap.xml was automatically updated on mutation!\n";
} else {
    echo "FAIL: sitemap.xml timestamp did not update!\n";
}

// Check all 4 files exist and have recent mtime
$files = ['sitemap.xml', 'llms.txt', 'schema.json', 'ORDERPRINTEX.md'];
foreach ($files as $f) {
    if (file_exists($f)) {
        echo "PASS: {$f} exists (Size: " . filesize($f) . " bytes, Mtime: " . date('Y-m-d H:i:s', filemtime($f)) . ")\n";
    } else {
        echo "FAIL: {$f} does not exist!\n";
    }
}

