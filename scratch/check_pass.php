<?php
require_once __DIR__ . '/../includes/database.php';
$u = db_fetch_one("SELECT * FROM users WHERE email = 'admin@orderprintex.com'");
echo "Admin user: " . json_encode($u) . "\n";
// Let's test standard passwords:
$candidates = ['Admin@123', 'admin123', 'admin', 'password', 'OrderPrintex@2026', 'OrderPrintex2026'];
foreach ($candidates as $c) {
    if (password_verify($c, $u['password_hash'])) {
        echo "Found working password: " . $c . "\n";
    }
}

