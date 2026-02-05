<?php

// Path to SQLite database file
$dbPath = __DIR__ . '/db.sqlite';

try {
    // Create / open the SQLite database
    $db = new SQLite3($dbPath);

    // VERY IMPORTANT for SQLite foreign keys
    $db->exec('PRAGMA foreign_keys = ON');

    /*
     * ROUTES TABLE
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS routes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            category TEXT,
            color TEXT,
            type TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    /*
     * COORDINATES TABLE
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS coordinates (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            route_id INTEGER NOT NULL,

            latitude REAL NOT NULL,
            longitude REAL NOT NULL,

            coordinate_type TEXT,
            coordinate_order INTEGER,
            icon TEXT,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (route_id)
                REFERENCES routes(id)
                ON DELETE CASCADE
        );
    ");

    echo "✅ Database and tables created successfully.\n";

} catch (Throwable $e) {
    echo "❌ Setup failed: " . $e->getMessage();
}
