<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
$p = db_fetch_one("SELECT content FROM blog_posts WHERE id = 1");
echo substr($p['content'] ?? '', 0, 500);

