<?php
require_once __DIR__ . '/../includes/database.php';

$title = "Delhi's Custom T-Shirt Printing Studio - Where Art Meets Fabric";
$short = "We take your brand from concept to production, printing vibrant high-density graphics onto those comfy 180-240 GSM bio-washed combed cotton tees with the perfect oversized fit, or the sleek athletic wear polyester. Whether you're just starting out or producing in bulk for your company, we've got you covered.";
$full = "At OrderPrintex, we bridge that gap between making a few special items for your event and churning out a thousand corporate tees in a week. Using the latest digital technology we pair with super-soft combed cotton, we bring you shirts that look and feel like the real deal.";
$hero = "Delhi's Custom T-Shirt Printing Studio - Where Art Meets Fabric";
$badge = "Apparel Production & Merchandise - Made to Order";

db_query("UPDATE services SET title = :t, short_description = :s, full_description = :f, hero_tagline = :h, badge = :b WHERE slug = 't-shirt-printing'", [
    't' => $title,
    's' => $short,
    'f' => $full,
    'h' => $hero,
    'b' => $badge
]);

echo "Updated t-shirt-printing service in DB.\n";
$svc = db_fetch_one("SELECT * FROM services WHERE slug = 't-shirt-printing'");
print_r($svc);

