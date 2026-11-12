<?php
require_once __DIR__ . '/../includes/database.php';
$posts = db_fetch_all("SELECT id, slug, title, category_id, status FROM blog_posts");
print_r($posts);

