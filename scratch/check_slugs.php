<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

echo "Products in DB:\n";
$prods = db_fetch_all("SELECT id, slug, title FROM products LIMIT 5");
foreach ($prods as $p) {
    echo "  - slug: '{$p['slug']}' | title: '{$p['title']}'\n";
}

echo "Blog posts in DB:\n";
$posts = db_fetch_all("SELECT id, slug, title FROM blog_posts LIMIT 5");
foreach ($posts as $b) {
    echo "  - slug: '{$b['slug']}' | title: '{$b['title']}'\n";
}
