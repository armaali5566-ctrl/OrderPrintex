<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/auth.php';

$res1 = auth_login('admin@orderprintex.com', 'admin123');
echo "Result for admin123: ";
print_r($res1);

$u = db_fetch_one("SELECT * FROM users WHERE email = 'admin@orderprintex.com'");
echo "Admin user record: ";
print_r($u);

