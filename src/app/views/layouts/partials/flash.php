<?php
$flashMessage = flash_get();
?>
<?php if ($flashMessage): ?>
    <div class="container flash-wrapper">
        <div class="alert <?= ($flashMessage['type'] ?? '') === 'success' ? 'alert-success' : 'alert-error' ?>" role="alert">
            <?= e($flashMessage['message'] ?? '') ?>
        </div>
    </div>
<?php endif; ?>
