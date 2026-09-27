<?php
use App\Core\Csrf;

$fieldClass = static fn (string $field): string => 'form-group' . (!empty($errors[$field]) ? ' has-error' : '');
$urlHint = ' · Để trống hoặc nhập đường dẫn bắt đầu bằng http:// hoặc https://.';
?>
<div class="admin-page-header">
    <div>
        <h1>Cài đặt thông tin trường</h1>
        <p class="admin-page-header__lead">
            Thông tin dùng cho trang chủ, trang giới thiệu, trang liên hệ và chân trang.
        </p>
    </div>
</div>

<section class="admin-card admin-form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error admin-form-card__alert" role="alert">
            Vui lòng kiểm tra lại các trường được đánh dấu.
        </div>
    <?php endif; ?>

    <?php if ($fields === []): ?>
        <p class="admin-empty">Chưa có mục cài đặt nào trong cơ sở dữ liệu.</p>
    <?php else: ?>
        <form class="admin-form" method="post" action="<?= e($formAction) ?>">
            <?= Csrf::field() ?>

            <?php foreach ($fields as $field): ?>
                <?php $inputId = 'setting-' . $field['key']; ?>
                <?php $value = old($field['key'], $field['value']); ?>
                <?php $hint = 'Mã: ' . $field['key'] . ($field['type'] === 'url' ? $urlHint : ''); ?>
                <div class="<?= $fieldClass($field['key']) ?>">
                    <label for="<?= e($inputId) ?>">
                        <?= e($field['label']) ?>
                        <?php if ($field['required']): ?><span class="required-mark">*</span><?php endif; ?>
                    </label>
                    <?php if ($field['type'] === 'textarea'): ?>
                        <textarea id="<?= e($inputId) ?>" name="<?= e($field['key']) ?>" rows="5"
                            maxlength="<?= e($valueMaxLength) ?>"><?= e($value) ?></textarea>
                    <?php else: ?>
                        <input type="<?= e($field['type']) ?>" id="<?= e($inputId) ?>" name="<?= e($field['key']) ?>"
                            maxlength="<?= e($valueMaxLength) ?>" value="<?= e($value) ?>"
                            <?= $field['required'] ? 'required' : '' ?>>
                    <?php endif; ?>
                    <div class="form-hint"><?= e($hint) ?></div>
                    <?php if (!empty($errors[$field['key']])): ?>
                        <div class="form-error"><?= e($errors[$field['key']]) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="form-actions">
                <button type="submit" class="btn btn-navy">Lưu</button>
                <a class="btn btn-link" href="<?= e(url('/admin')) ?>">Hủy</a>
            </div>
        </form>
    <?php endif; ?>
</section>
