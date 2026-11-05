<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

echo "=== PRODUCTS ===\n";
$products = db_fetch_all("
    SELECT p.*, c.name as category_name, c.slug as category_slug 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id ASC
");
foreach ($products as $p) {
    echo "ID: {$p['id']} | Title: {$p['title']} | Slug: {$p['slug']} | Cat: {$p['category_name']} | SKU: {$p['sku']} | Active: {$p['is_active']} | Price: Reg={$p['regular_price']}, Sale={$p['sale_price']}, Start={$p['starting_price']}\n";
    if (!empty($p['attributes'])) {
        echo "   Attributes: " . substr($p['attributes'], 0, 100) . "...\n";
    }
}

echo "\n=== CATEGORIES ===\n";
$categories = db_fetch_all("SELECT * FROM categories ORDER BY id ASC");
foreach ($categories as $c) {
    echo "ID: {$c['id']} | Name: {$c['name']} | Slug: {$c['slug']} | Active: " . ($c['is_active'] ?? 'N/A') . "\n";
}

echo "\n=== SERVICES ===\n";
$services = db_fetch_all("SELECT * FROM services ORDER BY id ASC");
foreach ($services as $s) {
    echo "ID: {$s['id']} | Title: {$s['title']} | Slug: {$s['slug']} | Active: {$s['is_active']}\n";
}

echo "\n=== BLOG POSTS ===\n";
$posts = db_fetch_all("SELECT id, title, slug, status, author, read_time, created_at FROM blog_posts ORDER BY id ASC");
foreach ($posts as $b) {
    echo "ID: {$b['id']} | Title: {$b['title']} | Slug: {$b['slug']} | Status: {$b['status']} | Read: {$b['read_time']}\n";
}
