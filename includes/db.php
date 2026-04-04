<?php

function getDB(): SQLite3
{
    static $db = null;

    if ($db === null) {
        $dbPath = __DIR__ . '/../map_routes.db';

        if (!file_exists($dbPath)) {
            throw new RuntimeException("Database file not found at: $dbPath");
        }

        $db = new SQLite3($dbPath);
        $db->exec('PRAGMA foreign_keys = ON');
        $db->exec('PRAGMA journal_mode = WAL');
        $db->exec('PRAGMA synchronous = NORMAL');
        $db->busyTimeout(5000);
    }

    return $db;
}