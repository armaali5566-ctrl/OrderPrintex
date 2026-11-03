<?php
require_once __DIR__ . '/../includes/database.php';
$admins = db_fetch_all("SELECT id, name, email, role FROM users WHERE role = 'admin'");
foreach ($admins as $a) {
    echo "Admin: ID={$a['id']}, Name={$a['name']}, Email={$a['email']}\n";
}

