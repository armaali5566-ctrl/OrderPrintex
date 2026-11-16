<?php
$lines = file('includes/seo_helper.php');
foreach ($lines as $num => $line) {
    if (strpos($line, 'blog-details') !== false) {
        echo ($num + 1) . ': ' . $line;
    }
}
