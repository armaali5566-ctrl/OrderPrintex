<?php
/**
 * OrderPrintex - Comprehensive Automated Verification Test Suite
 */

$baseUrl = 'http://127.0.0.1:8080';
$results = [];
$failures = 0;
$passes = 0;

function run_test(string $name, callable $fn) {
    global $results, $failures, $passes;
    try {
        $result = $fn();
        if ($result['pass']) {
            $passes++;
            $results[] = ["status" => "PASS", "test" => $name, "details" => $result['details'] ?? ''];
            echo "[PASS] {$name}: " . ($result['details'] ?? '') . "\n";
        } else {
            $failures++;
            $results[] = ["status" => "FAIL", "test" => $name, "details" => $result['details'] ?? ''];
            echo "[FAIL] {$name}: " . ($result['details'] ?? '') . "\n";
        }
    } catch (Throwable $t) {
        $failures++;
        $results[] = ["status" => "ERROR", "test" => $name, "details" => $t->getMessage()];
        echo "[ERROR] {$name}: " . $t->getMessage() . "\n";
    }
}

function http_req(string $url, string $method = 'GET', array $postData = [], bool $followRedirects = false): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followRedirects);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if (!empty($postData)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }
    $body = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    $err = curl_error($ch);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'body' => $body,
        'redirect_url' => $redirectUrl,
        'error' => $err
    ];
}

echo "=========================================================\n";
echo "   ORDERPRINTEX SUITE — EXECUTING VALIDATION CHECKS     \n";
echo "=========================================================\n\n";

// TEST 1: Homepage
run_test("Homepage (/) loads successfully", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/');
    $hasBar = str_contains($r['body'], 'announcement-bar');
    $hasProducts = str_contains($r['body'], 'Popular Printing Products');
    $hasBlog = str_contains($r['body'], 'Latest Printing Guides');
    $hasFourItems = substr_count($r['body'], 'SKU:');
    $pass = ($r['code'] === 200 && $hasBar && $hasProducts && $hasBlog);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, announcement={$hasBar}, products={$hasProducts}, blog={$hasBlog}, SKU count={$hasFourItems}"
    ];
});

// TEST 2: Every Product Detail Page by Clean Slug
$productsToTest = [
    'bookmark-03' => 'BKM-001',
    'custom-dtf-gang-sheet' => 'DTF-001',
    'custom-uv-dtf-decal-sheet' => 'UV-001',
    'streetwear-oversized-tee' => 'STR-001',
    'thermal-sipper-bottle' => 'BTL-001',
    'apparel-mailer-box' => 'BOX-001',
    'holographic-stickers' => 'STK-001',
    'bio-washed-cotton-tee' => 'TEE-001',
    'executive-welcome-hamper' => 'HMP-001'
];

foreach ($productsToTest as $slug => $sku) {
    run_test("Product: /product/{$slug}/", function() use ($baseUrl, $slug, $sku) {
        $r = http_req($baseUrl . "/product/{$slug}/");
        $hasSku = str_contains($r['body'], $sku);
        $hasCalc = str_contains($r['body'], 'pricing-calculator-card');
        $hasReviewSec = str_contains($r['body'], 'Customer Reviews');
        $pass = ($r['code'] === 200 && $hasSku && $hasCalc && $hasReviewSec);
        return [
            'pass' => $pass,
            'details' => "HTTP {$r['code']}, SKU '{$sku}' found=" . ($hasSku ? 'YES' : 'NO') . ", calculator card=" . ($hasCalc ? 'YES' : 'NO')
        ];
    });
}

// TEST 3: Invalid Product Slug returns HTTP 404 (NEVER redirects to homepage)
run_test("Invalid Product Slug returns HTTP 404 (No homepage redirect)", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/product/invalid-non-existent-product-12345/');
    $pass = ($r['code'] === 404 && str_contains($r['body'], '404'));
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, response contains 404=" . (str_contains($r['body'], '404') ? 'YES' : 'NO')
    ];
});

// TEST 4: Legacy URL /products/bookmark-03 301 redirects to /product/bookmark-03/
run_test("Legacy URL /products/bookmark-03 redirects 301 to clean URL", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/products/bookmark-03');
    $pass = ($r['code'] === 301 && str_contains($r['redirect_url'], '/product/bookmark-03/'));
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, Redirect Target: {$r['redirect_url']}"
    ];
});

