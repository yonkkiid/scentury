<?php
require_once __DIR__ . '/../includes/auth.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!verify_csrf($_POST['csrf'] ?? null)) {
		$error = 'CSRF ошибка';
	} else {
		$res = login_user(trim($_POST['email'] ?? ''), $_POST['password'] ?? '');
		if ($res['ok']) {
			redirect('/cyberhub/index.php');
		} else {
			$error = $res['error'] ?? 'Ошибка входа';
		}
	}
}
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Вход</h2>
<?php if($error): ?><div class="notice" style="border-color:#533"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<div class="card" style="max-width:460px">
	<form method="post" data-validate>
		<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
		<label>Email</label>
		<input type="email" class="input" name="email" required />
		<label>Пароль</label>
		<input type="password" class="input" name="password" required />
		<div style="margin-top:12px">
			<button class="btn" type="submit">Войти</button>
		</div>
	</form>

</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>







