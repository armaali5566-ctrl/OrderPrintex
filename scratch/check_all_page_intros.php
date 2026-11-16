<?php
$files = ['contact.php', 'faq.php', 'get-quote.php', 'services.php', 'about.php', 'products.php', 'pricing.php', 'blog.php', 'gallery.php'];
foreach ($files as $f) {
    $c = file_get_contents($f);
    preg_match('/<div class="page-intro-eyebrow"[^>]*>(.*?)<\/div>/is', $c, $eyebrow);
    preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $c, $h1);
    $eb = isset($eyebrow[1]) ? trim(preg_replace('/\s+/', ' ', strip_tags($eyebrow[1]))) : 'none';
    $heading = isset($h1[1]) ? trim(preg_replace('/\s+/', ' ', strip_tags($h1[1]))) : 'none';
    echo "{$f}:\n  Eyebrow: {$eb}\n  H1: {$heading}\n";
}
