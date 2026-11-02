<?php
/**
 * OrderPrintex - Account Logout
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

auth_logout();
set_flash('info', 'You have been logged out securely.');
header('Location: ' . url('account/login.php'));
exit;
