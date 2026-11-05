<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$pass = password_hash('customer123', PASSWORD_DEFAULT);
db_update('users', ['password_hash' => $pass], 'id = :id', ['id' => 2]);
echo "Password for customer@orderprintex.com updated to customer123\n";

