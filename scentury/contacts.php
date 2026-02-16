<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - Scentury</title>
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
                        <li><a href="blog.php" class="nav-link">Блог</a></li>
                        <li><a href="contacts.php" class="nav-link active">Контакты</a></li>
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

    <!-- Контакты -->
    <section class="contacts">
        <div class="container">
            <h2 class="section-title">Свяжитесь с нами</h2>
            
            <div class="contacts-content">
                <!-- Форма заказа -->
                <div class="contact-form-section">
                    <h3>Форма заказа</h3>
                    <form class="contact-form" id="order-form" action="process-form.php" method="POST">
                        <div class="form-group">
                            <label for="name">Имя *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Телефон *</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="scent-name">Название аромата</label>
                            <input type="text" id="scent-name" name="scent_name" placeholder="Если создавали в конструкторе">
                        </div>
                        
                        <div class="form-group">
                            <label for="selected-notes">Выбранные ноты</label>
                            <textarea id="selected-notes" name="selected_notes" placeholder="Опишите выбранные ноты или пожелания"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Дополнительные пожелания</label>
                            <textarea id="message" name="message" rows="4" placeholder="Расскажите о ваших предпочтениях в ароматах"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="agreement" required>
                                <span class="checkmark"></span>
                                Я согласен с обработкой персональных данных
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Отправить заявку</button>
                    </form>
                </div>

                <!-- Контактная информация -->
                <div class="contact-info">
                    <h3>Контактная информация</h3>
                    
                    <div class="contact-item">
                        <h4>Телефон</h4>
                        <p>+7 (495) 123-45-67</p>
                        <p>+7 (495) 123-45-68</p>
                    </div>
                    
                    <div class="contact-item">
                        <h4>Email</h4>
                        <p>info@scentury.ru</p>
                        <p>orders@scentury.ru</p>
                    </div>
                    
                    <div class="contact-item">
                        <h4>Адрес</h4>
                        <p>Москва, ул. Ароматная, 15</p>
                        <p>м. Сокольники, 5 минут пешком</p>
                    </div>
                    
                    <div class="contact-item">
                        <h4>Время работы</h4>
                        <p>Пн-Пт: 10:00 - 20:00</p>
                        <p>Сб-Вс: 11:00 - 18:00</p>
                    </div>
                    
                    <div class="contact-item">
                        <h4>Социальные сети</h4>
                        <div class="social-links">
                            <a href="#" class="social-link">Instagram</a>
                            <a href="#" class="social-link">VK</a>
                            <a href="#" class="social-link">Telegram</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Карта -->
            <div class="map-section">
                <h3>Как нас найти</h3>
                <div class="map">
                    <img src="images/map.jpg" alt="Карта проезда">
                    <div class="map-overlay">
                        <p>Москва, ул. Ароматная, 15</p>
                        <p>м. Сокольники</p>
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
