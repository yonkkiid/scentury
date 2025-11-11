<?php require_once __DIR__ . '/partials/header.php'; ?>
<h2>Цены</h2>

<div class="pricing-grid">
	<div class="pricing-card">
		<div class="pricing-head">
			<h3 class="pricing-title">Почасовая</h3>
			<span class="pricing-badge">Будни/Выходные</span>
		</div>
		<div class="pricing-body">
			<p class="price">200–300 ₽<small>за час</small></p>
			<ul class="features">
				<li>Будни до 17:00 — 200 ₽/час</li>
				<li>Будни после 17:00 — 250 ₽/час</li>
				<li>Выходные/праздники — 300 ₽/час</li>
				<li>Поминутная тарификация</li>
			</ul>
		</div>
		<div class="pricing-foot">
			<a class="btn full" href="/cyberhub/booking.php">Забронировать</a>
		</div>
	</div>

	<div class="pricing-card popular">
		<div class="pricing-head">
			<h3 class="pricing-title">Абонемент 10 часов</h3>
			<span class="pricing-badge">Популярно</span>
		</div>
		<div class="pricing-body">
			<p class="price">1 800 ₽<small>разово</small></p>
			<ul class="features">
				<li>Гибкое использование сессиями</li>
				<li>Действует 60 дней</li>
				<li>Передача друзьям — разрешена</li>
				<li>Экономия 200 ₽</li>
			</ul>
		</div>
		<div class="pricing-foot">
			<a class="btn full" href="/cyberhub/booking.php">Купить</a>
		</div>
	</div>

	<div class="pricing-card">
		<div class="pricing-head">
			<h3 class="pricing-title">Ночная сессия</h3>
			<span class="pricing-badge">23:00–08:00</span>
		</div>
		<div class="pricing-body">
			<p class="price">1 200 ₽<small>за ночь</small></p>
			<ul class="features">
				<li>Резерв места на всю ночь</li>
				<li>Горячие напитки на стойке</li>
				<li>Лаунж-зона для перерыва</li>
			</ul>
		</div>
		<div class="pricing-foot">
			<a class="btn full" href="/cyberhub/booking.php">Забронировать</a>
		</div>
	</div>
</div>

<div class="pricing-grid" style="margin-top:16px">
	<div class="pricing-card">
		<div class="pricing-head">
			<h3 class="pricing-title">Скидки</h3>
			<span class="pricing-badge">Акции</span>
		</div>
		<div class="pricing-body">
			<ul class="features">
				<li>Студентам — 10% по будням до 17:00</li>
				<li>Имениннику — 20% в день рождения</li>
				<li>Группам от 5 человек — 10% на почасовую</li>
			</ul>
			<p class="muted">Скидки не суммируются. Действуют при наличии мест.</p>
		</div>
	</div>
	<div class="pricing-card">
		<div class="pricing-head">
			<h3 class="pricing-title">Дополнительно</h3>
			<span class="pricing-badge">Сервис</span>
		</div>
		<div class="pricing-body">
			<ul class="features">
				<li>Аренда гарнитуры — бесплатно</li>
				<li>Игровые аккаунты — по запросу</li>
				<li>Приватная комната — +200 ₽/час</li>
			</ul>
		</div>
	</div>
	<div class="pricing-card">
		<div class="pricing-head">
			<h3 class="pricing-title">Условия</h3>
			<span class="pricing-badge">Важно</span>
		</div>
		<div class="pricing-body">
			<ul class="features">
				<li>Предоплата при бронировании — 100 ₽ за место</li>
				<li>Отмена за 2 часа — предоплата возвращается</li>
				<li>Опоздание более 30 минут — бронь может быть снята</li>
			</ul>
		</div>
	</div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>


