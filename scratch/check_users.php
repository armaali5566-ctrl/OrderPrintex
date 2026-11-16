<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/auth.php';

$users = db_fetch_all("SELECT id, username, email, role FROM users");
print_r($users);
