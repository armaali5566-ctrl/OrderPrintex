<?php
/**
 * OrderPrintex - Global Header Partial
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? get_setting('site_name', 'OrderPrintex') . ' — Manufacturer & Bulk Supplier of DTF and UV DTF in India';
$pageDesc  = $pageDesc ?? 'PRINTEX – Top Manufacturer & Bulk Supplier of DTF and UV DTF in India. Premium-quality garment transfers, UV decals, custom t-shirts, and corporate printing.';
$canonical = $canonical ?? url($_SERVER['REQUEST_URI'] ?? '');

// Ensure canonical URL has no trailing slash (except root '/')
if (!empty($canonical)) {
    $cParts = parse_url($canonical);
    $cPath = $cParts['path'] ?? '/';
    if ($cPath !== '/' && substr($cPath, -1) === '/') {
        $cPath = rtrim($cPath, '/');
    }
    $cScheme = !empty($cParts['scheme']) ? $cParts['scheme'] . '://' : '';
    $cHost = $cParts['host'] ?? '';
    $cPort = !empty($cParts['port']) ? ':' . $cParts['port'] : '';
    $cQuery = !empty($cParts['query']) ? '?' . $cParts['query'] : '';
    $canonical = $cScheme . $cHost . $cPort . $cPath . $cQuery;
}

$ogImage   = $ogImage ?? url('assets/images/logo.png');
if (!empty($ogImage) && !str_starts_with($ogImage, 'http://') && !str_starts_with($ogImage, 'https://')) {
    $ogImage = url($ogImage);
}
$ogType    = $ogType ?? 'website';

$reqUri = $_SERVER['REQUEST_URI'] ?? '';
$isPrivatePage = !empty($noindex) || 
    strpos($reqUri, '/account') !== false || 
    strpos($reqUri, '/admin') !== false || 
    strpos($reqUri, '/cart') !== false || 
    strpos($reqUri, '/checkout') !== false || 
    strpos($reqUri, '/login') !== false || 
    strpos($reqUri, '/register') !== false || 
    strpos($reqUri, '/verify-email') !== false || 
    strpos($reqUri, '/reset-password') !== false || 
    strpos($reqUri, '404') !== false || 
    !empty($_GET['search']);

$robotsMeta = $isPrivatePage ? 'noindex, follow' : 'index, follow';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    (function() {
      try {
        var t = localStorage.getItem('op_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', t);
      } catch (e) {}
    })();
  </script>
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDesc) ?>">
  <meta name="robots" content="<?= $robotsMeta ?>">
  <link rel="canonical" href="<?= e($canonical) ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= url('assets/images/logo.png') ?>">

  <!-- Open Graph / Social Meta -->
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDesc) ?>">
  <meta property="og:type" content="<?= e($ogType) ?>">
  <meta property="og:url" content="<?= e($canonical) ?>">
  <meta property="og:site_name" content="OrderPrintex">
  <meta property="og:image" content="<?= e($ogImage) ?>">

  <!-- Twitter Card Meta -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($pageTitle) ?>">
  <meta name="twitter:description" content="<?= e($pageDesc) ?>">
  <meta name="twitter:image" content="<?= e($ogImage) ?>">

  <!-- Core Native Stylesheets (No External CDNs) -->
  <link rel="stylesheet" href="<?= url('assets/css/style.css?v=' . (file_exists(__DIR__ . '/../assets/css/style.css') ? filemtime(__DIR__ . '/../assets/css/style.css') : '2.2')) ?>">
  <link rel="stylesheet" href="<?= url('assets/css/animations.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/responsive.css') ?>">

  <!-- AI Discovery, Sitemaps & Structured Graph Links -->
  <link rel="sitemap" type="application/xml" title="Sitemap" href="<?= url('sitemap.xml') ?>">
  <link rel="alternate" type="text/plain" href="<?= url('llms.txt') ?>" title="LLM Context">
  <link rel="alternate" type="application/json" href="<?= url('schema.json') ?>" title="Structured Data Graph">
  <link rel="alternate" type="text/markdown" href="<?= url('ORDERPRINTEX.md') ?>" title="Technical Documentation">

  <!-- Schema.org Organization Structured Data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "OrderPrintex",
    "alternateName": "PRINTEX Delhi",
    "url": <?= json_encode(url('')) ?>,
    "logo": <?= json_encode(url('assets/images/logo.png')) ?>,
    "image": <?= json_encode(url('assets/images/dtf_fabric_print.jpg')) ?>,
    "telephone": "+919354028199",
    "email": "sales@orderprintex.com",
    "priceRange": "₹₹",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh",
      "addressLocality": "New Delhi",
      "addressRegion": "Delhi",
      "postalCode": "110059",
      "addressCountry": "IN"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 28.6180,
      "longitude": 77.0805
    },
    "openingHoursSpecification": [
      {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        "opens": "09:30",
        "closes": "20:00"
      }
    ],
    "sameAs": [
      "https://www.instagram.com/order_printex",
      "https://www.facebook.com/orderprintex",
      "https://www.youtube.com/@orderprintex",
      "https://www.linkedin.com/company/orderprintex"
    ]
  }
  </script>

  <!-- Schema.org WebSite Structured Data with SearchAction -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "OrderPrintex",
    "url": <?= json_encode(url('')) ?>,
    "potentialAction": {
      "@type": "SearchAction",
      "target": {
        "@type": "EntryPoint",
        "urlTemplate": <?= json_encode(url('products.php?search={search_term_string}')) ?>
      },
      "query-input": "required name=search_term_string"
    }
  }
  </script>
</head>
<body<?= !empty($bodyClass) ? ' class="' . e($bodyClass) . '"' : '' ?>>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="site-wrapper">
