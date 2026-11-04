<?php
/**
 * OrderPrintex - Responsive Redesign Verification Script
 */

$baseUrl = 'http://127.0.0.1:8080';
$cssFile = __DIR__ . '/../assets/css/responsive.css';

echo "=========================================================\n";
echo "   ORDERPRINTEX — RESPONSIVE VERIFICATION SUITE         \n";
echo "=========================================================\n\n";

$errors = [];
$passes = 0;

// 1. Verify responsive.css file existence and size
if (!file_exists($cssFile)) {
    $errors[] = "responsive.css does not exist at $cssFile";
} else {
    $cssContent = file_get_contents($cssFile);
    $cssLen = strlen($cssContent);
    echo "[PASS] responsive.css exists ($cssLen bytes)\n";
    $passes++;

    // Check no zoom or transform scale hacks on body/html
    if (preg_match('/(html|body)[^{]*\{[^}]*zoom\s*:/i', $cssContent)) {
        $errors[] = "Found illegal zoom hack on html/body in responsive.css";
    } else {
        echo "[PASS] No illegal CSS zoom hacks detected\n";
        $passes++;
    }

    if (preg_match('/(html|body)[^{]*\{[^}]*transform\s*:\s*scale/i', $cssContent)) {
        $errors[] = "Found illegal transform scale hack on html/body in responsive.css";
    } else {
        echo "[PASS] No illegal CSS scale transform hacks detected\n";
        $passes++;
    }

    // Check all required breakpoint rules are represented
    $breakpoints = [
        '2560px' => 'min-width: 2560px',
        '1920px' => 'min-width: 1920px',
        '1440px / 1600px' => 'min-width: 1440px',
        '1121px - 1439px (1280px / 1366px)' => 'min-width: 1121px',
        '1120px (Drawer breakpoint)' => 'max-width: 1120px',
        '1024px (Small laptops & Tablets)' => 'max-width: 1024px',
        '820px (iPad Air)' => 'max-width: 820px',
        '768px (Standard Tablets)' => 'max-width: 768px',
        '600px (Large Phones / Small Tablets)' => 'max-width: 600px',
        '430px (Large Phones)' => 'max-width: 430px',
        '359px / 320px (Small Mobile)' => 'max-width: 359px',
    ];

    foreach ($breakpoints as $label => $pattern) {
        if (strpos($cssContent, $pattern) !== false) {
            echo "[PASS] Breakpoint range '$label' ($pattern) verified in responsive.css\n";
            $passes++;
        } else {
            $errors[] = "Breakpoint range '$label' ($pattern) missing in responsive.css";
        }
    }

    // Check 44px touch targets
    if (strpos($cssContent, '44px') !== false) {
        echo "[PASS] 44px minimum touch targets verified in responsive.css\n";
        $passes++;
    } else {
        $errors[] = "44px touch targets not defined in responsive.css";
    }

    // Check iOS auto-zoom prevention (16px input font size)
    if (strpos($cssContent, 'font-size: 16px !important;') !== false) {
        echo "[PASS] 16px iOS Safari auto-zoom prevention verified in responsive.css\n";
        $passes++;
    } else {
        $errors[] = "16px input font size rule missing in responsive.css";
    }
}

// 2. Test pages link responsive.css and return HTTP 200
$testRoutes = [
    'Homepage'          => '/',
    'Products'          => '/products/',
    'Product Details'   => '/product/custom-dtf-gang-sheet/',
    'Quotation'         => '/get-quote/',
    'Services'          => '/services/',
    'About'             => '/about/',
    'Contact'           => '/contact/',
    'Gallery'           => '/gallery/',
    'Blog Archive'      => '/blog/',
    'Blog Details'      => '/blog/complete-guide-to-business-card-printing/',
    'Pricing'           => '/pricing/',
    'Cart'              => '/cart/',
    'Checkout'          => '/checkout/',
    'Customer Login'    => '/account/login/',
];

foreach ($testRoutes as $name => $route) {
    $url = $baseUrl . $route;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 200) {
        if (strpos($html, 'responsive.css') !== false) {
            echo "[PASS] Page $name ($route) returns HTTP 200 and loads responsive.css\n";
            $passes++;
        } else {
            $errors[] = "Page $name ($route) returned 200 but lacks responsive.css link!";
        }
    } else {
        $errors[] = "Page $name ($route) returned HTTP $code";
    }
}

// Check admin header links responsive.css
$adminHeader = file_get_contents(__DIR__ . '/../admin/header.php');
if (strpos($adminHeader, 'responsive.css') !== false) {
    echo "[PASS] Admin Portal (admin/header.php) links responsive.css\n";
    $passes++;
} else {
    $errors[] = "Admin header.php does not link responsive.css";
}

echo "\n=========================================================\n";
if (empty($errors)) {
    echo "   RESPONSIVE REDESIGN VERIFICATION: ALL $passes CHECKS PASSED!\n";
    echo "=========================================================\n";
    exit(0);
} else {
    echo "   VERIFICATION FAILED WITH " . count($errors) . " ERRORS:\n";
    foreach ($errors as $err) {
        echo "   - $err\n";
    }
    echo "=========================================================\n";
    exit(1);
}

