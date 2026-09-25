<?php
use App\Core\Csrf;

$isEditing = $item !== null;
$selectedRole = (string) old('vai_tro', $item['vai_tro'] ?? 'bien_tap');
$selectedStatus = (string) old('trang_thai', (string) ($item['trang_thai'] ?? '1'));
if ($isSelf) {
    $selectedRole = (string) $item['vai_tro'];
    $selectedStatus = '1';
}
$fieldClass = static fn (string $field): string => 'form-group' . (!empty($errors[$field]) ? ' has-error' : '');
$minLengthHint = 'Tối thiểu ' . $passwordMinLength . ' ký tự.';
$passwordHint = $isEditing ? 'Bỏ trống để giữ mật khẩu hiện tại. ' . $minLengthHint : $minLengthHint;
$roleHint = 'Biên tập: quản lý nội dung và đăng ký; không quản lý tài khoản, cài đặt.';
$selfHint = 'Không thể tự khóa hoặc đổi vai trò của tài khoản đang đăng nhập.';
?>
<div class="admin-page-header">
    <div>
        <h1><?= $isEditing ? 'Sửa tài khoản' : 'Thêm tài khoản' ?></h1>
        <?php if ($isEditing): ?>
            <p class="admin-page-header__lead"><?= e($item['email']) ?></p>
        <?php endif; ?>
    </div>
    <a class="btn btn-outline" href="<?= e(url('/admin/tai-khoan')) ?>">&larr; Danh sách tài khoản</a>
</div>

<section class="admin-card admin-form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error admin-form-card__alert" role="alert">
            Vui lòng kiểm tra lại các trường được đánh dấu.
        </div>
    <?php endif; ?>

    <form class="admin-form" method="post" action="<?= e($formAction) ?>" autocomplete="off">
        <?= Csrf::field() ?>

        <div class="<?= $fieldClass('ho_ten') ?>">
            <label for="ho_ten">Họ tên <span class="required-mark">*</span></label>
            <input type="text" id="ho_ten" name="ho_ten" maxlength="100" required
                value="<?= e(old('ho_ten', $item['ho_ten'] ?? '')) ?>">
            <?php if (!empty($errors['ho_ten'])): ?>
                <div class="form-error"><?= e($errors['ho_ten']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('email') ?>">
            <label for="email">Email đăng nhập <span class="required-mark">*</span></label>
            <input type="email" id="email" name="email" maxlength="150" required
                value="<?= e(old('email', $item['email'] ?? '')) ?>">
            <?php if (!empty($errors['email'])): ?>
                <div class="form-error"><?= e($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('mat_khau') ?>">
            <label for="mat_khau">
                <?= $isEditing ? 'Mật khẩu mới' : 'Mật khẩu' ?>
                <?php if (!$isEditing): ?><span class="required-mark">*</span><?php endif; ?>
            </label>
            <input type="password" id="mat_khau" name="mat_khau" autocomplete="new-password"
                minlength="<?= e($passwordMinLength) ?>"<?= $isEditing ? '' : ' required' ?>>
            <div class="form-hint"><?= e($passwordHint) ?></div>
            <?php if (!empty($errors['mat_khau'])): ?>
                <div class="form-error"><?= e($errors['mat_khau']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="mat_khau_confirmation">
                Nhập lại mật khẩu
                <?php if (!$isEditing): ?><span class="required-mark">*</span><?php endif; ?>
            </label>
            <input type="password" id="mat_khau_confirmation" name="mat_khau_confirmation"
                autocomplete="new-password"<?= $isEditing ? '' : ' required' ?>>
        </div>

        <div class="<?= $fieldClass('vai_tro') ?>">
            <label for="vai_tro">Vai trò <span class="required-mark">*</span></label>
            <select id="vai_tro" name="vai_tro" required<?= $isSelf ? ' disabled' : '' ?>>
                <?php foreach ($roles as $roleValue => $roleLabel): ?>
                    <option value="<?= e($roleValue) ?>"<?= $selectedRole === $roleValue ? ' selected' : '' ?>>
                        <?= e($roleLabel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($isSelf): ?>
                <input type="hidden" name="vai_tro" value="<?= e($selectedRole) ?>">
            <?php endif; ?>
            <div class="form-hint"><?= e($roleHint) ?></div>
            <?php if (!empty($errors['vai_tro'])): ?>
                <div class="form-error"><?= e($errors['vai_tro']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('trang_thai') ?>">
            <label for="trang_thai">Trạng thái <span class="required-mark">*</span></label>
            <select id="trang_thai" name="trang_thai" required<?= $isSelf ? ' disabled' : '' ?>>
                <?php foreach ($statuses as $statusValue => $statusLabel): ?>
                    <?php $statusValue = (string) $statusValue; ?>
                    <option value="<?= e($statusValue) ?>"<?= $selectedStatus === $statusValue ? ' selected' : '' ?>>
                        <?= e($statusLabel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($isSelf): ?>
                <input type="hidden" name="trang_thai" value="<?= e($selectedStatus) ?>">
                <div class="form-hint"><?= e($selfHint) ?></div>
            <?php endif; ?>
            <?php if (!empty($errors['trang_thai'])): ?>
                <div class="form-error"><?= e($errors['trang_thai']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-navy">Lưu</button>
            <a class="btn btn-link" href="<?= e(url('/admin/tai-khoan')) ?>">Hủy</a>
        </div>
    </form>
</section>
