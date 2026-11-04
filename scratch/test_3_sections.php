<?php
/**
 * Test Suite: Mobile & Tablet Responsiveness for 3 Specific Sections
 * 1. Blog Page - Category Filter Chips Track
 * 2. Homepage - Hero CTAs & Visual Card / Product Cards
 * 3. Product Detail Page - Reviews Workbench & Sticky Cart Bar Auto-Hide
 */

$testCount = 0;
$passCount = 0;

function assertCondition($name, $condition, $details = '') {
    global $testCount, $passCount;
    $testCount++;
    if ($condition) {
        $passCount++;
        echo "[PASS] $name\n";
    } else {
        echo "[FAIL] $name" . ($details ? " - $details" : "") . "\n";
    }
}

echo "======================================================\n";
echo "RUNNING 3-SECTIONS RESPONSIVE TEST SUITE\n";
echo "======================================================\n\n";

$styleCss = file_get_contents(__DIR__ . '/../assets/css/style.css');
$respCss = file_get_contents(__DIR__ . '/../assets/css/responsive.css');
$blogPhp = file_get_contents(__DIR__ . '/../blog.php');
$indexPhp = file_get_contents(__DIR__ . '/../index.php');
$prodDetailsPhp = file_get_contents(__DIR__ . '/../product-details.php');

// --- SECTION 1: BLOG CATEGORY FILTER BUTTONS ---
echo "--- Testing Section 1: Blog Page Category Filters ---\n";

assertCondition(
    'blog.php contains blog-category-nav-section container',
    strpos($blogPhp, 'class="blog-category-nav-section"') !== false
);

assertCondition(
    'blog.php contains blog-category-track flex container',
    strpos($blogPhp, 'class="blog-category-track"') !== false
);

assertCondition(
    'blog.php contains blog-cat-chip links',
    strpos($blogPhp, 'class="blog-cat-chip') !== false
);

assertCondition(
    'style.css defines .blog-cat-chip with nowrap and flex-shrink: 0',
    strpos($styleCss, '.blog-cat-chip') !== false &&
    strpos($styleCss, 'white-space: nowrap !important;') !== false &&
    strpos($styleCss, 'flex-shrink: 0 !important;') !== false
);

assertCondition(
    'style.css defines .blog-cat-chip.is-active with gold gradient',
    strpos($styleCss, '.blog-cat-chip.is-active') !== false &&
    strpos($styleCss, '#D4AF37') !== false
);

assertCondition(
    'responsive.css enables horizontal scrolling on mobile/tablet for .blog-category-track',
    strpos($respCss, '.blog-category-track') !== false &&
    strpos($respCss, 'overflow-x: auto !important') !== false
);

// --- SECTION 2: HOMEPAGE HERO & PRODUCT CARDS ---
echo "\n--- Testing Section 2: Homepage Hero & Product Card Actions ---\n";

assertCondition(
    'index.php contains hero-ctas and hero-visual-card',
    strpos($indexPhp, 'class="hero-ctas"') !== false &&
    strpos($indexPhp, 'class="hero-visual-card"') !== false
);

assertCondition(
    'responsive.css stacks .hero-ctas on mobile (<=768px)',
    preg_match('/\.hero-ctas[^{]*\{[^}]*flex-direction:\s*column\s*!important/s', $respCss) === 1
);

assertCondition(
    'responsive.css sets full-width and nowrap on hero CTA buttons on mobile',
    preg_match('/\.hero-ctas\s+\.btn[^{]*\{[^}]*width:\s*100%\s*!important[^}]*white-space:\s*nowrap\s*!important/s', $respCss) === 1
);

assertCondition(
    'responsive.css constrains hero image max-height to 280px on <=768px',
    preg_match('/\.hero-visual-card\s+img[^{]*\{[^}]*max-height:\s*280px\s*!important/s', $respCss) === 1
);

assertCondition(
    'responsive.css constrains hero image max-height to 240px on <=480px',
    preg_match('/\.hero-visual-card\s+img[^{]*\{[^}]*max-height:\s*240px\s*!important/s', $respCss) === 1
);

assertCondition(
    'responsive.css uses object-fit: cover on hero visual images',
    preg_match('/\.hero-visual-card\s+img[^{]*\{[^}]*object-fit:\s*cover\s*!important/s', $respCss) === 1
);

assertCondition(
    'responsive.css product-card-actions has 2 columns on mobile/tablet',
    preg_match('/\.product-card-actions[^{]*\{[^}]*grid-template-columns:\s*1fr\s+1fr\s*!important/s', $respCss) === 1
);

