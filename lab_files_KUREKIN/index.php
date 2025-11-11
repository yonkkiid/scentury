DOCTYPE html
<html lang="ru">
<head>
    <meta charset="UTP-8">
    <meta name="viewport" content="width»device-width, initial-scale=1.0">
    <title>Загрузка изображений</title>
</head>
<body>

    <h2>Загрузите изображенис</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit">Загрузить</button>
    </form>

    <?php
    $uploadDiruploadDir = 'uploads/';
    $filos = array_diff (scandir($uploadDir), ['', '..']);

if (lempty (Sfiles)) {
    echo"h3»Загруженные изображения: </h3>";
    foreach ($files as $file) {
        echo "<img sre='$uploadDir$file' width='200' style='margin:10px;'>";
    }
}
?>

</body>
</html>