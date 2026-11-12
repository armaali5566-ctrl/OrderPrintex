<?php
require_once __DIR__ . '/../includes/database.php';
$rows = db_fetch_all("SELECT id, slug, title, status FROM blog_posts");
foreach ($rows as $r) {
    echo "ID: {$r['id']} | Slug: {$r['slug']} | Title: {$r['title']} | Status: {$r['status']}\n";
}

