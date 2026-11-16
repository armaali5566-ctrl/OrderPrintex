<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

echo "=== CHECKING SERVICES TABLE ===\n";
$services = db_fetch_all("SELECT * FROM services");
print_r($services);

echo "\n=== CHECKING SERVICES TABLE SCHEMA ===\n";
$cols = db_fetch_all("DESCRIBE services");
foreach ($cols as $c) {
    echo "{$c['Field']} ({$c['Type']})\n";
}
