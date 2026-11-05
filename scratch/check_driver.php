<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$pdo = Database::getInstance();
echo "Active driver: " . Database::getActiveDriver() . "\n";
if (Database::getConnectionError()) {
    echo "Connection error: " . Database::getConnectionError() . "\n";
}

