<?php
$files = ['includes/navbar.php', 'includes/header.php', 'includes/footer.php'];
foreach ($files as $f) {
    $c = file_get_contents($f);
    if (strpos($c, 'nav-drawer') !== false || strpos($c, 'mobile-menu') !== false || strpos($c, 'mobile-nav') !== false) {
        echo "Found mobile drawer in {$f}\n";
    }
}
