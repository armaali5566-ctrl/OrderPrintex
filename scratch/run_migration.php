<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$p1 = db_fetch_one("SELECT id, title, sku FROM products WHERE id = 1");
if ($p1) {
    echo "Product 1: " . $p1['title'] . ", current SKU: [" . ($p1['sku'] ?? 'NULL') . "]\n";
    if (empty($p1['sku'])) {
        db_update('products', ['sku' => 'DTF-001'], 'id = 1');
        echo "Updated Product 1 SKU to DTF-001 in database.\n";
    }
}

db_query("UPDATE blog_posts SET published_at = created_at WHERE published_at IS NULL AND status = 'published'");
echo "Done!\n";
