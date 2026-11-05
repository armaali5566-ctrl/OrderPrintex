<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$enqs = db_fetch_all("SELECT * FROM enquiries");
echo "Enquiries count: " . count($enqs) . "\n";
print_r($enqs);

