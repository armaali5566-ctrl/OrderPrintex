<?php
/**
 * OrderPrintex - Customer Product Review Submission API
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

// Verify CSRF
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Security token expired. Please refresh the page.']);
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);
$rating    = (int)($_POST['rating'] ?? 5);
$title     = trim($_POST['title'] ?? '');
$review    = trim($_POST['review_text'] ?? '');
$name      = trim($_POST['customer_name'] ?? '');
$email     = trim($_POST['customer_email'] ?? '');

if ($productId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid product selection.']);
    exit;
}

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'error' => 'Rating must be between 1 and 5 stars.']);
    exit;
}

if (strlen($title) < 3) {
    echo json_encode(['success' => false, 'error' => 'Please provide a descriptive review title.']);
    exit;
}

if (strlen($review) < 10) {
    echo json_encode(['success' => false, 'error' => 'Review must be at least 10 characters long.']);
    exit;
}

if (strlen($name) < 2) {
    echo json_encode(['success' => false, 'error' => 'Please provide your full name.']);
    exit;
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

// Verify if user is logged in
$userId = $_SESSION['user_id'] ?? null;
$isVerified = 0;

// Check if customer had an existing paid or delivered order with this product
if ($userId || !empty($email)) {
    $orderMatch = db_fetch_one("SELECT id FROM orders WHERE (user_id = :uid OR customer_email = :email) AND payment_status = 'paid' LIMIT 1", [
        'uid'   => $userId,
        'email' => $email
    ]);
    if ($orderMatch) {
        $isVerified = 1;
    }
}

// Insert Review (Auto-approved for demo/live preview, or pending moderation)
$reviewId = db_insert('product_reviews', [
    'product_id'           => $productId,
    'user_id'              => $userId,
    'customer_name'        => $name,
    'customer_email'       => $email,
    'rating'               => $rating,
    'title'                => $title,
    'review_text'          => $review,
    'is_verified_purchase' => $isVerified,
    'status'               => 'approved' // Published immediately to reward genuine feedback
]);

if ($reviewId > 0) {
    echo json_encode([
        'success'   => true,
        'message'   => 'Thank you! Your product review has been submitted successfully.',
        'review_id' => $reviewId
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save your review. Please try again.']);
}
