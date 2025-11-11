<?php require_once __DIR__ . '/partials/header.php'; ?>
<section class="hero card">
	<h1>Добро пожаловать в CyberHub</h1>
	<p>Игровой клуб с лучшим железом, турнирами и удобным бронированием мест.</p>
	<div style="margin-top:12px;display:flex;gap:12px;flex-wrap:wrap">
		<a class="btn" href="/cyberhub/booking.php">Забронировать</a>
		<a class="btn secondary" href="/cyberhub/gear.php">Посмотреть железо</a>
	</div>
</section>

<section class="grid cols-3" style="margin-top:16px">
	<div class="card">
		<h3>Железо</h3>
		<p>Топовые ПК, мониторы 240Hz, механические клавиатуры и игровые кресла.</p>
		<a href="/cyberhub/gear.php">Подробнее →</a>
	</div>
	<div class="card">
		<h3>Цены</h3>
		<p>Гибкие тарифы: почасовая оплата, абонементы и пакеты.</p>
		<a href="/cyberhub/prices.php">Подробнее →</a>
	</div>
	<div class="card">
		<h3>Турниры</h3>
		<p>Регулярные соревнования с призами. Следите за расписанием.</p>
		<a href="/cyberhub/tournaments.php">Подробнее →</a>
	</div>
</section>

<section class="card" style="margin-top:16px">
	<h3>Франшиза</h3>
	<p>Откройте CyberHub в вашем городе. Мы поможем с запуском и операциями.</p>
	<a class="btn ghost" href="/cyberhub/franchise.php">Узнать больше</a>
</section>
<?php require_once __DIR__ . '/partials/footer.php'; ?>


