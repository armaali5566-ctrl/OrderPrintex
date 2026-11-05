<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

echo "=== Verifying Backend Database Handlers ===\n";

// 1. Verify Gallery Category VARCHAR(100)
$galleryCol = db_fetch_one("SHOW COLUMNS FROM gallery WHERE Field = 'category'");
echo "Gallery category type: " . $galleryCol['Type'] . "\n";
assert(strpos(strtolower($galleryCol['Type']), 'varchar') !== false, "Gallery category must be varchar");

// 2. Insert test custom gallery item
$uniqueCat = "Custom Holographic Packaging " . time();
$itemId = db_insert('gallery', [
    'title'         => 'Test Custom Project ' . time(),
    'category'      => $uniqueCat,
    'image_url'     => 'assets/images/gallery_1.jpg',
    'description'   => 'Test description',
    'is_featured'   => 1,
    'is_published'  => 1,
    'display_order' => 99
]);
$savedItem = db_fetch_one("SELECT * FROM gallery WHERE id = :id", ['id' => $itemId]);
echo "Saved Gallery item category: " . $savedItem['category'] . "\n";
assert($savedItem['category'] === $uniqueCat, "Gallery category must match custom category");

// Clean up test item
db_delete('gallery', 'id = :id', ['id' => $itemId]);
echo "Test Gallery item verified and cleaned up.\n";

// 3. Verify Enquiries table material_options
$enqCol = db_fetch_one("SHOW COLUMNS FROM enquiries WHERE Field = 'material_options'");
echo "Enquiries material_options type: " . $enqCol['Type'] . "\n";
assert(strpos(strtolower($enqCol['Type']), 'varchar') !== false, "material_options must be varchar");

echo "All DB assertions passed successfully!\n";

