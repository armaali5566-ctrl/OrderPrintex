<?php
require_once __DIR__ . '/../includes/database.php';

$pdo = Database::getInstance();
if (!$pdo) {
    die("Failed to connect to database: " . Database::getConnectionError() . "\n");
}

try {
    echo "--- DESCRIBE services ---\n";
    $stmt = $pdo->query("DESCRIBE services");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo "{$col['Field']} - {$col['Type']} - Null: {$col['Null']} - Key: {$col['Key']} - Default: {$col['Default']}\n";
    }

    echo "\n--- CURRENT services ---\n";
    $stmt = $pdo->query("SELECT id, title, slug, is_active FROM services ORDER BY id ASC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo "ID: {$row['id']} | Title: {$row['title']} | Slug: {$row['slug']} | Active: {$row['is_active']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
