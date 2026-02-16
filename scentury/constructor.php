<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Конструктор ароматов - Scentury</title>
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
</div>                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link">Главная</a></li>
                        <li><a href="constructor.php" class="nav-link active">Конструктор</a></li>
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

    <!-- Конструктор ароматов -->
    <section class="constructor">
        <div class="container">
            <h2 class="section-title">Собери свой аромат</h2>
            <p class="constructor-description">Выберите ноты для создания уникального аромата</p>
            
            <div class="constructor-content">
                <!-- Колонки с нотами -->
                <div class="notes-columns">
                    <!-- Верхние ноты -->
                    <div class="notes-column">
                        <h3 class="notes-title">Верхние ноты</h3>
                        <p class="notes-description">Первое впечатление от аромата</p>
                        <div class="notes-list">
                            <div class="note-item" data-note="bergamot" data-type="top">
                                <span class="note-name">Бергамот</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="lemon" data-type="top">
                                <span class="note-name">Лимон</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="lime" data-type="top">
                                <span class="note-name">Лайм</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="mandarin" data-type="top">
                                <span class="note-name">Мандарин</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="grapefruit" data-type="top">
                                <span class="note-name">Грейпфрут</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="orange" data-type="top">
                                <span class="note-name">Апельсин</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="mint" data-type="top">
                                <span class="note-name">Мята</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="basil" data-type="top">
                                <span class="note-name">Базилик</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="rosemary" data-type="top">
                                <span class="note-name">Розмарин</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="thyme" data-type="top">
                                <span class="note-name">Тимьян</span>
                                <span class="note-check">✓</span>
                            </div>
                        </div>
                    </div>

                    <!-- Сердечные ноты -->
                    <div class="notes-column">
                        <h3 class="notes-title">Сердечные ноты</h3>
                        <p class="notes-description">Основа аромата</p>
                        <div class="notes-list">
                            <div class="note-item" data-note="rose" data-type="heart">
                                <span class="note-name">Роза</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="jasmine" data-type="heart">
                                <span class="note-name">Жасмин</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="lavender" data-type="heart">
                                <span class="note-name">Лаванда</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="ylang" data-type="heart">
                                <span class="note-name">Иланг-иланг</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="lily" data-type="heart">
                                <span class="note-name">Лилия</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="peony" data-type="heart">
                                <span class="note-name">Пион</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="magnolia" data-type="heart">
                                <span class="note-name">Магнолия</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="geranium" data-type="heart">
                                <span class="note-name">Герань</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="iris" data-type="heart">
                                <span class="note-name">Ирис</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="orchid" data-type="heart">
                                <span class="note-name">Орхидея</span>
                                <span class="note-check">✓</span>
                            </div>
                        </div>
                    </div>

                    <!-- Базовые ноты -->
                    <div class="notes-column">
                        <h3 class="notes-title">Базовые ноты</h3>
                        <p class="notes-description">Долгоиграющие ноты</p>
                        <div class="notes-list">
                            <div class="note-item" data-note="vanilla" data-type="base">
                                <span class="note-name">Ваниль</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="sandalwood" data-type="base">
                                <span class="note-name">Сандал</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="patchouli" data-type="base">
                                <span class="note-name">Пачули</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="amber" data-type="base">
                                <span class="note-name">Амбра</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="cedar" data-type="base">
                                <span class="note-name">Кедр</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="oakmoss" data-type="base">
                                <span class="note-name">Дубовый мох</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="musk" data-type="base">
                                <span class="note-name">Мускус</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="vetiver" data-type="base">
                                <span class="note-name">Ветивер</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="benzoin" data-type="base">
                                <span class="note-name">Бензоин</span>
                                <span class="note-check">✓</span>
                            </div>
                            <div class="note-item" data-note="tonka" data-type="base">
                                <span class="note-name">Тонка</span>
                                <span class="note-check">✓</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Предпросмотр аромата -->
                <div class="scent-preview">
                    <h3>Ваш аромат</h3>
                    <div class="scent-name-input">
                        <label for="scent-name">Название вашего аромата:</label>
                        <input type="text" id="scent-name" placeholder="Введите название аромата">
                    </div>
                    
                    <div class="selected-notes">
                        <h4>Выбранные ноты:</h4>
                        <div class="selected-notes-list">
                            <div class="selected-notes-empty">Выберите ноты для создания аромата</div>
                        </div>
                    </div>

                    <div class="scent-actions">
                        <button class="btn btn-secondary" id="clear-notes">Очистить</button>
                        <button class="btn btn-primary" id="send-request">Отправить заявку</button>
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
