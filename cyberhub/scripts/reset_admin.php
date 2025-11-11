<?php
require_once __DIR__ . '/../includes/db.php';

try {
	$pdo = db();
	$hash = password_hash('admin123', PASSWORD_DEFAULT);
	$st = $pdo->prepare('UPDATE users SET password_hash = ? WHERE email = ?');
	$st->execute([$hash, 'admin@cyberhub.local']);
	echo "Admin password reset to 'admin123'\n";
} catch (Throwable $e) {
	echo 'Error: ' . $e->getMessage() . "\n";
}









