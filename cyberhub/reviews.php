<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php $pdo = db(); $rows = $pdo->query('SELECT author_name, author_email, message, rating, created_at FROM reviews WHERE is_approved = 1 ORDER BY created_at DESC')->fetchAll(); ?>
<section class="card">
    <h2>Отзывы</h2>
    <p class="muted">Что говорят наши гости</p>
    <?php if(!empty($_SESSION['flash_success'])): ?>
        <div class="notice" style="margin-top:12px"><?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['flash_error'])): ?>
        <div class="notice" style="margin-top:12px;color:#ffbdbd;border-color:#3a1f1f;background:#1a1114"><?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>
    <div style="margin-top:12px">
        <button class="btn" data-open-feedback>Оставить отзыв</button>
    </div>
</section>

<?php if (count($rows) === 0): ?>
<section class="card" style="margin-top:16px;text-align:center">
    <p class="muted" style="margin:0 0 12px 0">Пока нет отзывов. Будьте первым!</p>
    <button class="btn" data-open-feedback>Оставить отзыв</button>
</section>
<?php else: ?>
<section class="grid cols-2" style="margin-top:16px">
    <?php foreach($rows as $r): ?>
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <strong><?php echo htmlspecialchars($r['author_name']); ?></strong>
            <small class="muted"><?php echo htmlspecialchars($r['created_at']); ?></small>
        </div>
        <?php if(!empty($r['rating'])): ?>
        <div class="muted" style="margin-top:4px">Оценка: <?php echo str_repeat('★', (int)$r['rating']); echo str_repeat('☆', 5-(int)$r['rating']); ?></div>
        <?php endif; ?>
        <p style="margin-top:8px"><?php echo nl2br(htmlspecialchars($r['message'])); ?></p>
    </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>
<?php require_once __DIR__ . '/partials/footer.php'; ?>



