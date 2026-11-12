<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

echo "--- Enquiry Files ---\n";
$files = db_fetch_all("SELECT * FROM enquiry_files");
echo "Count: " . count($files) . "\n";
foreach ($files as $f) {
    echo "ID: {$f['id']}, EnquiryID: {$f['enquiry_id']}, Original: {$f['original_name']}, Stored: {$f['stored_name']}, Path: {$f['file_path']}\n";
    $diskPath = ROOT_PATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $f['file_path']);
    $altDiskPath = ARTWORK_PATH . $f['stored_name'];
    echo "  file_path exists? " . (file_exists($diskPath) ? "YES" : "NO ($diskPath)") . "\n";
    echo "  ARTWORK_PATH exists? " . (file_exists($altDiskPath) ? "YES" : "NO ($altDiskPath)") . "\n";
}

echo "\n--- Recent Enquiries ---\n";
$enqs = db_fetch_all("SELECT id, ref_number, customer_name, customer_email, created_at FROM enquiries ORDER BY id DESC LIMIT 5");
foreach ($enqs as $e) {
    echo "ID: {$e['id']}, Ref: {$e['ref_number']}, Name: {$e['customer_name']}, Created: {$e['created_at']}\n";
}

