<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$tables = db_fetch_all("SHOW TABLES");
foreach ($tables as $t) {
    $tableName = array_values($t)[0];
    echo "=== Table: $tableName ===\n";
    $cols = db_fetch_all("SHOW COLUMNS FROM `$tableName`");
    foreach ($cols as $c) {
        echo "  {$c['Field']} : {$c['Type']}\n";
    }
}
