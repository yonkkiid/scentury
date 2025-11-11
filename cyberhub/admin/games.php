<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pdo = db();

function q($s){return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $errors[] = 'CSRF ошибка';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'add_game') {
            $title = trim($_POST['title'] ?? '');
            
            if (!$title) {
                $errors[] = 'Название игры обязательно';
            } else {
                try {
                    $stmt = $pdo->prepare('INSERT INTO games (title) VALUES (?)');
                    $stmt->execute([$title]);
                    $success = 'Игра добавлена';
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $errors[] = 'Игра с таким названием уже существует';
                    } else {
                        $errors[] = 'Ошибка добавления игры';
                    }
                }
            }
        } elseif ($action === 'delete_game') {
            $gameId = (int)($_POST['game_id'] ?? 0);
            if ($gameId > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM games WHERE id = ?');
                    $stmt->execute([$gameId]);
                    $success = 'Игра удалена';
                } catch (PDOException $e) {
                    $errors[] = 'Ошибка удаления игры (возможно, есть связанные заказы)';
                }
            }
        } elseif ($action === 'bulk_add_games') {
            $gamesText = trim($_POST['games_text'] ?? '');
            if ($gamesText) {
                $games = array_filter(array_map('trim', explode("\n", $gamesText)));
                $added = 0;
                $skipped = 0;
                
                foreach ($games as $gameTitle) {
                    if ($gameTitle) {
                        try {
                            $stmt = $pdo->prepare('INSERT INTO games (title) VALUES (?)');
                            $stmt->execute([$gameTitle]);
                            $added++;
                        } catch (PDOException $e) {
                            if ($e->getCode() == 23000) {
                                $skipped++;
                            }
                        }
                    }
                }
                $success = "Добавлено игр: $added, пропущено (уже существуют): $skipped";
            }
        }
    }
}

$games = $pdo->query('SELECT * FROM games ORDER BY title')->fetchAll();
$popularGames = $pdo->query('SELECT g.title, COUNT(go.id) as order_count FROM games g LEFT JOIN game_orders go ON g.id = go.game_id GROUP BY g.id ORDER BY order_count DESC, g.title LIMIT 10')->fetchAll();
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Админ — Управление играми</h2>

<div class="card" style="margin-bottom:16px">
    <a class="btn" href="/cyberhub/admin/index.php">← Назад к админ-панели</a>
    <a class="btn" href="/cyberhub/admin/reports.php">Отчеты</a>
</div>

<?php if($errors): ?>
<div class="notice" style="border-color:#533">
    <?php foreach($errors as $e): ?>
        <div><?php echo q($e); ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if($success): ?>
<div class="notice" style="border-color:#353">
    <div><?php echo q($success); ?></div>
</div>
<?php endif; ?>

<div class="grid cols-2" style="margin-bottom:16px">
    <div class="card">
        <h3>Добавить игру</h3>
        <form method="post" class="form">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
            <input type="hidden" name="action" value="add_game" />
            
            <div>
                <label>Название игры</label>
                <input type="text" name="title" class="input" placeholder="Например: Counter-Strike 2" required />
            </div>
            
            <div style="margin-top:12px">
                <button type="submit" class="btn">Добавить игру</button>
            </div>
        </form>
    </div>
    
    <div class="card">
        <h3>Массовое добавление игр</h3>
        <form method="post" class="form">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
            <input type="hidden" name="action" value="bulk_add_games" />
            
            <div>
                <label>Список игр (по одной на строку)</label>
                <textarea name="games_text" class="input" rows="6" placeholder="Counter-Strike 2&#10;Dota 2&#10;Valorant&#10;..."></textarea>
            </div>
            
            <div style="margin-top:12px">
                <button type="submit" class="btn">Добавить все игры</button>
            </div>
        </form>
    </div>
</div>

<div class="grid cols-2">
    <div class="card">
        <h3>Библиотека игр (<?php echo count($games); ?> игр)</h3>
        <div style="overflow:auto;max-height:400px">
            <table class="table">
                <thead>
                    <tr><th>ID</th><th>Название</th><th>Действия</th></tr>
                </thead>
                <tbody>
                    <?php foreach($games as $game): ?>
                    <tr>
                        <td>#<?php echo (int)$game['id']; ?></td>
                        <td><?php echo q($game['title']); ?></td>
                        <td>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
                                <input type="hidden" name="action" value="delete_game" />
                                <input type="hidden" name="game_id" value="<?php echo (int)$game['id']; ?>" />
                                <button type="submit" class="btn ghost" onclick="return confirm('Удалить игру?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card">
        <h3>Популярные игры</h3>
        <table class="table">
            <thead>
                <tr><th>Игра</th><th>Заказов</th></tr>
            </thead>
            <tbody>
                <?php foreach($popularGames as $game): ?>
                <tr>
                    <td><?php echo q($game['title']); ?></td>
                    <td>
                        <span class="badge secondary"><?php echo (int)$game['order_count']; ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>








