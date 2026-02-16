<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Блог о парфюмерии - Scentury</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body>
    <!-- Шапка сайта -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
    <img src="images/logo.png" alt="Scentury Logo" class="logo-img">
    <div class="logo-text">
        <h1>Scentury</h1>
    </div>
</div>
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link">Главная</a></li>
                        <li><a href="constructor.php" class="nav-link">Конструктор</a></li>
                        <li><a href="catalog.php" class="nav-link">Каталог</a></li>
                        <li><a href="about.php" class="nav-link">О компании</a></li>
                        <li><a href="blog.php" class="nav-link active">Блог</a></li>
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

    <!-- Блог -->
    <section class="blog">
        <div class="container">
            <h2 class="section-title">Блог о парфюмерии</h2>
            <p class="blog-description">Узнайте больше о мире ароматов, их истории и создании</p>
            
            <div class="blog-grid">
                <article class="blog-post">
                    <div class="post-image">
                        <img src="images/blog1.jpg" alt="История парфюмерии">
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date">15 марта 2024</span>
                            <span class="post-category">История</span>
                        </div>
                        <h3>История парфюмерии: от древности до наших дней</h3>
                        <p>Парфюмерия имеет богатую историю, уходящую корнями в глубокую древность. Первые ароматы использовались в религиозных ритуалах и для маскировки неприятных запахов...</p>
                        <a href="#" class="read-more">Читать далее</a>
                    </div>
                </article>

                <article class="blog-post">
                    <div class="post-image">
                        <img src="images/blog2.jpg" alt="Ноты парфюмерии">
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date">10 марта 2024</span>
                            <span class="post-category">Образование</span>
                        </div>
                        <h3>Как правильно сочетать ноты в парфюмерии</h3>
                        <p>Создание идеального аромата - это искусство. Узнайте, как правильно сочетать верхние, сердечные и базовые ноты для создания гармоничного аромата...</p>
                        <a href="#" class="read-more">Читать далее</a>
                    </div>
                </article>

                <article class="blog-post">
                    <div class="post-image">
                        <img src="images/blog3.jpg" alt="Натуральные ингредиенты">
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date">5 марта 2024</span>
                            <span class="post-category">Ингредиенты</span>
                        </div>
                        <h3>Натуральные vs синтетические ингредиенты в парфюмерии</h3>
                        <p>Разбираемся в различиях между натуральными и синтетическими ингредиентами, их преимуществах и недостатках...</p>
                        <a href="#" class="read-more">Читать далее</a>
                    </div>
                </article>

                <article class="blog-post">
                    <div class="post-image">
                        <img src="images/blog4.jpg" alt="Хранение парфюмерии">
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date">1 марта 2024</span>
                            <span class="post-category">Советы</span>
                        </div>
                        <h3>Как правильно хранить парфюмерию</h3>
                        <p>Правильное хранение парфюмерии поможет сохранить аромат на долгие годы. Узнайте основные правила хранения духов...</p>
                        <a href="#" class="read-more">Читать далее</a>
                    </div>
                </article>

                <article class="blog-post">
                    <div class="post-image">
                        <img src="images/blog5.jpg" alt="Сезонные ароматы">
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date">25 февраля 2024</span>
                            <span class="post-category">Стиль</span>
                        </div>
                        <h3>Сезонные ароматы: какие духи выбрать для каждого времени года</h3>
                        <p>Разные времена года требуют разных ароматов. Узнайте, какие ноты лучше подходят для весны, лета, осени и зимы...</p>
                        <a href="#" class="read-more">Читать далее</a>
                    </div>
                </article>

                <article class="blog-post">
                    <div class="post-image">
                        <img src="images/blog6.jpg" alt="Создание аромата">
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date">20 февраля 2024</span>
                            <span class="post-category">Мастер-класс</span>
                        </div>
                        <h3>Мастер-класс: создание аромата в домашних условиях</h3>
                        <p>Хотите попробовать создать свой аромат дома? Мы расскажем, как это сделать с минимальным набором ингредиентов...</p>
                        <a href="#" class="read-more">Читать далее</a>
                    </div>
                </article>
            </div>

            <div class="blog-pagination">
                <button class="btn btn-secondary">Предыдущая</button>
                <span class="pagination-info">Страница 1 из 3</span>
                <button class="btn btn-secondary">Следующая</button>
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