// TEST 5: Blog Archive Page
run_test("Blog Archive (/blog/) loads 6 articles and search", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/blog/');
    $hasArticles = str_contains($r['body'], 'Complete Guide to Business Card Printing');
    $hasFilter = str_contains($r['body'], 'Printing Guides');
    $pass = ($r['code'] === 200 && $hasArticles && $hasFilter);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, contains articles=" . ($hasArticles ? 'YES' : 'NO')
    ];
});

// TEST 6: All 6 Demo Blog Articles (Matching Database Slugs)
$articles = [
    'complete-guide-to-business-card-printing',
    'dtf-printing-explained-guide',
    'how-to-choose-the-right-paper',
    'offset-vs-digital-printing',
    'custom-stickers-and-labels-guide',
    'packaging-printing-ideas-for-brands'
];

foreach ($articles as $artSlug) {
    run_test("Blog Post: /blog/{$artSlug}/", function() use ($baseUrl, $artSlug) {
        $r = http_req($baseUrl . "/blog/{$artSlug}/");
        $hasSchema = str_contains($r['body'], 'BlogPosting');
        $hasSocial = str_contains($r['body'], 'Share');
        $pass = ($r['code'] === 200 && $hasSchema && $hasSocial);
        return [
            'pass' => $pass,
            'details' => "HTTP {$r['code']}, BlogPosting schema=" . ($hasSchema ? 'YES' : 'NO')
        ];
    });
}

// TEST 7: About Us Page (All 6 sections)
run_test("About Us (/about/) contains 6 dedicated sections", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/about/');
    $hasTech = str_contains($r['body'], 'Specialized Printing Technologies');
    $hasDualHead = str_contains($r['body'], 'Dual-Head Japanese Inks');
    $hasWhy = str_contains($r['body'], 'Why Choose OrderPrintex');
    $hasTimeline = str_contains($r['body'], 'Simple Four-Step Production Flow');
    $hasTools = str_contains($r['body'], 'Tools &amp; Printing Technologies') || str_contains($r['body'], 'Tools & Printing Technologies');
    $hasCta = str_contains($r['body'], 'Have a Printing Project in Mind?');
    $pass = ($r['code'] === 200 && $hasTech && $hasDualHead && $hasWhy && $hasTimeline && $hasTools && $hasCta);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, Tech={$hasTech}, DualHead={$hasDualHead}, Timeline={$hasTimeline}, Tools={$hasTools}, CTA={$hasCta}"
    ];
});

// TEST 8: Services Page & Clean Service Subpages
run_test("Services Overview (/services/) & /services/dtf-printing/", function() use ($baseUrl) {
    $r1 = http_req($baseUrl . '/services/');
    $r2 = http_req($baseUrl . '/services/dtf-printing/');
    $pass = ($r1['code'] === 200 && $r2['code'] === 200);
    return [
        'pass' => $pass,
        'details' => "/services/ HTTP {$r1['code']}, /services/dtf-printing/ HTTP {$r2['code']}"
    ];
});

// TEST 9: Dynamic Pricing Backend API
run_test("Dynamic Pricing API (api/calculate_price.php)", function() use ($baseUrl) {
    // Test with bookmark product (id=9, qty=100)
    $r = http_req($baseUrl . '/api/calculate_price.php', 'POST', [
        'product_id' => 9,
        'quantity' => 100
    ]);
    $json = json_decode($r['body'], true);
    $pass = ($r['code'] === 200 && !empty($json['success']) && isset($json['grand_total']) && $json['grand_total'] > 0);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, grand_total=₹" . ($json['grand_total'] ?? 0) . ", subtotal=₹" . ($json['subtotal'] ?? 0) . ", tax=₹" . ($json['tax_amount'] ?? 0)
    ];
});

// TEST 10: Technical SEO (sitemap.xml and robots.txt)
run_test("Sitemap (sitemap.xml) and Robots (robots.txt)", function() use ($baseUrl) {
    $rSitemap = http_req($baseUrl . '/sitemap.xml');
    $rRobots = http_req($baseUrl . '/robots.txt');
    $hasCleanSitemap = str_contains($rSitemap['body'], '/product/bookmark-03/') 
                    && str_contains($rSitemap['body'], '/product/category/dtf-gang-sheets/')
                    && str_contains($rSitemap['body'], '/pricing/');
    $hasRobotsMap = str_contains($rRobots['body'], 'sitemap.xml');
    $pass = ($rSitemap['code'] === 200 && $rRobots['code'] === 200 && $hasCleanSitemap && $hasRobotsMap);
    return [
        'pass' => $pass,
        'details' => "sitemap HTTP {$rSitemap['code']}, robots HTTP {$rRobots['code']}, clean URLs verified"
    ];
});

