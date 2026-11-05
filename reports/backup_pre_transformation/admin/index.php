<?php
/**
 * OrderPrintex - Admin Panel Entry Point
 * Redirects authenticated admins to dashboard, or guests to admin login
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (auth_check() && auth_is_admin()) {
    header('Location: ' . url('admin/dashboard.php'));
    exit;
}

header('Location: ' . url('admin/login.php'));
exit;

