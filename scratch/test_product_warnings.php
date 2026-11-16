<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$_GET['slug'] = 'custom-dtf-gang-sheet';
$_SERVER['REQUEST_URI'] = '/products/custom-dtf-gang-sheet';

ob_start();
require __DIR__ . '/../product-details.php';
$html = ob_get_clean();

preg_match_all('/href="([^"]*wa\.me[^"]*)"/', $html, $matches);
foreach ($matches[1] as $idx => $m) {
    echo "WhatsApp link {$idx}: {$m}\n";
    echo "Decoded: " . urldecode($m) . "\n\n";
}
