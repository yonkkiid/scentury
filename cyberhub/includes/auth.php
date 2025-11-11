<?php
require_once __DIR__ . '/db.php';

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_auth(): void {
    if (!current_user()) {
        redirect('/cyberhub/auth/login.php');
    }
}

function require_admin(): void {
    $u = current_user();
    if (!$u || ($u['role'] ?? 'user') !== 'admin') {
        redirect('/cyberhub/index.php');
    }
}

function register_user(string $email, string $name, string $password): array {
    $pdo = db();
    $existing = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $existing->execute([$email]);
    if ($existing->fetch()) {
        return ['ok' => false, 'error' => 'Email уже зарегистрирован'];
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $now = date('Y-m-d H:i:s');
    $ins = $pdo->prepare('INSERT INTO users (email, password_hash, name, role, created_at) VALUES (?, ?, ?, ?, ?)');
    $ins->execute([$email, $hash, $name, 'user', $now]);
    return ['ok' => true];
}

function login_user(string $email, string $password): array {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return ['ok' => false, 'error' => 'Неверный email или пароль'];
    }
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'email' => $user['email'],
        'name' => $user['name'],
        'role' => $user['role'],
    ];
    return ['ok' => true];
}

function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function verify_csrf(?string $token): bool {
    return is_string($token) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

