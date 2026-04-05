<?php

// Path to SQLite database file
$dbPath = __DIR__ . '/map_routes.db';
echo "Attempting to create DB in $dbPath. \n";

try {
    // Create / open the SQLite database
    $db = new SQLite3($dbPath);

    // VERY IMPORTANT for SQLite foreign keys
    $db->exec('PRAGMA foreign_keys = ON');

    /*
     * PROJECTS TABLE
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS projects (
            project_id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_name TEXT NOT NULL,
            project_desc TEXT,
            project_icon TEXT,
            project_color TEXT,
            created_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
            modified_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_accessed_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
            user_id_FK INTEGER DEFAULT '1'
        );
    ");

    /*
     * GROUPS Table (Basically what each button does)
    */
    $db->exec("
        CREATE TABLE IF NOT EXISTS groups (
            group_id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_id_FK INTEGER NOT NULL,

            group_name TEXT,
            group_button_text TEXT,
            group_autoexec TEXT,
            group_order INTEGER,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (project_id_FK)
                REFERENCES projects(project_id)
                ON DELETE CASCADE
        );
    ");

    /*
     * COORDINATES Table (Basically what each button does)
    */
    $db->exec("
        CREATE TABLE IF NOT EXISTS coordinates (
            coord_id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_id_FK INTEGER NOT NULL,

            lat REAL NOT NULL,
            lng REAL NOT NULL,

            coord_name TEXT,
            coord_icon TEXT,
            coord_zoom INTEGER,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_accessed_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
            modified_dt DATETIME DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (project_id_FK)
                REFERENCES projects(project_id)
                ON DELETE CASCADE
        );
    ");    
    
    /*
     * GROUPS Table (Basically what each button does)
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS groups (
            group_id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_id_FK INTEGER NOT NULL,

            lat REAL NOT NULL,
            lng REAL NOT NULL,

            coordinate_type TEXT,
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
