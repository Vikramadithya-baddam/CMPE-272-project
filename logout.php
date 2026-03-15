<?php
// ============================================================
// PARADOX SYSTEMS — logout.php
// Destroys the admin session and redirects to login page.
// ============================================================
session_start();
session_unset();    // Clear all session variables
session_destroy();  // Destroy the session on the server

// Expire the session cookie in the browser too
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect back to login with a goodbye flag
header('Location: login.php?loggedout=1');
exit;
