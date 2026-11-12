<?php
/**
 * OrderPrintex - Secure Authorized Artwork Viewer & Download Handler
 * Protects private customer designs from unauthorized public access,
 * prevents directory traversal, and safely streams artwork files to authorized personnel.
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Strict permission check: only authorized staff/admin can view client artwork
require_permission('enquiries.view');

$fileId = intval($_GET['file_id'] ?? 0);
if ($fileId <= 0) {
    http_response_code(400);
    exit('Invalid artwork file identifier.');
}

$fileRecord = db_fetch_one("SELECT * FROM enquiry_files WHERE id = :id LIMIT 1", ['id' => $fileId]);
if (!$fileRecord) {
    http_response_code(404);
    exit('Artwork file record not found in database.');
}

// Candidate locations where the file might reside
$candidates = [
    ROOT_PATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fileRecord['file_path']),
    ARTWORK_PATH . $fileRecord['stored_name'],
    UPLOADS_PATH . 'artwork' . DIRECTORY_SEPARATOR . $fileRecord['stored_name'],
    UPLOADS_PATH . $fileRecord['stored_name']
];

$realPath = null;
foreach ($candidates as $candidate) {
    if (file_exists($candidate) && is_file($candidate)) {
        $realPath = realpath($candidate);
        break;
    }
}

// Strict directory traversal prevention: must reside within UPLOADS_PATH
$uploadsRoot = realpath(UPLOADS_PATH);
if (!$realPath || !$uploadsRoot || strpos($realPath, $uploadsRoot) !== 0) {
    http_response_code(404);
    exit('Artwork file does not exist on server storage.');
}

$extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
$mimeMap = [
    'png'  => 'image/png',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'webp' => 'image/webp',
    'svg'  => 'image/svg+xml',
    'pdf'  => 'application/pdf',
    'ai'   => 'application/illustrator',
    'psd'  => 'image/vnd.adobe.photoshop',
    'eps'  => 'application/postscript'
];

$contentType = $mimeMap[$extension] ?? (mime_content_type($realPath) ?: 'application/octet-stream');
$isDownload = (isset($_GET['download']) && $_GET['download'] === '1');
$disposition = $isDownload ? 'attachment' : 'inline';
$safeFilename = str_replace(['"', "'", "\r", "\n", "\\", "/"], '', $fileRecord['original_name'] ?: basename($realPath));

// Output safe streaming headers
header('Content-Type: ' . $contentType);
header('Content-Length: ' . filesize($realPath));
header('Content-Disposition: ' . $disposition . '; filename="' . $safeFilename . '"');
header('Cache-Control: private, max-age=86400');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

// Clear any buffered output before binary stream
if (ob_get_level()) {
    ob_end_clean();
}

readfile($realPath);
exit;

