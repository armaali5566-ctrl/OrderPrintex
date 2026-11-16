<?php
$lines = file('includes/functions.php');
foreach ($lines as $idx => $line) {
    if (strpos($line, 'handle_blog_image_upload') !== false) {
        echo ($idx + 1) . ': ' . $line;
    }
}
