<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$res = db_fetch_all("DESCRIBE products");
foreach ($res as $r) {
    echo $r['Field'] . " (" . $r['Type'] . ")\n";
}
