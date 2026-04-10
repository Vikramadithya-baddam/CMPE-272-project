<?php
// ============================================================
// PARADOX SYSTEMS — db_config.php
// PostgreSQL connection via PDO.
// Reads credentials from Render Environment Variables — never
// hardcode credentials in source code.
//
// 
//  Render Dashboard → your PHP Web Service → Environment
//  Add these key/value pairs (copy from your DB's "Connections" tab):
//
//   DB_HOST   = dpg-xxxxxxxxxx-a.oregon-postgres.render.com
//   DB_PORT   = 5432
//   DB_NAME   = paradox_db
//   DB_USER   = paradox_db_user
//   DB_PASS   = <your password>
// ============================================================

function get_db(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $host = getenv('DB_HOST') ?: 'dpg-d7c2vfhkh4rs73cdcan0-a';
    $port = getenv('DB_PORT') ?: '5432';
    $name = getenv('DB_NAME') ?: 'pardoxsys_db';
    $user = getenv('DB_USER') ?: 'users_db_meo6_user';
    $pass = getenv('DB_PASS') ?: '';

    $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode=require";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // In production don't expose details — log and show generic error
        error_log('DB connection failed: ' . $e->getMessage());
        http_response_code(500);
        die(json_encode(['error' => 'Database connection failed']));
    }

    return $pdo;
}
