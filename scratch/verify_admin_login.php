<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$res = auth_login('admin@orderprintex.com', 'admin123');
echo "Login result: " . ($res['success'] ? 'SUCCESS' : 'FAILED: ' . ($res['error'] ?? 'unknown')) . PHP_EOL;

