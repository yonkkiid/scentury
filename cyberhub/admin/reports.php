<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pdo = db();

function q($s){return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');}

$action = $_GET['action'] ?? '';
$data = [];
$count = null;

if ($action === 'equipment_by_type') {
    $type = trim($_GET['type'] ?? '');
    $spec = trim($_GET['spec'] ?? '');
    $sql = 'SELECT ei.* , et.name AS type_name FROM equipment_items ei JOIN equipment_types et ON et.id = ei.type_id WHERE 1=1';
    $params = [];
    if ($type !== '') { $sql .= ' AND et.name = ?'; $params[] = $type; }
    if ($spec !== '') { $sql .= ' AND ei.specs LIKE ?'; $params[] = "%$spec%"; }
    $st = $pdo->prepare($sql);
    $st->execute($params);
    $data = $st->fetchAll();
    $count = count($data);
}

if ($action === 'all_clients') {
    $data = $pdo->query('SELECT id, email, name, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
    $count = count($data);
}

if ($action === 'all_games') {
    $data = $pdo->query('SELECT id, title FROM games ORDER BY title')->fetchAll();
    $count = count($data);
}

if ($action === 'seats_by_equipment_type') {
    $type = trim($_GET['type'] ?? '');
    $sql = 'SELECT cs.*, et.name AS equipment_type FROM computer_seats cs LEFT JOIN equipment_types et ON et.id = cs.equipment_type_id WHERE 1=1';
    $params = [];
    if ($type !== '') { $sql .= ' AND et.name = ?'; $params[] = $type; }
    $st = $pdo->prepare($sql);
    $st->execute($params);
    $data = $st->fetchAll();
    $count = count($data);
}

if ($action === 'night_tariff_visitors') {
    $seatClass = trim($_GET['seat_class'] ?? '');
    $sql = "SELECT r.*, u.name, u.email FROM rentals r JOIN users u ON u.id = r.user_id JOIN tariffs t ON t.id = r.tariff_id JOIN computer_seats cs ON cs.id = r.seat_id WHERE t.is_night = 1";
    $params = [];
    if ($seatClass !== '') { $sql .= ' AND cs.seat_class = ?'; $params[] = $seatClass; }
    $st = $pdo->prepare($sql);
    $st->execute($params);
    $data = $st->fetchAll();
    $count = count($data);
}

if ($action === 'games_ordered_more_than_two') {
    $data = $pdo->query('SELECT g.title, COUNT(*) cnt FROM game_orders go JOIN games g ON g.id = go.game_id GROUP BY go.game_id HAVING COUNT(*) > 2 ORDER BY cnt DESC')->fetchAll();
    $count = count($data);
}

if ($action === 'free_computers_at_time') {
    $date = trim($_GET['date'] ?? '');
    $time = trim($_GET['time'] ?? '');
    $dt = $date && $time ? ("$date $time:00") : '';
    if ($dt) {
        $sql = "SELECT cs.* FROM computer_seats cs WHERE cs.id NOT IN (
            SELECT seat_id FROM rentals r WHERE ? BETWEEN r.start_at AND r.end_at
        ) ORDER BY cs.label";
        $st = $pdo->prepare($sql);
        $st->execute([$dt]);
        $data = $st->fetchAll();
        $count = count($data);
    }
}

if ($action === 'rented_count_by_type') {
    $data = $pdo->query("SELECT et.name AS type_name, COUNT(*) AS rented
        FROM rentals r JOIN computer_seats cs ON cs.id = r.seat_id
        LEFT JOIN equipment_types et ON et.id = cs.equipment_type_id
        GROUP BY et.name ORDER BY rented DESC")->fetchAll();
    $count = count($data);
}

if ($action === 'client_rentals_in_period') {
    $email = trim($_GET['email'] ?? '');
    $from = trim($_GET['from'] ?? '');
    $to = trim($_GET['to'] ?? '');
    if ($email && $from && $to) {
        $sql = 'SELECT r.*, cs.label FROM rentals r JOIN users u ON u.id = r.user_id JOIN computer_seats cs ON cs.id = r.seat_id WHERE u.email = ? AND r.start_at >= ? AND r.end_at <= ? ORDER BY r.start_at DESC';
        $st = $pdo->prepare($sql);
        $st->execute([$email, "$from 00:00:00", "$to 23:59:59"]);
        $data = $st->fetchAll();
        $count = count($data);
    }
}

if ($action === 'tariffs_in_day_by_seat_class') {
    $date = trim($_GET['date'] ?? '');
    $seatClass = trim($_GET['seat_class'] ?? '');
    if ($date) {
        $sql = "SELECT t.name, COUNT(*) cnt FROM rentals r JOIN tariffs t ON t.id = r.tariff_id JOIN computer_seats cs ON cs.id = r.seat_id WHERE DATE(r.start_at) = ?";
        $params = [$date];
        if ($seatClass) { $sql .= ' AND cs.seat_class = ?'; $params[] = $seatClass; }
        $sql .= ' GROUP BY t.id ORDER BY cnt DESC';
        $st = $pdo->prepare($sql);
        $st->execute($params);
        $data = $st->fetchAll();
        $count = array_sum(array_map(fn($r)=> (int)$r['cnt'], $data));
    }
}
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Админ — отчеты</h2>

<div class="card" style="margin-bottom:16px">
	<h3>Навигация по отчетам</h3>
	<div class="grid cols-3">
		<div>
			<form method="get"><input type="hidden" name="action" value="equipment_by_type" />
				<label>Тип оборудования</label><input class="input" type="text" name="type" placeholder="GPU/Monitor/..." />
				<label>Характеристики</label><input class="input" type="text" name="spec" placeholder="RTX 3070, 240Hz..." />
				<div style="margin-top:8px"><button class="btn">Получить</button></div>
			</form>
		</div>
		<div>
			<form method="get"><input type="hidden" name="action" value="all_clients" />
				<p class="muted">Список всех клиентов</p>
				<div><button class="btn secondary">Показать</button></div>
			</form>
			<form method="get" style="margin-top:12px"><input type="hidden" name="action" value="all_games" />
				<p class="muted">Список всех игр</p>
				<div><button class="btn secondary">Показать</button></div>
			</form>
		</div>
		<div>
			<form method="get"><input type="hidden" name="action" value="seats_by_equipment_type" />
				<label>Тип оборудования</label><input class="input" type="text" name="type" placeholder="GPU/Monitor/..." />
				<div style="margin-top:8px"><button class="btn">Получить</button></div>
			</form>
		</div>
	</div>
	<div class="grid cols-3" style="margin-top:12px">
		<div>
			<form method="get"><input type="hidden" name="action" value="night_tariff_visitors" />
				<label>Класс места</label>
				<select name="seat_class" class="input">
					<option value="">Любой</option>
					<option value="basic">basic</option>
					<option value="pro">pro</option>
					<option value="vip">vip</option>
				</select>
				<div style="margin-top:8px"><button class="btn">Получить</button></div>
			</form>
		</div>
		<div>
			<form method="get"><input type="hidden" name="action" value="games_ordered_more_than_two" />
				<p class="muted">Игры заказаны > 2 раз</p>
				<div><button class="btn secondary">Показать</button></div>
			</form>
		</div>
		<div>
			<form method="get"><input type="hidden" name="action" value="free_computers_at_time" />
				<label>Дата</label><input class="input" type="date" name="date" />
				<label>Время</label><input class="input" type="time" name="time" />
				<div style="margin-top:8px"><button class="btn">Найти свободные</button></div>
			</form>
		</div>
	</div>
	<div class="grid cols-3" style="margin-top:12px">
		<div>
			<form method="get"><input type="hidden" name="action" value="rented_count_by_type" />
				<p class="muted">Кол-во аренд по типу ПК</p>
				<div><button class="btn secondary">Показать</button></div>
			</form>
		</div>
		<div>
			<form method="get"><input type="hidden" name="action" value="client_rentals_in_period" />
				<label>Email клиента</label><input class="input" type="email" name="email" />
				<div class="form-row">
					<div><label>С</label><input class="input" type="date" name="from" /></div>
					<div><label>По</label><input class="input" type="date" name="to" /></div>
				</div>
				<div style="margin-top:8px"><button class="btn">Показать</button></div>
			</form>
		</div>
		<div>
			<form method="get"><input type="hidden" name="action" value="tariffs_in_day_by_seat_class" />
				<label>Дата</label><input class="input" type="date" name="date" />
				<label>Класс места</label>
				<select name="seat_class" class="input">
					<option value="">Любой</option>
					<option value="basic">basic</option>
					<option value="pro">pro</option>
					<option value="vip">vip</option>
				</select>
				<div style="margin-top:8px"><button class="btn">Показать</button></div>
			</form>
		</div>
	</div>
</div>

<?php if($action): ?>
<div class="card">
	<h3>Результат</h3>
	<?php if($count !== null): ?><div class="badge">Найдено: <?php echo (int)$count; ?></div><?php endif; ?>
	<div style="overflow:auto">
		<table class="table">
			<thead>
				<tr>
					<?php if($data){ foreach(array_keys($data[0]) as $col){ echo '<th>'.q($col).'</th>'; } } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $row): ?>
				<tr>
					<?php foreach($row as $val){ echo '<td>'.q($val).'</td>'; } ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>











