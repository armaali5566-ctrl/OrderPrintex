<?php
require_once __DIR__ . '/../includes/database.php';
$cols = db_fetch_all("PRAGMA table_info(enquiries)");
foreach ($cols as $c) {
    echo $c['name'] . " (" . $c['type'] . ")\n";
}

