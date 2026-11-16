<?php
$lines = file('assets/js/main.js');
foreach ($lines as $i => $l) {
    if (strpos($l, 'mobileMenuToggle') !== false || strpos($l, 'nav-menu') !== false || strpos($l, 'primaryNav') !== false) {
        echo ($i + 1) . ': ' . $l;
    }
}
