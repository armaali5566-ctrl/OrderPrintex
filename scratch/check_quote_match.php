<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$u = db_fetch_one("SELECT * FROM users WHERE email = 'customer@orderprintex.com'");
echo "User:\n";
print_r($u);

$q = db_fetch_one("SELECT * FROM quotations WHERE id = 4");
echo "\nQuote 4:\n";
print_r($q);

$query = "
    SELECT q.*, e.ref_number as enquiry_ref, e.service_name 
    FROM quotations q
    LEFT JOIN enquiries e ON e.id = q.enquiry_id
    WHERE q.id = :id AND (q.user_id = :uid OR q.customer_email = :email)
";
$match = db_fetch_one($query, ['id' => 4, 'uid' => $u['id'], 'email' => $u['email']]);
echo "\nMatching quote 4:\n";
print_r($match);

