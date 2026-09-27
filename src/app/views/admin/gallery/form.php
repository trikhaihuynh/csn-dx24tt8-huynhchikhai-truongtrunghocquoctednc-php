<?php
use App\Core\Csrf;

$isEditing = $item !== null;
$selectedStatus = (string) old('trang_thai', (string) ($item['trang_thai'] ?? '1'));
$fieldClass = static fn (string $field): string => 'form-group' . (!empty($errors[$field]) ? ' has-error' : '');
?>
<div class="admin-page-header">
    <div>
        <h1><?= $isEditing ? 'Sửa hình ảnh' : 'Thêm hình ảnh' ?></h1>
        <?php if (!$isEditing): ?>
            <p class="admin-page-header__lead">
                Có thể chọn nhiều ảnh cùng lúc; mỗi ảnh hợp lệ được lưu thành một mục riêng.
            </p>
        <?php endif; ?>
    </div>
    <a class="btn btn-outline" href="<?= e(url('/admin/hinh-anh')) ?>">&larr; Danh sách hình ảnh</a>
</div>

<section class="admin-card admin-form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error admin-form-card__alert" role="alert">
            Vui lòng kiểm tra lại các trường được đánh dấu.
        </div>
    <?php endif; ?>

    <form class="admin-form" method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data">
        <?= Csrf::field() ?>

        <div class="<?= $fieldClass('hinh_anh') ?>">
            <?php if ($isEditing): ?>
                <label for="hinh_anh">Thay ảnh</label>
                <input type="file" id="hinh_anh" name="hinh_anh"
                    accept="image/jpeg,image/png,image/webp" data-preview="#gallery-image-preview">
                <div class="form-hint">Định dạng JPG, PNG hoặc WEBP, tối đa 5MB. Bỏ trống để giữ ảnh hiện tại.</div>
            <?php else: ?>
                <label for="hinh_anh">Tệp ảnh <span class="required-mark">*</span></label>
                <input type="file" id="hinh_anh" name="hinh_anh[]" multiple required
                    accept="image/jpeg,image/png,image/webp">
                <div class="form-hint">
                    Định dạng JPG, PNG hoặc WEBP, tối đa 5MB mỗi ảnh. Tệp không hợp lệ sẽ được bỏ qua.
                </div>
            <?php endif; ?>
            <?php if (!empty($errors['hinh_anh'])): ?>
                <div class="form-error"><?= e($errors['hinh_anh']) ?></div>
            <?php endif; ?>
            <?php if ($isEditing): ?>
                <img id="gallery-image-preview" class="image-preview"
                    src="<?= e(upload_url($item['duong_dan'])) ?>" alt="Ảnh hiện tại">
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('tieu_de') ?>">
            <label for="tieu_de">
                Tiêu đề<?php if ($isEditing): ?> <span class="required-mark">*</span><?php endif; ?>
            </label>
            <input type="text" id="tieu_de" name="tieu_de" maxlength="200"<?= $isEditing ? ' required' : '' ?>
                value="<?= e(old('tieu_de', $item['tieu_de'] ?? '')) ?>">
            <?php if (!$isEditing): ?>
                <div class="form-hint">Bỏ trống để lấy tên tệp (không gồm phần mở rộng) làm tiêu đề cho từng ảnh.</div>
            <?php endif; ?>
            <?php if (!empty($errors['tieu_de'])): ?>
                <div class="form-error"><?= e($errors['tieu_de']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('mo_ta') ?>">
            <label for="mo_ta">Mô tả</label>
            <textarea id="mo_ta" name="mo_ta" rows="3"
                maxlength="500"><?= e(old('mo_ta', $item['mo_ta'] ?? '')) ?></textarea>
            <div class="form-hint">Tối đa 500 ký tự.</div>
            <?php if (!empty($errors['mo_ta'])): ?>
                <div class="form-error"><?= e($errors['mo_ta']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('album') ?>">
            <label for="album">Album</label>
            <input type="text" id="album" name="album" maxlength="100" list="album-options"
                value="<?= e(old('album', $item['album'] ?? '')) ?>">
            <datalist id="album-options">
                <?php foreach ($albums as $albumValue => $albumLabel): ?>
                    <option value="<?= e((string) $albumValue) ?>"><?= e($albumLabel) ?></option>
                <?php endforeach; ?>
            </datalist>
            <div class="form-hint">
                Ví dụ: hoat-dong, co-so-vat-chat. Tên album được chuyển về chữ thường không dấu, nối bằng gạch ngang.
            </div>
            <?php if (!empty($errors['album'])): ?>
                <div class="form-error"><?= e($errors['album']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('thu_tu') ?>">
            <label for="thu_tu">Thứ tự hiển thị</label>
            <input type="number" id="thu_tu" name="thu_tu" min="0" max="999999" step="1"
                value="<?= e(old('thu_tu', (string) ($item['thu_tu'] ?? '0'))) ?>">
            <div class="form-hint">Số nhỏ hiển thị trước trong cùng album. Bỏ trống sẽ lấy 0.</div>
            <?php if (!empty($errors['thu_tu'])): ?>
                <div class="form-error"><?= e($errors['thu_tu']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('trang_thai') ?>">
            <label for="trang_thai">Trạng thái <span class="required-mark">*</span></label>
            <select id="trang_thai" name="trang_thai" required>
                <?php foreach ($statuses as $statusValue => $statusLabel): ?>
                    <?php $statusValue = (string) $statusValue; ?>
                    <option value="<?= e($statusValue) ?>"<?= $selectedStatus === $statusValue ? ' selected' : '' ?>>
                        <?= e($statusLabel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-hint">Ảnh ẩn không hiển thị ở thư viện ảnh và trang chủ.</div>
            <?php if (!empty($errors['trang_thai'])): ?>
                <div class="form-error"><?= e($errors['trang_thai']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-navy">Lưu</button>
            <a class="btn btn-link" href="<?= e(url('/admin/hinh-anh')) ?>">Hủy</a>
        </div>
    </form>
</section>
