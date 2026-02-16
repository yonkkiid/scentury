<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scentury - Конструктор парфюмерии</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body>
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
                        <li><a href="index.php" class="nav-link active">Главная</a></li>
                        <li><a href="constructor.php" class="nav-link">Конструктор</a></li>
                        <li><a href="catalog.php" class="nav-link">Каталог</a></li>
                        <li><a href="about.php" class="nav-link">О компании</a></li>
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

    <!-- Главный баннер -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2 class="hero-title">Создайте свой уникальный аромат</h2>
                <p class="hero-description">Откройте мир индивидуальной парфюмерии с нашим интерактивным конструктором</p>
                <a href="constructor.php" class="btn btn-primary">Создать аромат</a>
            </div>
        </div>
    </section>

    <!-- Как это работает -->
    <section class="how-it-works">
        <div class="container">
            <h2 class="section-title">Как это работает</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Выберите ноты</h3>
                    <p>Соберите композицию из верхних, сердечных и базовых нот</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Назовите аромат</h3>
                    <p>Дайте имя своему уникальному творению</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Получите результат</h3>
                    <p>Мы создадим ваш аромат и доставим его вам</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Популярные ароматы -->
    <section class="popular-scents">
        <div class="container">
            <h2 class="section-title">Популярные ароматы</h2>
            <div class="scents-grid">
                <div class="scent-card">
                    <div class="scent-image">
                        <img src="images/scent1.jpg" alt="Цитрусовый бриз">
                    </div>
                    <h3>Цитрусовый бриз</h3>
                    <p>Свежий и бодрящий аромат с нотами бергамота и лимона</p>
                    <div class="scent-price">2,500 ₽</div>
                </div>
                <div class="scent-card">
                    <div class="scent-image">
                        <img src="images/scent2.jpg" alt="Розовый сад">
                    </div>
                    <h3>Розовый сад</h3>
                    <p>Нежный и романтичный аромат с нотами розы и жасмина</p>
                    <div class="scent-price">3,200 ₽</div>
                </div>
                <div class="scent-card">
                    <div class="scent-image">
                        <img src="images/scent3.jpg" alt="Древесная тайна">
                    </div>
                    <h3>Древесная тайна</h3>
                    <p>Загадочный аромат с нотами сандала и пачули</p>
                    <div class="scent-price">2,800 ₽</div>
                </div>
                <div class="scent-card">
                    <div class="scent-image">
                        <img src="images/scent4.jpg" alt="Лавандовые сны">
                    </div>
                    <h3>Лавандовые сны</h3>
                    <p>Успокаивающий аромат с нотами лаванды и ванили</p>
                    <div class="scent-price">2,600 ₽</div>
                </div>
                <div class="scent-card">
                    <div class="scent-image">
                        <img src="images/scent5.jpg" alt="Тропический рай">
                    </div>
                    <h3>Тропический рай</h3>
                    <p>Экзотический аромат с нотами иланг-иланга и амбры</p>
                    <div class="scent-price">3,500 ₽</div>
                </div>
                <div class="scent-card">
                    <div class="scent-image">
                        <img src="images/scent6.jpg" alt="Мандариновый закат">
                    </div>
                    <h3>Мандариновый закат</h3>
                    <p>Теплый и уютный аромат с нотами мандарина и ванили</p>
                    <div class="scent-price">2,400 ₽</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Отзывы клиентов -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">Отзывы наших клиентов</h2>
            <div class="testimonials-grid">
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"Невероятно! Я создала аромат, который идеально отражает мою личность. Качество превосходное!"</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>Анна К.</strong>
                        <span>Москва</span>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"Процесс создания аромата был увлекательным, а результат превзошел все ожидания!"</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>Михаил С.</strong>
                        <span>Санкт-Петербург</span>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"Отличный сервис! Быстро, качественно, индивидуальный подход к каждому клиенту."</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>Елена В.</strong>
                        <span>Казань</span>
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
