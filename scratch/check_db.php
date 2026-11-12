<?php
require_once __DIR__ . '/../includes/database.php';
$p = db_fetch_all('SELECT id, title, slug, min_order, specifications FROM products');
echo json_encode($p, JSON_PRETTY_PRINT);
