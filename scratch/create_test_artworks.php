<?php
$im = imagecreatetruecolor(400, 300);
$gold = imagecolorallocate($im, 212, 175, 55);
$black = imagecolorallocate($im, 10, 10, 15);
imagefilledrectangle($im, 0, 0, 400, 300, $gold);
imagestring($im, 5, 80, 140, 'ORDERPRINTEX ARTWORK 1', $black);
imagepng($im, __DIR__ . '/test_artwork_1.png');
imagedestroy($im);

$im2 = imagecreatetruecolor(600, 450);
$dark = imagecolorallocate($im2, 20, 24, 36);
$yellow = imagecolorallocate($im2, 255, 215, 0);
imagefilledrectangle($im2, 0, 0, 600, 450, $dark);
imagestring($im2, 5, 150, 210, 'ORDERPRINTEX ARTWORK 2', $yellow);
imagejpeg($im2, __DIR__ . '/test_artwork_2.jpg');
imagedestroy($im2);

// Simple test PDF
$pdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f\n0000000009 00000 n\n0000000052 00000 n\n0000000108 00000 n\ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n185\n%%EOF";
file_put_contents(__DIR__ . '/test_artwork_3.pdf', $pdfContent);

echo "ARTWORKS CREATED SUCCESSFULLY\n";

