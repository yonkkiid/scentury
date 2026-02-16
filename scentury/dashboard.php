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
            background: #1a1a1a;
            padding: 2rem 0;
        }
        
        .dashboard-header {
            background: #2d2d2d;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            margin-bottom: 2rem;
            border-bottom: 1px solid #404040;
        }
        
        .dashboard-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .nav-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .back-to-site {
            color: #d4a574;
            text-decoration: none;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            border: 2px solid #d4a574;
            border-radius: 6px;
            transition: all 0.3s ease;
            background: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .back-to-site:hover {
            background: linear-gradient(135deg, #d4a574 0%, #c4966b 100%);
            color: #1a1a1a;
            box-shadow: 0 4px 15px rgba(212, 165, 116, 0.3);
            transform: translateY(-2px);
        }
        
        .dashboard-logo {
            font-size: 1.5rem;
            color: #d4a574;
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
            color: #e0e0e0;
            margin: 0;
        }
        
        .user-email {
            color: #b0b0b0;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }
        
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #3d3d3d 0%, #2d2d2d 100%);
            color: #e0e0e0;
            padding: 3rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
            border: 1px solid #404040;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
        }
        
        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.1) 0%, rgba(232, 196, 160, 0.05) 100%);
            pointer-events: none;
        }
        
        .welcome-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #d4a574;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            color: #b0b0b0;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            background: linear-gradient(135deg, #3d3d3d 0%, #2d2d2d 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            border: 1px solid #404040;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
        
        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #d4a574 0%, #e8c4a0 50%, #d4a574 100%);
            border-radius: 15px 15px 0 0;
        }
        
        .card-title {
            color: #d4a574;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .user-details {
            list-style: none;
            padding: 0;
        }
        
        .user-details li {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #404040;
        }
        
        .user-details li:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 500;
            color: #b0b0b0;
        }
        
        .detail-value {
            color: #e0e0e0;
            font-weight: 500;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .action-btn {
            display: block;
            padding: 1rem;
            background: linear-gradient(135deg, #d4a574 0%, #c4966b 100%);
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid #e8c4a0;
            box-shadow: 0 4px 15px rgba(212, 165, 116, 0.3);
        }
        
        .action-btn:hover {
            background: linear-gradient(135deg, #c4966b 0%, #b8875a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 165, 116, 0.4);
            color: #1a1a1a;
        }
        
        .action-btn.secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            border: 1px solid #8a8a8a;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        
        .action-btn.secondary:hover {
            background: linear-gradient(135deg, #5a6268 0%, #545b62 100%);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }
        
        .recent-orders {
            margin-top: 2rem;
        }
        
        .order-item {
            background: linear-gradient(135deg, #3d3d3d 0%, #2d2d2d 100%);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #d4a574;
            border: 1px solid #404040;
            transition: all 0.3s ease;
        }
        
        .order-item:hover {
            border-color: #d4a574;
            box-shadow: 0 5px 15px rgba(212, 165, 116, 0.2);
        }
        
        .order-title {
            font-weight: 600;
            color: #d4a574;
            margin-bottom: 0.5rem;
        }
        
        .order-date {
            color: #b0b0b0;
            font-size: 0.9rem;
        }
        
        .no-orders {
            text-align: center;
            color: #b0b0b0;
            padding: 3rem;
            background: linear-gradient(135deg, #3d3d3d 0%, #2d2d2d 100%);
            border-radius: 10px;
            border: 2px solid #404040;
        }
        
        @media (max-width: 768px) {
            .dashboard-nav {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-left {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
                text-align: center;
            }
            
            .back-to-site {
                justify-content: center;
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
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Анимации */
        .dashboard-card {
            animation: fadeInUp 0.6s ease;
        }

        .welcome-section {
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="container">
                <div class="dashboard-nav">
                    <div class="nav-left">
                        <a href="index.php" class="back-to-site">
                            <span>←</span>
                            <span>На сайт</span>
                        </a>
                        <div class="dashboard-logo">Scentury</div>
                    </div>
                    
                    <div class="dashboard-user">
                        <div class="user-info">
                            <div class="user-name">Слава Курекин</div>
                            <div class="user-email">kurekin@mail.ru</div>
                        </div>
                        <a href="auth.php?action=logout" class="logout-btn">Выйти</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="welcome-section">
                <h1 class="welcome-title">Добро пожаловать, Слава!</h1>
                <p class="welcome-subtitle">Управляйте своими ароматами и заказами</p>
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3 class="card-title">Информация о профиле</h3>
                    <ul class="user-details">
                        <li>
                            <span class="detail-label">Имя:</span>
                            <span class="detail-value">Слава Курекин</span>
                        </li>
                        <li>
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">kurekin@mail.ru</span>
                        </li>
                        <li>
                            <span class="detail-label">Телефон:</span>
                            <span class="detail-value">+79777531208</span>
                        </li>
                        <li>
                            <span class="detail-label">Роль:</span>
                            <span class="detail-value">User</span>
                        </li>
                        <li>
                            <span class="detail-label">Дата регистрации:</span>
                            <span class="detail-value">25.11.2025</span>
                        </li>
                        <li>
                            <span class="detail-label">Последний вход:</span>
                            <span class="detail-value">25.11.2025 16:29</span>
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
                    <a href="constructor.php" class="action-btn" style="display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem;">Создать первый аромат</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Автоматическое обновление времени последнего входа
        setInterval(function() {
            // Можно добавить AJAX запрос для обновления времени
        }, 60000); // Каждую минуту

        // Добавляем анимации при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>