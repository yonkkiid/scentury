<?php
// Старт сессии (если понадобится сохранять логин)
session_start();

// Подключение к БД
require_once 'db.php';

// Проверка, что форма отправлена методом POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Получение данных из формы
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    // Экранирование входных данных для безопасности
    $login = $conn->real_escape_string(trim($login));
    $password = $conn->real_escape_string(trim($password));

    // Проверка на пустые значения
    if (empty($login) || empty($password)) {
        echo "Пожалуйста, заполните все поля.";
        exit;
    }

    // Запрос к БД для проверки логина и пароля
    $sql = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Авторизация успешна
        $user = $result->fetch_assoc();

        // Пример: можно сохранить пользователя в сессию
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];

        echo "Авторизация прошла успешно. Добро пожаловать, {$user['login']}!";
        // Или редирект на главную:
        // header("Location: index.php");
        // exit;
    } else {
        echo "Неверный логин или пароль.";
    }

    // Закрытие соединения
    $conn->close();
} else {
    echo "Недопустимый метод запроса.";
}
?>
