<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($login) || empty($password)) {
        echo "Пожалуйста, заполните все поля.";
        exit;
    }

  
    $stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Пользователь с таким логином уже существует.";
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $login, $password);

    if ($stmt->execute()) {
        echo " Регистрация прошла успешно!<br>";
        echo '<a href="index.html">Вернуться к авторизации</a>';
    } else {
        echo " Ошибка при регистрации. Повторите попытку позже.";
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Неверный метод запроса.";
}
?>
