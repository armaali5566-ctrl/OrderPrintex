<?php
/**
 * OrderPrintex - Database Connection & Helper Functions
 * PDO Prepared Statements with full SQL injection prevention.
 */

require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $instance = null;
    private static ?string $connectionError = null;
    private static string $activeDriver = 'sqlite';

    public static function getInstance(): ?PDO {
        if (self::$instance === null) {
            $driver = defined('DB_DRIVER') ? strtolower(DB_DRIVER) : 'sqlite';

            // Attempt MySQL if explicitly selected
            if ($driver === 'mysql') {
                try {
                    $dsn = sprintf(
                        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                        DB_HOST,
                        DB_PORT,
                        DB_NAME,
                        DB_CHARSET
                    );
                    $options = [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                    ];
                    self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
                    self::$activeDriver = 'mysql';
                    return self::$instance;
                } catch (PDOException $e) {
                    self::$connectionError = $e->getMessage();
                    // Fall through to SQLite if available
                }
            }

            // SQLite (Local zero-config or fallback)
            try {
                $sqlitePath = defined('DB_SQLITE_PATH') ? DB_SQLITE_PATH : (__DIR__ . '/../database/orderprintex.sqlite');
                if (!file_exists($sqlitePath)) {
                    // Auto-migrate if needed
                    $migrator = __DIR__ . '/../database/migrate.php';
                    if (file_exists($migrator)) {
                        require_once $migrator;
                    }
                }

                $pdo = new PDO('sqlite:' . $sqlitePath, null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
                $pdo->exec("PRAGMA foreign_keys = ON;");
                self::$instance = $pdo;
                self::$activeDriver = 'sqlite';
                self::$connectionError = null;
            } catch (PDOException $e) {
                self::$connectionError = $e->getMessage();
                return null;
            }
        }
        return self::$instance;
    }

    public static function getActiveDriver(): string {
        return self::$activeDriver;
    }

    public static function getError(): ?string {
        return self::$connectionError;
    }

    public static function isConnected(): bool {
        return self::getInstance() !== null;
    }
}

/**
 * Execute a prepared SQL statement with automatic SQLite/MySQL dialect compatibility
 */
function db_query(string $sql, array $params = []): ?PDOStatement {
    $db = Database::getInstance();
    if (!$db) {
        return null;
    }

    if (Database::getActiveDriver() === 'sqlite') {
        // Normalize common MySQL syntax to SQLite
        if (stripos($sql, 'SHOW TABLES') !== false) {
            $sql = "SELECT name as Tables_in_orderprintex, name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'";
        } elseif (preg_match('/^DESCRIBE\s+`?([a-zA-Z0-9_]+)`?/i', trim($sql), $m)) {
            $sql = "PRAGMA table_info(" . $m[1] . ")";
        }
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetch a single row
 */
function db_fetch_one(string $sql, array $params = []): ?array {
    $stmt = db_query($sql, $params);
    if (!$stmt) {
        return null;
    }
    $result = $stmt->fetch();
    return $result ?: null;
}

/**
 * Fetch all matching rows
 */
function db_fetch_all(string $sql, array $params = []): array {
    $stmt = db_query($sql, $params);
    if (!$stmt) {
        return [];
    }
    return $stmt->fetchAll();
}

/**
 * Execute an INSERT and return lastInsertId
 */
function db_insert(string $table, array $data): int {
    $db = Database::getInstance();
    if (!$db) {
        return 0;
    }
    $columns = array_keys($data);
    $placeholders = array_map(fn($col) => ':' . $col, $columns);

    $sql = sprintf(
        'INSERT INTO `%s` (`%s`) VALUES (%s)',
        $table,
        implode('`, `', $columns),
        implode(', ', $placeholders)
    );

    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    return (int) $db->lastInsertId();
}

/**
 * Execute an UPDATE statement
 */
function db_update(string $table, array $data, string $whereClause, array $whereParams = []): int {
    $db = Database::getInstance();
    if (!$db) {
        return 0;
    }
    $setParts = [];
    $params = [];
    foreach ($data as $col => $val) {
        $setParts[] = sprintf('`%s` = :set_%s', $col, $col);
        $params['set_' . $col] = $val;
    }

    $sql = sprintf(
        'UPDATE `%s` SET %s WHERE %s',
        $table,
        implode(', ', $setParts),
        $whereClause
    );

    $mergedParams = array_merge($params, $whereParams);
    $stmt = $db->prepare($sql);
    $stmt->execute($mergedParams);
    return $stmt->rowCount();
}

/**
 * Execute a DELETE statement
 */
function db_delete(string $table, string $whereClause, array $whereParams = []): int {
    $db = Database::getInstance();
    if (!$db) {
        return 0;
    }
    $sql = sprintf('DELETE FROM `%s` WHERE %s', $table, $whereClause);
    $stmt = $db->prepare($sql);
    $stmt->execute($whereParams);
    return $stmt->rowCount();
}
