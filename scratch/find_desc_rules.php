<?php
$lines = file('assets/css/style.css');
foreach ($lines as $i => $line) {
    if (preg_match('/(\.product-desc|\.product-card-desc|\.pricing-calculator-card|\.service-card-desc|\.service-desc)/i', $line)) {
        echo ($i + 1) . ': ' . trim($line) . "\n";
    }
}
