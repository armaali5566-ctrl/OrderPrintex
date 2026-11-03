<?php
require_once __DIR__ . '/../includes/database.php';
$tables = db_fetch_all("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name ASC");
echo "Existing tables:\n";
foreach ($tables as $t) {
    echo "- " . $t['name'] . "\n";
}

