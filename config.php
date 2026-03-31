<?php
// ============================================================
// config.php — Centralized application configuration
// ============================================================

// Database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'guesthouse');

// Application settings
define('APP_NAME', 'Allan Guest House');
define('APP_EMAIL', 'emoruallan@gmail.com');
define('BASE_URL', '');   // Set to your domain in production, e.g. 'https://allanguesthouse.com'

// Session lifetime (seconds)
define('SESSION_LIFETIME', 3600);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'secure'   => false,   // Set to true when using HTTPS in production
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}
