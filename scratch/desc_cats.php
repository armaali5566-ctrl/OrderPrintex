<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$cols = db_fetch_all('DESCRIBE categories');
print_r($cols);

