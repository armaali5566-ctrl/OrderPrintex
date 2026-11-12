<?php
require_once __DIR__ . '/../includes/database.php';
$rows = db_fetch_all('SELECT id, title, category, image_url, display_order FROM gallery ORDER BY display_order ASC, id ASC');
echo json_encode($rows, JSON_PRETTY_PRINT);

