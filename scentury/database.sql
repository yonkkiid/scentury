
-- Создание базы данных для сайта Scentury
CREATE DATABASE IF NOT EXISTS scentury CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE scentury;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    newsletter BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    role ENUM('user', 'admin', 'moderator') DEFAULT 'user',
    remember_token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Таблица заказов
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    scent_name VARCHAR(255),
    selected_notes TEXT,
    description TEXT,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    total_price DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Таблица ароматов (готовые)
CREATE TABLE IF NOT EXISTS scents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(500),
    category ENUM('citrus', 'floral', 'woody', 'oriental') DEFAULT 'citrus',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Таблица нот
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('top', 'heart', 'base') NOT NULL,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица статей блога
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    image_url VARCHAR(500),
    category VARCHAR(100),
    status ENUM('published', 'draft') DEFAULT 'draft',
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Вставка базовых нот
INSERT INTO notes (name, type, description) VALUES
-- Верхние ноты
('Бергамот', 'top', 'Свежий цитрусовый аромат'),
('Лимон', 'top', 'Яркий цитрусовый аромат'),
('Лайм', 'top', 'Освежающий цитрусовый аромат'),
('Мандарин', 'top', 'Сладкий цитрусовый аромат'),
('Грейпфрут', 'top', 'Горьковато-сладкий цитрусовый аромат'),
('Апельсин', 'top', 'Сочный цитрусовый аромат'),
('Мята', 'top', 'Освежающий ментоловый аромат'),
('Базилик', 'top', 'Пряный травяной аромат'),
('Розмарин', 'top', 'Ароматный травяной аромат'),
('Тимьян', 'top', 'Пряный травяной аромат'),

-- Сердечные ноты
('Роза', 'heart', 'Классический цветочный аромат'),
('Жасмин', 'heart', 'Нежный цветочный аромат'),
('Лаванда', 'heart', 'Успокаивающий травяной аромат'),
('Иланг-иланг', 'heart', 'Экзотический цветочный аромат'),
('Лилия', 'heart', 'Нежный цветочный аромат'),
('Пион', 'heart', 'Романтичный цветочный аромат'),
('Магнолия', 'heart', 'Элегантный цветочный аромат'),
('Герань', 'heart', 'Пряный цветочный аромат'),
('Ирис', 'heart', 'Порошковый цветочный аромат'),
('Орхидея', 'heart', 'Экзотический цветочный аромат'),

-- Базовые ноты
('Ваниль', 'base', 'Сладкий теплый аромат'),
('Сандал', 'base', 'Древесный аромат'),
('Пачули', 'base', 'Земляной аромат'),
('Амбра', 'base', 'Теплый животный аромат'),
('Кедр', 'base', 'Древесный аромат'),
('Дубовый мох', 'base', 'Земляной аромат'),
('Мускус', 'base', 'Животный аромат'),
('Ветивер', 'base', 'Земляной аромат'),
('Бензоин', 'base', 'Сладкий смолистый аромат'),
('Тонка', 'base', 'Сладкий бобовый аромат');

-- Вставка готовых ароматов
INSERT INTO scents (name, description, price, category) VALUES
('Цитрусовый бриз', 'Свежий и бодрящий аромат с нотами бергамота и лимона', 2500.00, 'citrus'),
('Розовый сад', 'Нежный и романтичный аромат с нотами розы и жасмина', 3200.00, 'floral'),
('Древесная тайна', 'Загадочный аромат с нотами сандала и пачули', 2800.00, 'woody'),
('Лавандовые сны', 'Успокаивающий аромат с нотами лаванды и ванили', 2600.00, 'floral'),
('Тропический рай', 'Экзотический аромат с нотами иланг-иланга и амбры', 3500.00, 'oriental'),
('Мандариновый закат', 'Теплый и уютный аромат с нотами мандарина и ванили', 2400.00, 'citrus'),
('Сосновый лес', 'Свежий лесной аромат с нотами сосны и можжевельника', 2900.00, 'woody'),
('Восточная ночь', 'Загадочный восточный аромат с нотами сандала и амбры', 3100.00, 'oriental');

-- Создание администратора по умолчанию
INSERT INTO users (first_name, last_name, email, password, role, status) VALUES
('Администратор', 'Системы', 'admin@scentury.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- Создание индексов для оптимизации
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_status ON users(status);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_scents_category ON scents(category);
CREATE INDEX idx_scents_status ON scents(status);
CREATE INDEX idx_notes_type ON notes(type);
CREATE INDEX idx_blog_posts_status ON blog_posts(status);
CREATE INDEX idx_blog_posts_category ON blog_posts(category);
