<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pdo = db();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!verify_csrf($_POST['csrf'] ?? null)) {
		$errors[] = 'CSRF ошибка';
	} else {
		$action = $_POST['action'] ?? '';
		$id = (int)($_POST['id'] ?? 0);
		if ($action === 'cancel') {
			$upd = $pdo->prepare('UPDATE bookings SET status = "cancelled", updated_at = ? WHERE id = ?');
			$upd->execute([date('Y-m-d H:i:s'), $id]);
		} elseif ($action === 'move') {
			$newDate = trim($_POST['date'] ?? '');
			$newTime = trim($_POST['time'] ?? '');
			if ($newDate && $newTime) {
				$upd = $pdo->prepare('UPDATE bookings SET booking_date = ?, booking_time = ?, status = "moved", updated_at = ? WHERE id = ?');
				$upd->execute([$newDate, $newTime, date('Y-m-d H:i:s'), $id]);
			}
		} elseif ($action === 'hard_delete') {
			$del = $pdo->prepare('DELETE FROM bookings WHERE id = ?');
			$del->execute([$id]);
		}
		redirect('/cyberhub/admin/index.php');
	}
}

$rows = $pdo->query('SELECT b.*, u.email, u.name FROM bookings b JOIN users u ON u.id = b.user_id ORDER BY booking_date DESC, booking_time DESC')->fetchAll();

// Stats
$totalUsers = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$totalVisits = (int)$pdo->query('SELECT COUNT(*) FROM site_visits')->fetchColumn();
$visitsToday = (int)$pdo->query("SELECT COUNT(*) FROM site_visits WHERE DATE(visited_at) = CURDATE()")->fetchColumn();
$bookingsToday = (int)$pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE()")->fetchColumn();
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Админ панель</h2>
<div class="grid cols-3" style="margin-bottom:16px">
    <div class="card">
        <div class="muted">Пользователи всего</div>
        <div class="price" style="font-size:28px;margin:6px 0 0 0;color:#fff"><?php echo $totalUsers; ?></div>
    </div>
    <div class="card">
        <div class="muted">Посещений всего</div>
        <div class="price" style="font-size:28px;margin:6px 0 0 0;color:#fff"><?php echo $totalVisits; ?></div>
    </div>
    <div class="card">
        <div class="muted">Посещений сегодня</div>
        <div class="price" style="font-size:28px;margin:6px 0 0 0;color:#fff"><?php echo $visitsToday; ?></div>
    </div>
</div>
<div class="grid cols-3" style="margin-bottom:16px">
    <div class="card">
        <div class="muted">Бронирований сегодня</div>
        <div class="price" style="font-size:28px;margin:6px 0 0 0;color:#fff"><?php echo $bookingsToday; ?></div>
    </div>
    <div></div>
    <div></div>
</div>
<div class="card" style="margin-bottom:16px">
	<a class="btn" href="/cyberhub/admin/reports.php">Отчеты</a>
	<a class="btn secondary" href="/cyberhub/admin/reviews.php">Отзывы</a>
</div>
<?php if($errors): ?><div class="notice" style="border-color:#533"><?php foreach($errors as $e){ echo '<div>'.htmlspecialchars($e).'</div>'; } ?></div><?php endif; ?>
<div class="card">
	<h3>Все брони</h3>
	<table class="table">
		<thead>
			<tr><th>ID</th><th>Пользователь</th><th>Дата</th><th>Время</th><th>Длит.</th><th>Статус</th><th>Действия</th></tr>
		</thead>
		<tbody>
			<?php foreach($rows as $r): ?>
			<tr>
				<td>#<?php echo (int)$r['id']; ?></td>
				<td><?php echo htmlspecialchars($r['name'] . ' (' . $r['email'] . ')'); ?></td>
				<td><?php echo htmlspecialchars($r['booking_date']); ?></td>
				<td><?php echo htmlspecialchars($r['booking_time']); ?></td>
				<td><?php echo (int)$r['duration_minutes']; ?> мин</td>
				<td class="status-<?php echo htmlspecialchars($r['status']); ?>"><?php echo htmlspecialchars($r['status']); ?></td>
				<td style="display:flex;gap:8px">
					<form method="post">
						<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
						<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
						<input type="hidden" name="action" value="cancel" />
						<button class="btn ghost">Отменить</button>
					</form>
					<form method="post" style="display:flex;gap:6px;align-items:center">
						<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
						<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
						<input type="hidden" name="action" value="move" />
						<input type="date" name="date" class="input" style="width:auto" required />
						<input type="time" name="time" class="input" style="width:auto" required />
						<button class="btn secondary">Перенести</button>
					</form>
				<form method="post" onsubmit="return confirm('Полностью удалить бронирование? Это действие необратимо.');">
					<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
					<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
					<input type="hidden" name="action" value="hard_delete" />
					<button class="btn" style="background:#a33">Удалить</button>
				</form>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


