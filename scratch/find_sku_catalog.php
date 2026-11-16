<?php
$lines = file('includes/catalog_helper.php');
foreach ($lines as $i => $l) {
    if (strpos($l, 'custom-dtf-gang-sheet') !== false || strpos($l, 'sku') !== false) {
        echo ($i + 1) . ': ' . $l;
    }
}
