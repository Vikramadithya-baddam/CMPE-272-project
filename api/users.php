<?php
// ============================================================
// PARADOX SYSTEMS — api/users.php
// PUBLIC JSON API — returns users from PostgreSQL.
// Other companies fetch this via CURL to get Paradox's users.
//
// URL:  https://yourdomain.com/api/users.php
// Returns: JSON array of user objects
//
// Optional query params:
//   ?status=Active      → filter by status
//   ?role=Premium+Member → filter by role
// ============================================================

require_once __DIR__ . '/../db_config.php';

// Allow other origins to CURL this endpoint (CORS)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Cache-Control: no-cache');

try {
    $pdo = get_db();

    $where  = [];
    $params = [];

    // Optional filters
    if (!empty($_GET['status'])) {
        $where[]          = 'status = :status';
        $params[':status'] = $_GET['status'];
    }
    if (!empty($_GET['role'])) {
        $where[]        = 'role = :role';
        $params[':role'] = $_GET['role'];
    }

    $sql = 'SELECT id, name, email, role, joined::text AS joined, status
            FROM users';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY id ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll();

    echo json_encode([
        'company'    => 'Paradox Systems',
        'company_id' => 'paradox',
        'total'      => count($users),
        'users'      => $users,
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch users']);
}
