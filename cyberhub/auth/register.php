<?php
require_once __DIR__ . '/../includes/auth.php';

$error = null;
$ok = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!verify_csrf($_POST['csrf'] ?? null)) {
		$error = 'CSRF ошибка';
	} else {
		$email = trim($_POST['email'] ?? '');
		$name = trim($_POST['name'] ?? '');
		$pass = $_POST['password'] ?? '';
		$pass2 = $_POST['password2'] ?? '';
		if (!$email || !$name || !$pass) {
			$error = 'Заполните все поля';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = 'Некорректный email';
		} elseif (!preg_match('/^[A-Za-z\s\'-]{2,}$/', $name)) {
			$error = 'Имя должно быть на английском (латиница), минимум 2 символа';
		} elseif ($pass !== $pass2) {
			$error = 'Пароли не совпадают';
		} else {
			$res = register_user($email, $name, $pass);
			if ($res['ok']) {
				$ok = 'Регистрация успешна. Теперь войдите.';
			} else {
				$error = $res['error'] ?? 'Ошибка регистрации';
			}
		}
	}
}
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Регистрация</h2>
<?php if($error): ?><div class="notice" style="border-color:#533"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if($ok): ?><div class="notice" style="border-color:#353"><?php echo htmlspecialchars($ok); ?></div><?php endif; ?>
<div class="card" style="max-width:520px">
	<form method="post" data-validate>
		<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
		<label>Имя</label>
		<input type="text" class="input" name="name" pattern="^[A-Za-z\s'-]{2,}$" title="Только латинские буквы, пробел, - и ' ; минимум 2 символа" required />
		<div style="font-size:12px;color:#777;margin-top:4px">Только латинские буквы, пробел, дефис и апостроф. Минимум 2 символа.</div>
		<label>Email</label>
		<input type="email" class="input" name="email" inputmode="email" autocomplete="email" title="Укажите действительный адрес почты" required />
		<div style="font-size:12px;color:#777;margin-top:4px">Укажите действительный адрес. Пример: name@example.com</div>
		<label>Пароль</label>
		<input type="password" class="input" name="password" minlength="8" title="Минимум 8 символов" required />
		<div style="font-size:12px;color:#777;margin-top:4px">Минимум 8 символов. Рекомендуется использовать буквы и цифры.</div>
		<label>Повторите пароль</label>
		<input type="password" class="input" name="password2" minlength="8" title="Минимум 8 символов" required />
		<div style="margin-top:12px">
			<button class="btn" type="submit">Создать аккаунт</button>
		</div>
	</form>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>



