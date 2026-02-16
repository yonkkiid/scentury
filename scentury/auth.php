<?php
ob_start();
session_start();

// Подключение к базе данных
function getDBConnection() {
    $host = 'localhost';
    $dbname = 'scentury';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        // Если база данных недоступна, используем файловое хранение
        return null;
    }
}

// Файловое хранение пользователей (резервный вариант)
function getUsersFromFile() {
    $usersFile = 'users.json';
    if (file_exists($usersFile)) {
        $content = file_get_contents($usersFile);
        return json_decode($content, true) ?: [];
    }
    return [];
}

function saveUsersToFile($users) {
    $usersFile = 'users.json';
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Валидация email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Валидация пароля
function isValidPassword($password) {
    return strlen($password) >= 8;
}

// Хеширование пароля
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Проверка пароля
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Генерация случайного токена
function generateToken() {
    return bin2hex(random_bytes(32));
}

// ============= ИСПРАВЛЕННАЯ СЕКЦИЯ =============
// Обработка POST и GET запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['action'])) {
    // Получаем action из POST или GET
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    
    switch ($action) {
        case 'login':
            handleLogin();
            break;
        case 'register':
            handleRegister();
            break;
        case 'logout':
            handleLogout();
            break;
        default:
            header('Location: login.php?error=invalid_action');
            exit;
    }
}
// ==============================================

// Обработка входа
function handleLogin() {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($email) || empty($password)) {
        header('Location: login.php?error=empty_fields');
        exit;
    }
    
    if (!isValidEmail($email)) {
        header('Location: login.php?error=invalid_email');
        exit;
    }
    
    $pdo = getDBConnection();
    
    if ($pdo) {
        // Используем базу данных
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && verifyPassword($password, $user['password'])) {
                // Успешный вход
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role'];
                
                // Обновляем время последнего входа
                $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                // Запомнить пользователя
                if ($remember) {
                    $token = generateToken();
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 дней
                    
                    $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                    $stmt->execute([$token, $user['id']]);
                }
                
                header('Location: dashboard.php');
                exit;
            } else {
                header('Location: login.php?error=invalid_credentials');
                exit;
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            header('Location: login.php?error=database_error');
            exit;
        }
    } else {
        // Используем файловое хранение
        $users = getUsersFromFile();
        $user = null;
        
        foreach ($users as $u) {
            if ($u['email'] === $email && $u['status'] === 'active') {
                $user = $u;
                break;
            }
        }
        
        if ($user && verifyPassword($password, $user['password'])) {
            // Успешный вход
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            
            // Обновляем время последнего входа
            $user['last_login'] = date('Y-m-d H:i:s');
            $users = array_map(function($u) use ($user) {
                return $u['id'] === $user['id'] ? $user : $u;
            }, $users);
            saveUsersToFile($users);
            
            // Запомнить пользователя
            if ($remember) {
                $token = generateToken();
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
                $user['remember_token'] = $token;
            }
            
            header('Location: dashboard.php');
            exit;
        } else {
            header('Location: login.php?error=invalid_credentials');
            exit;
        }
    }
}

// Обработка регистрации
function handleRegister() {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);
    $newsletter = isset($_POST['newsletter']);
    
    // Валидация
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        header('Location: register.php?error=empty_fields');
        exit;
    }
    
    if (!isValidEmail($email)) {
        header('Location: register.php?error=invalid_email');
        exit;
    }
    
    if (!isValidPassword($password)) {
        header('Location: register.php?error=weak_password');
        exit;
    }
    
    if ($password !== $confirmPassword) {
        header('Location: register.php?error=password_mismatch');
        exit;
    }
    
    if (!$terms) {
        header('Location: register.php?error=terms_not_accepted');
        exit;
    }
    
    $pdo = getDBConnection();
    
    if ($pdo) {
        // Используем базу данных
        try {
            // Проверяем, существует ли пользователь
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                header('Location: register.php?error=email_exists');
                exit;
            }
            
            // Создаем пользователя
            $hashedPassword = hashPassword($password);
            $stmt = $pdo->prepare("
                INSERT INTO users (first_name, last_name, email, phone, password, newsletter, status, role, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, 'active', 'user', NOW())
            ");
            $stmt->execute([$firstName, $lastName, $email, $phone, $hashedPassword, $newsletter ? 1 : 0]);
            
            header('Location: login.php?success=registered&email=' . urlencode($email));
            exit;
            
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            header('Location: register.php?error=database_error');
            exit;
        }
    } else {
        // Используем файловое хранение
        $users = getUsersFromFile();
        
        // Проверяем, существует ли пользователь
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                header('Location: register.php?error=email_exists');
                exit;
            }
        }
        
        // Создаем нового пользователя
        $newUser = [
            'id' => uniqid(),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'password' => hashPassword($password),
            'newsletter' => $newsletter,
            'status' => 'active',
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
            'last_login' => null,
            'remember_token' => null
        ];
        
        $users[] = $newUser;
        saveUsersToFile($users);
        
        header('Location: login.php?success=registered&email=' . urlencode($email));
        exit;
    }
}

// Обработка выхода
function handleLogout() {
    // Начинаем сессию, если она еще не начата
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Очищаем все данные сессии
    $_SESSION = array();
    
    // Удаляем cookie сессии
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Уничтожаем сессию
    session_destroy();
    
    // Удаляем cookie запоминания
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
        unset($_COOKIE['remember_token']);
    }
    
    // Очищаем буфер вывода перед редиректом
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Редирект на страницу входа
    header('Location: login.php?success=logged_out');
    exit;
}

// Проверка авторизации
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Получение текущего пользователя
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $pdo = getDBConnection();
    
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    } else {
        $users = getUsersFromFile();
        foreach ($users as $user) {
            if ($user['id'] === $_SESSION['user_id']) {
                return $user;
            }
        }
    }
    
    return null;
}

// Проверка роли
function hasRole($role) {
    $user = getCurrentUser();
    return $user && $user['role'] === $role;
}

// Перенаправление на страницу входа
function redirectToLogin() {
    header('Location: login.php');
    exit;
}

// Проверка авторизации для защищенных страниц
function requireAuth() {
    if (!isLoggedIn()) {
        redirectToLogin();
    }
}

// Проверка роли для административных страниц
function requireRole($role) {
    requireAuth();
    if (!hasRole($role)) {
        header('Location: dashboard.php?error=access_denied');
        exit;
    }
}

ob_end_flush();
?>