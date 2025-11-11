<?php
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        
        $tables = $pdo->query("SHOW TABLES LIKE 'users'")->fetchAll();
        if (empty($tables)) {
            migrate($pdo);
        }

        $reviewsTable = $pdo->query("SHOW TABLES LIKE 'reviews'")->fetch();
        if (!$reviewsTable) {
            $pdo->exec('CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                author_name VARCHAR(255) NOT NULL,
                author_email VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                rating TINYINT NULL,
                is_approved TINYINT(1) NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL,
                FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_reviews_created (created_at),
                INDEX idx_reviews_approved (is_approved)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        }

        $visitsTable = $pdo->query("SHOW TABLES LIKE 'site_visits'")->fetch();
        if (!$visitsTable) {
            $pdo->exec('CREATE TABLE IF NOT EXISTS site_visits (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                path VARCHAR(255) NOT NULL,
                ip VARCHAR(64) NULL,
                user_agent VARCHAR(255) NULL,
                visited_at DATETIME NOT NULL,
                FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_visited_at (visited_at),
                INDEX idx_path (path)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        }
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }

    return $pdo;
}

function migrate(PDO $pdo): void {
    // users table
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        name VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL DEFAULT "user",
        created_at DATETIME NOT NULL,
        INDEX idx_email (email),
        INDEX idx_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    // bookings table
    $pdo->exec('CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        booking_date DATE NOT NULL,
        booking_time TIME NOT NULL,
        duration_minutes INT NOT NULL DEFAULT 60,
        status VARCHAR(20) NOT NULL DEFAULT "active",
        seat_class ENUM(\'basic\',\'pro\',\'vip\') NULL,
        seat_id INT NULL,
        notes TEXT,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY(seat_id) REFERENCES computer_seats(id) ON DELETE SET NULL,
        INDEX idx_booking_date (booking_date),
        INDEX idx_booking_time (booking_time),
        INDEX idx_status (status),
        INDEX idx_user_date (user_id, booking_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    // tournament registrations
    $pdo->exec('CREATE TABLE IF NOT EXISTS tournament_registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        game VARCHAR(100) NOT NULL,
        team_name VARCHAR(255) NOT NULL,
        captain_name VARCHAR(255) NOT NULL,
        captain_contacts VARCHAR(255) NOT NULL,
        players TEXT,
        created_at DATETIME NOT NULL,
        INDEX idx_game (game),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');


    $pdo->exec('CREATE TABLE IF NOT EXISTS equipment_types (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        UNIQUE KEY uq_type_name (name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS equipment_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        specs TEXT,
        FOREIGN KEY(type_id) REFERENCES equipment_types(id) ON DELETE CASCADE,
        INDEX idx_equipment_type (type_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');


    $pdo->exec('CREATE TABLE IF NOT EXISTS games (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        UNIQUE KEY uq_game_title (title)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    // ensure new fields for bookings exist for legacy DBs
    try {
        $col = $pdo->query("SHOW COLUMNS FROM bookings LIKE 'seat_class'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE bookings ADD COLUMN seat_class ENUM('basic','pro','vip') NULL AFTER status");
        }
    } catch (Throwable $e) {}
    try {
        $col = $pdo->query("SHOW COLUMNS FROM bookings LIKE 'seat_id'")->fetch();
        if (!$col) {
            $pdo->exec('ALTER TABLE bookings ADD COLUMN seat_id INT NULL AFTER seat_class, ADD CONSTRAINT fk_bookings_seat FOREIGN KEY (seat_id) REFERENCES computer_seats(id) ON DELETE SET NULL');
        }
    } catch (Throwable $e) {}

    $pdo->exec("CREATE TABLE IF NOT EXISTS computer_seats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        label VARCHAR(50) NOT NULL,
        seat_class ENUM('basic','pro','vip') NOT NULL DEFAULT 'basic',
        equipment_type_id INT NULL,
        FOREIGN KEY(equipment_type_id) REFERENCES equipment_types(id) ON DELETE SET NULL,
        UNIQUE KEY uq_seat_label (label),
        INDEX idx_seat_class (seat_class)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec('CREATE TABLE IF NOT EXISTS tariffs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        is_night TINYINT(1) NOT NULL DEFAULT 0,
        UNIQUE KEY uq_tariff_name (name),
        INDEX idx_is_night (is_night)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS rentals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        seat_id INT NOT NULL,
        tariff_id INT NULL,
        start_at DATETIME NOT NULL,
        end_at DATETIME NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY(seat_id) REFERENCES computer_seats(id) ON DELETE CASCADE,
        FOREIGN KEY(tariff_id) REFERENCES tariffs(id) ON DELETE SET NULL,
        INDEX idx_rentals_time (start_at, end_at),
        INDEX idx_rentals_user (user_id),
        INDEX idx_rentals_seat (seat_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS game_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        game_id INT NOT NULL,
        created_at DATETIME NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY(game_id) REFERENCES games(id) ON DELETE CASCADE,
        INDEX idx_game_orders_game (game_id),
        INDEX idx_game_orders_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        author_name VARCHAR(255) NOT NULL,
        author_email VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        rating TINYINT NULL,
        is_approved TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
        INDEX idx_reviews_created (created_at),
        INDEX idx_reviews_approved (is_approved)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    try {
        $col = $pdo->query("SHOW COLUMNS FROM reviews LIKE 'rating'")->fetch();
        if (!$col) {
            $pdo->exec('ALTER TABLE reviews ADD COLUMN rating TINYINT NULL AFTER message');
        }
    } catch (Throwable $e) {}
    try {
        $col = $pdo->query("SHOW COLUMNS FROM reviews LIKE 'is_approved'")->fetch();
        if (!$col) {
            $pdo->exec('ALTER TABLE reviews ADD COLUMN is_approved TINYINT(1) NOT NULL DEFAULT 0 AFTER rating, ADD INDEX idx_reviews_approved (is_approved)');
        }
    } catch (Throwable $e) {}

    $pdo->exec('CREATE TABLE IF NOT EXISTS site_visits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        path VARCHAR(255) NOT NULL,
        ip VARCHAR(64) NULL,
        user_agent VARCHAR(255) NULL,
        visited_at DATETIME NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
        INDEX idx_visited_at (visited_at),
        INDEX idx_path (path)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');


    // seed admin if none
    $stmt = $pdo->query('SELECT COUNT(*) as c FROM users');
    $count = (int)$stmt->fetchColumn();
    if ($count === 0) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $now = date('c');
        $ins = $pdo->prepare('INSERT INTO users (email, password_hash, name, role, created_at) VALUES (?, ?, ?, ?, ?)');
        $ins->execute(['admin@cyberhub.local', $password, 'Administrator', 'admin', $now]);
    }
}


