<?php
$lines = file(__DIR__ . '/../includes/seo_helper.php');
foreach ($lines as $i => $line) {
    if (preg_match('/function (seo_build_|seo_render_|seo_generate_page_schemas)/', $line)) {
        echo ($i + 1) . ": " . trim($line) . "\n";
    }
}
