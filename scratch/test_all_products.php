<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/catalog_helper.php';

$products = get_curated_catalog_products();
foreach ($products as $p) {
    $_GET['slug'] = $p['slug'];
    $_SERVER['REQUEST_URI'] = '/products/' . $p['slug'];

    ob_start();
    require __DIR__ . '/../product-details.php';
    $html = ob_get_clean();

    $hasWarning = (strpos($html, '<b>Warning</b>') !== false || strpos($html, 'Undefined array key') !== false);
    echo "Product " . $p['slug'] . " (SKU: " . $p['sku'] . "): " . ($hasWarning ? 'WARNING FOUND' : 'OK') . "\n";
}
