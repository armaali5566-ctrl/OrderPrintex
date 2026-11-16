<?php
$lines = file('router.php');
foreach ($lines as $i => $l) {
    if (strpos($l, 'service') !== false || strpos($l, 'preg_match') !== false) {
        echo ($i + 1) . ': ' . $l;
    }
}
