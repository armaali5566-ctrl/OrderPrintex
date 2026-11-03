<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';

ob_start();
include __DIR__ . '/../admin/variations.php';
$out = ob_get_clean();
echo "Output length: " . strlen($out) . "\n";
echo "First 200 chars:\n" . substr($out, 0, 200) . "\n";

