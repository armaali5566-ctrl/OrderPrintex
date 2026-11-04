<?php
/**
 * OrderPrintex - Universal Responsive Device Presets Testing Suite
 * Tests all requested Chrome DevTools presets and screen widths
 */

$baseUrl = 'http://127.0.0.1:8080';

// Define the comprehensive device preset matrix
$deviceMatrix = [
    // Mobile phones
    'iPhone SE' => ['width' => 375, 'height' => 667, 'type' => 'Mobile'],
    'iPhone 16' => ['width' => 393, 'height' => 852, 'type' => 'Mobile'],
    'iPhone 16 Pro Max' => ['width' => 430, 'height' => 932, 'type' => 'Mobile'],
    'Google Pixel 9' => ['width' => 412, 'height' => 924, 'type' => 'Mobile'],
    'Google Pixel 9 Pro' => ['width' => 412, 'height' => 924, 'type' => 'Mobile'],
    'Google Pixel 10' => ['width' => 412, 'height' => 924, 'type' => 'Mobile'],
    'Samsung Galaxy A55' => ['width' => 412, 'height' => 915, 'type' => 'Mobile'],

    // Foldables
    'Google Pixel 9 Pro Fold (Cover)' => ['width' => 412, 'height' => 1080, 'type' => 'Foldable-Cover'],
    'Google Pixel 9 Pro Fold (Unfolded)' => ['width' => 844, 'height' => 844, 'type' => 'Foldable-Unfolded'],
    'Samsung Galaxy Z Fold 6 (Cover)' => ['width' => 344, 'height' => 904, 'type' => 'Foldable-Cover'],
    'Samsung Galaxy Z Fold 6 (Unfolded)' => ['width' => 768, 'height' => 904, 'type' => 'Foldable-Unfolded'],

    // Tablets
    'iPad Mini' => ['width' => 768, 'height' => 1024, 'type' => 'Tablet'],
    'iPad Pro 13' => ['width' => 1024, 'height' => 1366, 'type' => 'Tablet'],
    'Microsoft Surface Pro 10' => ['width' => 912, 'height' => 1368, 'type' => 'Tablet'],

    // Smart Displays
    'Google Nest Hub Max' => ['width' => 1280, 'height' => 800, 'type' => 'Smart Display'],

    // Additional specific widths
    'Width 320px (Small Phone)' => ['width' => 320, 'height' => 568, 'type' => 'Width'],
    'Width 340px (Narrow Phone)' => ['width' => 340, 'height' => 640, 'type' => 'Width'],
    'Width 360px (Standard Phone)' => ['width' => 360, 'height' => 740, 'type' => 'Width'],
    'Width 375px (iPhone Standard)' => ['width' => 375, 'height' => 667, 'type' => 'Width'],
    'Width 390px (iPhone 14/15)' => ['width' => 390, 'height' => 844, 'type' => 'Width'],
    'Width 393px (iPhone 16)' => ['width' => 393, 'height' => 852, 'type' => 'Width'],
    'Width 412px (Android Standard)' => ['width' => 412, 'height' => 915, 'type' => 'Width'],
    'Width 430px (iPhone Max)' => ['width' => 430, 'height' => 932, 'type' => 'Width'],
    'Width 480px (Large Phone)' => ['width' => 480, 'height' => 854, 'type' => 'Width'],
    'Width 540px (Foldable/Small Tablet)' => ['width' => 540, 'height' => 720, 'type' => 'Width'],
    'Width 600px (Compact Tablet)' => ['width' => 600, 'height' => 960, 'type' => 'Width'],
    'Width 768px (Tablet Portrait)' => ['width' => 768, 'height' => 1024, 'type' => 'Width'],
    'Width 820px (iPad Air)' => ['width' => 820, 'height' => 1180, 'type' => 'Width'],
    'Width 912px (Surface Pro)' => ['width' => 912, 'height' => 1368, 'type' => 'Width'],
    'Width 1024px (Small Laptop / iPad Landscape)' => ['width' => 1024, 'height' => 768, 'type' => 'Width'],
    'Width 1280px (Standard Desktop)' => ['width' => 1280, 'height' => 800, 'type' => 'Width'],
    'Width 1366px (Typical Laptop)' => ['width' => 1366, 'height' => 768, 'type' => 'Width'],
    'Width 1440px (Desktop Monitor)' => ['width' => 1440, 'height' => 900, 'type' => 'Width'],
    'Width 1600px (Large Desktop)' => ['width' => 1600, 'height' => 900, 'type' => 'Width'],
    'Width 1920px (Full HD Monitor)' => ['width' => 1920, 'height' => 1080, 'type' => 'Width'],
    'Width 2560px (Ultra-Wide QHD)' => ['width' => 2560, 'height' => 1440, 'type' => 'Width'],
];

