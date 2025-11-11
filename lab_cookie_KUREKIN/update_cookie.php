<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Обновить Cookie</title>
</head>
<body>
    <h1>Обновить Cookie</h1>
    <form method="post">
        <label for="name">Имя cookie: </label>
        <input type="text" id="name" name="name" required><br>

        <label for="value">Новое значение соіе: </label>
        <input type="text" id="value" name="value" required><br>

        <button type="submit" >Обновить</button>
</form>
<?php
if ($_SERVER [' REQUEST_METHOD' ] == 'POST') {
$name = $_POST ['name'];
$value = $_POST [' value '];

if (isset ($_COOKIE [$name])) {
    setcookie ($name, $value, time () + (7 * 24 * 60 * 60), "/");
    echo "<p>Cookie '$name' обновлено. </p>";
} else {
echo
    "<p>Cookie '$name' не найдено. </р>";
    }
}
?>
<a href="index.php">Вернуться на главную</а>
</body> 
</html>