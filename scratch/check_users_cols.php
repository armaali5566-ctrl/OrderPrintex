<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
$cols = db_fetch_all("DESCRIBE users");
foreach ($cols as $c) echo $c['Field'] . "\n";