assertCondition(
    'responsive.css product-card-actions stacks to 1 column on narrow phones (<=380px)',
    preg_match('/@media\s*\(\s*max-width:\s*380px\s*\)[^\{]*\{.*?\.product-card-actions[^{]*\{[^}]*grid-template-columns:\s*1fr\s*!important/s', $respCss) === 1
);

// --- SECTION 3: PRODUCT REVIEWS WORKBENCH ---
echo "\n--- Testing Section 3: Product Reviews Workbench & Sticky Bar ---\n";

assertCondition(
    'product-details.php contains review-workbench-grid',
    strpos($prodDetailsPhp, 'class="review-workbench-grid"') !== false
);

assertCondition(
    'product-details.php contains review-summary-card and review-form-card',
    strpos($prodDetailsPhp, 'class="review-summary-card"') !== false &&
    strpos($prodDetailsPhp, 'class="review-form-card"') !== false
);

assertCondition(
    'product-details.php contains review-form-row-2 and submit-review-btn',
    strpos($prodDetailsPhp, 'class="review-form-row-2"') !== false &&
    strpos($prodDetailsPhp, 'submit-review-btn') !== false
);

assertCondition(
    'product-details.php has focusin/focusout listeners on reviewForm to toggle stickyCartBar',
    strpos($prodDetailsPhp, "reviewForm.addEventListener('focusin'") !== false &&
    strpos($prodDetailsPhp, "stickyBar.classList.add('is-hidden-by-form')") !== false
);

assertCondition(
    'style.css contains #stickyCartBar.is-hidden-by-form and :focus-within rules',
    strpos($styleCss, '#stickyCartBar.is-hidden-by-form') !== false &&
    strpos($styleCss, 'body:has(#productReviewForm:focus-within) #stickyCartBar') !== false
);

assertCondition(
    'responsive.css stacks review-workbench-grid vertically on mobile (<=768px)',
    preg_match('/\.review-workbench-grid[^{]*\{[^}]*flex-direction:\s*column\s*!important/s', $respCss) === 1
);

assertCondition(
    'responsive.css stacks review-form-row-2 vertically on mobile (<=768px)',
    preg_match('/\.review-form-row-2[^{]*\{[^}]*flex-direction:\s*column\s*!important/s', $respCss) === 1
);

assertCondition(
    'responsive.css sets #reviews-section bottom safe-area clearance on mobile',
    preg_match('/#reviews-section[^{]*\{[^}]*padding-bottom:\s*calc\([^}]*env\(safe-area-inset-bottom/s', $respCss) === 1
);

// --- HTTP SERVER INTEGRATION CHECKS ---
echo "\n--- Testing Live HTTP Responses ---\n";

$homeHtml = @file_get_contents('http://127.0.0.1:8080/index.php');
assertCondition(
    'Homepage live HTTP 200 response contains .hero-ctas and Upload Your Design',
    $homeHtml && strpos($homeHtml, 'hero-ctas') !== false && strpos($homeHtml, 'Upload Your Design') !== false
);

$blogHtml = @file_get_contents('http://127.0.0.1:8080/blog.php');
assertCondition(
    'Blog page live HTTP 200 response contains .blog-category-nav-section and .blog-cat-chip',
    $blogHtml && strpos($blogHtml, 'blog-category-nav-section') !== false && strpos($blogHtml, 'blog-cat-chip') !== false
);

// Get a valid product slug
require_once __DIR__ . '/../includes/database.php';
$prod = db_fetch_one("SELECT slug FROM products WHERE is_active = 1 LIMIT 1");
$prodSlug = $prod ? $prod['slug'] : 'dtf-gang-sheet-transfers';

$prodDetailsHtml = @file_get_contents("http://127.0.0.1:8080/products/{$prodSlug}");
assertCondition(
    "Product details live HTTP 200 response (/products/{$prodSlug}) contains review-workbench-grid and productReviewForm",
    $prodDetailsHtml && strpos($prodDetailsHtml, 'review-workbench-grid') !== false && strpos($prodDetailsHtml, 'productReviewForm') !== false
);

echo "\n======================================================\n";
echo "SUMMARY: $passCount / $testCount TESTS PASSED\n";
echo "======================================================\n";

if ($passCount === $testCount) {
    echo "SUCCESS: ALL 3-SECTION RESPONSIVE REQUIREMENTS VERIFIED!\n";
    exit(0);
} else {
    echo "FAILURE: SOME TESTS FAILED.\n";
    exit(1);
}
