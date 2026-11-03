<?php
/**
 * OrderPrintex - Product Comparison API
 * Returns structured comparison specifications for up to 3 product IDs.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

$idsParam = $_GET['ids'] ?? '';
$ids = array_filter(array_map('intval', explode(',', $idsParam)));

if (empty($ids)) {
    echo json_encode(['success' => false, 'products' => [], 'error' => 'No product IDs specified.']);
    exit;
}

$ids = array_slice($ids, 0, 3);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$sql = "
    SELECT p.id, p.sku, p.title, p.slug, p.short_desc, p.specifications, p.image_url,
           p.starting_price, p.price_unit, p.min_order, c.name as category_name, s.title as service_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN services s ON p.service_id = s.id
    WHERE p.id IN ($placeholders)
";

$products = db_fetch_all($sql, $ids);

$formatted = [];
foreach ($products as $p) {
    $formatted[] = [
        'id'             => (int)$p['id'],
        'sku'            => $p['sku'] ?? 'N/A',
        'title'          => $p['title'],
        'slug'           => $p['slug'],
        'url'            => product_url($p['slug']),
        'category_name'  => $p['category_name'] ?? 'General Printing',
        'service_name'   => $p['service_name'] ?? 'Custom Print',
        'starting_price' => $p['starting_price'] ?? '₹249',
        'price_unit'     => $p['price_unit'] ?? '',
        'min_order'      => $p['min_order'] ?: 'None',
        'specifications' => $p['specifications'] ?: 'Calibrated Industrial Grade',
        'image_url'      => url($p['image_url'] ?: 'assets/images/gallery_1.jpg')
    ];
}

echo json_encode([
    'success'  => true,
    'count'    => count($formatted),
    'products' => $formatted
]);
