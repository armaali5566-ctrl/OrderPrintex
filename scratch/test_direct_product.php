<?php
$_GET['slug'] = 'custom-dtf-gang-sheet';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/product/custom-dtf-gang-sheet';
$_SERVER['HTTP_HOST'] = '127.0.0.1:8080';

error_reporting(E_ALL);
ini_set('display_errors', '1');

ob_start();
require __DIR__ . '/../product-details.php';
$output = ob_get_clean();

echo "Length: " . strlen($output) . "\n";
if (strpos($output, 'Warning:') !== false || strpos($output, 'Notice:') !== false) {
    echo "ERRORS FOUND:\n";
    preg_match_all('/(Warning|Notice):.*?(<br|<\/)/is', $output, $m);
    foreach ($m[0] as $e) echo strip_tags($e) . "\n";
} else {
    echo "NO ERRORS IN OUTPUT\n";
}
