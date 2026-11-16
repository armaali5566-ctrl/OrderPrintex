<?php
$lines = file('assets/css/style.css');
foreach ($lines as $i => $line) {
    if (preg_match('/(font-size|line-height|--font-body|\.nav-link|\.footer-link|\.product|\.article-body)/i', $line)) {
        if ($i < 100 || strpos($line, 'root') !== false || strpos($line, 'body') !== false) {
            echo ($i + 1) . ': ' . trim($line) . "\n";
        }
    }
}
