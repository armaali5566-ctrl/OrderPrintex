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

// Determine Base URL dynamically (supports http://127.0.0.1:8080/ and http://localhost/OrderPrintex/)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Check script directory relative to document root
    $docRoot = !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/') : '';
    $root = rtrim(str_replace('\\', '/', ROOT_PATH), '/');
    $subDir = '';
    
    // If running under a server where document root is a parent directory (e.g. C:/xampp/htdocs)
    if ($docRoot !== '' && strpos($root, $docRoot) === 0 && strlen($root) > strlen($docRoot)) {
        $subDir = trim(substr($root, strlen($docRoot)), '/');
    }

    // Check if the actual incoming request uses /OrderPrintex/ in its URI
    if (!empty($_SERVER['REQUEST_URI'])) {
        $reqPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
        if (preg_match('~^/([^/?#]+)~', $reqPath, $matches)) {
            $firstSegment = $matches[1];
            if (strcasecmp($firstSegment, 'OrderPrintex') === 0) {
                $subDir = $firstSegment;
            } elseif (in_array(strtolower($firstSegment), ['assets', 'admin', 'api', 'products', 'product', 'services', 'about', 'contact', 'gallery', 'blog', 'pricing', 'cart', 'checkout', 'faq', 'get-quote', 'account', 'login', 'register'])) {
                // Hitting root routes directly (e.g. http://127.0.0.1:8080/assets/...)
                $subDir = '';
            }
        } elseif ($reqPath === '/' || $reqPath === '') {
            if ($docRoot === $root || empty($subDir)) {
                $subDir = '';
            }
        }
    }

    // Handle XAMPP subdirectory when SCRIPT_NAME indicates subfolder
    if (empty($subDir) && !empty($_SERVER['SCRIPT_NAME'])) {
        $scriptPath = trim(str_replace('\\', '/', $_SERVER['SCRIPT_NAME']), '/');
        $parts = explode('/', $scriptPath);
        if (count($parts) > 1 && strcasecmp($parts[0], 'OrderPrintex') === 0) {
            $subDir = $parts[0];
        }
    }

    // Default for pure CLI execution without webserver context
    if (empty($subDir) && php_sapi_name() === 'cli' && empty($_SERVER['HTTP_HOST'])) {
        $subDir = 'OrderPrintex';
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
