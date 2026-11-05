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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    (function() {
      try {
        var t = localStorage.getItem('op_theme');
        if (!t || t === 'light') {
          t = 'dark';
          localStorage.setItem('op_theme', 'dark');
        }
        document.documentElement.setAttribute('data-theme', t);
      } catch (e) {}
    })();
  </script>
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDesc) ?>">
  <link rel="canonical" href="<?= e($canonical) ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= url('assets/images/logo.png') ?>">

  <!-- Open Graph / Social Meta -->
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDesc) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= e($canonical) ?>">
  <meta property="og:site_name" content="OrderPrintex">
  <meta property="og:image" content="<?= url('assets/images/logo.png') ?>">

  <!-- Core Native Stylesheets (No External CDNs) -->
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
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
