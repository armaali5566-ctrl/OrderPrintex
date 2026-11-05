<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$sample = db_fetch_one("SELECT * FROM categories LIMIT 1");
print_r($sample);

