<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
$users = db_fetch_all("SELECT id, name, email, role, department, status FROM users ORDER BY id ASC");
print_r($users);

