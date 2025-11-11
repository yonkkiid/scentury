<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	redirect('/cyberhub/index.php');
}

if (!verify_csrf($_POST['csrf'] ?? null)) {
	$_SESSION['flash_error'] = 'CSRF ошибка';
	redirect('/cyberhub/index.php');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$rating = (int)($_POST['rating'] ?? 0);
$rating = max(1, min(5, $rating ?: 0));

if (!$name || !$email || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$_SESSION['flash_error'] = 'Проверьте корректность полей';
	redirect('/cyberhub/index.php');
}

$pdo = db();
$stmt = $pdo->prepare('INSERT INTO reviews (user_id, author_name, author_email, message, rating, is_approved, created_at) VALUES (?, ?, ?, ?, ?, 0, ?)');
$userId = $_SESSION['user_id'] ?? null;
$stmt->execute([$userId ?: null, $name, $email, $message, $rating ?: null, date('Y-m-d H:i:s')]);

$_SESSION['flash_success'] = 'Спасибо! Ваш отзыв отправлен и появится после модерации.';
redirect('/cyberhub/reviews.php');



