<?php
require_once 'auth.php';
requireAuth();

$user = getCurrentUser();
$userName = $user['first_name'] . ' ' . $user['last_name'];
$userEmail = $user['email'];
$userPhone = $user['phone'] ?? 'Не указан';
$userRole = $user['role'];
$createdAt = date('d.m.Y', strtotime($user['created_at']));
$lastLogin = $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'Никогда';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Scentury</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            min-height: 100vh;
            background: #f8f9fa;
            padding: 2rem 0;
        }
        
        .dashboard-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .dashboard-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .dashboard-logo {
            font-size: 1.5rem;
            color: #2c5530;
            font-weight: bold;
        }
        
        .dashboard-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .user-email {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
        
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #4a7b5d 0%, #2c5530 100%);
            color: white;
            padding: 3rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .welcome-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-title {
            color: #2c5530;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }
        
        .user-details {
            list-style: none;
            padding: 0;
        }
        
        .user-details li {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .user-details li:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 500;
            color: #666;
        }
        
        .detail-value {
            color: #333;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .action-btn {
            display: block;
            padding: 1rem;
            background: #4a7b5d;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        
        .action-btn:hover {
            background: #2c5530;
        }
        
        .action-btn.secondary {
            background: #6c757d;
        }
        
        .action-btn.secondary:hover {
            background: #545b62;
        }
        
        .recent-orders {
            margin-top: 2rem;
        }
        
        .order-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #4a7b5d;
        }
        
        .order-title {
            font-weight: 600;
            color: #2c5530;
            margin-bottom: 0.5rem;
        }
        
        .order-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .no-orders {
            text-align: center;
            color: #666;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .back-to-site {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #4a7b5d;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-to-site:hover {
            color: #2c5530;
        }
        
        @media (max-width: 768px) {
            .dashboard-nav {
                flex-direction: column;
                gap: 1rem;
            }
            
            .dashboard-user {
                flex-direction: column;
                text-align: center;
            }
            
            .welcome-title {
                font-size: 2rem;
            }
            
            .dashboard-content {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="container">
                <div class="dashboard-nav">
                    <div class="dashboard-logo">Scentury</div>
                    <div class="dashboard-user">
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($userName); ?></div>
                            <div class="user-email"><?php echo htmlspecialchars($userEmail); ?></div>
                        </div>
                        <a href="auth.php?action=logout" class="logout-btn">Выйти</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-content">
            <a href="index.php" class="back-to-site">← На сайт</a>
            
            <div class="welcome-section">
                <h1 class="welcome-title">Добро пожаловать, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
                <p class="welcome-subtitle">Управляйте своими ароматами и заказами</p>
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3 class="card-title">Информация о профиле</h3>
                    <ul class="user-details">
                        <li>
                            <span class="detail-label">Имя:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($userName); ?></span>
                        </li>
                        <li>
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($userEmail); ?></span>
                        </li>
                        <li>
                            <span class="detail-label">Телефон:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($userPhone); ?></span>
                        </li>
                        <li>
                            <span class="detail-label">Роль:</span>
                            <span class="detail-value"><?php echo ucfirst($userRole); ?></span>
                        </li>
                        <li>
                            <span class="detail-label">Дата регистрации:</span>
                            <span class="detail-value"><?php echo $createdAt; ?></span>
                        </li>
                        <li>
                            <span class="detail-label">Последний вход:</span>
                            <span class="detail-value"><?php echo $lastLogin; ?></span>
                        </li>
                    </ul>
                </div>
                
                <div class="dashboard-card">
                    <h3 class="card-title">Быстрые действия</h3>
                    <div class="quick-actions">
                        <a href="constructor.php" class="action-btn">Создать аромат</a>
                        <a href="catalog.php" class="action-btn">Каталог ароматов</a>
                        <a href="contacts.php" class="action-btn secondary">Связаться с нами</a>
                        <a href="profile-edit.php" class="action-btn secondary">Редактировать профиль</a>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card recent-orders">
                <h3 class="card-title">Последние заказы</h3>
                <div class="no-orders">
                    <p>У вас пока нет заказов</p>
                    <a href="constructor.php" class="action-btn" style="display: inline-block; margin-top: 1rem;">Создать первый аромат</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Автоматическое обновление времени последнего входа
        setInterval(function() {
            // Можно добавить AJAX запрос для обновления времени
        }, 60000); // Каждую минуту
    </script>
</body>
</html>

