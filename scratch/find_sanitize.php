<?php
$lines = file('includes/functions.php');
foreach ($lines as $idx => $line) {
    if (strpos($line, 'sanitize') !== false) {
        echo ($idx + 1) . ': ' . $line;
    }
}
