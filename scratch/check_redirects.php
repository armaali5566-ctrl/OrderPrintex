<?php
$files = [
    'admin/orders.php',
    'admin/homepage.php',
    'admin/pages.php',
    'admin/media.php',
    'admin/faqs.php',
    'admin/seo.php',
    'admin/variations.php',
    'admin/attributes.php',
    'cart.php',
    'checkout.php',
    'account/orders.php',
    'account/order-details.php',
    'account/wishlist.php'
];

foreach ($files as $f) {
    if (!file_exists($f)) {
        echo "$f => FILE DOES NOT EXIST\n";
        continue;
    }
    $c = file_get_contents($f);
    if (preg_match('/header\([^\)]+\)/i', $c, $m)) {
        echo "$f => " . $m[0] . "\n";
    } else {
        echo "$f => NO REDIRECT FOUND\n";
    }
}

