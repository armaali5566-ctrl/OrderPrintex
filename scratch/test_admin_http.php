<?php
/**
 * Test Admin Suite via HTTP Curl with Real Admin Authentication
 */

$baseUrl = 'http://127.0.0.1:8080';
$cookieFile = __DIR__ . '/admin_cookie.txt';
if (file_exists($cookieFile)) @unlink($cookieFile);

// 1. Get Login Page to obtain CSRF token
$ch = curl_init($baseUrl . '/admin/login.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$html = curl_exec($ch);
curl_close($ch);

preg_match('/name="csrf_token"\s+value="([^"]+)"/', $html, $m);
$csrfToken = $m[1] ?? '';

// 2. Perform Login with the actual password
$ch = curl_init($baseUrl . '/admin/login.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'csrf_token' => $csrfToken,
    'email'      => 'admin@orderprintex.com',
    'password'   => 'Admin@Printex2026!'
]));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$isLoggedIn = (strpos($res, 'Dashboard Overview') !== false);
echo "Admin Login result: " . ($isLoggedIn ? "SUCCESSFUL" : "FAILED") . " (HTTP {$httpCode})\n\n";

if (!$isLoggedIn) {
    echo "Aborting page checks as login failed.\n";
    exit(1);
}

$pages = [
    '/admin/dashboard.php'  => 'Dashboard Overview',
    '/admin/products.php'   => 'Manage Product Catalog',
    '/admin/variations.php' => 'Product Variations',
    '/admin/attributes.php' => 'Product Attributes',
    '/admin/categories.php' => 'Manage Product Categories',
    '/admin/pages.php'      => 'Website Pages CMS',
    '/admin/homepage.php'   => 'Homepage CMS',
    '/admin/media.php'      => 'Media Library',
    '/admin/settings.php'   => 'Website &amp; Business Settings',
    '/admin/reviews.php'    => 'Moderate Product Reviews',
    '/admin/blog.php'       => 'Blog Articles'
];

$passes = 0;
$fails = 0;

foreach ($pages as $uri => $needle) {
    $ch = curl_init($baseUrl . $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $pageHtml = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $found = strpos($pageHtml, $needle) !== false;
    if ($code === 200 && $found) {
        echo "[PASS] {$uri}: HTTP 200, found '{$needle}'\n";
        $passes++;
    } else {
        echo "[FAIL] {$uri}: HTTP {$code}, found=" . ($found ? 'YES' : 'NO') . "\n";
        $fails++;
    }
}

echo "\n=========================================================\n";
echo "   ADMIN HTTP SUITE: {$passes} PASSED, {$fails} FAILED\n";
echo "=========================================================\n";

if (file_exists($cookieFile)) @unlink($cookieFile);
