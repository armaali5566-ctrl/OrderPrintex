<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$cats = [
    1 => ['name' => 'Printing Basics', 'slug' => 'printing-basics'],
    2 => ['name' => 'T-Shirts & Apparel', 'slug' => 't-shirts-apparel'],
    3 => ['name' => 'Corporate & Branding', 'slug' => 'corporate-branding'],
    4 => ['name' => 'Packaging & Labels', 'slug' => 'packaging-labels'],
];

foreach ($cats as $id => $data) {
    db_update('blog_categories', $data, 'id = :id', ['id' => $id]);
}
echo "Blog categories updated!\n";

