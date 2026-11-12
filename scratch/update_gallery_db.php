<?php
require_once __DIR__ . '/../includes/database.php';

db_query("UPDATE gallery SET category = 'stickers' WHERE id = 5");
db_query("UPDATE gallery SET category = 'branding', display_order = 7 WHERE id = 8");
db_query("UPDATE gallery SET display_order = 8 WHERE id = 7");

echo "Gallery updated in DB\n";
$rows = db_fetch_all('SELECT id, title, category, display_order FROM gallery ORDER BY display_order ASC, id ASC');
print_r($rows);
