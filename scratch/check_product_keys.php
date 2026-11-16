<?php
$code = file_get_contents(__DIR__ . '/../product-details.php');
preg_match_all('/\$product\[[\'"]([a-zA-Z0-9_]+)[\'"]\]/', $code, $m);
$keys = array_unique($m[1]);
sort($keys);
print_r($keys);
