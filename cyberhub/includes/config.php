<?php
// Basic configuration and bootstrap

// Start session early
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base paths
define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('ASSETS_URL', '/cyberhub/assets');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'cyberhub');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Timezone
date_default_timezone_set('Europe/Moscow');

// Simple function to build absolute path to project files
function project_path(string $relative): string {
    return BASE_PATH . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative), DIRECTORY_SEPARATOR);
}

// Simple redirect helper
function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}


// Track visits for analytics (lightweight)
if (!defined('CYBERHUB_TRACKED')) {
    define('CYBERHUB_TRACKED', true);
    try {
        // Avoid tracking during CLI or missing server vars
        if (php_sapi_name() !== 'cli' && isset($_SERVER['REQUEST_URI'])) {
            require_once __DIR__ . '/db.php';
            if (function_exists('db')) {
                // Lazy create tracking table handled in migrate()
                $pdo = db();
                $userId = $_SESSION['user']['id'] ?? null;
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 250);
                $path = substr($_SERVER['REQUEST_URI'], 0, 250);
                $stmt = $pdo->prepare('INSERT INTO site_visits (user_id, path, ip, user_agent, visited_at) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$userId, $path, $ip, $ua, date('Y-m-d H:i:s')]);
            }
        }
    } catch (Throwable $e) {
        // swallow analytics errors
    }
}


