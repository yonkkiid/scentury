<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Установить Cookie</title>
</head>
<body>
    <h1>Установить Cookie</h1>
    <form method="post">
        <label for="name">Имя cookie:</label>
        <input type="text" id="name" name="name" required><br>

    <label for="value">Значение cookie: </label›
    <input type="text" id="value" name="value" required><br>

    <label for="lifetime">Срок жизни (в секундах) : </label>
    <input type="number" id="lifetime" name="lifetime" required>‹br›

    <button type="submit">Установить</button>
</form>

<?php
if ($_SERVER[ 'REQUEST_METHOD' ] === ' POST') {
$name = $_POST [' name'];
$value = $_POST [' value'];
$lifetime = (int) $_POST| ['lifetime'];

setcookie ($name, $value, time () + $lifetime, "/") ; 
echo "<p>Cookie '$name' установлено.</р>";
}
?>
    <a href="index.php">Вернуться на главную</а>
</body> 
</html>