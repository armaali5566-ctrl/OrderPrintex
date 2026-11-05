<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$quotes = db_fetch_all("SELECT id, quote_number, user_id, customer_email, status FROM quotations");
print_r($quotes);

