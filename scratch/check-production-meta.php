<?php
$_SERVER['HTTP_HOST'] = 'orderprintex.com';
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 443;
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
ob_start();
require dirname(__DIR__) . '/index.php';
$html = ob_get_clean();
preg_match('/<link rel="canonical" href="([^"]+)"/', $html, $canonical);
preg_match('/<meta property="og:url" content="([^"]+)"/', $html, $og);
echo json_encode(['production_context_canonical' => $canonical[1] ?? null, 'og_url' => $og[1] ?? null]);
