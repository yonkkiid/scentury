<?php
$success = '';
$error = '';

if (isset($_POST['send'])) {
    $to = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $error = "Неверный формат email адреса";
    } else {
        $headers = "From: no-reply@yourdomain.com\r\n";
        $headers .= "Reply-To: no-reply@yourdomain.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message, $headers)) {
            $success = "Письмо успешно отправлено на адрес: " . htmlspecialchars($to);
        } else {
            $error = "Ошибка при отправке письма. Проверьте настройки сервера.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проверка отправки письма</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Тест отправки email</h1>
        
        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="email-form">
            <div class="form-group">
                <label for="email">Email получателя:</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="subject">Тема письма:</label>
                <input type="text" id="subject" name="subject" required 
                       value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="message">Текст сообщения:</label>
                <textarea id="message" name="message" rows="5" required><?php 
                    echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; 
                ?></textarea>
            </div>
            
            <button type="submit" name="send" class="submit-btn">Отправить письмо</button>
        </form>
    </div>
</body>
</html>