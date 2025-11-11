<?php
// Обработка сброса пароля

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
        return null;
    }
}

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

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function isValidPassword($password) {
    return strlen($password) >= 8;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($token) || empty($password) || empty($confirmPassword)) {
        header('Location: reset-password.php?token=' . urlencode($token) . '&error=empty_fields');
        exit;
    }
    
    if (!isValidPassword($password)) {
        header('Location: reset-password.php?token=' . urlencode($token) . '&error=weak_password');
        exit;
    }
    
    if ($password !== $confirmPassword) {
        header('Location: reset-password.php?token=' . urlencode($token) . '&error=password_mismatch');
        exit;
    }
    
    // Проверяем токен
    $resetFile = 'password_resets.json';
    $userEmail = '';
    $validToken = false;
    
    if (file_exists($resetFile)) {
        $content = file_get_contents($resetFile);
        $resets = json_decode($content, true) ?: [];
        
        foreach ($resets as $index => $reset) {
            if ($reset['token'] === $token && $reset['expires'] > time()) {
                $validToken = true;
                $userEmail = $reset['email'];
                
                // Удаляем использованный токен
                unset($resets[$index]);
                file_put_contents($resetFile, json_encode(array_values($resets), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                break;
            }
        }
    }
    
    if (!$validToken) {
        header('Location: reset-password.php?token=' . urlencode($token) . '&error=invalid_token');
        exit;
    }
    
    // Обновляем пароль
    $pdo = getDBConnection();
    $success = false;
    
    if ($pdo) {
        // Используем базу данных
        try {
            $hashedPassword = hashPassword($password);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?");
            $success = $stmt->execute([$hashedPassword, $userEmail]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
        }
    } else {
        // Используем файловое хранение
        $users = getUsersFromFile();
        foreach ($users as $index => $user) {
            if ($user['email'] === $userEmail) {
                $users[$index]['password'] = hashPassword($password);
                $users[$index]['updated_at'] = date('Y-m-d H:i:s');
                $success = saveUsersToFile($users);
                break;
            }
        }
    }
    
    if ($success) {
        header('Location: reset-password.php?token=' . urlencode($token) . '&success=password_reset');
        exit;
    } else {
        header('Location: reset-password.php?token=' . urlencode($token) . '&error=password_reset_failed');
        exit;
    }
} else {
    header('Location: forgot-password.php');
    exit;
}
?>

