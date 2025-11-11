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
        
        if ($action === 'add_equipment_type') {
            $name = trim($_POST['name'] ?? '');
            
            if (!$name) {
                $errors[] = 'Название типа обязательно';
            } else {
                try {
                    $stmt = $pdo->prepare('INSERT INTO equipment_types (name) VALUES (?)');
                    $stmt->execute([$name]);
                    $success = 'Тип оборудования добавлен';
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $errors[] = 'Тип оборудования с таким названием уже существует';
                    } else {
                        $errors[] = 'Ошибка добавления типа';
                    }
                }
            }
        } elseif ($action === 'add_equipment_item') {
            $typeId = (int)($_POST['type_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $specs = trim($_POST['specs'] ?? '');
            
            if (!$typeId || !$name) {
                $errors[] = 'Тип и название обязательны';
            } else {
                try {
                    $stmt = $pdo->prepare('INSERT INTO equipment_items (type_id, name, specs) VALUES (?, ?, ?)');
                    $stmt->execute([$typeId, $name, $specs]);
                    $success = 'Элемент оборудования добавлен';
                } catch (PDOException $e) {
                    $errors[] = 'Ошибка добавления элемента';
                }
            }
        } elseif ($action === 'delete_equipment_type') {
            $typeId = (int)($_POST['type_id'] ?? 0);
            if ($typeId > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM equipment_types WHERE id = ?');
                    $stmt->execute([$typeId]);
                    $success = 'Тип оборудования удален';
                } catch (PDOException $e) {
                    $errors[] = 'Ошибка удаления типа (возможно, есть связанные элементы)';
                }
            }
        } elseif ($action === 'delete_equipment_item') {
            $itemId = (int)($_POST['item_id'] ?? 0);
            if ($itemId > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM equipment_items WHERE id = ?');
                    $stmt->execute([$itemId]);
                    $success = 'Элемент оборудования удален';
                } catch (PDOException $e) {
                    $errors[] = 'Ошибка удаления элемента';
                }
            }
        }
    }
}

$equipmentTypes = $pdo->query('SELECT * FROM equipment_types ORDER BY name')->fetchAll();
$equipmentItems = $pdo->query('SELECT ei.*, et.name AS type_name FROM equipment_items ei JOIN equipment_types et ON et.id = ei.type_id ORDER BY et.name, ei.name')->fetchAll();
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Админ — Управление оборудованием</h2>

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

<div class="grid cols-2">
    <div class="card">
        <h3>Добавить тип оборудования</h3>
        <form method="post" class="form">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
            <input type="hidden" name="action" value="add_equipment_type" />
            
            <div>
                <label>Название типа</label>
                <input type="text" name="name" class="input" placeholder="Например: GPU, Monitor, Keyboard" required />
            </div>
            
            <div style="margin-top:12px">
                <button type="submit" class="btn">Добавить тип</button>
            </div>
        </form>
    </div>
    
    <div class="card">
        <h3>Добавить элемент оборудования</h3>
        <form method="post" class="form">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
            <input type="hidden" name="action" value="add_equipment_item" />
            
            <div>
                <label>Тип оборудования</label>
                <select name="type_id" class="input" required>
                    <option value="">Выберите тип</option>
                    <?php foreach($equipmentTypes as $type): ?>
                        <option value="<?php echo (int)$type['id']; ?>"><?php echo q($type['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label>Название</label>
                <input type="text" name="name" class="input" placeholder="Например: RTX 4090" required />
            </div>
            
            <div>
                <label>Характеристики</label>
                <textarea name="specs" class="input" rows="3" placeholder="Подробные характеристики..."></textarea>
            </div>
            
            <div style="margin-top:12px">
                <button type="submit" class="btn">Добавить элемент</button>
            </div>
        </form>
    </div>
</div>

<div class="grid cols-2" style="margin-top:16px">
    <div class="card">
        <h3>Типы оборудования</h3>
        <table class="table">
            <thead>
                <tr><th>ID</th><th>Название</th><th>Действия</th></tr>
            </thead>
            <tbody>
                <?php foreach($equipmentTypes as $type): ?>
                <tr>
                    <td>#<?php echo (int)$type['id']; ?></td>
                    <td><?php echo q($type['name']); ?></td>
                    <td>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
                            <input type="hidden" name="action" value="delete_equipment_type" />
                            <input type="hidden" name="type_id" value="<?php echo (int)$type['id']; ?>" />
                            <button type="submit" class="btn ghost" onclick="return confirm('Удалить тип?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="card">
        <h3>Элементы оборудования</h3>
        <div style="overflow:auto;max-height:400px">
            <table class="table">
                <thead>
                    <tr><th>ID</th><th>Тип</th><th>Название</th><th>Характеристики</th><th>Действия</th></tr>
                </thead>
                <tbody>
                    <?php foreach($equipmentItems as $item): ?>
                    <tr>
                        <td>#<?php echo (int)$item['id']; ?></td>
                        <td><?php echo q($item['type_name']); ?></td>
                        <td><?php echo q($item['name']); ?></td>
                        <td><?php echo q($item['specs']); ?></td>
                        <td>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
                                <input type="hidden" name="action" value="delete_equipment_item" />
                                <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>" />
                                <button type="submit" class="btn ghost" onclick="return confirm('Удалить элемент?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>








