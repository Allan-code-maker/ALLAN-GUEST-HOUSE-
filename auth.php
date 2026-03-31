<?php
// ============================================================
// auth.php — Authentication helpers
// ============================================================
require_once __DIR__ . '/config.php';

/**
 * Redirect to login page if the user is not authenticated.
 * Accepts an optional required role: 'admin' or 'user'.
 */
function require_login(string $role = ''): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: admin.php');
        exit;
    }
    if ($role && ($_SESSION['role'] ?? '') !== $role) {
        header('Location: admin.php');
        exit;
    }
}

/**
 * Returns true if a user is currently logged in.
 */
function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

/**
 * Returns the logged-in user's role ('admin' or 'user'), or empty string.
 */
function current_role(): string {
    return $_SESSION['role'] ?? '';
}

/**
 * Returns the logged-in user's username, or empty string.
 */
function current_username(): string {
    return $_SESSION['username'] ?? '';
}

/**
 * Destroy the current session and redirect to login.
 */
function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
    header('Location: admin.php');
    exit;
}
