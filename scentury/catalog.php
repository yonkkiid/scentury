<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог ароматов - Scentury</title>
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
                        <li><a href="catalog.php" class="nav-link active">Каталог</a></li>
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

    <!-- Каталог ароматов -->
    <section class="catalog">
        <div class="container">
            <h2 class="section-title">Каталог готовых ароматов</h2>
            
            <!-- Фильтры -->
            <div class="catalog-filters">
                <div class="filter-group">
                    <label for="price-filter">Цена:</label>
                    <select id="price-filter">
                        <option value="all">Все цены</option>
                        <option value="0-2500">До 2,500 ₽</option>
                        <option value="2500-3000">2,500 - 3,000 ₽</option>
                        <option value="3000+">От 3,000 ₽</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="type-filter">Тип аромата:</label>
                    <select id="type-filter">
                        <option value="all">Все типы</option>
                        <option value="citrus">Цитрусовые</option>
                        <option value="floral">Цветочные</option>
                        <option value="woody">Древесные</option>
                        <option value="oriental">Восточные</option>
                    </select>
                </div>
                <button class="btn btn-secondary" id="reset-filters">Сбросить фильтры</button>
            </div>

            <!-- Сетка ароматов -->
            <div class="catalog-grid">
                <div class="catalog-item" data-price="2500" data-type="citrus">
                    <div class="catalog-image">
                        <img src="images/scent1.jpg" alt="Цитрусовый бриз">
                    </div>
                    <h3>Цитрусовый бриз</h3>
                    <p>Свежий и бодрящий аромат с нотами бергамота и лимона. Идеален для утреннего настроения.</p>
                    <div class="catalog-price">2,500 ₽</div>
                    <button class="btn btn-primary">Заказать</button>
                </div>

                <div class="catalog-item" data-price="3200" data-type="floral">
                    <div class="catalog-image">
                        <img src="images/scent2.jpg" alt="Розовый сад">
                    </div>
                    <h3>Розовый сад</h3>
                    <p>Нежный и романтичный аромат с нотами розы и жасмина. Для особых моментов.</p>
                    <div class="catalog-price">3,200 ₽</div>
                    <button class="btn btn-primary">Заказать</button>
                </div>

                <div class="catalog-item" data-price="2800" data-type="woody">
                    <div class="catalog-image">
                        <img src="images/scent3.jpg" alt="Древесная тайна">
                    </div>
                    <h3>Древесная тайна</h3>
                    <p>Загадочный аромат с нотами сандала и пачули. Для уверенных в себе.</p>
                    <div class="catalog-price">2,800 ₽</div>
                    <button class="btn btn-primary">Заказать</button>
                </div>

                <div class="catalog-item" data-price="2600" data-type="floral">
                    <div class="catalog-image">
                        <img src="images/scent4.jpg" alt="Лавандовые сны">
                    </div>
                    <h3>Лавандовые сны</h3>
                    <p>Успокаивающий аромат с нотами лаванды и ванили. Для расслабления.</p>
                    <div class="catalog-price">2,600 ₽</div>
                    <button class="btn btn-primary">Заказать</button>
                </div>

                <div class="catalog-item" data-price="3500" data-type="oriental">
                    <div class="catalog-image">
                        <img src="images/scent5.jpg" alt="Тропический рай">
                    </div>
                    <h3>Тропический рай</h3>
                    <p>Экзотический аромат с нотами иланг-иланга и амбры. Для путешествий.</p>
                    <div class="catalog-price">3,500 ₽</div>
                    <button class="btn btn-primary">Заказать</button>
                </div>

                <div class="catalog-item" data-price="2400" data-type="citrus">
                    <div class="catalog-image">
                        <img src="images/scent6.jpg" alt="Мандариновый закат">
                    </div>
                    <h3>Мандариновый закат</h3>
                    <p>Теплый и уютный аромат с нотами мандарина и ванили. Для домашнего уюта.</p>
                    <div class="catalog-price">2,400 ₽</div>
                    <button class="btn btn-primary">Заказать</button>
                </div>

                <div class="catalog-item" data-price="2900" data-type="woody">
                    <div class="catalog-image">
                        <img src="images/scent7.jpg" alt="Сосновый лес">
                    </div>
                    <h3>Сосновый лес</h3>
                    <p>Свежий лесной аромат с нотами сосны и можжевельника. Для природы.</p>
                    <div class="catalog-price">2,900 ₽</div>
                    <button class="btn btn-primary">Заказать</button>
                </div>

                <div class="catalog-item" data-price="3100" data-type="oriental">
                    <div class="catalog-image">
                        <img src="images/scent8.jpg" alt="Восточная ночь">
                    </div>
                    <h3>Восточная ночь</h3>
                    <p>Загадочный восточный аромат с нотами сандала и амбры. Для вечера.</p>
                    <div class="catalog-price">3,100 ₽</div>
                    <button class="btn btn-primary">Заказать</button>
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