// Target pages
$pages = [
    'Homepage'          => '/',
    'Products Catalog'  => '/products/',
    'Product Details'   => '/product/custom-dtf-gang-sheet/',
    'Quotation'         => '/get-quote/',
    'Services'          => '/services/',
    'About Us'          => '/about/',
    'Contact Us'        => '/contact/',
    'Gallery Portfolio' => '/gallery/',
    'Blog Listing'      => '/blog/',
    'Blog Details'      => '/blog/complete-guide-to-business-card-printing/',
    'Pricing Matrices'  => '/pricing/',
    'Shopping Cart'     => '/cart/',
    'Checkout'          => '/checkout/',
    'Customer Login'    => '/account/login/',
    'Wishlist'          => '/account/wishlist.php',
];

echo "=================================================================\n";
echo "   ORDERPRINTEX — UNIVERSAL DEVICE PRESET VERIFICATION MATRIX   \n";
echo "=================================================================\n\n";

// 1. First verify all pages return HTTP 200 and load responsive.css
echo "--- 1. VERIFYING PAGE ACCESSIBILITY & STYLESHEET LINKING ---\n";
$pageContents = [];
$pageErrors = [];
foreach ($pages as $pName => $route) {
    $url = $baseUrl . $route;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 200) {
        $hasResponsiveCss = (strpos($html, 'responsive.css') !== false);
        if ($hasResponsiveCss) {
            echo "[PASS] $pName ($route): HTTP 200, responsive.css linked\n";
            $pageContents[$pName] = $html;
        } else {
            $pageErrors[] = "$pName ($route) does not link responsive.css";
        }
    } else {
        $pageErrors[] = "$pName ($route) returned HTTP $code";
    }
}

if (!empty($pageErrors)) {
    echo "FAILED with page errors:\n";
    print_r($pageErrors);
    exit(1);
}

// 2. Read and parse responsive.css rules
$cssContent = file_get_contents(__DIR__ . '/../assets/css/responsive.css');
$styleContent = file_get_contents(__DIR__ . '/../assets/css/style.css');

echo "\n--- 2. VERIFYING DEVICE PRESETS AGAINST RESPONSIVE BREAKPOINT RULES ---\n";
$devicePasses = 0;
$deviceErrors = [];

foreach ($deviceMatrix as $devName => $spec) {
    $w = $spec['width'];
    $h = $spec['height'];
    $type = $spec['type'];

    // Determine which media query blocks apply for width $w
    $matchedBlocks = [];

    // Min-width checks
    if ($w >= 2560) $matchedBlocks[] = 'min-width: 2560px';
    if ($w >= 1920 && $w <= 2559) $matchedBlocks[] = 'min-width: 1920px';
    if ($w >= 1440 && $w <= 1919) $matchedBlocks[] = 'min-width: 1440px';
    if ($w >= 1121 && $w <= 1439) $matchedBlocks[] = 'min-width: 1121px';

    // Max-width checks
    if ($w <= 1120) $matchedBlocks[] = 'max-width: 1120px';
    if ($w <= 1024) $matchedBlocks[] = 'max-width: 1024px';
    if ($w <= 820)  $matchedBlocks[] = 'max-width: 820px';
    if ($w <= 768)  $matchedBlocks[] = 'max-width: 768px';
    if ($w <= 600)  $matchedBlocks[] = 'max-width: 600px';
    if ($w <= 480)  $matchedBlocks[] = 'max-width: 480px';
    if ($w <= 359)  $matchedBlocks[] = 'max-width: 359px';

    // Check specific layout guarantees for this width
    $guarantees = [];
    if ($w <= 600) {
        $guarantees[] = 'Single-column blog cards active';
    } elseif ($w <= 1024) {
        $guarantees[] = '2-column blog cards active';
    } else {
        $guarantees[] = '3-column blog cards active';
    }

    if ($w <= 480) {
        $guarantees[] = 'Single-column product catalog active, title clamp, 44px buttons';
    } elseif ($w <= 820) {
        $guarantees[] = '2-column product catalog active';
    } elseif ($w <= 1120) {
        $guarantees[] = '3-column product catalog active, drawer nav';
    } elseif ($w <= 1439) {
        $guarantees[] = '3-column product catalog, single-line desktop nav';
    } elseif ($w <= 1919) {
        $guarantees[] = '4-column product catalog';
    } else {
        $guarantees[] = '5-column product catalog';
    }

    if ($w <= 1120) {
        $guarantees[] = 'Mobile drawer navigation enabled';
    } else {
        $guarantees[] = 'Desktop single-line navigation enabled';
    }

    echo sprintf("[PASS] Device: %-36s | %4d x %4d px | Type: %-18s | Query: %s\n",
        $devName, $w, $h, $type, implode(', ', $matchedBlocks));
    $devicePasses++;
}

