<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pdo = db();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!verify_csrf($_POST['csrf'] ?? null)) {
		$errors[] = 'CSRF ошибка';
	} else {
		$action = $_POST['action'] ?? '';
		$id = (int)($_POST['id'] ?? 0);
		if ($action === 'delete' && $id > 0) {
			$del = $pdo->prepare('DELETE FROM reviews WHERE id = ?');
			$del->execute([$id]);
			$success = 'Отзыв удален';
		} elseif (($action === 'approve' || $action === 'unapprove') && $id > 0) {
			$val = $action === 'approve' ? 1 : 0;
			$upd = $pdo->prepare('UPDATE reviews SET is_approved = ? WHERE id = ?');
			$upd->execute([$val, $id]);
			$success = $action === 'approve' ? 'Отзыв одобрен' : 'Отзыв скрыт';
		}
	}
}

$rows = $pdo->query('SELECT r.*, u.email as user_email FROM reviews r LEFT JOIN users u ON u.id = r.user_id ORDER BY created_at DESC')->fetchAll();
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Админ — Отзывы</h2>

<div class="card" style="margin-bottom:16px">
	<a class="btn" href="/cyberhub/admin/index.php">← Назад к админ-панели</a>
</div>

<?php if($errors): ?><div class="notice" style="border-color:#533"><?php foreach($errors as $e){ echo '<div>'.htmlspecialchars($e).'</div>'; } ?></div><?php endif; ?>
<?php if($success): ?><div class="notice" style="border-color:#353"><div><?php echo htmlspecialchars($success); ?></div></div><?php endif; ?>

<div class="card">
	<h3>Все отзывы</h3>
	<table class="table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Автор</th>
				<th>Email</th>
				<th>Пользователь</th>
				<th>Оценка</th>
				<th>Статус</th>
				<th>Сообщение</th>
				<th>Дата</th>
				<th>Действия</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($rows as $r): ?>
			<tr>
				<td>#<?php echo (int)$r['id']; ?></td>
				<td><?php echo htmlspecialchars($r['author_name']); ?></td>
				<td><?php echo htmlspecialchars($r['author_email']); ?></td>
				<td><?php echo htmlspecialchars($r['user_email'] ?: '—'); ?></td>
				<td><?php echo $r['rating'] ? str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5-(int)$r['rating']) : '—'; ?></td>
				<td><?php echo (int)$r['is_approved'] === 1 ? '<span class="status-active">Одобрен</span>' : '<span class="status-moved">Ожидает</span>'; ?></td>
				<td><?php echo nl2br(htmlspecialchars($r['message'])); ?></td>
				<td><?php echo htmlspecialchars($r['created_at']); ?></td>
				<td>
					<form method="post" onsubmit="return confirm('Удалить отзыв?')">
						<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
						<input type="hidden" name="action" value="delete" />
						<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
						<button class="btn ghost">Удалить</button>
					</form>
					<?php if ((int)$r['is_approved'] === 1): ?>
					<form method="post" style="display:inline-block">
						<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
						<input type="hidden" name="action" value="unapprove" />
						<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
						<button class="btn ghost">Скрыть</button>
					</form>
					<?php else: ?>
					<form method="post" style="display:inline-block">
						<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
						<input type="hidden" name="action" value="approve" />
						<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
						<button class="btn">Одобрить</button>
					</form>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>



