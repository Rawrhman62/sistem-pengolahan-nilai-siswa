<?php

/**
 * MySQL to SQLite Data Migration Script
 * This script exports all data from MySQL and imports it into SQLite
 */

// MySQL connection
$mysqlHost = '127.0.0.1';
$mysqlPort = '3306';
$mysqlDb = 'db_sinilai';
$mysqlUser = 'root';
$mysqlPass = '';

// SQLite connection
$sqlitePath = __DIR__ . '/database.sqlite';

echo "=== MySQL to SQLite Migration ===\n\n";

try {
    // Connect to MySQL
    echo "Connecting to MySQL...\n";
    $mysql = new PDO(
        "mysql:host={$mysqlHost};port={$mysqlPort};dbname={$mysqlDb};charset=utf8mb4",
        $mysqlUser,
        $mysqlPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✓ MySQL connected\n\n";

    // Connect to SQLite
    echo "Connecting to SQLite...\n";
    $sqlite = new PDO("sqlite:{$sqlitePath}");
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ SQLite connected\n\n";

    // Tables to migrate (in order to respect foreign keys)
    $tables = [
        'users',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'kelas',
        'mapels',
        'gurus',
        'siswas',
        'nilai'
    ];

    $totalRecords = 0;

    foreach ($tables as $table) {
        echo "Migrating table: {$table}\n";
        
        // Check if table exists in MySQL
        $checkTable = $mysql->query("SHOW TABLES LIKE '{$table}'");
        if ($checkTable->rowCount() === 0) {
            echo "  ⚠ Table doesn't exist in MySQL, skipping\n\n";
            continue;
        }

        // Get all data from MySQL
        $stmt = $mysql->query("SELECT * FROM {$table}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            echo "  ℹ No data to migrate\n\n";
            continue;
        }

        $count = count($rows);
        echo "  Found {$count} records\n";

        // Get column names
        $columns = array_keys($rows[0]);
        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        $columnList = implode(',', $columns);

        // Prepare insert statement for SQLite
        $insertSql = "INSERT INTO {$table} ({$columnList}) VALUES ({$placeholders})";
        $insertStmt = $sqlite->prepare($insertSql);

        // Insert each row
        $inserted = 0;
        foreach ($rows as $row) {
            try {
                $insertStmt->execute(array_values($row));
                $inserted++;
            } catch (PDOException $e) {
                echo "  ⚠ Error inserting row: " . $e->getMessage() . "\n";
            }
        }

        echo "  ✓ Inserted {$inserted} records\n\n";
        $totalRecords += $inserted;
    }

    echo "=== Migration Complete ===\n";
    echo "Total records migrated: {$totalRecords}\n";

} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
