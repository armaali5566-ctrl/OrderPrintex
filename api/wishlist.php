<?php
/**
 * OrderPrintex - Wishlist API
 * Handles guest and account-based wishlist operations.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Ensure table exists
try {
    db_query("
        CREATE TABLE IF NOT EXISTS user_wishlists (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(user_id, product_id)
        )
    ");
} catch (Exception $e) {}

$user = auth_user();
$action = $_POST['action'] ?? ($_GET['action'] ?? 'get');
$productId = (int)($_POST['product_id'] ?? ($_GET['product_id'] ?? 0));

if ($action === 'toggle' && $productId > 0) {
    if ($user) {
        $existing = db_fetch_one("SELECT id FROM user_wishlists WHERE user_id = :uid AND product_id = :pid", [
            'uid' => $user['id'],
            'pid' => $productId
        ]);

        if ($existing) {
            db_query("DELETE FROM user_wishlists WHERE user_id = :uid AND product_id = :pid", [
                'uid' => $user['id'],
                'pid' => $productId
            ]);
            echo json_encode(['success' => true, 'wishlisted' => false, 'message' => 'Removed from wishlist.']);
        } else {
            db_query("INSERT INTO user_wishlists (user_id, product_id) VALUES (:uid, :pid)", [
                'uid' => $user['id'],
                'pid' => $productId
            ]);
            echo json_encode(['success' => true, 'wishlisted' => true, 'message' => 'Added to wishlist.']);
        }
    } else {
        // Guest mode confirmation
        echo json_encode(['success' => true, 'guest' => true, 'message' => 'Synced with local wishlist.']);
    }
    exit;
}

if ($action === 'get') {
    $productIds = [];
    if ($user) {
        $rows = db_fetch_all("SELECT product_id FROM user_wishlists WHERE user_id = :uid ORDER BY created_at DESC", [
            'uid' => $user['id']
        ]);
        $productIds = array_column($rows, 'product_id');
    }

    echo json_encode([
        'success'    => true,
        'logged_in'  => (bool)$user,
        'product_ids'=> $productIds
    ]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid action.']);