// TEST 11: Clean Category URLs (/product/category/{slug}/)
$categoriesToTest = ['dtf-gang-sheets', 'uv-dtf-decals', 'custom-tshirts', 'printing-materials'];
foreach ($categoriesToTest as $cSlug) {
    run_test("Category: /product/category/{$cSlug}/", function() use ($baseUrl, $cSlug) {
        $r = http_req($baseUrl . "/product/category/{$cSlug}/");
        $hasContent = str_contains($r['body'], 'product-card') || str_contains($r['body'], 'Commercial Print Catalog');
        $pass = ($r['code'] === 200 && $hasContent);
        return [
            'pass' => $pass,
            'details' => "HTTP {$r['code']}, content loaded=" . ($hasContent ? 'YES' : 'NO')
        ];
    });
}

// TEST 11b: 301 Redirect from legacy /category/{slug}/ to /product/category/{slug}/
run_test("301 Redirect: /category/printing-materials/ -> /product/category/printing-materials/", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/category/printing-materials/');
    $target = $r['redirect_url'];
    $pass = ($r['code'] === 301 && str_ends_with($target, '/product/category/printing-materials/'));
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, Target={$target}"
    ];
});

// TEST 12: Invalid Category Slug returns HTTP 404
run_test("Invalid Category Slug returns HTTP 404", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/product/category/non-existent-category-xyz-99999/');
    $pass = ($r['code'] === 404);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, 404 status received=" . ($pass ? 'YES' : 'NO')
    ];
});

// TEST 13: 301 Canonical Redirects from legacy query and php URLs
run_test("301 Redirect: /products.php?category=printing-materials -> /product/category/printing-materials/", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/products.php?category=printing-materials');
    $target = $r['redirect_url'];
    $pass = ($r['code'] === 301 && str_ends_with($target, '/product/category/printing-materials/'));
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, Target={$target}"
    ];
});

run_test("301 Redirect: /products.php -> /products/", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/products.php');
    $target = $r['redirect_url'];
    $pass = ($r['code'] === 301 && str_ends_with($target, '/products/'));
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, Target={$target}"
    ];
});

run_test("301 Redirect: /about.php -> /about/", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/about.php');
    $target = $r['redirect_url'];
    $pass = ($r['code'] === 301 && str_ends_with($target, '/about/'));
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, Target={$target}"
    ];
});

// TEST 14: Shopping Pages (/pricing/, /cart/, /checkout/)
$shoppingPages = [
    '/pricing/' => 'Volume Pricing',
    '/cart/' => 'Your Shopping Cart',
    '/checkout/' => 'Secure Checkout'
];
foreach ($shoppingPages as $sPath => $sHeading) {
    run_test("Shopping Page: {$sPath}", function() use ($baseUrl, $sPath, $sHeading) {
        $r = http_req($baseUrl . $sPath);
        $hasHeading = str_contains($r['body'], $sHeading);
        $pass = ($r['code'] === 200 && $hasHeading);
        return [
            'pass' => $pass,
            'details' => "HTTP {$r['code']}, Heading found=" . ($hasHeading ? 'YES' : 'NO')
        ];
    });
}

// TEST 15: Customer Pages (/account/login/, /account/register/)
run_test("Customer Page: /account/login/", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/account/login/');
    $hasForm = str_contains($r['body'], 'Sign In');
    $pass = ($r['code'] === 200 && $hasForm);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, form found=" . ($hasForm ? 'YES' : 'NO')
    ];
});

run_test("Customer Page: /account/register/", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/account/register/');
    $hasForm = str_contains($r['body'], 'Create an Account');
    $pass = ($r['code'] === 200 && $hasForm);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, form found=" . ($hasForm ? 'YES' : 'NO')
    ];
});

// TEST 16: Clean Blog Category and 404 Handling
run_test("Blog Category: /blog/category/printing-guides/", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/blog/category/printing-guides/');
    $pass = ($r['code'] === 200);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}"
    ];
});

run_test("Invalid Blog Category returns HTTP 404", function() use ($baseUrl) {
    $r = http_req($baseUrl . '/blog/category/invalid-non-existent-cat-12345/');
    $pass = ($r['code'] === 404);
    return [
        'pass' => $pass,
        'details' => "HTTP {$r['code']}, 404 status received=" . ($pass ? 'YES' : 'NO')
    ];
});

echo "\n=========================================================\n";
echo "   TEST SUMMARY: {$passes} PASSED, {$failures} FAILED    \n";
echo "=========================================================\n";
exit($failures === 0 ? 0 : 1);
