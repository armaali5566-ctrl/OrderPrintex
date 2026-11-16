<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
$u = db_fetch_all("SELECT id, name, email, role, status FROM users");
print_r($u);
