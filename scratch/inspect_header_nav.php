<?php
$lines = file('includes/header.php');
foreach ($lines as $i => $l) {
    if (strpos($l, 'nav-link') !== false || strpos($l, 'about') !== false || strpos($l, 'products') !== false || strpos($l, 'pricing') !== false || strpos($l, 'gallery') !== false || strpos($l, 'blog') !== false) {
        echo ($i + 1) . ': ' . trim($l) . "\n";
    }
}
