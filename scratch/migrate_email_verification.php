<?php
/**
 * Migration: Add Email Verification Columns to users table
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

echo "Running email verification migration...\n";

// 1. Check and add email_verified_at
$col = db_fetch_one("SHOW COLUMNS FROM users LIKE 'email_verified_at'");
if (!$col) {
    db_query("ALTER TABLE users ADD COLUMN email_verified_at DATETIME NULL DEFAULT NULL AFTER status");
    echo "Added column 'email_verified_at'\n";
} else {
    echo "Column 'email_verified_at' already exists\n";
}

// 2. Check and add email_verification_token
$col = db_fetch_one("SHOW COLUMNS FROM users LIKE 'email_verification_token'");
if (!$col) {
    db_query("ALTER TABLE users ADD COLUMN email_verification_token VARCHAR(255) NULL DEFAULT NULL AFTER email_verified_at");
    echo "Added column 'email_verification_token'\n";
} else {
    echo "Column 'email_verification_token' already exists\n";
}

// 3. Check and add email_verification_expires_at
$col = db_fetch_one("SHOW COLUMNS FROM users LIKE 'email_verification_expires_at'");
if (!$col) {
    db_query("ALTER TABLE users ADD COLUMN email_verification_expires_at DATETIME NULL DEFAULT NULL AFTER email_verification_token");
    echo "Added column 'email_verification_expires_at'\n";
} else {
    echo "Column 'email_verification_expires_at' already exists\n";
}

// 4. Mark existing admin user ID 1 (and customer demo accounts) as already verified
db_query("UPDATE users SET email_verified_at = NOW() WHERE id = 1 AND email_verified_at IS NULL");
echo "Super Admin ID 1 marked verified.\n";

echo "Migration completed successfully.\n";

