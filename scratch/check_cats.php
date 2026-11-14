<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

echo "CATEGORIES:\n";
print_r(db_fetch_all("SELECT id, name, slug FROM categories"));

echo "SERVICES:\n";
print_r(db_fetch_all("SELECT id, title, slug FROM services"));

echo "PRODUCTS:\n";
print_r(db_fetch_all("SELECT id, title, slug, category_id FROM products"));
