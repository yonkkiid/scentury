<?php
require_once __DIR__ . '/includes/auth.php';
require_auth();
$u = current_user();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'cancel') {
	if (verify_csrf($_POST['csrf'] ?? null)) {
		$id = (int)($_POST['id'] ?? 0);
		$upd = $pdo->prepare('UPDATE bookings SET status = "cancelled", updated_at = ? WHERE id = ? AND user_id = ?');
		$upd->execute([date('Y-m-d H:i:s'), $id, $u['id']]);
		redirect('/cyberhub/account.php');
	}
}

$stmt = $pdo->prepare('SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_date DESC, booking_time DESC');
$stmt->execute([$u['id']]);
$bookings = $stmt->fetchAll();
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<h2>Личный кабинет</h2>
<div class="card">
	<h3>Мои брони</h3>
	<table class="table">
		<thead>
			<tr><th>Дата</th><th>Время</th><th>Длит.</th><th>Статус</th><th></th></tr>
		</thead>
		<tbody>
		<?php foreach($bookings as $b): ?>
			<tr>
				<td><?php echo htmlspecialchars($b['booking_date']); ?></td>
				<td><?php echo htmlspecialchars($b['booking_time']); ?></td>
				<td><?php echo (int)$b['duration_minutes']; ?> мин</td>
				<td class="status-<?php echo htmlspecialchars($b['status']); ?>"><?php echo htmlspecialchars($b['status']); ?></td>
				<td>
					<?php if($b['status'] === 'active'): ?>
						<form method="post" style="display:inline">
							<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
							<input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>" />
							<input type="hidden" name="action" value="cancel" />
							<button class="btn ghost">Отменить</button>
						</form>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>


