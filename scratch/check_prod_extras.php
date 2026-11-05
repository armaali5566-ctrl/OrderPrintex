<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

echo "=== PRODUCT SIZES ===\n";
print_r(db_fetch_all("SELECT * FROM product_sizes LIMIT 10"));

echo "=== PRODUCT PRICING TIERS ===\n";
print_r(db_fetch_all("SELECT * FROM product_pricing_tiers LIMIT 10"));

echo "=== PRODUCT REVIEWS ===\n";
print_r(db_fetch_all("SELECT * FROM product_reviews LIMIT 5"));

echo "=== PAGES ===\n";
print_r(db_fetch_all("SELECT id, title, slug, is_published FROM pages LIMIT 10"));

