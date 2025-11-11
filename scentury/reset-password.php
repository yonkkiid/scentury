<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сброс пароля - Scentury</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4a7b5d 0%, #2c5530 100%);
            padding: 2rem;
        }
        
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-logo {
            font-size: 2.5rem;
            color: #2c5530;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        
        .auth-subtitle {
            color: #666;
            font-size: 1.1rem;
        }
        
        .auth-form {
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #4a7b5d;
        }
        
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }
        
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
        
        .auth-button {
            width: 100%;
            padding: 1rem;
            background: #4a7b5d;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .auth-button:hover {
            background: #2c5530;
        }
        
        .auth-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .auth-divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
            color: #666;
        }
        
        .auth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
        }
        
        .auth-divider span {
            background: white;
            padding: 0 1rem;
        }
        
        .auth-links {
            text-align: center;
        }
        
        .auth-links a {
            color: #4a7b5d;
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-links a:hover {
            color: #2c5530;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }
        
        .back-to-home {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #4a7b5d;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-to-home:hover {
            color: #2c5530;
        }
        
        .info-text {
            background: #e7f3ff;
            color: #0066cc;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <a href="index.php" class="back-to-home">← На главную</a>
            
            <div class="auth-header">
                <div class="auth-logo">Scentury</div>
                <div class="auth-subtitle">Сброс пароля</div>
            </div>
            
            <?php
            $token = $_GET['token'] ?? '';
            $validToken = false;
            $userEmail = '';
            
            if ($token) {
                // Проверяем токен
                $resetFile = 'password_resets.json';
                if (file_exists($resetFile)) {
                    $content = file_get_contents($resetFile);
                    $resets = json_decode($content, true) ?: [];
                    
                    foreach ($resets as $reset) {
                        if ($reset['token'] === $token && $reset['expires'] > time()) {
                            $validToken = true;
                            $userEmail = $reset['email'];
                            break;
                        }
                    }
                }
            }
            
            if (!$validToken) {
                echo '<div class="error-message">Неверный или истекший токен восстановления</div>';
                echo '<div class="auth-links"><p><a href="forgot-password.php">Запросить новый токен</a></p></div>';
            } else {
                // Обработка ошибок
                if (isset($_GET['error'])) {
                    $error = $_GET['error'];
                    switch($error) {
                        case 'password_mismatch':
                            echo '<div class="error-message">Пароли не совпадают</div>';
                            break;
                        case 'weak_password':
                            echo '<div class="error-message">Пароль слишком слабый. Используйте минимум 8 символов</div>';
                            break;
                        case 'password_reset_failed':
                            echo '<div class="error-message">Ошибка сброса пароля. Попробуйте позже</div>';
                            break;
                    }
                }
                
                if (isset($_GET['success'])) {
                    $success = $_GET['success'];
                    switch($success) {
                        case 'password_reset':
                            echo '<div class="success-message">Пароль успешно изменен! Теперь вы можете войти в систему</div>';
                            echo '<div class="auth-links"><p><a href="login.php">Войти в систему</a></p></div>';
                        break;
                    }
                }
                
                if (!isset($_GET['success'])) {
                    echo '<div class="info-text">Введите новый пароль для аккаунта: ' . htmlspecialchars($userEmail) . '</div>';
                    ?>
                    
                    <form class="auth-form" action="password-reset-process.php" method="POST">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        
                        <div class="form-group">
                            <label for="password">Новый пароль</label>
                            <input type="password" id="password" name="password" required>
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Подтвердите пароль</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="auth-button" id="submitButton">Сбросить пароль</button>
                    </form>
                    
                    <div class="auth-divider">
                        <span>или</span>
                    </div>
                    
                    <div class="auth-links">
                        <p><a href="login.php">Вернуться к входу</a></p>
                        <p><a href="index.php">На главную</a></p>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    
    <script>
        // Проверка силы пароля
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.textContent = '';
                return;
            }
            
            let strength = 0;
            let message = '';
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (strength < 3) {
                message = 'Слабый пароль';
                strengthDiv.className = 'password-strength strength-weak';
            } else if (strength < 5) {
                message = 'Средний пароль';
                strengthDiv.className = 'password-strength strength-medium';
            } else {
                message = 'Надежный пароль';
                strengthDiv.className = 'password-strength strength-strong';
            }
            
            strengthDiv.textContent = message;
        });
        
        // Проверка совпадения паролей
        document.getElementById('confirm_password')?.addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const submitButton = document.getElementById('submitButton');
            
            if (confirmPassword && password !== confirmPassword) {
                this.style.borderColor = '#dc3545';
                submitButton.disabled = true;
            } else {
                this.style.borderColor = '#e9ecef';
                submitButton.disabled = false;
            }
        });
        
        // Валидация формы
        document.querySelector('.auth-form')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Пароли не совпадают');
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Пароль должен содержать минимум 8 символов');
                return;
            }
        });
        
        // Автофокус на поле пароля
        document.getElementById('password')?.focus();
    </script>
</body>
</html>

