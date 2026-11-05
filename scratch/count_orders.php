<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$cnt = db_fetch_one("SELECT COUNT(*) as cnt FROM orders")['cnt'];
echo "Current order count: $cnt\n";

