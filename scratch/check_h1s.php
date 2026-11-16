<?php
$pages = [
    '/about' => 'about.php',
    '/products' => 'products.php',
    '/pricing' => 'pricing.php',
    '/blog' => 'blog.php',
    '/gallery' => 'gallery.php'
];

foreach ($pages as $url => $file) {
    $content = file_get_contents($file);
    if (preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $content, $m)) {
        $h1 = trim(strip_tags($m[1]));
        $h1 = preg_replace('/\s+/', ' ', $h1);
        echo "{$url} ({$file}):\n  H1 = \"{$h1}\"\n";
    } else {
        echo "{$url} ({$file}): H1 NOT FOUND\n";
    }
}
