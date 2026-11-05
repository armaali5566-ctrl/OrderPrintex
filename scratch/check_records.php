<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$enqs = db_fetch_all('SELECT id, ref_number, user_id, customer_email, status FROM enquiries');
$quotes = db_fetch_all('SELECT id, quote_number, user_id, customer_email, status, grand_total FROM quotations');
echo "Enquiries:\n";
print_r($enqs);
echo "\nQuotations:\n";
print_r($quotes);

