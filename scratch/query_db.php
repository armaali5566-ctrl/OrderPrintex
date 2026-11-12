<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$mode = $argv[1] ?? '';
$param = $argv[2] ?? '';

if ($mode === 'insert_file') {
    $data = json_decode(file_get_contents('php://stdin'), true);
    $id = db_insert('enquiry_files', $data);
    echo json_encode(['id' => $id]);
} elseif ($mode === 'stdin_one') {
    $sql = file_get_contents('php://stdin');
    echo json_encode(db_fetch_one($sql));
} elseif ($mode === 'stdin_all') {
    $sql = file_get_contents('php://stdin');
    echo json_encode(db_fetch_all($sql));
} elseif ($mode === 'last_enquiry_by_email') {
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
} else {
    $stdin = file_get_contents('php://stdin');
    if ($stdin) {
        echo json_encode(db_fetch_one($stdin));
    }
}
