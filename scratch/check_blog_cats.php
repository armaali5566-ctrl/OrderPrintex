<?php
require_once __DIR__ . '/../includes/database.php';
$cats = db_fetch_all("SELECT * FROM blog_categories");
print_r($cats);

