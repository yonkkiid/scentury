<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Удалить Cookie</title>
</head> 
<body>
    <hl>Удалить Cookie</h1>
    <form method="post">
        <label for="name">Имя cookie:</label>
        <input type="text" id="name" name="name" required><br>

        <button type="submit" >Удалить</button>
</form>

<?php
if ($_SERVER[' REQUEST_METHOD' ] === 'POST') {
$name = $_POST [ 'name'];

    if (isset ($_COOKIE [$name])) {
        setcookie ($name, "", time () - 3600, "/"); 
        echo "<p>Cookie '$name' удалено. </р>";
    } else {
        echo "<p>Cookie '$name' не найдено. </p>";
    }
}
?>

    <a href="index.php">Вернуться на главную</а>
</body> 
</html>