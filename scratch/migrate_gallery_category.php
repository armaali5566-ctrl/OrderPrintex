<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

try {
    echo "Active driver: " . Database::getActiveDriver() . "\n";
    if (Database::getActiveDriver() === 'mysql') {
        echo "Altering gallery.category to VARCHAR(100)...\n";
        db_query("ALTER TABLE gallery MODIFY category VARCHAR(100) NOT NULL DEFAULT 'dtf'");
        echo "SUCCESS: gallery.category is now VARCHAR(100)!\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

$cols = db_fetch_all("SHOW COLUMNS FROM gallery");
foreach ($cols as $col) {
    if ($col['Field'] === 'category') {
        print_r($col);
    }
}
