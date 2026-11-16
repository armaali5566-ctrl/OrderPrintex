<?php
$pages = [
    'about' => 'http://127.0.0.1:8080/about',
    'products' => 'http://127.0.0.1:8080/products',
    'pricing' => 'http://127.0.0.1:8080/pricing',
    'blog' => 'http://127.0.0.1:8080/blog',
    'gallery' => 'http://127.0.0.1:8080/gallery'
];

foreach ($pages as $name => $url) {
    $html = file_get_contents($url);
    preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $html, $m);
    preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $t);
    $h1 = isset($m[1]) ? trim(preg_replace('/\s+/', ' ', strip_tags($m[1]))) : 'none';
    $title = isset($t[1]) ? trim(preg_replace('/\s+/', ' ', strip_tags($t[1]))) : 'none';
    echo "{$name} ({$url}):\n  Title: {$title}\n  H1: {$h1}\n\n";
}
