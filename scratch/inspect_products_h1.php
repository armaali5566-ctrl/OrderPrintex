<?php
$lines = file('products.php');
foreach ($lines as $i => $l) {
    if (strpos($l, '<h1') !== false || strpos($l, 'page-intro') !== false || strpos($l, 'page-title') !== false || strpos($l, 'section-title') !== false) {
        echo ($i + 1) . ': ' . $l;
    }
}
