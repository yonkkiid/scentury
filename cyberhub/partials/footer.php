	</main>
	<footer class="footer">
		<div class="container">
			<div class="footer-grid">
				<div class="footer-brand">
					<div class="brand">CYBERHUB</div>
					<div class="muted">Игровой клуб</div>
				</div>
				<div class="footer-contacts">
					<div class="footer-title">Контакты</div>
					<ul class="contact-list">
						<li><span>Тел.:</span> <a href="tel:+79990001122">+7 (999) 000-11-22</a></li>
						<li><span>Email:</span> <a href="mailto:info@cyberhub.local">info@cyberhub.local</a></li>
						<li><span>Адрес:</span> г. Москва, киберпроспект 1</li>
					</ul>
					<div class="socials">
						<a href="#" aria-label="VK">VK</a>
						<a href="#" aria-label="Telegram">TG</a>
						<a href="#" aria-label="YouTube">YT</a>
					</div>
				</div>
				<div class="footer-actions">
					<button class="btn full" data-open-feedback>Обратная связь</button>
				</div>
			</div>
			<div class="footer-copy">© <?php echo date('Y'); ?> CyberHub. Все права защищены.</div>
		</div>
	</footer>

	<!-- Feedback Modal -->
	<div class="modal" id="feedback-modal" aria-hidden="true" role="dialog" aria-labelledby="feedback-title">
		<div class="modal-backdrop" data-close-feedback></div>
		<div class="modal-dialog card">
			<div class="modal-head">
				<h3 id="feedback-title">Обратная связь</h3>
				<button class="btn ghost" data-close-feedback>×</button>
			</div>
            <form class="modal-body" data-validate action="/cyberhub/reviews_submit.php" method="post">
				<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
				<div class="form-row">
					<div>
						<label for="fb-name">Имя</label>
						<input id="fb-name" class="input" name="name" required />
					</div>
					<div>
						<label for="fb-email">Email</label>
						<input id="fb-email" class="input" type="email" name="email" required />
					</div>
				</div>
                <div>
                    <label>Оценка</label>
                    <div class="rating-input" style="display:flex;gap:6px;align-items:center">
                        <label><input type="radio" name="rating" value="5" required /> ★★★★★</label>
                        <label><input type="radio" name="rating" value="4" /> ★★★★☆</label>
                        <label><input type="radio" name="rating" value="3" /> ★★★☆☆</label>
                        <label><input type="radio" name="rating" value="2" /> ★★☆☆☆</label>
                        <label><input type="radio" name="rating" value="1" /> ★☆☆☆☆</label>
                    </div>
                </div>
				<div>
					<label for="fb-message">Сообщение</label>
					<textarea id="fb-message" class="input" name="message" rows="4" required></textarea>
				</div>
				<div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px">
					<button type="button" class="btn ghost" data-close-feedback>Отмена</button>
					<button type="submit" class="btn">Отправить</button>
				</div>
			</form>
		</div>
	</div>
	<script src="/cyberhub/assets/app.js"></script>
</body>
</html>


