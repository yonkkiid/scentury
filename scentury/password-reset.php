<?php
// Обработка восстановления пароля

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

function generateResetToken() {
    return bin2hex(random_bytes(32));
}

function sendPasswordResetEmail($email, $token, $userName) {
    // В реальном проекте здесь должна быть отправка email
    // Для демонстрации просто сохраняем токен в файл
    
    $resetData = [
        'email' => $email,
        'token' => $token,
        'expires' => time() + (60 * 60), // 1 час
        'created' => time()
    ];
    
    $resetFile = 'password_resets.json';
    $resets = [];
    
    if (file_exists($resetFile)) {
        $content = file_get_contents($resetFile);
        $resets = json_decode($content, true) ?: [];
    }
    
    // Удаляем старые токены для этого email
    $resets = array_filter($resets, function($reset) use ($email) {
        return $reset['email'] !== $email;
    });
    
    $resets[] = $resetData;
    file_put_contents($resetFile, json_encode($resets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    // В реальном проекте здесь отправляем email:
    /*
    $subject = 'Восстановление пароля - Scentury';
    $message = "
Здравствуйте, $userName!

Для восстановления пароля перейдите по ссылке:
http://localhost/scentury/reset-password.php?token=$token

Ссылка действительна в течение 1 часа.

Если вы не запрашивали восстановление пароля, проигнорируйте это письмо.

С уважением,
Команда Scentury
    ";
    
    $headers = 'From: noreply@scentury.ru' . "\r\n" .
               'Content-Type: text/plain; charset=UTF-8' . "\r\n";
    
    mail($email, $subject, $message, $headers);
    */
    
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        header('Location: forgot-password.php?error=empty_email');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: forgot-password.php?error=invalid_email');
        exit;
    }
    
    $pdo = getDBConnection();
    $user = null;
    
    if ($pdo) {
        // Используем базу данных
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            header('Location: forgot-password.php?error=database_error');
            exit;
        }
    } else {
        // Используем файловое хранение
        $users = getUsersFromFile();
        foreach ($users as $u) {
            if ($u['email'] === $email && $u['status'] === 'active') {
                $user = $u;
                break;
            }
        }
    }
    
    if (!$user) {
        header('Location: forgot-password.php?error=email_not_found');
        exit;
    }
    
    // Генерируем токен восстановления
    $token = generateResetToken();
    $userName = $user['first_name'] . ' ' . $user['last_name'];
    
    // Отправляем email
    if (sendPasswordResetEmail($email, $token, $userName)) {
        header('Location: forgot-password.php?success=email_sent&email=' . urlencode($email));
        exit;
    } else {
        header('Location: forgot-password.php?error=email_send_failed');
        exit;
    }
} else {
    header('Location: forgot-password.php');
    exit;
}
?>

