<?php
require_once __DIR__ . '/../includes/db.php';

function testReports() {
    $pdo = db();
    
    echo "Тестирование отчетов админ-панели...\n\n";
    
    // 1. Тест отчета по оборудованию по типу
    echo "1. Тест отчета 'equipment_by_type':\n";
    $stmt = $pdo->prepare("SELECT ei.*, et.name AS type_name FROM equipment_items ei JOIN equipment_types et ON et.id = ei.type_id WHERE et.name = ?");
    $stmt->execute(['GPU']);
    $results = $stmt->fetchAll();
    echo "   Найдено GPU: " . count($results) . " шт.\n";
    foreach (array_slice($results, 0, 3) as $item) {
        echo "   - " . $item['name'] . " (" . $item['specs'] . ")\n";
    }
    echo "\n";
    
    // 2. Тест отчета всех клиентов
    echo "2. Тест отчета 'all_clients':\n";
    $results = $pdo->query("SELECT id, email, name, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
    echo "   Всего клиентов: " . count($results) . "\n";
    foreach (array_slice($results, 0, 3) as $user) {
        echo "   - " . $user['name'] . " (" . $user['email'] . ") - " . $user['role'] . "\n";
    }
    echo "\n";
    
    // 3. Тест отчета всех игр
    echo "3. Тест отчета 'all_games':\n";
    $results = $pdo->query("SELECT id, title FROM games ORDER BY title")->fetchAll();
    echo "   Всего игр: " . count($results) . "\n";
    foreach (array_slice($results, 0, 5) as $game) {
        echo "   - " . $game['title'] . "\n";
    }
    echo "\n";
    
    // 4. Тест отчета мест по типу оборудования
    echo "4. Тест отчета 'seats_by_equipment_type':\n";
    $stmt = $pdo->prepare("SELECT cs.*, et.name AS equipment_type FROM computer_seats cs LEFT JOIN equipment_types et ON et.id = cs.equipment_type_id WHERE et.name = ?");
    $stmt->execute(['GPU']);
    $results = $stmt->fetchAll();
    echo "   Мест с GPU: " . count($results) . "\n";
    foreach (array_slice($results, 0, 3) as $seat) {
        echo "   - " . $seat['label'] . " (класс: " . $seat['seat_class'] . ")\n";
    }
    echo "\n";
    
    // 5. Тест отчета ночных тарифов
    echo "5. Тест отчета 'night_tariff_visitors':\n";
    $stmt = $pdo->prepare("SELECT r.*, u.name, u.email FROM rentals r JOIN users u ON u.id = r.user_id JOIN tariffs t ON t.id = r.tariff_id JOIN computer_seats cs ON cs.id = r.seat_id WHERE t.is_night = 1");
    $stmt->execute();
    $results = $stmt->fetchAll();
    echo "   Ночных аренд: " . count($results) . "\n";
    foreach (array_slice($results, 0, 3) as $rental) {
        echo "   - " . $rental['name'] . " с " . $rental['start_at'] . " по " . $rental['end_at'] . "\n";
    }
    echo "\n";
    
    // 6. Тест отчета игр заказанных более 2 раз
    echo "6. Тест отчета 'games_ordered_more_than_two':\n";
    $results = $pdo->query("SELECT g.title, COUNT(*) cnt FROM game_orders go JOIN games g ON g.id = go.game_id GROUP BY go.game_id HAVING COUNT(*) > 2 ORDER BY cnt DESC")->fetchAll();
    echo "   Популярных игр: " . count($results) . "\n";
    foreach ($results as $game) {
        echo "   - " . $game['title'] . " (" . $game['cnt'] . " заказов)\n";
    }
    echo "\n";
    
    // 7. Тест отчета свободных компьютеров
    echo "7. Тест отчета 'free_computers_at_time':\n";
    $testTime = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("SELECT cs.* FROM computer_seats cs WHERE cs.id NOT IN (SELECT seat_id FROM rentals r WHERE ? BETWEEN r.start_at AND r.end_at) ORDER BY cs.label");
    $stmt->execute([$testTime]);
    $results = $stmt->fetchAll();
    echo "   Свободных мест сейчас: " . count($results) . "\n";
    foreach (array_slice($results, 0, 3) as $seat) {
        echo "   - " . $seat['label'] . " (класс: " . $seat['seat_class'] . ")\n";
    }
    echo "\n";
    
    // 8. Тест отчета количества аренд по типу
    echo "8. Тест отчета 'rented_count_by_type':\n";
    $results = $pdo->query("SELECT et.name AS type_name, COUNT(*) AS rented FROM rentals r JOIN computer_seats cs ON cs.id = r.seat_id LEFT JOIN equipment_types et ON et.id = cs.equipment_type_id GROUP BY et.name ORDER BY rented DESC")->fetchAll();
    echo "   Статистика по типам:\n";
    foreach ($results as $stat) {
        echo "   - " . ($stat['type_name'] ?: 'Неизвестный тип') . ": " . $stat['rented'] . " аренд\n";
    }
    echo "\n";
    
    // 9. Тест отчета аренд клиента за период
    echo "9. Тест отчета 'client_rentals_in_period':\n";
    $user = $pdo->query("SELECT email FROM users WHERE role = 'user' LIMIT 1")->fetch();
    if ($user) {
        $from = date('Y-m-d', strtotime('-30 days'));
        $to = date('Y-m-d');
        $stmt = $pdo->prepare("SELECT r.*, cs.label FROM rentals r JOIN users u ON u.id = r.user_id JOIN computer_seats cs ON cs.id = r.seat_id WHERE u.email = ? AND r.start_at >= ? AND r.end_at <= ? ORDER BY r.start_at DESC");
        $stmt->execute([$user['email'], "$from 00:00:00", "$to 23:59:59"]);
        $results = $stmt->fetchAll();
        echo "   Аренд для " . $user['email'] . " за последние 30 дней: " . count($results) . "\n";
    }
    echo "\n";
    
    // 10. Тест отчета тарифов по дням и классам мест
    echo "10. Тест отчета 'tariffs_in_day_by_seat_class':\n";
    $testDate = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT t.name, COUNT(*) cnt FROM rentals r JOIN tariffs t ON t.id = r.tariff_id JOIN computer_seats cs ON cs.id = r.seat_id WHERE DATE(r.start_at) = ? GROUP BY t.id ORDER BY cnt DESC");
    $stmt->execute([$testDate]);
    $results = $stmt->fetchAll();
    echo "   Тарифы на сегодня: " . count($results) . "\n";
    foreach ($results as $tariff) {
        echo "   - " . $tariff['name'] . ": " . $tariff['cnt'] . " использований\n";
    }
    echo "\n";
    
    echo "Все отчеты протестированы успешно!\n";
}

if (php_sapi_name() === 'cli') {
    testReports();
} else {
    echo "Этот скрипт должен запускаться из командной строки.\n";
}
?>








