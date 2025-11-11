<?php require_once __DIR__ . '/partials/header.php'; ?>
<h2>Турниры</h2>
<div class="card" style="padding:0;overflow:hidden">
	<img src="/cyberhub/images/tournaments.jpg" alt="Турниры в CyberHub" class="tournament-image">
</div>

<div class="grid cols-3" style="margin-top:16px">
	<div class="card">
		<h3>CyberHub Cup — CS2</h3>
		<p class="muted">Дата: 12–13 октября • Формат: 5v5 • Призовой фонд: 50 000 ₽</p>
		<ul>
			<li>Групповой этап + single-elimination плей-офф</li>
			<li>Правила FACEIT, античит — на стороне сервера</li>
			<li>Регистрация команды до 10 октября включительно</li>
		</ul>
		<div style="margin-top:12px">
			<a href="/cyberhub/tournament_register.php?game=CS2" class="btn">Зарегистрировать команду</a>
		</div>
	</div>
	<div class="card">
		<h3>Valorant Night Clash</h3>
		<p class="muted">Дата: 26 октября • Формат: 5v5 • Призовой фонд: 30 000 ₽</p>
		<ul>
			<li>Single-elimination BO3, финал BO5</li>
			<li>Соблюдение правил Riot Competitive</li>
			<li>Слотов ограничено, поспешите!</li>
		</ul>
		<div style="margin-top:12px">
			<a href="/cyberhub/tournament_register.php?game=Valorant" class="btn secondary">Записаться</a>
		</div>
	</div>
	<div class="card">
		<h3>FIFA 24 1v1 Open</h3>
		<p class="muted">Дата: 2 ноября • Формат: 1v1 • Призовой фонд: 15 000 ₽</p>
		<ul>
			<li>Double-elimination, матчи BO3</li>
			<li>Геймпады клуба или свои</li>
			<li>Регистрация — на стойке и онлайн</li>
		</ul>
		<div style="margin-top:12px">
			<a href="/cyberhub/tournament_register.php?game=FIFA 24" class="btn">Участвовать</a>
		</div>
	</div>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>


