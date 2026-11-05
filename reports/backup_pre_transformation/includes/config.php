<?php
/**
 * OrderPrintex - Application Configuration
 * Compatible with XAMPP (http://localhost/OrderPrintex/) and standard PHP servers.
 */

// Start output buffering early to prevent "headers already sent" warnings
if (!ob_get_level()) {
    ob_start();
}

// Prevent multiple inclusions
if (defined('ORDERPRINTEX_CONFIG_LOADED')) {
    return;
}
define('ORDERPRINTEX_CONFIG_LOADED', true);

// Error Reporting (Display errors in local development, toggle for production)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Database Configuration
// Configured for XAMPP MySQL (Port 3307) with fallback to SQLite if needed
define('DB_DRIVER', getenv('DB_DRIVER') ?: 'mysql'); // 'mysql' (XAMPP) or 'sqlite'
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3307');
define('DB_NAME', getenv('DB_NAME') ?: 'orderprintex');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', 'utf8mb4');
define('DB_SQLITE_PATH', __DIR__ . '/../database/orderprintex.sqlite');

// Base Paths
define('ROOT_PATH', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);
define('UPLOADS_PATH', ROOT_PATH . 'uploads' . DIRECTORY_SEPARATOR);
define('ARTWORK_PATH', UPLOADS_PATH . 'artwork' . DIRECTORY_SEPARATOR);
define('PROOFS_PATH', UPLOADS_PATH . 'proofs' . DIRECTORY_SEPARATOR);

// Upload Restrictions
define('MAX_FILE_SIZE_BYTES', 25 * 1024 * 1024); // 25 MB max
define('ALLOWED_EXTENSIONS', ['png', 'jpg', 'jpeg', 'pdf', 'ai', 'psd', 'svg', 'eps']);
define('ALLOWED_MIME_TYPES', [
    'image/png',
    'image/jpeg',
    'image/svg+xml',
    'application/pdf',
    'application/postscript',
    'application/illustrator',
    'image/vnd.adobe.photoshop',
    'application/x-photoshop',
    'application/octet-stream' // common for .ai/.psd in some browsers
]);

// Determine Base URL dynamically (supports http://localhost/OrderPrintex/)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Check script directory relative to document root
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ROOT_PATH));
    $root = str_replace('\\', '/', ROOT_PATH);
    $subDir = '';
    
    if (strpos($root, $docRoot) === 0) {
        $subDir = trim(substr($root, strlen($docRoot)), '/');
    }

    // Handle XAMPP subdirectory (e.g. /OrderPrintex/) when SCRIPT_NAME indicates subfolder
    if (empty($subDir) && !empty($_SERVER['SCRIPT_NAME'])) {
        $scriptPath = trim(str_replace('\\', '/', $_SERVER['SCRIPT_NAME']), '/');
        $parts = explode('/', $scriptPath);
        if (count($parts) > 1 && !in_array($parts[0], ['admin', 'api', 'account', 'products', 'services', 'blog', 'uploads', 'assets'])) {
            $subDir = $parts[0];
        }
    }
    
    $baseUrl = $protocol . $host . ($subDir ? '/' . $subDir : '') . '/';
    define('BASE_URL', $baseUrl);
}

// Session Security Configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}
