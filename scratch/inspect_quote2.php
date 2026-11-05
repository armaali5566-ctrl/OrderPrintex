<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$q = db_fetch_one("SELECT * FROM quotations WHERE id = 2");
print_r($q);

$items = db_fetch_all("SELECT * FROM quotation_items WHERE quotation_id = 2");
print_r($items);

