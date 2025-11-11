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
        
        if ($action === 'add_tariff') {
            $name = trim($_POST['name'] ?? '');
            $isNight = isset($_POST['is_night']) ? 1 : 0;
            
            if (!$name) {
                $errors[] = 'Название тарифа обязательно';
            } else {
                try {
                    $stmt = $pdo->prepare('INSERT INTO tariffs (name, is_night) VALUES (?, ?)');
                    $stmt->execute([$name, $isNight]);
                    $success = 'Тариф добавлен';
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $errors[] = 'Тариф с таким названием уже существует';
                    } else {
                        $errors[] = 'Ошибка добавления тарифа';
                    }
                }
            }
        } elseif ($action === 'delete_tariff') {
            $tariffId = (int)($_POST['tariff_id'] ?? 0);
            if ($tariffId > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM tariffs WHERE id = ?');
                    $stmt->execute([$tariffId]);
                    $success = 'Тариф удален';
                } catch (PDOException $e) {
                    $errors[] = 'Ошибка удаления тарифа (возможно, есть связанные аренды)';
                }
            }
        }
    }
}

$tariffs = $pdo->query('SELECT * FROM tariffs ORDER BY is_night DESC, name')->fetchAll();
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Админ — Управление тарифами</h2>

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

<div class="card" style="margin-bottom:16px">
    <h3>Добавить новый тариф</h3>
    <form method="post" class="form">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
        <input type="hidden" name="action" value="add_tariff" />
        
        <div class="form-row">
            <div>
                <label>Название тарифа</label>
                <input type="text" name="name" class="input" placeholder="Например: Дневной VIP, Ночной BASIC" required />
            </div>
            <div style="display:flex;align-items:center;margin-top:20px">
                <label style="display:flex;align-items:center;gap:8px">
                    <input type="checkbox" name="is_night" />
                    Ночной тариф
                </label>
            </div>
        </div>
        
        <div style="margin-top:12px">
            <button type="submit" class="btn">Добавить тариф</button>
        </div>
    </form>
</div>

<div class="card">
    <h3>Список тарифов</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Тип</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tariffs as $tariff): ?>
            <tr>
                <td>#<?php echo (int)$tariff['id']; ?></td>
                <td><?php echo q($tariff['name']); ?></td>
                <td>
                    <span class="badge <?php echo $tariff['is_night'] ? 'primary' : 'secondary'; ?>">
                        <?php echo $tariff['is_night'] ? 'Ночной' : 'Дневной'; ?>
                    </span>
                </td>
                <td>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
                        <input type="hidden" name="action" value="delete_tariff" />
                        <input type="hidden" name="tariff_id" value="<?php echo (int)$tariff['id']; ?>" />
                        <button type="submit" class="btn ghost" onclick="return confirm('Удалить тариф?')">Удалить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>








