<?php
require_once __DIR__ . '/../includes/database.php';
$cols = db_fetch_all("DESCRIBE services");
print_r($cols);

