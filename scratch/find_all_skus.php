<?php
$lines = file('product-details.php');
foreach ($lines as $idx => $line) {
    if (stripos($line, 'sku') !== false) {
        echo ($idx + 1) . ': ' . trim($line) . "\n";
    }
}
