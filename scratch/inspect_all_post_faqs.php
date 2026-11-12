<?php
require_once __DIR__ . '/../includes/database.php';

$posts = db_fetch_all("SELECT id, slug, title, content FROM blog_posts WHERE status = 'published'");

foreach ($posts as $p) {
    echo "=====================================\n";
    echo "ID: {$p['id']} | Slug: {$p['slug']}\n";
    // Check if FAQ or h2 exists
    preg_match_all('/<h2[^>]*>(.*?)<\/h2>/i', $p['content'], $h2_matches);
    echo "H2 Headings: " . count($h2_matches[1]) . "\n";
    foreach ($h2_matches[1] as $h) {
        echo "  - $h\n";
    }
    // Check FAQ occurrence
    if (stripos($p['content'], 'faq') !== false || stripos($p['content'], 'frequently asked') !== false) {
        echo "Contains FAQ section\n";
        // print snippet around faq
        $pos = stripos($p['content'], 'frequently');
        if ($pos !== false) {
            echo substr($p['content'], $pos, 400) . "\n";
        }
    }
}

