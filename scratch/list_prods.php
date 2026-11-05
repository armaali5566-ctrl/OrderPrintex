<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$prods = db_fetch_all("SELECT id, title, slug FROM products");
print_r($prods);

