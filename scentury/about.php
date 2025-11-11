<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О компании - Scentury</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body>
    <!-- Шапка сайта -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>Scentury</h1>
                    <span>Конструктор парфюмерии</span>
                </div>
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link">Главная</a></li>
                        <li><a href="constructor.php" class="nav-link">Конструктор</a></li>
                        <li><a href="catalog.php" class="nav-link">Каталог</a></li>
                        <li><a href="about.php" class="nav-link active">О компании</a></li>
                        <li><a href="blog.php" class="nav-link">Блог</a></li>
                        <li><a href="contacts.php" class="nav-link">Контакты</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <?php
                    session_start();
                    if (isset($_SESSION['user_id'])) {
                        echo '<a href="dashboard.php" class="btn btn-secondary">Личный кабинет</a>';
                        echo '<a href="auth.php?action=logout" class="btn btn-outline">Выйти</a>';
                    } else {
                        echo '<a href="login.php" class="btn btn-secondary">Войти</a>';
                        echo '<a href="register.php" class="btn btn-primary">Регистрация</a>';
                    }
                    ?>
                </div>
                <div class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>

    <!-- О компании -->
    <section class="about">
        <div class="container">
            <h2 class="section-title">О компании Scentury</h2>
            
            <div class="about-content">
                <div class="about-text">
                    <h3>Наша миссия</h3>
                    <p>Мы верим, что каждый человек уникален, и его аромат должен отражать эту уникальность. Scentury - это не просто магазин парфюмерии, это место, где рождаются индивидуальные ароматы, созданные специально для вас.</p>
                    
                    <h3>Наша история</h3>
                    <p>Компания Scentury была основана в 2020 году группой энтузиастов парфюмерии. Мы начали с небольшой лаборатории в Москве, где экспериментировали с различными комбинациями ароматических нот. Сегодня мы предлагаем более 1000 различных нот и создали уже более 10,000 уникальных ароматов для наших клиентов.</p>
                    
                    <h3>Наши ценности</h3>
                    <ul>
                        <li><strong>Индивидуальность</strong> - каждый аромат создается специально для вас</li>
                        <li><strong>Качество</strong> - мы используем только натуральные и высококачественные ингредиенты</li>
                        <li><strong>Инновации</strong> - постоянно развиваем технологии создания ароматов</li>
                        <li><strong>Доступность</strong> - делаем индивидуальную парфюмерию доступной для всех</li>
                    </ul>
                </div>
                
                <div class="about-image">
                    <img src="images/about-lab.jpg" alt="Наша лаборатория">
                </div>
            </div>

            <div class="about-stats">
                <div class="stat-item">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">Созданных ароматов</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5,000+</div>
                    <div class="stat-label">Довольных клиентов</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1,000+</div>
                    <div class="stat-label">Ароматических нот</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Года опыта</div>
                </div>
            </div>

            <div class="about-team">
                <h3>Наша команда</h3>
                <div class="team-grid">
                    <div class="team-member">
                        <div class="member-photo">
                            <img src="images/team1.jpg" alt="Анна Смирнова">
                        </div>
                        <h4>Анна Смирнова</h4>
                        <p>Главный парфюмер</p>
                        <p>15 лет опыта в создании ароматов</p>
                    </div>
                    <div class="team-member">
                        <div class="member-photo">
                            <img src="images/team2.jpg" alt="Михаил Петров">
                        </div>
                        <h4>Михаил Петров</h4>
                        <p>Технолог</p>
                        <p>Специалист по натуральным ингредиентам</p>
                    </div>
                    <div class="team-member">
                        <div class="member-photo">
                            <img src="images/team3.jpg" alt="Елена Козлова">
                        </div>
                        <h4>Елена Козлова</h4>
                        <p>Консультант</p>
                        <p>Помогает клиентам выбрать идеальный аромат</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Подвал -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Scentury</h3>
                    <p>Создавайте уникальные ароматы с нашим интерактивным конструктором</p>
                </div>
                <div class="footer-section">
                    <h4>Навигация</h4>
                    <ul>
                        <li><a href="index.php">Главная</a></li>
                        <li><a href="constructor.php">Конструктор</a></li>
                        <li><a href="catalog.php">Каталог</a></li>
                        <li><a href="about.php">О компании</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Контакты</h4>
                    <p>+7 (495) 123-45-67</p>
                    <p>info@scentury.ru</p>
                    <p>Москва, ул. Ароматная, 15</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Scentury. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
