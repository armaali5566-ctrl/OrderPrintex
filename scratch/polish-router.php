<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (is_file(dirname(__DIR__) . $path)) return false;
require dirname(__DIR__) . '/router.php';
