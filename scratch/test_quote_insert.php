<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$pdo = Database::getInstance();
$pdo->beginTransaction();
$qid = db_insert('quotations', [
    'quote_number' => 'QP-TEST-100',
    'user_id' => 2,
    'customer_name' => 'Demo Customer',
    'customer_email' => 'customer@orderprintex.com',
    'subtotal' => 1000,
    'tax_rate' => 18,
    'tax_amount' => 180,
    'delivery_charge' => 50,
    'grand_total' => 1230,
    'status' => 'sent',
    'created_at' => date('Y-m-d H:i:s')
]);
$pdo->commit();

$check = db_fetch_one("SELECT * FROM quotations WHERE id = :id", ['id' => $qid]);
echo "Inserted and committed quote ID: $qid\n";
print_r($check);

