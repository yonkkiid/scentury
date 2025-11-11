<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/settings.php';
$u = current_user();

$errors = [];
$success = null;

$pdo = db();

function count_slot_bookings(PDO $pdo, string $date, string $time): int {
	$st = $pdo->prepare('SELECT COUNT(*) FROM bookings WHERE booking_date = ? AND booking_time = ? AND status = "active"');
	$st->execute([$date, $time]);
	return (int)$st->fetchColumn();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!verify_csrf($_POST['csrf'] ?? null)) {
		$errors[] = 'Неверный CSRF токен';
	} else {
        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');
        $duration = (int)($_POST['duration'] ?? 60);
        $notes = trim($_POST['notes'] ?? '');
        $seatClass = trim($_POST['seat_class'] ?? '');
        $seatId = (int)($_POST['seat_id'] ?? 0);

		if (!$u) {
			$errors[] = 'Войдите, чтобы бронировать';
		}
		if (!$date || !$time) {
			$errors[] = 'Укажите дату и время';
		}
		if ($date && $date < date('Y-m-d')) {
			$errors[] = 'Нельзя бронировать прошлые даты';
		}
		if ($time && !in_array($time, generate_time_slots(), true)) {
			$errors[] = 'Время вне часов работы';
		}

        if ($seatClass && !in_array($seatClass, ['basic','pro','vip'], true)) {
            $errors[] = 'Неверный класс места';
        }
        if ($seatId <= 0) {
            $errors[] = 'Выберите место на схеме';
        }

        if (!$errors) {
			$current = count_slot_bookings($pdo, $date, $time);
			if ($current >= CAPACITY_PER_SLOT) {
				$errors[] = 'Выбранный слот занят. Выберите другое время';
			} else {
				$check = $pdo->prepare('SELECT COUNT(*) FROM bookings WHERE user_id = ? AND booking_date = ? AND booking_time = ? AND status = "active"');
				$check->execute([$u['id'], $date, $time]);
				if ((int)$check->fetchColumn() > 0) {
					$errors[] = 'У вас уже есть бронь на это время';
				} else {
                    $dup = $pdo->prepare('SELECT COUNT(*) FROM bookings WHERE booking_date = ? AND booking_time = ? AND seat_id = ? AND status = "active"');
                    $dup->execute([$date, $time, $seatId]);
                    if ((int)$dup->fetchColumn() > 0) {
                        $errors[] = 'Это место уже занято на выбранное время';
                    } else {
                        $ins = $pdo->prepare('INSERT INTO bookings (user_id, booking_date, booking_time, duration_minutes, status, seat_class, seat_id, notes, created_at) VALUES (?, ?, ?, ?, "active", ?, ?, ?, ?)');
                        $ins->execute([$u['id'], $date, $time, $duration, $seatClass ?: null, $seatId ?: null, $notes, date('Y-m-d H:i:s')]);
                        $success = 'Бронь создана';
                    }
				}
			}
		}
	}
}
$days = [];
for ($i = 0; $i < 7; $i++) {
	$d = date('Y-m-d', strtotime("+{$i} day"));
	$slots = [];
	foreach (generate_time_slots() as $t) {
		$cnt = count_slot_bookings($pdo, $d, $t);
		$slots[] = [
			'time' => $t,
			'count' => $cnt,
			'free' => max(0, CAPACITY_PER_SLOT - $cnt)
		];
	}
	$days[] = ['date' => $d, 'slots' => $slots];
}
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<h2>Бронирование</h2>
<?php if($success): ?><div class="notice"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<?php if($errors): ?>
	<div class="notice" style="border-color:#533"><?php foreach($errors as $e){ echo '<div>'.htmlspecialchars($e).'</div>'; } ?></div>
<?php endif; ?>

<div class="card">
	<h3 style="margin-top:0">Новая бронь</h3>
	<p class="muted" style="margin:6px 0 16px 0">Выберите удобные дату и время. Вместимость на слот: <?php echo (int)CAPACITY_PER_SLOT; ?> мест.</p>
	<form method="post" data-validate>
		<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
		<div class="form-row">
			<div>
				<label>Дата</label>
				<input type="date" class="input" name="date" min="<?php echo date('Y-m-d'); ?>" required />
			</div>
			<div>
				<label>Время</label>
				<select class="input" name="time" required>
					<option value="">Выберите время</option>
					<?php foreach(generate_time_slots() as $s): ?>
						<option value="<?php echo $s; ?>"><?php echo $s; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
        <div class="seat-toolbar">
            <div>
                <label>Класс станции</label>
                <select class="input" name="seat_class" required>
                    <option value="">Выберите класс</option>
                    <option value="basic">BASIC</option>
                    <option value="pro">PRO</option>
                    <option value="vip">VIP</option>
                </select>
            </div>
            <div class="badge">Выберите место ниже</div>
        </div>
        <input type="hidden" name="seat_id" value="" />
        <div class="seat-map">
            <?php
            $seats = $pdo->query("SELECT id, label, seat_class FROM computer_seats ORDER BY id ASC")->fetchAll();
            $selDate = $_POST['date'] ?? null; $selTime = $_POST['time'] ?? null;
            if ($selDate && $selTime) {
                $st = $pdo->prepare('SELECT seat_id FROM bookings WHERE booking_date = ? AND booking_time = ? AND status = "active" AND seat_id IS NOT NULL');
                $st->execute([$selDate, $selTime]);
                foreach($st->fetchAll() as $o){ $occupied[(int)$o['seat_id']] = true; }
            }
            foreach($seats as $s){
                $isDisabled = isset($occupied[(int)$s['id']]);
                echo '<div class="seat'.($isDisabled?' disabled':'').'" data-id="'.(int)$s['id'].'" data-class="'.htmlspecialchars($s['seat_class']).'">'.htmlspecialchars($s['label']).'</div>';
            }
            ?>
        </div>
		<div class="form-row">
			<div>
				<label>Длительность</label>
				<div class="segmented" role="radiogroup" aria-label="Длительность сеанса">
					<input type="radio" id="dur60" name="duration" value="60" checked />
					<label for="dur60">60 мин</label>
					<input type="radio" id="dur90" name="duration" value="90" />
					<label for="dur90">90 мин</label>
					<input type="radio" id="dur120" name="duration" value="120" />
					<label for="dur120">120 мин</label>
				</div>
			</div>
			<div>
				<label>Комментарий</label>
				<textarea name="notes" class="input" rows="2" placeholder="Пожелания по месту, игре и т.п."></textarea>
				<div class="muted" style="margin-top:6px">Мы постараемся учесть пожелания при размещении.</div>
			</div>
		</div>
		<div style="margin-top:12px">
			<button class="btn" type="submit">Забронировать</button>
		</div>
	</form>
</div>

<div class="card" style="margin-top:16px">
	<h3>Доступность на 7 дней</h3>
	<div class="grid cols-2">
		<?php foreach($days as $day): ?>
			<div>
				<div class="badge" style="margin-bottom:8px"><?php echo htmlspecialchars($day['date']); ?></div>
				<table class="table">
					<thead><tr><th>Время</th><th>Свободно</th></tr></thead>
					<tbody>
						<?php foreach($day['slots'] as $sl): ?>
						<tr>
							<td><?php echo htmlspecialchars($sl['time']); ?></td>
							<td><?php echo (int)$sl['free']; ?> / <?php echo CAPACITY_PER_SLOT; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>

