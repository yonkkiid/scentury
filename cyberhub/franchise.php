<?php require_once __DIR__ . '/partials/header.php'; ?>
<h2>Франшиза</h2>
<div class="grid cols-2">
	<div class="card">
		<h3>Почему CyberHub</h3>
		<ul>
			<li>Готовые стандарты клуба</li>
			<li>Помощь с запуском и маркетингом</li>
			<li>Обучение персонала</li>
		</ul>
	</div>
	<div class="card">
		<h3>Оставьте заявку</h3>
		<form data-validate>
			<label>Имя</label>
			<input class="input" required />
			<label>Email</label>
			<input type="email" class="input" required />
			<label>Город</label>
			<input class="input" required />
			<div style="margin-top:12px"><button class="btn" type="button" onclick="alert('Спасибо! Мы свяжемся с вами.')">Отправить</button></div>
		</form>
	</div>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>











