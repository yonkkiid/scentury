<?php
require_once __DIR__ . '/../includes/db.php';

function seedData() {
    $pdo = db();
    
    echo "Заполнение базы данных тестовыми данными...\n";
    
    // 1. Добавляем типы оборудования
    echo "Добавление типов оборудования...\n";
    $equipmentTypes = ['GPU', 'Monitor', 'Keyboard', 'Mouse', 'Headset', 'CPU', 'RAM', 'Motherboard'];
    foreach ($equipmentTypes as $type) {
        try {
            $pdo->exec("INSERT IGNORE INTO equipment_types (name) VALUES ('$type')");
        } catch (Exception $e) {
            // Игнорируем ошибки дублирования
        }
    }
    
    // 2. Добавляем элементы оборудования
    echo "Добавление элементов оборудования...\n";
    $equipmentData = [
        ['GPU', 'RTX 4090', '24GB GDDR6X, 16384 CUDA cores, 2.52 GHz'],
        ['GPU', 'RTX 4080', '16GB GDDR6X, 9728 CUDA cores, 2.51 GHz'],
        ['GPU', 'RTX 4070', '12GB GDDR6X, 5888 CUDA cores, 2.48 GHz'],
        ['GPU', 'RTX 3070', '8GB GDDR6, 5888 CUDA cores, 1.73 GHz'],
        ['Monitor', 'ASUS ROG Swift 360Hz', '24.5", 1920x1080, 360Hz, 1ms'],
        ['Monitor', 'LG UltraGear 240Hz', '27", 2560x1440, 240Hz, 1ms'],
        ['Monitor', 'BenQ ZOWIE XL2566K', '24.5", 1920x1080, 360Hz, 0.5ms'],
        ['Keyboard', 'Corsair K100 RGB', 'Mechanical, Cherry MX Speed, RGB'],
        ['Keyboard', 'Razer BlackWidow V3', 'Mechanical, Green switches, RGB'],
        ['Keyboard', 'SteelSeries Apex Pro', 'Mechanical, OmniPoint switches'],
        ['Mouse', 'Logitech G Pro X Superlight', 'Wireless, 25,600 DPI, 63g'],
        ['Mouse', 'Razer DeathAdder V3 Pro', 'Wireless, 30,000 DPI, 63g'],
        ['Mouse', 'ZOWIE EC2-C', 'Wired, 3200 DPI, Ergonomic'],
        ['Headset', 'SteelSeries Arctis Pro', 'Wireless, 40kHz, 2.4GHz'],
        ['Headset', 'HyperX Cloud II', 'Wired, 7.1 surround, 53mm drivers'],
        ['Headset', 'Razer BlackShark V2', 'Wired, 50mm drivers, THX Spatial'],
        ['CPU', 'Intel i9-13900K', '24 cores, 32 threads, 5.8 GHz'],
        ['CPU', 'AMD Ryzen 9 7950X', '16 cores, 32 threads, 5.7 GHz'],
        ['CPU', 'Intel i7-13700K', '16 cores, 24 threads, 5.4 GHz'],
        ['RAM', 'Corsair Vengeance 32GB', 'DDR5-5600, CL36, 2x16GB'],
        ['RAM', 'G.Skill Trident Z5 32GB', 'DDR5-6000, CL36, 2x16GB'],
        ['Motherboard', 'ASUS ROG Maximus Z790', 'LGA 1700, DDR5, PCIe 5.0'],
        ['Motherboard', 'MSI MPG X670E Carbon', 'AM5, DDR5, PCIe 5.0']
    ];
    
    foreach ($equipmentData as $item) {
        $typeName = $item[0];
        $equipName = $item[1];
        $specs = $item[2];
        
        $typeId = $pdo->query("SELECT id FROM equipment_types WHERE name = '$typeName'")->fetchColumn();
        if ($typeId) {
            try {
                $stmt = $pdo->prepare("INSERT IGNORE INTO equipment_items (type_id, name, specs) VALUES (?, ?, ?)");
                $stmt->execute([$typeId, $equipName, $specs]);
            } catch (Exception $e) {
                // Игнорируем ошибки дублирования
            }
        }
    }
    
    // 3. Создаем компьютерные места
    echo "Создание компьютерных мест...\n";
    $seatData = [
        ['VIP-01', 'vip', 'RTX 4090'],
        ['VIP-02', 'vip', 'RTX 4080'],
        ['VIP-03', 'vip', 'RTX 4070'],
        ['PRO-01', 'pro', 'RTX 4080'],
        ['PRO-02', 'pro', 'RTX 4070'],
        ['PRO-03', 'pro', 'RTX 3070'],
        ['PRO-04', 'pro', 'RTX 3070'],
        ['BASIC-01', 'basic', 'RTX 3070'],
        ['BASIC-02', 'basic', 'RTX 3070'],
        ['BASIC-03', 'basic', 'RTX 3070'],
        ['BASIC-04', 'basic', 'RTX 3070'],
        ['BASIC-05', 'basic', 'RTX 3070'],
        ['BASIC-06', 'basic', 'RTX 3070'],
        ['BASIC-07', 'basic', 'RTX 3070'],
        ['BASIC-08', 'basic', 'RTX 3070']
    ];
    
    foreach ($seatData as $seat) {
        $label = $seat[0];
        $class = $seat[1];
        $gpuName = $seat[2];
        
        $equipTypeId = $pdo->query("SELECT type_id FROM equipment_items WHERE name LIKE '%$gpuName%' LIMIT 1")->fetchColumn();
        
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO computer_seats (label, seat_class, equipment_type_id) VALUES (?, ?, ?)");
            $stmt->execute([$label, $class, $equipTypeId]);
        } catch (Exception $e) {
            // Игнорируем ошибки дублирования
        }
    }
    
    // 4. Добавляем тарифы
    echo "Добавление тарифов...\n";
    $tariffs = [
        ['Дневной VIP', 0],
        ['Дневной PRO', 0],
        ['Дневной BASIC', 0],
        ['Ночной VIP', 1],
        ['Ночной PRO', 1],
        ['Ночной BASIC', 1],
        ['Суточный VIP', 0],
        ['Суточный PRO', 0]
    ];
    
    foreach ($tariffs as $tariff) {
        $name = $tariff[0];
        $isNight = $tariff[1];
        
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO tariffs (name, is_night) VALUES (?, ?)");
            $stmt->execute([$name, $isNight]);
        } catch (Exception $e) {
            // Игнорируем ошибки дублирования
        }
    }
    
    // 5. Добавляем игры
    echo "Добавление игр...\n";
    $games = [
        'Counter-Strike 2',
        'Dota 2',
        'Valorant',
        'League of Legends',
        'Apex Legends',
        'Fortnite',
        'PUBG',
        'Overwatch 2',
        'Rocket League',
        'Call of Duty: Warzone',
        'World of Warcraft',
        'Final Fantasy XIV',
        'Minecraft',
        'Among Us',
        'Fall Guys',
        'Genshin Impact',
        'Lost Ark',
        'New World',
        'Elden Ring',
        'Cyberpunk 2077',
        'Baldur\'s Gate 3',
        'Starfield',
        'Diablo IV',
        'Hogwarts Legacy',
        'Spider-Man Remastered'
    ];
    
    foreach ($games as $game) {
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO games (title) VALUES (?)");
            $stmt->execute([$game]);
        } catch (Exception $e) {
            // Игнорируем ошибки дублирования
        }
    }
    
    // 6. Добавляем тестовых пользователей (если их нет)
    echo "Добавление тестовых пользователей...\n";
    $users = [
        ['user1@cyberhub.local', 'Иван Петров', 'user'],
        ['user2@cyberhub.local', 'Мария Сидорова', 'user'],
        ['user3@cyberhub.local', 'Алексей Козлов', 'user'],
        ['user4@cyberhub.local', 'Елена Волкова', 'user'],
        ['user5@cyberhub.local', 'Дмитрий Морозов', 'user'],
        ['gamer1@cyberhub.local', 'Андрей Геймер', 'user'],
        ['gamer2@cyberhub.local', 'Светлана Про', 'user'],
        ['vip_user@cyberhub.local', 'Владимир VIP', 'user']
    ];
    
    $password = password_hash('password123', PASSWORD_DEFAULT);
    $now = date('Y-m-d H:i:s');
    
    foreach ($users as $user) {
        $email = $user[0];
        $name = $user[1];
        $role = $user[2];
        
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO users (email, password_hash, name, role, created_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$email, $password, $name, $role, $now]);
        } catch (Exception $e) {
            // Игнорируем ошибки дублирования
        }
    }
    
    // 7. Создаем записи аренды
    echo "Создание записей аренды...\n";
    $rentalData = [];
    
    // Получаем ID пользователей и мест
    $userIds = $pdo->query("SELECT id FROM users WHERE role = 'user' LIMIT 5")->fetchAll(PDO::FETCH_COLUMN);
    $seatIds = $pdo->query("SELECT id FROM computer_seats ORDER BY seat_class DESC LIMIT 8")->fetchAll(PDO::FETCH_COLUMN);
    $tariffIds = $pdo->query("SELECT id FROM tariffs LIMIT 6")->fetchAll(PDO::FETCH_COLUMN);
    
    // Создаем аренды за последние 30 дней
    for ($i = 0; $i < 50; $i++) {
        $userId = $userIds[array_rand($userIds)];
        $seatId = $seatIds[array_rand($seatIds)];
        $tariffId = $tariffIds[array_rand($tariffIds)];
        
        // Случайная дата за последние 30 дней
        $daysAgo = rand(0, 30);
        $date = date('Y-m-d', strtotime("-$daysAgo days"));
        
        // Случайное время начала (8:00 - 22:00)
        $startHour = rand(8, 22);
        $startMinute = rand(0, 59);
        $startTime = sprintf('%02d:%02d:00', $startHour, $startMinute);
        
        // Длительность от 1 до 6 часов
        $duration = rand(1, 6);
        $endHour = $startHour + $duration;
        $endMinute = $startMinute;
        
        if ($endHour >= 24) {
            $endHour = 23;
            $endMinute = 59;
        }
        
        $endTime = sprintf('%02d:%02d:00', $endHour, $endMinute);
        
        $startAt = "$date $startTime";
        $endAt = "$date $endTime";
        
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO rentals (user_id, seat_id, tariff_id, start_at, end_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $seatId, $tariffId, $startAt, $endAt]);
        } catch (Exception $e) {
            // Игнорируем ошибки дублирования
        }
    }
    
    // 8. Создаем заказы игр
    echo "Создание заказов игр...\n";
    $gameIds = $pdo->query("SELECT id FROM games ORDER BY RAND() LIMIT 15")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($userIds as $userId) {
        // Каждый пользователь заказывает 1-5 игр
        $numGames = rand(1, 5);
        $userGames = array_slice($gameIds, 0, $numGames);
        
        foreach ($userGames as $gameId) {
            // Случайная дата заказа за последние 60 дней
            $daysAgo = rand(0, 60);
            $createdAt = date('Y-m-d H:i:s', strtotime("-$daysAgo days"));
            
            try {
                $stmt = $pdo->prepare("INSERT IGNORE INTO game_orders (user_id, game_id, created_at) VALUES (?, ?, ?)");
                $stmt->execute([$userId, $gameId, $createdAt]);
            } catch (Exception $e) {
                // Игнорируем ошибки дублирования
            }
        }
    }
    
    echo "Заполнение базы данных завершено!\n";
    echo "Добавлено:\n";
    echo "- " . count($equipmentTypes) . " типов оборудования\n";
    echo "- " . count($equipmentData) . " элементов оборудования\n";
    echo "- " . count($seatData) . " компьютерных мест\n";
    echo "- " . count($tariffs) . " тарифов\n";
    echo "- " . count($games) . " игр\n";
    echo "- " . count($users) . " пользователей\n";
    echo "- 50 записей аренды\n";
    echo "- Множество заказов игр\n";
}

// Запускаем заполнение
if (php_sapi_name() === 'cli') {
    seedData();
} else {
    echo "Этот скрипт должен запускаться из командной строки.\n";
    echo "Используйте: php scripts/seed_data.php\n";
}
?>








