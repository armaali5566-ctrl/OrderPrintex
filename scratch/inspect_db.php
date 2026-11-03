<?php
require_once __DIR__ . '/../includes/database.php';
$db = Database::getInstance();
$users = $db->query("SELECT id, name, email, role, status FROM users")->fetchAll(PDO::FETCH_ASSOC);
echo "Users:\n";
print_r($users);
