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
        
        if ($action === 'add_seat') {
            $label = trim($_POST['label'] ?? '');
            $seatClass = trim($_POST['seat_class'] ?? 'basic');
            $equipmentTypeId = !empty($_POST['equipment_type_id']) ? (int)$_POST['equipment_type_id'] : null;
            
            if (!$label) {
                $errors[] = 'Метка места обязательна';
            } elseif (!in_array($seatClass, ['basic', 'pro', 'vip'])) {
                $errors[] = 'Неверный класс места';
            } else {
                try {
                    $stmt = $pdo->prepare('INSERT INTO computer_seats (label, seat_class, equipment_type_id) VALUES (?, ?, ?)');
                    $stmt->execute([$label, $seatClass, $equipmentTypeId]);
                    $success = 'Компьютерное место добавлено';
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $errors[] = 'Место с такой меткой уже существует';
                    } else {
                        $errors[] = 'Ошибка добавления места';
                    }
                }
            }
        } elseif ($action === 'delete_seat') {
            $seatId = (int)($_POST['seat_id'] ?? 0);
            if ($seatId > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM computer_seats WHERE id = ?');
                    $stmt->execute([$seatId]);
                    $success = 'Компьютерное место удалено';
                } catch (PDOException $e) {
                    $errors[] = 'Ошибка удаления места (возможно, есть связанные аренды)';
                }
            }
        }
    }
}

$seats = $pdo->query('SELECT cs.*, et.name AS equipment_type_name FROM computer_seats cs LEFT JOIN equipment_types et ON et.id = cs.equipment_type_id ORDER BY cs.seat_class, cs.label')->fetchAll();
$equipmentTypes = $pdo->query('SELECT * FROM equipment_types ORDER BY name')->fetchAll();
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Админ — Управление компьютерными местами</h2>

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
    <h3>Добавить компьютерное место</h3>
    <form method="post" class="form">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
        <input type="hidden" name="action" value="add_seat" />
        
        <div class="form-row">
            <div>
                <label>Метка места</label>
                <input type="text" name="label" class="input" placeholder="Например: VIP-04, PRO-05, BASIC-16" required />
            </div>
            <div>
                <label>Класс места</label>
                <select name="seat_class" class="input" required>
                    <option value="basic">Basic</option>
                    <option value="pro">Pro</option>
                    <option value="vip">VIP</option>
                </select>
            </div>
            <div>
                <label>Тип оборудования</label>
                <select name="equipment_type_id" class="input">
                    <option value="">Без привязки к типу</option>
                    <?php foreach($equipmentTypes as $type): ?>
                        <option value="<?php echo (int)$type['id']; ?>"><?php echo q($type['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div style="margin-top:12px">
            <button type="submit" class="btn">Добавить место</button>
        </div>
    </form>
</div>

<div class="card">
    <h3>Список компьютерных мест</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Метка</th>
                <th>Класс</th>
                <th>Тип оборудования</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($seats as $seat): ?>
            <tr>
                <td>#<?php echo (int)$seat['id']; ?></td>
                <td><?php echo q($seat['label']); ?></td>
                <td>
                    <span class="badge <?php echo $seat['seat_class'] === 'vip' ? 'primary' : ($seat['seat_class'] === 'pro' ? 'secondary' : ''); ?>">
                        <?php echo q(strtoupper($seat['seat_class'])); ?>
                    </span>
                </td>
                <td><?php echo $seat['equipment_type_name'] ? q($seat['equipment_type_name']) : '<span class="muted">Не указан</span>'; ?></td>
                <td>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
                        <input type="hidden" name="action" value="delete_seat" />
                        <input type="hidden" name="seat_id" value="<?php echo (int)$seat['id']; ?>" />
                        <button type="submit" class="btn ghost" onclick="return confirm('Удалить место?')">Удалить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>








