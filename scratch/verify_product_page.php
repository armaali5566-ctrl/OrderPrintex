<?php
$html = file_get_contents('http://127.0.0.1:8080/product/streetwear-oversized-tee/');
echo "HTML length: " . strlen($html) . "\n";
echo "Has Small (S): " . (strpos($html, 'Small (S)') !== false ? 'YES' : 'NO') . "\n";
echo "Has Phantom Black: " . (strpos($html, 'Phantom Black') !== false ? 'YES' : 'NO') . "\n";
echo "Has Section 2 (specs): " . (strpos($html, 'id="specs-section"') !== false ? 'YES' : 'NO') . "\n";
echo "Has Section 3 (artwork): " . (strpos($html, 'id="artwork-section"') !== false ? 'YES' : 'NO') . "\n";
echo "Has Section 4 (application): " . (strpos($html, 'id="application-section"') !== false ? 'YES' : 'NO') . "\n";
echo "Has Section 5 (reviews): " . (strpos($html, 'id="reviews-section"') !== false ? 'YES' : 'NO') . "\n";
echo "Has Section 6 (shipping): " . (strpos($html, 'id="shipping-section"') !== false ? 'YES' : 'NO') . "\n";
echo "Tabs removed (productSpecsTabs): " . (strpos($html, 'productSpecsTabs') === false ? 'YES' : 'NO') . "\n";

