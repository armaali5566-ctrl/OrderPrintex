<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$enq = db_fetch_one("SELECT * FROM enquiries ORDER BY id ASC LIMIT 1");
if (!$enq) {
    die("No enquiry found!\n");
}
echo "Using enquiry ID: {$enq['id']} for user {$enq['user_id']}\n";

// Update enquiry to user 2 so customer can view it
db_update('enquiries', [
    'user_id' => 2,
    'customer_email' => 'customer@orderprintex.com'
], 'id = :id', ['id' => $enq['id']]);

$quoteNum = 'QP-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));
$qid = db_insert('quotations', [
    'quote_number' => $quoteNum,
    'enquiry_id' => $enq['id'],
    'user_id' => 2,
    'customer_name' => 'Demo Customer',
    'customer_email' => 'customer@orderprintex.com',
    'subtotal' => 25000.00,
    'tax_rate' => 18.0,
    'tax_amount' => 4500.00,
    'delivery_charge' => 400.00,
    'grand_total' => 29900.00,
    'status' => 'sent',
    'valid_until' => date('Y-m-d', strtotime('+15 days')),
    'admin_notes' => 'Prepress check completed. 300 DPI verified.'
]);

// Insert quote item
db_insert('quotation_items', [
    'quotation_id' => $qid,
    'item_description' => 'Custom Commercial DTF Transfer Roll (22" x 40")',
    'dimensions' => '22" x 40"',
    'quantity' => 20,
    'unit_price' => 1250.00,
    'total_price' => 25000.00
]);

echo "Created valid persistent quotation ID: $qid ($quoteNum)\n";

