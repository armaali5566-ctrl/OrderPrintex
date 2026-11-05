<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$mode = $argv[1] ?? '';
$param = $argv[2] ?? '';

if ($mode === 'last_enquiry_by_email') {
    $row = db_fetch_one("SELECT * FROM enquiries WHERE customer_email = :email ORDER BY id DESC LIMIT 1", ['email' => $param]);
    echo json_encode($row);
} elseif ($mode === 'user_by_email') {
    $row = db_fetch_one("SELECT * FROM users WHERE email = :email LIMIT 1", ['email' => $param]);
    echo json_encode($row);
} elseif ($mode === 'gallery_by_title') {
    $row = db_fetch_one("SELECT * FROM gallery WHERE title = :title LIMIT 1", ['title' => $param]);
    echo json_encode($row);
} elseif ($mode === 'blog_category') {
    $row = db_fetch_one("SELECT * FROM blog_categories WHERE name = :name LIMIT 1", ['name' => $param]);
    echo json_encode($row);
} elseif ($mode === 'product_by_title') {
    $row = db_fetch_one("SELECT * FROM products WHERE title = :title LIMIT 1", ['title' => $param]);
    echo json_encode($row);
}

