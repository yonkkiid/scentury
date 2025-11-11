<!DOCTYPE htmi>
<html lang="en">
<nead>
    <meta charset="UTF-8">
    <title>COOKIE</title>
</head> 
<body>
    <hl>Тестовый пример cookie.</hl>
    <h2>Список активных cookies:</h2>
<?php
if (count ($_COOKIE) > 0) {
    echo "<ul>";
    foreach ($_COOKIE as $key => $value) {
        echo "<li>strong>" . htnlspecialchars ($key) . ":</strong>". htmlspecialchars ($value) • "</li>";
    }
    echo "</ul>";
 } else {
    echo "‹р›Нет активных cookies.</p>";
}
?>

<h2>Действия: </h2>|
<ul>
        <li><a href="set_cookie.php">Установить cookie</a>/li>
        <li><a href="update_cookie.php">Обновить cookie</a>/li>
        <li><a href="delete_cookie.php">Удалить cookie</a>/li>
    </u1>
</body> 
</html>