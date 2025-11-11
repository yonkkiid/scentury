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
        
        if ($action === 'create_user') {
            $email = trim($_POST['email'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = trim($_POST['role'] ?? 'user');
            
            if (!$email || !$name || !$password) {
                $errors[] = 'Все поля обязательны';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Неверный формат email';
            } else {
                try {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, name, role, created_at) VALUES (?, ?, ?, ?, ?)');
                    $stmt->execute([$email, $passwordHash, $name, $role, date('Y-m-d H:i:s')]);
                    $success = 'Пользователь успешно создан';
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $errors[] = 'Пользователь с таким email уже существует';
                    } else {
                        $errors[] = 'Ошибка создания пользователя';
                    }
                }
            }
        } elseif ($action === 'delete_user') {
            $userId = (int)($_POST['user_id'] ?? 0);
            if ($userId > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ? AND role != "admin"');
                    $stmt->execute([$userId]);
                    $success = 'Пользователь удален';
                } catch (PDOException $e) {
                    $errors[] = 'Ошибка удаления пользователя';
                }
            }
        } elseif ($action === 'update_role') {
            $userId = (int)($_POST['user_id'] ?? 0);
            $newRole = trim($_POST['role'] ?? 'user');
            
            if ($userId > 0 && in_array($newRole, ['user', 'admin'])) {
                try {
                    $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ? AND id != ?');
                    $stmt->execute([$newRole, $userId, $_SESSION['user_id']]);
                    $success = 'Роль пользователя обновлена';
                } catch (PDOException $e) {
                    $errors[] = 'Ошибка обновления роли';
                }
            }
        }
    }
}

$users = $pdo->query('SELECT id, email, name, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h2>Админ — Управление пользователями</h2>

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
    <h3>Создать нового пользователя</h3>
    <form method="post" class="form">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
        <input type="hidden" name="action" value="create_user" />
        
        <div class="form-row">
            <div>
                <label>Email</label>
                <input type="email" name="email" class="input" required />
            </div>
            <div>
                <label>Имя</label>
                <input type="text" name="name" class="input" required />
            </div>
        </div>
        
        <div class="form-row">
            <div>
                <label>Пароль</label>
                <input type="password" name="password" class="input" required />
            </div>
            <div>
                <label>Роль</label>
                <select name="role" class="input">
                    <option value="user">Пользователь</option>
                    <option value="admin">Администратор</option>
                </select>
            </div>
        </div>
        
        <div style="margin-top:12px">
            <button type="submit" class="btn">Создать пользователя</button>
        </div>
    </form>
</div>

<div class="card">
    <h3>Список пользователей</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Имя</th>
                <th>Роль</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td>#<?php echo (int)$user['id']; ?></td>
                <td><?php echo q($user['email']); ?></td>
                <td><?php echo q($user['name']); ?></td>
                <td>
                    <span class="badge <?php echo $user['role'] === 'admin' ? 'primary' : 'secondary'; ?>">
                        <?php echo q($user['role']); ?>
                    </span>
                </td>
                <td><?php echo q($user['created_at']); ?></td>
                <td style="display:flex;gap:8px">
                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
                            <input type="hidden" name="action" value="update_role" />
                            <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>" />
                            <select name="role" class="input" style="width:auto;margin-right:4px">
                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>user</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>admin</option>
                            </select>
                            <button type="submit" class="btn ghost">Изменить роль</button>
                        </form>
                        
                        <form method="post" style="display:inline">
                            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>" />
                            <input type="hidden" name="action" value="delete_user" />
                            <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>" />
                            <button type="submit" class="btn ghost" onclick="return confirm('Удалить пользователя?')">Удалить</button>
                        </form>
                    <?php else: ?>
                        <span class="muted">Вы</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>








