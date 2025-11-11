<?php
require_once __DIR__ . '/../includes/auth.php';
$u = current_user();
?>
<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>CyberHub</title>
	<link rel="stylesheet" href="/cyberhub/assets/styles.css" />
	<link rel="icon" href="data:," />
</head>
<body>
	<header class="header">
		<div class="container nav">
			<div class="brand"><img src="/cyberhub/images/logo.png" alt="CyberHub" class="logo" /> CYBERHUB</div>
			<a href="/cyberhub/index.php">Главная</a>
			<a href="/cyberhub/booking.php">Бронирование</a>
			<a href="/cyberhub/gear.php">Железо</a>
			<a href="/cyberhub/prices.php">Цены</a>
			<a href="/cyberhub/rules.php">Правила клуба</a>
			<a href="/cyberhub/tournaments.php">Турниры</a>
			<a href="/cyberhub/reviews.php">Отзывы</a>
			<a href="/cyberhub/franchise.php">Франшиза</a>
			<div class="spacer"></div>
			<?php if($u): ?>
				<a href="/cyberhub/account.php" class="badge">Личный кабинет</a>
				<?php if(($u['role'] ?? 'user') === 'admin'): ?>
					<a href="/cyberhub/admin/index.php" class="badge">Админ</a>
				<?php endif; ?>
				<a href="/cyberhub/auth/logout.php" class="btn ghost">Выход</a>
			<?php else: ?>
				<a href="/cyberhub/auth/login.php" class="btn">Войти</a>
				<a href="/cyberhub/auth/register.php" class="btn secondary">Регистрация</a>
			<?php endif; ?>
		</div>
	</header>
	<main class="container">


