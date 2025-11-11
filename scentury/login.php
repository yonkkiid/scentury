<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему - Scentury</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 50%, #2d2d2d 100%);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.1) 0%, rgba(232, 196, 160, 0.05) 100%);
            pointer-events: none;
        }
        
        .auth-card {
            background: linear-gradient(135deg, #3d3d3d 0%, #2d2d2d 100%);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 3px 10px rgba(212, 165, 116, 0.1);
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            position: relative;
            border: 1px solid #404040;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-logo {
            font-size: 2.5rem;
            color: #d4a574;
            margin-bottom: 0.5rem;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .auth-subtitle {
            color: #e0e0e0;
            font-size: 1.1rem;
            opacity: 0.9;
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
            color: #e0e0e0;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #404040;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #2d2d2d;
            color: #e0e0e0;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #d4a574;
            box-shadow: 0 0 10px rgba(212, 165, 116, 0.3);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 0.5rem;
        }
        
        .remember-me label {
            color: #b0b0b0;
            font-size: 0.9rem;
        }
        
        .forgot-password {
            color: #d4a574;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #e8c4a0;
        }
        
        .auth-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #d4a574 0%, #c4966b 100%);
            color: #1a1a1a;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 165, 116, 0.3);
            border: 1px solid #e8c4a0;
        }
        
        .auth-button:hover {
            background: linear-gradient(135deg, #c4966b 0%, #b8875a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 165, 116, 0.4);
        }
        
        .auth-divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
            color: #b0b0b0;
        }
        
        .auth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #404040;
        }
        
        .auth-divider span {
            background: #3d3d3d;
            padding: 0 1rem;
        }
        
        .auth-links {
            text-align: center;
        }
        
        .auth-links a {
            color: #d4a574;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .auth-links a:hover {
            color: #e8c4a0;
        }
        
        .auth-links p {
            color: #b0b0b0;
            margin-bottom: 0.5rem;
        }
        
        .error-message {
            background: linear-gradient(135deg, #3d2d2d 0%, #2d1a1a 100%);
            color: #ff6b6b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #5a3a3a;
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.2);
        }
        
        .success-message {
            background: linear-gradient(135deg, #2d3d2d 0%, #1a2d1a 100%);
            color: #6bff6b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #3a5a3a;
            box-shadow: 0 2px 8px rgba(107, 255, 107, 0.2);
        }
        
        .back-to-home {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #d4a574;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .back-to-home:hover {
            color: #e8c4a0;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <a href="index.php" class="back-to-home">← На главную</a>
            
            <div class="auth-header">
                <div class="auth-logo">Scentury</div>
                <div class="auth-subtitle">Вход в личный кабинет</div>
            </div>
            
            <?php
            // Обработка сообщений
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                switch($error) {
                    case 'invalid_credentials':
                        echo '<div class="error-message">Неверный email или пароль</div>';
                        break;
                    case 'account_not_found':
                        echo '<div class="error-message">Аккаунт не найден</div>';
                        break;
                    case 'account_disabled':
                        echo '<div class="error-message">Аккаунт заблокирован</div>';
                        break;
                    case 'session_expired':
                        echo '<div class="error-message">Сессия истекла. Войдите снова</div>';
                        break;
                }
            }
            
            if (isset($_GET['success'])) {
                $success = $_GET['success'];
                switch($success) {
                    case 'registered':
                        echo '<div class="success-message">Регистрация успешна! Теперь войдите в систему</div>';
                        break;
                    case 'password_reset':
                        echo '<div class="success-message">Пароль успешно изменен! Войдите с новым паролем</div>';
                        break;
                    case 'logged_out':
                        echo '<div class="success-message">Вы успешно вышли из системы</div>';
                        break;
                }
            }
            ?>
            
            <form class="auth-form" action="auth.php" method="POST">
                <input type="hidden" name="action" value="login">
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Запомнить меня</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">Забыли пароль?</a>
                </div>
                
                <button type="submit" class="auth-button">Войти</button>
            </form>
            
            <div class="auth-divider">
                <span>или</span>
            </div>
            
            <div class="auth-links">
                <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
                <p><a href="index.php">Вернуться на главную</a></p>
            </div>
        </div>
    </div>
    
    <script>
        // Автофокус на поле email
        document.getElementById('email').focus();
        
        // Обработка Enter в форме
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.auth-form').submit();
            }
        });
    </script>
</body>
</html>

