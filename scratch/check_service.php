<?php
require_once __DIR__ . '/../includes/database.php';
$svc = db_fetch_one("SELECT * FROM services WHERE slug = 't-shirt-printing'");
echo json_encode($svc, JSON_PRETTY_PRINT);

