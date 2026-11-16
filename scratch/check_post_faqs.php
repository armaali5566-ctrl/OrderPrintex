<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$posts = db_fetch_all("SELECT id, title, content FROM blog_posts");
foreach ($posts as $p) {
    echo "Post {$p['id']} FAQs in content: ";
    if (preg_match_all('/<h[23][^>]*>(.*?\?.*?)<\/h[23]>/i', $p['content'], $m)) {
        echo count($m[1]) . " questions found: " . implode(' | ', array_slice($m[1], 0, 3)) . "\n";
    } else {
        echo "No FAQ pattern matched\n";
    }
}

$faqs = db_fetch_all("SELECT * FROM blog_post_faqs");
echo "\nTotal rows in blog_post_faqs: " . count($faqs) . "\n";
