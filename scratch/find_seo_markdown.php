<?php
$lines = file('includes/seo_helper.php');
foreach ($lines as $i => $line) {
    if (stripos($line, 'function seo_build_blog') !== false || stripos($line, 'function seo_build_markdown') !== false || stripos($line, 'blog-') !== false) {
        echo ($i + 1) . ': ' . trim($line) . "\n";
    }
}
