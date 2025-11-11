<?php
// Обработка формы заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $scent_name = trim($_POST['scent_name'] ?? '');
    $selected_notes = trim($_POST['selected_notes'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $agreement = isset($_POST['agreement']);
    
    // Валидация данных
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Имя обязательно для заполнения';
    }
    
    if (empty($phone)) {
        $errors[] = 'Телефон обязателен для заполнения';
    } elseif (!preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $phone)) {
        $errors[] = 'Неверный формат телефона';
    }
    
    if (empty($email)) {
        $errors[] = 'Email обязателен для заполнения';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Неверный формат email';
    }
    
    if (!$agreement) {
        $errors[] = 'Необходимо согласие на обработку персональных данных';
    }
    
    // Если есть ошибки, возвращаем их
    if (!empty($errors)) {
        $response = [
            'success' => false,
            'errors' => $errors
        ];
        echo json_encode($response);
        exit;
    }
    
    // Подготовка данных для отправки
    $order_data = [
        'date' => date('Y-m-d H:i:s'),
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'scent_name' => $scent_name,
        'selected_notes' => $selected_notes,
        'message' => $message
    ];
    
    // Сохранение заказа в файл
    $orders_file = 'orders.txt';
    $order_line = json_encode($order_data, JSON_UNESCAPED_UNICODE) . "\n";
    file_put_contents($orders_file, $order_line, FILE_APPEND | LOCK_EX);
    
    // Отправка email (заглушка - в реальном проекте нужно настроить SMTP)
    $to = 'orders@scentury.ru';
    $subject = 'Новый заказ от ' . $name;
    $email_message = "
Новый заказ на создание аромата:

Имя: {$name}
Телефон: {$phone}
Email: {$email}
Название аромата: {$scent_name}
Выбранные ноты: {$selected_notes}
Дополнительные пожелания: {$message}

Дата заказа: " . date('Y-m-d H:i:s');
    
    $headers = 'From: noreply@scentury.ru' . "\r\n" .
               'Reply-To: ' . $email . "\r\n" .
               'Content-Type: text/plain; charset=UTF-8' . "\r\n";
    
    // В реальном проекте раскомментировать:
    // mail($to, $subject, $email_message, $headers);
    
    // Успешный ответ
    $response = [
        'success' => true,
        'message' => 'Ваша заявка успешно отправлена! Мы свяжемся с вами в ближайшее время.'
    ];
    
    echo json_encode($response);
    exit;
}

// Если запрос не POST, перенаправляем на главную
header('Location: index.php');
exit;
?>

