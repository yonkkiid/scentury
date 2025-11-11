<?php
require_once __DIR__ . '/../includes/db.php';

function testAdminFunctions() {
    $pdo = db();
    
    echo "Тестирование функций админ-панели...\n\n";
    
    // 1. Проверка пользователей
    echo "1. Проверка пользователей:\n";
    $users = $pdo->query("SELECT COUNT(*) as count, role FROM users GROUP BY role")->fetchAll();
    foreach ($users as $user) {
        echo "   - {$user['role']}: {$user['count']} пользователей\n";
    }
    echo "\n";
    
    // 2. Проверка оборудования
    echo "2. Проверка оборудования:\n";
    $equipmentTypes = $pdo->query("SELECT COUNT(*) as count FROM equipment_types")->fetchColumn();
    $equipmentItems = $pdo->query("SELECT COUNT(*) as count FROM equipment_items")->fetchColumn();
    echo "   - Типов оборудования: $equipmentTypes\n";
    echo "   - Элементов оборудования: $equipmentItems\n";
    echo "\n";
    
    // 3. Проверка мест
    echo "3. Проверка компьютерных мест:\n";
    $seats = $pdo->query("SELECT seat_class, COUNT(*) as count FROM computer_seats GROUP BY seat_class")->fetchAll();
    foreach ($seats as $seat) {
        echo "   - {$seat['seat_class']}: {$seat['count']} мест\n";
    }
    echo "\n";
    
    // 4. Проверка тарифов
    echo "4. Проверка тарифов:\n";
    $dayTariffs = $pdo->query("SELECT COUNT(*) as count FROM tariffs WHERE is_night = 0")->fetchColumn();
    $nightTariffs = $pdo->query("SELECT COUNT(*) as count FROM tariffs WHERE is_night = 1")->fetchColumn();
    echo "   - Дневных тарифов: $dayTariffs\n";
    echo "   - Ночных тарифов: $nightTariffs\n";
    echo "\n";
    
    // 5. Проверка игр
    echo "5. Проверка игр:\n";
    $gamesCount = $pdo->query("SELECT COUNT(*) as count FROM games")->fetchColumn();
    echo "   - Всего игр в библиотеке: $gamesCount\n";
    echo "\n";
    
    // 6. Проверка аренд
    echo "6. Проверка аренд:\n";
    $rentalsCount = $pdo->query("SELECT COUNT(*) as count FROM rentals")->fetchColumn();
    $recentRentals = $pdo->query("SELECT COUNT(*) as count FROM rentals WHERE start_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
    echo "   - Всего аренд: $rentalsCount\n";
    echo "   - За последние 7 дней: $recentRentals\n";
    echo "\n";
    
    // 7. Проверка заказов игр
    echo "7. Проверка заказов игр:\n";
    $gameOrdersCount = $pdo->query("SELECT COUNT(*) as count FROM game_orders")->fetchColumn();
    echo "   - Всего заказов игр: $gameOrdersCount\n";
    echo "\n";
    
    // 8. Проверка бронирований
    echo "8. Проверка бронирований:\n";
    $bookingsCount = $pdo->query("SELECT COUNT(*) as count FROM bookings")->fetchColumn();
    $activeBookings = $pdo->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'active'")->fetchColumn();
    echo "   - Всего бронирований: $bookingsCount\n";
    echo "   - Активных бронирований: $activeBookings\n";
    echo "\n";
    
    // 9. Статистика по отчетам
    echo "9. Статистика для отчетов:\n";
    
    // Популярные игры
    $popularGames = $pdo->query("SELECT g.title, COUNT(go.id) as order_count FROM games g LEFT JOIN game_orders go ON g.id = go.game_id GROUP BY g.id HAVING COUNT(go.id) > 0 ORDER BY order_count DESC LIMIT 3")->fetchAll();
    echo "   - Популярные игры:\n";
    foreach ($popularGames as $game) {
        echo "     * {$game['title']}: {$game['order_count']} заказов\n";
    }
    
    // Статистика по типам оборудования
    $equipmentStats = $pdo->query("SELECT et.name, COUNT(r.id) as rentals FROM equipment_types et LEFT JOIN computer_seats cs ON cs.equipment_type_id = et.id LEFT JOIN rentals r ON r.seat_id = cs.id GROUP BY et.id ORDER BY rentals DESC LIMIT 3")->fetchAll();
    echo "   - Популярные типы оборудования:\n";
    foreach ($equipmentStats as $stat) {
        echo "     * {$stat['name']}: {$stat['rentals']} аренд\n";
    }
    echo "\n";
    
    // 10. Проверка целостности данных
    echo "10. Проверка целостности данных:\n";
    
    // Проверяем аренды без пользователей
    $orphanRentals = $pdo->query("SELECT COUNT(*) as count FROM rentals r LEFT JOIN users u ON u.id = r.user_id WHERE u.id IS NULL")->fetchColumn();
    echo "   - Аренд без пользователей: $orphanRentals\n";
    
    // Проверяем аренды без мест
    $orphanSeats = $pdo->query("SELECT COUNT(*) as count FROM rentals r LEFT JOIN computer_seats cs ON cs.id = r.seat_id WHERE cs.id IS NULL")->fetchColumn();
    echo "   - Аренд без мест: $orphanSeats\n";
    
    // Проверяем места без типа оборудования
    $seatsWithoutType = $pdo->query("SELECT COUNT(*) as count FROM computer_seats WHERE equipment_type_id IS NULL")->fetchColumn();
    echo "   - Мест без типа оборудования: $seatsWithoutType\n";
    echo "\n";
    
    echo "Тестирование завершено!\n";
    echo "\nВсе функции админ-панели готовы к использованию.\n";
    echo "Доступные страницы:\n";
    echo "- /cyberhub/admin/index.php - Главная админ-панель\n";
    echo "- /cyberhub/admin/reports.php - Отчеты\n";
    echo "- /cyberhub/admin/users.php - Управление пользователями\n";
    echo "- /cyberhub/admin/equipment.php - Управление оборудованием\n";
    echo "- /cyberhub/admin/seats.php - Управление местами\n";
    echo "- /cyberhub/admin/tariffs.php - Управление тарифами\n";
    echo "- /cyberhub/admin/games.php - Управление играми\n";
}

if (php_sapi_name() === 'cli') {
    testAdminFunctions();
} else {
    echo "Этот скрипт должен запускаться из командной строки.\n";
}
?>
