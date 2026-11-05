<?php
require 'includes/config.php';
require 'includes/database.php';
$cols = db_fetch_all('SHOW COLUMNS FROM gallery');
echo json_encode(array_column($cols, 'Field'));

