<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$rows = db_fetch_all("SELECT * FROM quotations");
echo "Quotations count: " . count($rows) . "\n";
print_r($rows);

