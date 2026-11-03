<?php
require_once __DIR__ . '/../includes/database.php';
$db = Database::getInstance();

foreach (['product_sizes', 'product_pricing_tiers', 'product_options', 'products'] as $t) {
    echo "=== Table $t ===\n";
    $cols = $db->query("PRAGMA table_info($t)")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) {
        echo "  {$c['name']} ({$c['type']})\n";
    }
    $rows = $db->query("SELECT * FROM $t LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    echo "  Sample count: " . count($rows) . "\n";
    if (!empty($rows)) {
        print_r($rows[0]);
    }
}

