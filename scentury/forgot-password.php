<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля - Scentury</title>
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
                <div class="auth-subtitle">Восстановление пароля</div>
            </div>
            
            <?php
            // Обработка сообщений
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                switch($error) {
                    case 'email_not_found':
                        echo '<div class="error-message">Пользователь с таким email не найден</div>';
                        break;
                    case 'invalid_email':
                        echo '<div class="error-message">Неверный формат email</div>';
                        break;
                    case 'email_send_failed':
                        echo '<div class="error-message">Ошибка отправки email. Попробуйте позже</div>';
                        break;
                }
            }
            
            if (isset($_GET['success'])) {
                $success = $_GET['success'];
                switch($success) {
                    case 'email_sent':
                        echo '<div class="success-message">Инструкции по восстановлению пароля отправлены на ваш email</div>';
                        break;
                }
            }
            ?>
            
            <div class="info-text">
                Введите email, указанный при регистрации. Мы отправим вам инструкции по восстановлению пароля.
            </div>
            
            <form class="auth-form" action="password-reset.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Введите ваш email"
                           value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                </div>
                
                <button type="submit" class="auth-button">Отправить инструкции</button>
            </form>
            
            <div class="auth-divider">
                <span>или</span>
            </div>
            
            <div class="auth-links">
                <p><a href="login.php">Вернуться к входу</a></p>
                <p><a href="register.php">Создать новый аккаунт</a></p>
                <p><a href="index.php">На главную</a></p>
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

