<?php
if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
    $uploadDir = 'uploads/';
    $allowedTypes = ['image/jpeg','image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    if (!isset ($_FILES ['image']) || $_FILES ['image'] ['error'] !== UPLOAD_ERR_OK) {
        die('Ошибка загрузки файла.');
    }

    $fileTmp = $_FILES ['image'] [' tmp_name'];
    $fileName = basename ($_FILES [' image'] [' name ']);
    $faliSize = $_FILES ['image'] ['size'];
    $fileType = mime_content_type ($fileTmp);

    if ($fileSize > $maxSize) {
            die ('файл слишком большой! Максимальный размер - 2мв.') ;
    }
    if (!in_array ($fileType, $allowedTypes)) {
        die('Недопустимый формат файла. Разрешены только JPEG,PNG,GIF,');
    }
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION)
    $newFileName = uniqid('img_', true) . '.' . $fileExt;

    if (move_uploaded_file($fileImp, $uploadDir . $newFileName) ) {
        echo"файл успешно загружен! ‹br><br> <a href='index.php'>Вернуться</а>";
    } else {
        die ('Ошибка при сохранении файла.') ; 
    }
}
