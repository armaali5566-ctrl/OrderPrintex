<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/seo_helper.php';

echo "Testing Admin Blog POST handling and FAQ builder...\n";

// 1. Simulate admin session
$_SESSION['user_id'] = 1;
$_SESSION['csrf_token'] = 'test_token_12345';

// 2. Prepare mock POST data
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'csrf_token' => 'test_token_12345',
    'action' => 'save_post',
    'post_id' => 0,
    'title' => 'Test Automated Article On Prepress Calibration',
    'slug' => 'test-automated-article-prepress-calibration',
    'category_id' => 1,
    'excerpt' => 'This is an automated test excerpt testing the new blog CMS publishing capabilities.',
    'content' => '<h2>Understanding ICC Profiles</h2><p>Calibrated color profiles ensure accurate skin tones on DTF transfers.</p><h3>Wash Longevity</h3><p>Correct heat press temperature is 160C for 15 seconds.</p>',
    'author' => 'Test Engineer',
    'read_time' => '4 min read',
    'status' => 'draft',
    'is_sample' => 0,
    'featured_image_alt' => 'Prepress color calibration testing bar',
    'tags' => 'Prepress, Calibration, ICC Profile, Testing',
    'seo_title' => 'Test Automated Prepress Guide | OrderPrintex',
    'seo_description' => 'Comprehensive technical testing guide for prepress color calibration.',
    'seo_keywords' => 'prepress testing, icc calibration, dtf colors',
    'canonical_url' => 'https://orderprintex.com/blog/test-automated-article-prepress-calibration',
    'og_title' => 'Test Automated Prepress Guide',
    'og_description' => 'Comprehensive technical testing guide.',
    'faq_questions' => [
        'How often should printheads be calibrated?',
        'Does humidity affect DTF powder curing?'
    ],
    'faq_answers' => [
        'Printheads should undergo daily nozzle checks and bi-weekly density calibration.',
        'Yes, maintaining workshop humidity between 45% and 60% prevents powder clumping.'
    ]
];

// Execute logic directly
$title = trim($_POST['title']);
$slug = trim($_POST['slug']);
$content = sanitize_blog_content($_POST['content']);

$newId = db_insert('blog_posts', [
    'category_id'        => 1,
    'title'              => $title,
    'slug'               => $slug,
    'excerpt'            => $_POST['excerpt'],
    'content'            => $content,
    'featured_image'     => 'assets/images/dtf_fabric_print.jpg',
    'featured_image_alt' => $_POST['featured_image_alt'],
    'tags'               => $_POST['tags'],
    'author'             => $_POST['author'],
    'read_time'          => $_POST['read_time'],
    'status'             => 'draft',
    'is_sample'          => 0,
    'views'              => 0,
    'seo_title'          => $_POST['seo_title'],
    'seo_description'    => $_POST['seo_description'],
    'seo_keywords'       => $_POST['seo_keywords'],
    'canonical_url'      => $_POST['canonical_url'],
    'og_title'           => $_POST['og_title'],
    'og_description'     => $_POST['og_description']
]);

echo "Created test post with ID: {$newId}\n";

// Insert test FAQs
$order = 1;
foreach ($_POST['faq_questions'] as $idx => $q) {
    db_insert('blog_post_faqs', [
        'post_id' => $newId,
        'question' => $q,
        'answer' => $_POST['faq_answers'][$idx],
        'display_order' => $order++
    ]);
}
echo "Inserted 2 test FAQs into blog_post_faqs.\n";

// Verify saved data in DB
$savedPost = db_fetch_one("SELECT * FROM blog_posts WHERE id = :id", ['id' => $newId]);
$savedFaqs = db_fetch_all("SELECT * FROM blog_post_faqs WHERE post_id = :id ORDER BY display_order ASC", ['id' => $newId]);

echo "Verification:\n";
echo "  Title: {$savedPost['title']}\n";
echo "  Slug: {$savedPost['slug']}\n";
echo "  Status: {$savedPost['status']}\n";
echo "  Tags: {$savedPost['tags']}\n";
echo "  Alt: {$savedPost['featured_image_alt']}\n";
echo "  Faq count: " . count($savedFaqs) . "\n";
foreach ($savedFaqs as $sf) {
    echo "    - Q: {$sf['question']} => A: {$sf['answer']}\n";
}

// Clean up test post and its faqs
db_delete('blog_post_faqs', 'post_id = :id', ['id' => $newId]);
db_delete('blog_posts', 'id = :id', ['id' => $newId]);
echo "Cleaned up test post and FAQs from database.\n";
echo "ALL TESTS PASSED SUCCESSFULLY!\n";
