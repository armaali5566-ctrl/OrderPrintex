<?php
$ch = curl_init('https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js');
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "CDN status code: " . $code . "\n";
