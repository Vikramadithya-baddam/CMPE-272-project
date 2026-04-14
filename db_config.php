<?php
// ============================================================
// PARADOX SYSTEMS — db_config.php
// Shared PostgreSQL connection via PDO.
// Place in project root. Include with: require_once 'db_config.php';
//
// Set these on Render → your PHP service → Environment:
//   DB_HOST = your-db-host.render.com
//   DB_PORT = 5432
//   DB_NAME = paradox_db
//   DB_USER = paradox_db_user
//   DB_PASS = your_password
// ============================================================

function get_db(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '5432';
    $name = getenv('DB_NAME') ?: 'paradox_db';
    $user = getenv('DB_USER') ?: 'postgres';
    $pass = getenv('DB_PASS') ?: '';

    $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode=require";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        error_log('DB connection failed: ' . $e->getMessage());
        http_response_code(500);
        die(json_encode(['error' => 'Database connection failed']));
    }

    return $pdo;
}
