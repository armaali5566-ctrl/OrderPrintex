<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$users = db_fetch_all('SELECT id, name, email, role FROM users');
print_r($users);

