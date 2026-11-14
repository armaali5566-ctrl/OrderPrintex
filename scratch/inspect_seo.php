<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/seo_helper.php';

$funcs = get_defined_functions()['user'];
$seoFuncs = array_values(array_filter($funcs, fn($f) => str_starts_with($f, 'seo_')));
echo "Defined seo_* functions:\n";
foreach ($seoFuncs as $f) {
    echo "- $f\n";
}
