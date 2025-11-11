<?php
require_once __DIR__ . '/includes/auth.php';
$pdo = db();

$game = trim($_GET['game'] ?? ($_POST['game'] ?? ''));
$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $errors[] = 'Неверный CSRF токен';
    } else {
        $game = trim($_POST['game'] ?? '');
        $team = trim($_POST['team_name'] ?? '');
        $captain = trim($_POST['captain_name'] ?? '');
        $contacts = trim($_POST['captain_contacts'] ?? '');
        $players = trim($_POST['players'] ?? '');

        if (!$game) { $errors[] = 'Укажите дисциплину'; }
        if (!$team) { $errors[] = 'Укажите название команды'; }
        if (!$captain) { $errors[] = 'Укажите имя капитана'; }
        if (!$contacts) { $errors[] = 'Укажите контакты для связи'; }

        if (!$errors) {
            $st = $pdo->prepare('INSERT INTO tournament_registrations (game, team_name, captain_name, captain_contacts, players, created_at) VALUES (?, ?, ?, ?, ?, ?)');
            $st->execute([$game, $team, $captain, $contacts, $players, date('Y-m-d H:i:s')]);
            $success = 'Заявка принята! Мы свяжемся с вами в ближайшее время.';
            // Очистим форму
            $team = $captain = $contacts = $players = '';
        }
    }
}
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<h2>Регистрация команды на турнир</h2>
<?php if($success): ?><div class="notice"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<?php if($errors): ?><div class="notice" style="border-color:#533"><?php foreach($errors as $e){ echo '<div>'.htmlspecialchars($e).'</div>'; } ?></div><?php endif; ?>

<div class="card">
    <form method="post">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
        <div class="form-row">
            <div>
                <label>Дисциплина</label>
                <select name="game" class="input" required>
                    <option value="">Выберите игру</option>
                    <?php foreach(['CS2','Valorant','FIFA 24'] as $g): ?>
                        <option value="<?php echo $g; ?>" <?php echo ($game===$g?'selected':''); ?>><?php echo $g; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Название команды</label>
                <input type="text" name="team_name" class="input" value="<?php echo htmlspecialchars($team ?? ''); ?>" required />
            </div>
        </div>
        <div class="form-row">
            <div>
                <label>Капитан</label>
                <input type="text" name="captain_name" class="input" value="<?php echo htmlspecialchars($captain ?? ''); ?>" required />
            </div>
            <div>
                <label>Контакты (телеграм/телефон)</label>
                <input type="text" name="captain_contacts" class="input" value="<?php echo htmlspecialchars($contacts ?? ''); ?>" required />
            </div>
        </div>
        <div>
            <label>Состав (по одному на строку)</label>
            <textarea name="players" class="input" rows="5" placeholder="Игрок 1&#10;Игрок 2&#10;..."><?php echo htmlspecialchars($players ?? ''); ?></textarea>
        </div>
        <div style="margin-top:12px">
            <button class="btn" type="submit">Отправить заявку</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>











