<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

// Check if customer 2 has an enquiry
$enq = db_fetch_one("SELECT * FROM enquiries WHERE user_id = 2");
if (!$enq) {
    $enqId = db_insert('enquiries', [
        'ref_number' => 'OP-ENQ-' . strtoupper(substr(md5(uniqid()), 0, 5)),
        'user_id' => 2,
        'customer_name' => 'Demo Customer',
        'customer_email' => 'customer@orderprintex.com',
        'customer_phone' => '+91 93540 28199',
        'company_name' => 'Acme Apparel Brands',
        'service_id' => 1,
        'service_name' => 'DTF Gang Sheet Printing',
        'width_inches' => 22.0,
        'height_inches' => 60.0,
        'quantity' => 25,
        'material_options' => 'Cold Peel Matte Film / Ultra-HD White Underbase',
        'customer_notes' => 'Bulk seasonal order. Needs express prepress audit.',
        'status' => 'under_review'
    ]);
} else {
    $enqId = $enq['id'];
}

// Insert quotation
$quoteNum = 'QP-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));
$subtotal = 35000.00;
$taxRate = 18.0;
$taxAmount = 6300.00;
$delivery = 500.00;
$grandTotal = 41800.00;

$qid = db_insert('quotations', [
    'quote_number' => $quoteNum,
    'enquiry_id' => $enqId,
    'user_id' => 2,
    'customer_name' => 'Demo Customer',
    'customer_email' => 'customer@orderprintex.com',
    'subtotal' => $subtotal,
    'tax_rate' => $taxRate,
    'tax_amount' => $taxAmount,
    'delivery_charge' => $delivery,
    'grand_total' => $grandTotal,
    'status' => 'sent',
    'valid_until' => date('Y-m-d', strtotime('+15 days')),
    'admin_notes' => 'Prepress file check passed. 300 DPI verified. CMYK ink density optimized for 100% cotton garments.'
]);

// Insert quotation items
db_insert('quotation_items', [
    'quotation_id' => $qid,
    'item_description' => '22" x 60" DTF Commercial Gang Sheet Roll (Custom Artwork Proofed)',
    'dimensions' => '22" x 60"',
    'quantity' => 25,
    'unit_price' => 1400.00,
    'total_price' => 35000.00
]);

echo "Created test quote ID: $qid ($quoteNum) for customer 2\n";

