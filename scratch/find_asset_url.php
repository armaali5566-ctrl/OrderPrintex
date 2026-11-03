<?php
$files = glob(__DIR__ . '/../admin/*.php');
foreach ($files as $f) {
    $content = file_get_contents($f);
    if (strpos($content, 'asset_url(') !== false) {
        echo "Found asset_url in: " . basename($f) . "\n";
    }
}

