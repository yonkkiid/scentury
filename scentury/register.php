<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Scentury</title>
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
            max-width: 450px;
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
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
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
        
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }
        
        .strength-weak { color: #ff6b6b; }
        .strength-medium { color: #ffa726; }
        .strength-strong { color: #66bb6a; }
        
        .form-options {
            margin-bottom: 1.5rem;
        }
        
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .checkbox-group input {
            margin-top: 0.25rem;
        }
        
        .checkbox-group label {
            font-size: 0.9rem;
            line-height: 1.4;
            color: #b0b0b0;
        }
        
        .checkbox-group a {
            color: #d4a574;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .checkbox-group a:hover {
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
        
        .auth-button:disabled {
            background: linear-gradient(135deg, #666 0%, #555 100%);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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
                <div class="auth-subtitle">Создать аккаунт</div>
            </div>
            
            <?php
            // Обработка ошибок
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                switch($error) {
                    case 'email_exists':
                        echo '<div class="error-message">Пользователь с таким email уже существует</div>';
                        break;
                    case 'password_mismatch':
                        echo '<div class="error-message">Пароли не совпадают</div>';
                        break;
                    case 'weak_password':
                        echo '<div class="error-message">Пароль слишком слабый. Используйте минимум 8 символов</div>';
                        break;
                    case 'invalid_email':
                        echo '<div class="error-message">Неверный формат email</div>';
                        break;
                    case 'terms_not_accepted':
                        echo '<div class="error-message">Необходимо принять условия использования</div>';
                        break;
                }
            }
            ?>
            
            <form class="auth-form" action="auth.php" method="POST" id="registerForm">
                <input type="hidden" name="action" value="register">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">Имя *</label>
                        <input type="text" id="first_name" name="first_name" required
                               value="<?php echo isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Фамилия *</label>
                        <input type="text" id="last_name" name="last_name" required
                               value="<?php echo isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required
                           value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="tel" id="phone" name="phone" placeholder="+7 (999) 123-45-67"
                           value="<?php echo isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль *</label>
                    <input type="password" id="password" name="password" required>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Подтвердите пароль *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            Я принимаю <a href="terms.php" target="_blank">условия использования</a> 
                            и <a href="privacy.php" target="_blank">политику конфиденциальности</a>
                        </label>
                    </div>
                </div>
                
                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="newsletter" name="newsletter">
                        <label for="newsletter">
                            Подписаться на новости и специальные предложения
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="auth-button" id="submitButton">Зарегистрироваться</button>
            </form>
            
            <div class="auth-divider">
                <span>или</span>
            </div>
            
            <div class="auth-links">
                <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
                <p><a href="index.php">Вернуться на главную</a></p>
            </div>
        </div>
    </div>
    
    <script>
        // Проверка силы пароля
        document.getElementById('password').addEventListener('input', function() {
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
        document.getElementById('confirm_password').addEventListener('input', function() {
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
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            
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
            
            if (!terms) {
                e.preventDefault();
                alert('Необходимо принять условия использования');
                return;
            }
        });
        
        // Автофокус на первое поле
        document.getElementById('first_name').focus();
    </script>
</body>
</html>

