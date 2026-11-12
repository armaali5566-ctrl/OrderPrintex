<?php
// Comprehensive verification of all pages modified in this task

$urls = [
    'http://127.0.0.1:8080/products/',
    'http://127.0.0.1:8080/gallery.php',
    'http://127.0.0.1:8080/services/dtf-printing/',
    'http://127.0.0.1:8080/services/uv-dtf-printing/',
    'http://127.0.0.1:8080/services/corporate-printing/',
    'http://127.0.0.1:8080/services/t-shirt-printing/',
    'http://127.0.0.1:8080/blog/',
    'http://127.0.0.1:8080/blog/dtf-vs-screen-printing-differences-benefits-applications',
    'http://127.0.0.1:8080/blog/how-custom-printed-t-shirts-help-businesses-build-brand-identity'
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "URL: $url -> Status: $status | Length: " . strlen($response) . " bytes\n";
}