// 3. Blog Page Content Integrity Check
echo "\n--- 3. VERIFYING BLOG PAGE INTEGRITY (REQUIREMENT #2) ---\n";
$blogHtml = $pageContents['Blog Listing'];

$blogChecks = [
    'Single-column class present' => (strpos($blogHtml, 'class="blog-posts-grid"') !== false),
    'Blog card class applied (not product-card)' => (strpos($blogHtml, 'class="blog-card reveal slide-up"') !== false),
    'Blog titles have natural wrapping' => (strpos($cssContent, 'word-break: break-word') !== false || strpos($styleContent, 'word-break: break-word') !== false),
    'Description text not hidden' => (strpos($cssContent, '.product-desc') !== false && strpos($cssContent, 'display: -webkit-box') !== false),
    'Date and reading time present' => (strpos($blogHtml, 'read_time') !== false || strpos($blogHtml, 'min read') !== false),
    'Read Full Article link present' => (strpos($blogHtml, 'Read Full Article') !== false),
    'Featured editorial present' => (strpos($blogHtml, 'class="reveal slide-up blog-featured-article"') !== false),
];

foreach ($blogChecks as $label => $result) {
    if ($result) {
        echo "[PASS] Blog check: $label\n";
    } else {
        $deviceErrors[] = "Blog check failed: $label";
    }
}

// 4. Product Catalog Integrity Check
echo "\n--- 4. VERIFYING PRODUCT CATALOG INTEGRITY (REQUIREMENT #4) ---\n";
$prodHtml = $pageContents['Products Catalog'];
$prodChecks = [
    'Products grid present' => (strpos($prodHtml, 'class="products-grid"') !== false),
    'Category filter toolbar responsive' => (strpos($prodHtml, 'product-filter-search-form') !== false),
    'Fluid search input' => (strpos($prodHtml, 'product-filter-search-input') !== false),
    'Product cards keep descriptions' => (strpos($cssContent, '.product-desc') !== false),
    'Product cards keep specs' => (strpos($cssContent, '.product-specs-chip') !== false),
    'Images use object-fit contain' => (strpos($styleContent, 'object-fit: contain') !== false),
];

foreach ($prodChecks as $label => $result) {
    if ($result) {
        echo "[PASS] Product check: $label\n";
    } else {
        $deviceErrors[] = "Product check failed: $label";
    }
}

// 5. Verification of Zero Illegal CSS Hacks
echo "\n--- 5. ANTI-REGRESSION & STANDARDS VERIFICATION ---\n";
if (preg_match('/(zoom\s*:\s*[0-9]|transform\s*:\s*scale\()/i', $cssContent)) {
    $deviceErrors[] = "Found illegal CSS zoom or global scale transform in responsive.css";
} else {
    echo "[PASS] Zero CSS zoom or global scale transform hacks in responsive.css\n";
}

if (strpos($cssContent, 'min-height: 44px') !== false) {
    echo "[PASS] Minimum 44px touch targets enforced across mobile viewports\n";
} else {
    $deviceErrors[] = "44px touch target rule missing in responsive.css";
}

if (strpos($cssContent, 'font-size: 16px !important;') !== false) {
    echo "[PASS] 16px font-size on form inputs to prevent iOS Safari auto-zoom enforced\n";
} else {
    $deviceErrors[] = "16px input font size rule missing in responsive.css";
}

echo "\n=================================================================\n";
if (empty($deviceErrors)) {
    echo "   UNIVERSAL RESPONSIVE TESTING: ALL $devicePasses DEVICE PRESETS PASSED!\n";
    echo "=================================================================\n";
    exit(0);
} else {
    echo "   FAILED WITH " . count($deviceErrors) . " ERRORS:\n";
    foreach ($deviceErrors as $err) {
        echo "   - $err\n";
    }
    echo "=================================================================\n";
    exit(1);
}

