<?php
$flashMessage = flash_get();
$flashClasses = ['success' => 'alert-success', 'warning' => 'alert-warning'];
?>
<?php if ($flashMessage): ?>
    <div class="container flash-wrapper">
        <div class="alert <?= $flashClasses[$flashMessage['type'] ?? ''] ?? 'alert-error' ?>" role="alert">
            <?= e($flashMessage['message'] ?? '') ?>
        </div>
    </div>
<?php endif; ?>
