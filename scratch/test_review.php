<?php
require 'includes/config.php';
require 'includes/database.php';

// Insert authentic test review for bookmark-03
$revId = db_insert('product_reviews', [
    'product_id' => 9,
    'customer_name' => 'Aarav Mehta',
    'customer_email' => 'aarav@example.com',
    'rating' => 5,
    'title' => 'Exceptional Quality Bookmarks',
    'review_text' => 'The 350 GSM cardstock with matte lamination feels ultra premium. Fast delivery to Mumbai.',
    'is_verified_purchase' => 1,
    'status' => 'approved'
]);

echo "Inserted test review ID: {$revId}\n";
$row = db_fetch_one("SELECT * FROM product_reviews WHERE id = :id", ['id' => $revId]);
print_r($row);
