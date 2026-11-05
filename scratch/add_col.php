<?php
require 'includes/config.php';
require 'includes/database.php';
$cols = array_column(db_fetch_all('SHOW COLUMNS FROM gallery'), 'Field');
if (!in_array('is_published', $cols)) {
    db_query('ALTER TABLE gallery ADD COLUMN is_published TINYINT(1) DEFAULT 1 AFTER is_featured');
    echo "Added is_published column\n";
} else {
    echo "is_published already exists\n";
}
