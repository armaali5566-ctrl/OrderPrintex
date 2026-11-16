<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/catalog_helper.php';
require_once __DIR__ . '/../includes/seo_helper.php';

echo "====================================================\n";
echo "ORDERPRINTEX FULL-STACK VERIFICATION AUDIT\n";
echo "====================================================\n\n";

$passCount = 0;
$failCount = 0;

function assert_test($condition, $name) {
    global $passCount, $failCount;
    if ($condition) {
        echo "[PASS] " . $name . "\n";
        $passCount++;
    } else {
        echo "[FAIL] " . $name . "\n";
        $failCount++;
    }
}

// TEST 1: Database Columns & Table
$bpCols = array_column(db_fetch_all("DESCRIBE blog_posts"), 'Field');
assert_test(in_array('featured_image_alt', $bpCols), "blog_posts has featured_image_alt");
assert_test(in_array('tags', $bpCols), "blog_posts has tags");
assert_test(in_array('seo_keywords', $bpCols), "blog_posts has seo_keywords");
assert_test(in_array('canonical_url', $bpCols), "blog_posts has canonical_url");
assert_test(in_array('og_title', $bpCols), "blog_posts has og_title");
assert_test(in_array('og_description', $bpCols), "blog_posts has og_description");
assert_test(in_array('og_image', $bpCols), "blog_posts has og_image");
assert_test(in_array('published_at', $bpCols), "blog_posts has published_at");

$faqsTable = db_fetch_all("SHOW TABLES LIKE 'blog_post_faqs'");
assert_test(!empty($faqsTable), "blog_post_faqs table exists");

$faqCount = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM blog_post_faqs")['cnt'] ?? 0);
assert_test($faqCount >= 10, "blog_post_faqs contains seeded FAQs (count: {$faqCount})");

// TEST 2: Product 1 SKU & All Products Warnings
$p1 = db_fetch_one("SELECT id, title, sku FROM products WHERE id = 1");
assert_test(!empty($p1['sku']) && $p1['sku'] === 'DTF-001', "Product 1 SKU is DTF-001 in DB (current: " . ($p1['sku'] ?? 'empty') . ")");

$curated = get_curated_catalog_products();
$curatedSkuCount = count(array_filter($curated, fn($p) => !empty($p['sku'])));
assert_test($curatedSkuCount === 9, "All 9 curated products have defined SKUs in catalog helper");

// Test Product Details Rendering for 0 warnings
$_GET['slug'] = 'custom-dtf-gang-sheet';
$_SERVER['REQUEST_URI'] = '/products/custom-dtf-gang-sheet';
ob_start();
require __DIR__ . '/../product-details.php';
$prodHtml = ob_get_clean();

assert_test(strpos($prodHtml, '<b>Warning</b>') === false, "product-details.php renders with 0 PHP warnings");
assert_test(strpos($prodHtml, 'SKU: DTF-001') !== false, "product-details.php displays SKU: DTF-001 badge");

// Check WhatsApp quote URL in product-details.php
preg_match('/href="([^"]*wa\.me\/919354028199\?text=[^"]*)"/i', $prodHtml, $waMatches);
$waText = isset($waMatches[1]) ? urldecode($waMatches[1]) : '';
assert_test(strpos($waText, 'Hi OrderPrintex, I am inquiring about DTF Gang Sheet (22" Wide) (SKU: DTF-001)') !== false, "WhatsApp URL contains correct decoded inquiry string without warnings/debug HTML");

// TEST 3: Footer Printing Services Names
ob_start();
require __DIR__ . '/../includes/footer.php';
$footerHtml = ob_get_clean();

assert_test(strpos($footerHtml, '>DTF Printing</a>') !== false, "Footer contains 'DTF Printing'");
assert_test(strpos($footerHtml, '>UV DTF Printing</a>') !== false, "Footer contains 'UV DTF Printing'");
assert_test(strpos($footerHtml, '>T-Shirt Printing</a>') !== false, "Footer contains 'T-Shirt Printing'");
assert_test(strpos($footerHtml, '>Corporate Printing</a>') !== false, "Footer contains 'Corporate Printing'");
assert_test(strpos($footerHtml, '>All Printing Services</a>') !== false, "Footer contains 'All Printing Services'");
assert_test(strpos($footerHtml, '>Custom Quotation Studio</a>') !== false, "Footer contains 'Custom Quotation Studio'");

// TEST 4: Blog Details Schema & Accordion
$_GET['slug'] = 'dtf-printing-technology-custom-apparel';
$_SERVER['REQUEST_URI'] = '/blog/dtf-printing-technology-custom-apparel';
ob_start();
require __DIR__ . '/../blog-details.php';
$blogHtml = ob_get_clean();

preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $blogHtml, $jsonBlocks);
$schemaTypes = [];
foreach ($jsonBlocks[1] as $jb) {
    $dec = json_decode($jb, true);
    if (!empty($dec['@type'])) {
        $schemaTypes[] = $dec['@type'];
    }
}

assert_test(in_array('Organization', $schemaTypes), "Schema contains Organization");
assert_test(in_array('LocalBusiness', $schemaTypes), "Schema contains LocalBusiness");
assert_test(in_array('WebSite', $schemaTypes), "Schema contains WebSite");
assert_test(in_array('WebPage', $schemaTypes), "Schema contains WebPage");
assert_test(in_array('BlogPosting', $schemaTypes), "Schema contains BlogPosting");
assert_test(in_array('BreadcrumbList', $schemaTypes), "Schema contains BreadcrumbList");
assert_test(in_array('FAQPage', $schemaTypes), "Schema contains independent FAQPage block");

assert_test(strpos($blogHtml, 'blog-faqs-wrapper') !== false, "Blog details displays FAQ section");
assert_test(strpos($blogHtml, 'faq-accordion') !== false, "Blog details displays FAQ accordion");
assert_test(strpos($blogHtml, 'blog-tags-container') !== false, "Blog details displays tags container");

// TEST 5: Admin Blog Composer File Check
$adminBlogCode = file_get_contents(__DIR__ . '/../admin/blog.php');
assert_test(strpos($adminBlogCode, 'blogComposerForm') !== false, "admin/blog.php contains blogComposerForm");
assert_test(strpos($adminBlogCode, 'faqBuilderContainer') !== false, "admin/blog.php contains interactive FAQ builder");
assert_test(strpos($adminBlogCode, 'google-preview-box') !== false, "admin/blog.php contains live Google SERP preview");
assert_test(strpos($adminBlogCode, 'rt-toolbar') !== false, "admin/blog.php contains rich text formatting toolbar");

// TEST 6: Sanitize Function
$dirty = '<p>Good paragraph</p><script>alert(1);</script><a href="javascript:evil()">link</a><h2 onclick="bad()">Heading</h2>';
$cleaned = sanitize_blog_content($dirty);
assert_test(strpos($cleaned, '<script>') === false && strpos($cleaned, 'alert(1)') === false, "sanitize_blog_content strips <script>");
assert_test(strpos($cleaned, 'onclick') === false, "sanitize_blog_content strips onclick");
assert_test(strpos($cleaned, 'javascript:') === false, "sanitize_blog_content strips javascript: URI");
assert_test(strpos($cleaned, '<h2>Heading</h2>') !== false, "sanitize_blog_content preserves <h2>");

echo "\n====================================================\n";
echo "SUMMARY: {$passCount} PASSED, {$failCount} FAILED\n";
echo "====================================================\n";
