<?php
// Verification script for exact copy and components

function check_url_contains($url, $needle, $label) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    curl_close($ch);

    $found = (strpos($html, $needle) !== false);
    echo ($found ? "[PASS] " : "[FAIL] ") . "$label (Looking for '$needle')\n";
    return $found;
}

function check_url_not_contains($url, $needle, $label) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    curl_close($ch);

    $found = (strpos($html, $needle) !== false);
    echo (!$found ? "[PASS] " : "[FAIL] ") . "$label (Must NOT contain '$needle')\n";
    return !$found;
}

echo "=== 1. Products Page Checks ===\n";
check_url_not_contains('http://127.0.0.1:8080/products/', 'SKU:', 'Products page has no SKU tags');
check_url_contains('http://127.0.0.1:8080/products/', '350 GSM card', 'Card 9 shows 350 GSM card specifications');
check_url_contains('http://127.0.0.1:8080/products/', 'Min. 100 pcs', 'Card 9 shows Min. 100 pcs MOQ');

echo "\n=== 2. Gallery Page Checks ===\n";
check_url_contains('http://127.0.0.1:8080/gallery.php', 'CHOOSE A PRINT TO PREVIEW:', 'Preview selector label');
check_url_contains('http://127.0.0.1:8080/gallery.php', 'Stickers', 'Gallery has Stickers category');
check_url_contains('http://127.0.0.1:8080/gallery.php', 'Branding Material', 'Gallery has Branding Material category');

echo "\n=== 3. T-Shirt Printing Page Checks ===\n";
check_url_contains('http://127.0.0.1:8080/services/t-shirt-printing/', "Delhi's Custom T-Shirt Printing Studio - Where Art Meets Fabric", 'T-Shirt H1 title');
check_url_contains('http://127.0.0.1:8080/services/t-shirt-printing/', 'Like Retail, But Better - Custom T-Shirt Printing on Premium Bio-Washed Fabrics', 'Retail But Better H2');
check_url_contains('http://127.0.0.1:8080/services/t-shirt-printing/', 'Hit us up for a T-Shirt quote', 'Quote CTA button');
check_url_contains('http://127.0.0.1:8080/services/t-shirt-printing/', 'Check out our Garment styles', 'Explore CTA button');
check_url_contains('http://127.0.0.1:8080/services/t-shirt-printing/', '[timeline confirm karo]', 'Timeline placeholder preserved exactly');
check_url_contains('http://127.0.0.1:8080/services/t-shirt-printing/', 'Call / WhatsApp:', 'Studio call line preserved exactly');

echo "\n=== 4. Blog Posts Checks ===\n";
check_url_contains('http://127.0.0.1:8080/blog/', 'DTF vs Screen Printing: Understanding the Differences', 'Blog lists Article 4');
check_url_contains('http://127.0.0.1:8080/blog/', 'How Custom Printed T-Shirts Help Businesses Build Brand Identity', 'Blog lists Article 5');
check_url_contains('http://127.0.0.1:8080/blog/dtf-vs-screen-printing-differences-benefits-applications', 'Ready to Start Your Print Order?', 'Article 4 has CTA card');
check_url_contains('http://127.0.0.1:8080/blog/how-custom-printed-t-shirts-help-businesses-build-brand-identity', 'Ready to Start Your Print Order?', 'Article 5 has CTA card');

