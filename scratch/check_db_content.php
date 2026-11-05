<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$tables = ['products', 'categories', 'services', 'gallery', 'blog_posts', 'faqs', 'reviews', 'site_settings'];
foreach ($tables as $t) {
    try {
        $count = db_fetch_one("SELECT COUNT(*) as c FROM {$t}");
        echo "Table {$t}: " . ($count['c'] ?? 0) . " rows\n";
    } catch (Exception $e) {
        echo "Table {$t}: ERROR " . $e->getMessage() . "\n";
    }
}

