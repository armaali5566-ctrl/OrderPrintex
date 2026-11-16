<?php
$lines = file('assets/css/style.css');
foreach ($lines as $i => $line) {
    if (preg_match('/(\.nav-link|\.footer-link|\.footer-title|\.navbar|\.product-page-title|\.article-body-content)/i', $line)) {
        echo ($i + 1) . ': ' . trim($line) . "\n";
    }
}
