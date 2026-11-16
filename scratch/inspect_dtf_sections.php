<?php
$lines = file('services/dtf-printing.php');
foreach ($lines as $i => $l) {
    if (preg_match('/<section|<h2|<h3/i', $l)) {
        echo ($i + 1) . ': ' . trim($l) . "\n";
    }
}
