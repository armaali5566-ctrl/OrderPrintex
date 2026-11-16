<?php
$lines = file('.htaccess');
foreach ($lines as $i => $l) {
    if (strpos($l, 'service') !== false || strpos($l, 'RewriteRule') !== false) {
        echo ($i + 1) . ': ' . $l;
    }
}
