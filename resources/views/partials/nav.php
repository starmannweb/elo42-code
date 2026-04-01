<div class="topbar__inner">
    <div class="topbar__search">
        <!-- Search will be added -->
    </div>
    <div class="topbar__actions">
        <?php if (is_authenticated()): ?>
            <span><?= e(auth()['name'] ?? '') ?></span>
            <form method="POST" action="<?= url('/logout') ?>" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" style="background:none;border:none;padding:0;color:inherit;cursor:pointer;font:inherit;">Sair</button>
            </form>
        <?php endif; ?>
    </div>
</div>
