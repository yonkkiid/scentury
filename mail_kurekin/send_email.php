<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = $_POST ["to"]
    $subject = $_POST ["subject"];
    $message = $_POST ["message";]
    $headers = "From:test@localhost\r\n";

    $result = mail ($to, $subject, $message, $headers);

    if ($result){
        echo "<div class ='success'>Письмо успешно отправлено!
                Проверьте <a href='http://localhost:8025' target='_blank'>MailHog</a>.
                </div>
             ";
    } else {
        echo "<div class='error'>Ошибка отправки. Проверьте:</div>";
    }
}
?>