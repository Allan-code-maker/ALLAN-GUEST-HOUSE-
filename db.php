<?php
// ============================================================
// db.php — Returns a shared MySQLi connection
// ============================================================
require_once __DIR__ . '/config.php';

function get_db(): mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            // In production, log this error instead of displaying it
            error_log('DB connection failed: ' . $conn->connect_error);
            die(json_encode(['error' => 'Database connection failed. Please try again later.']));
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}
