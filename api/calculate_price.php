<?php
/**
 * OrderPrintex - Server-Side Dynamic Price Calculator API
 * Validates product sizes, quantity discount tiers, and finishing options on the PHP backend.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);
$sizeId    = (int)($_POST['size_id'] ?? 0);
$quantity  = max(1, (int)($_POST['quantity'] ?? 1));
$optionIds = $_POST['options'] ?? [];

if (!is_array($optionIds)) {
    $optionIds = !empty($optionIds) ? explode(',', $optionIds) : [];
}
$optionIds = array_map('intval', array_filter($optionIds));

$product = db_fetch_one("SELECT * FROM products WHERE id = :id AND is_active = 1", ['id' => $productId]);
if (!$product) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Product not found.']);
    exit;
}

// 1. Determine Base Price from Selected Size (or starting price)
$size = null;
if ($sizeId > 0) {
    $size = db_fetch_one("SELECT * FROM product_sizes WHERE id = :id AND product_id = :pid", [
        'id'  => $sizeId,
        'pid' => $productId
    ]);
}

if (!$size) {
    // Pick the first available size for this product
    $size = db_fetch_one("SELECT * FROM product_sizes WHERE product_id = :pid ORDER BY display_order ASC LIMIT 1", [
        'pid' => $productId
    ]);
}

$basePrice = $size ? (float)$size['base_price'] : (float)preg_replace('/[^0-9.]/', '', $product['starting_price'] ?? '249');

// 2. Check Quantity Discount Tiers
$tiers = db_fetch_all("SELECT * FROM product_pricing_tiers WHERE product_id = :pid ORDER BY min_quantity DESC", [
    'pid' => $productId
]);

$discountPercent = 0.00;
foreach ($tiers as $tier) {
    $min = (int)$tier['min_quantity'];
    $max = !empty($tier['max_quantity']) ? (int)$tier['max_quantity'] : PHP_INT_MAX;
    if ($quantity >= $min && $quantity <= $max) {
        $discountPercent = (float)$tier['discount_percent'];
        break;
    }
}

// Discounted unit price before options
$discountedUnit = $basePrice * (1 - ($discountPercent / 100));

// 3. Add Selected Options Charges
$optionsCharge = 0.00;
$selectedOptionNames = [];

if (!empty($optionIds)) {
    $placeholders = implode(',', array_fill(0, count($optionIds), '?'));
    $params = array_merge([$productId], $optionIds);
    $sql = "SELECT * FROM product_options WHERE product_id = ? AND id IN ($placeholders)";
    $dbOptions = db_fetch_all($sql, $params);
    foreach ($dbOptions as $opt) {
        $optionsCharge += (float)$opt['additional_price'];
        $selectedOptionNames[] = $opt['option_name'];
    }
}

// Final calculations
$finalUnitPrice = round($discountedUnit + $optionsCharge, 2);
$subtotal = round($finalUnitPrice * $quantity, 2);
$gstRate = 0.18; // 18% standard GST
$taxAmount = round($subtotal * $gstRate, 2);
$grandTotal = round($subtotal + $taxAmount, 2);

echo json_encode([
    'success'               => true,
    'product_id'            => $productId,
    'sku'                   => $product['sku'],
    'size_id'               => $size ? $size['id'] : null,
    'size_label'            => $size ? $size['size_label'] : 'Standard',
    'quantity'              => $quantity,
    'base_unit_price'       => $basePrice,
    'discount_percent'      => $discountPercent,
    'discounted_unit_price' => round($discountedUnit, 2),
    'options_charge_unit'   => $optionsCharge,
    'final_unit_price'      => $finalUnitPrice,
    'subtotal'              => $subtotal,
    'tax_amount'            => $taxAmount,
    'grand_total'           => $grandTotal,
    'formatted'             => [
        'base_unit_price'  => '₹' . number_format($basePrice, 2),
        'final_unit_price' => '₹' . number_format($finalUnitPrice, 2),
        'subtotal'         => '₹' . number_format($subtotal, 2),
        'tax_amount'       => '₹' . number_format($taxAmount, 2),
        'grand_total'      => '₹' . number_format($grandTotal, 2)
    ]
]);
