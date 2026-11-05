<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$tables = db_fetch_all("SHOW TABLES");
foreach ($tables as $t) {
    echo array_values($t)[0] . "\n";
}

