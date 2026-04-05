<?php

class DB
{
    private static ?SQLite3 $conn = null;

    public function __construct()
    {
        if (self::$conn === null) {
            $dbPath = __DIR__ . '/../map_routes.db';

            if (!file_exists($dbPath)) {
                throw new RuntimeException("Database not found at: $dbPath");
            }

            self::$conn = new SQLite3($dbPath);

            // Required for foreign keys
            self::$conn->exec('PRAGMA foreign_keys = ON');

            // Optional performance tweaks
            self::$conn->exec('PRAGMA journal_mode = WAL');
            self::$conn->exec('PRAGMA synchronous = NORMAL');

            self::$conn->busyTimeout(5000);
        }
    }

    /*
     * RUN (INSERT / UPDATE / DELETE)
     */
    public function run(string $sql, array $params = []): bool
    {
        $stmt = self::$conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(
                is_int($key) ? $key + 1 : ':' . $key,
                $value,
                $this->getType($value)
            );
        }

        $result = $stmt->execute();

        return $result !== false;
    }

    /*
     * GET ALL ROWS
     */
    public function get(string $sql, array $params = []): array
    {
        $stmt = self::$conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(
                is_int($key) ? $key + 1 : ':' . $key,
                $value,
                $this->getType($value)
            );
        }

        $result = $stmt->execute();

        $rows = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    /*
     * GET ONE ROW
     */
    public function getOne(string $sql, array $params = []): ?array
    {
        $rows = $this->get($sql, $params);
        return $rows[0] ?? null;
    }

    /*
     * LAST INSERT ID
     */
    public function lastId(): int
    {
        return self::$conn->lastInsertRowID();
    }

    /*
     * TYPE HELPER
     */
    private function getType($value): int
    {
        return match (true) {
            is_int($value)   => SQLITE3_INTEGER,
            is_float($value) => SQLITE3_FLOAT,
            is_null($value)  => SQLITE3_NULL,
            default          => SQLITE3_TEXT,
        };
    }
}