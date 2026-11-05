<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$hash = password_hash('admin123', PASSWORD_DEFAULT);
db_update('users', ['password_hash' => $hash], "email = 'admin@orderprintex.com'");
echo "Admin password updated to admin123\n";

