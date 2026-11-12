<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$json = file_get_contents(__DIR__ . '/articles_payload.json');
$articles = json_decode($json, true);

foreach ($articles as $art) {
    $existing = db_fetch_one("SELECT id FROM blog_posts WHERE slug = :s OR id = :id", [
        's' => $art['slug'],
        'id' => $art['id']
    ]);

    $data = [
        'title' => $art['title'],
        'slug' => $art['slug'],
        'excerpt' => $art['excerpt'],
        'content' => $art['content'],
        'featured_image' => $art['featured_image'],
        'author' => $art['author'],
        'read_time' => $art['read_time'],
        'category_id' => $art['category_id'],
        'status' => 'published',
        'seo_title' => $art['seo_title'],
        'seo_description' => $art['seo_description']
    ];

    if ($existing) {
        db_update('blog_posts', $data, 'id = :id', ['id' => $existing['id']]);
        echo "Updated post ID {$existing['id']} ({$art['slug']})\n";
    } else {
        db_insert('blog_posts', array_merge(['id' => $art['id']], $data));
        echo "Inserted post ID {$art['id']} ({$art['slug']})\n";
    }
}

echo "All 3 blog articles processed successfully!\n";

