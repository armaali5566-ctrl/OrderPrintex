<?php
$files = ['about.php', 'includes/navbar.php', 'ORDERPRINTEX.md', 'SITE_OVERVIEW.md', 'llms.txt', 'schema.json'];
foreach ($files as $f) {
    if (file_exists($f)) {
        $c = file_get_contents($f);
        if (preg_match_all('/About\s+Us/i', $c, $m)) {
            echo "{$f} has " . count($m[0]) . " occurrences of 'About Us'\n";
        }
    }
}
