<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

echo "=== CURATED GALLERY IN GALLERY.PHP ===\n";
// Read gallery.php and extract $curatedGallery
include 'gallery.php';
// $curatedGallery is defined in gallery.php
foreach ($curatedGallery as $idx => $item) {
    $path = $item['image_url'];
    $exists = file_exists($path);
    $url = url($path);
    echo "Item {$idx}: '{$item['title']}'\n";
    echo "   DB/Array path: {$path} | File exists: " . ($exists ? 'YES' : 'NO') . "\n";
    echo "   Resolved URL: {$url}\n";
}

echo "\n=== DATABASE GALLERY TABLE ===\n";
$dbItems = db_fetch_all("SELECT * FROM gallery");
foreach ($dbItems as $item) {
    $path = $item['image_url'];
    $exists = file_exists($path);
    echo "ID {$item['id']}: '{$item['title']}' | Path: {$path} | Exists: " . ($exists ? 'YES' : 'NO') . "\n";
}

