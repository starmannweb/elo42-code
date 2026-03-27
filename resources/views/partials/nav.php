<div class="topbar__inner">
    <div class="topbar__search">
        <!-- Search will be added -->
    </div>
    <div class="topbar__actions">
        <?php if (is_authenticated()): ?>
            <span><?= e(auth()['name'] ?? '') ?></span>
            <a href="<?= url('/logout') ?>">Sair</a>
        <?php endif; ?>
    </div>
</div>
