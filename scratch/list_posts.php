<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$posts = db_fetch_all("SELECT id, title, slug, created_at, status FROM blog_posts");
echo "Total posts: " . count($posts) . "\n";
foreach ($posts as $p) {
    echo "ID {$p['id']}: {$p['title']} [{$p['slug']}] ({$p['status']})\n";
}
