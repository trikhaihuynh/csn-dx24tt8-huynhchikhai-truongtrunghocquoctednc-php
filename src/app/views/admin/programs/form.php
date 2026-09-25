<?php
use App\Core\Csrf;

$isEditing = $item !== null;
$currentImage = $item['hinh_dai_dien'] ?? null;
$isVisible = $isEditing && (string) $item['trang_thai'] === '1';
$selectedStatus = (string) old('trang_thai', (string) ($item['trang_thai'] ?? '1'));
$fieldClass = static fn (string $field): string => 'form-group' . (!empty($errors[$field]) ? ' has-error' : '');
$imageHint = 'Định dạng JPG, PNG hoặc WEBP, tối đa 5MB.';
if ($currentImage) {
    $imageHint .= ' Bỏ trống để giữ ảnh hiện tại.';
}
?>
<div class="admin-page-header">
    <div>
        <h1><?= $isEditing ? 'Sửa chương trình' : 'Thêm chương trình' ?></h1>
        <?php if ($isVisible): ?>
            <p class="admin-page-header__lead">
                Đường dẫn:
                <a href="<?= e(url('/chuong-trinh/' . $item['slug'])) ?>" target="_blank" rel="noopener">
                    /chuong-trinh/<?= e($item['slug']) ?>
                </a>
            </p>
        <?php elseif ($isEditing): ?>
            <p class="admin-page-header__lead">Đường dẫn: /chuong-trinh/<?= e($item['slug']) ?> (đang ẩn)</p>
        <?php endif; ?>
    </div>
    <a class="btn btn-outline" href="<?= e(url('/admin/chuong-trinh')) ?>">&larr; Danh sách chương trình</a>
</div>

<section class="admin-card admin-form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error admin-form-card__alert" role="alert">
            Vui lòng kiểm tra lại các trường được đánh dấu.
        </div>
    <?php endif; ?>

    <form class="admin-form" method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data">
        <?= Csrf::field() ?>

        <div class="<?= $fieldClass('ten') ?>">
            <label for="ten">Tên chương trình <span class="required-mark">*</span></label>
            <input type="text" id="ten" name="ten" maxlength="150" required
                value="<?= e(old('ten', $item['ten'] ?? '')) ?>">
            <?php if (!empty($errors['ten'])): ?>
                <div class="form-error"><?= e($errors['ten']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('mo_ta') ?>">
            <label for="mo_ta">Mô tả ngắn</label>
            <textarea id="mo_ta" name="mo_ta" rows="3"
                maxlength="500"><?= e(old('mo_ta', $item['mo_ta'] ?? '')) ?></textarea>
            <div class="form-hint">Tối đa 500 ký tự, hiển thị ở danh sách chương trình và trang chủ.</div>
            <?php if (!empty($errors['mo_ta'])): ?>
                <div class="form-error"><?= e($errors['mo_ta']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('noi_dung') ?>">
            <label for="noi_dung">Nội dung chi tiết</label>
            <textarea id="noi_dung" name="noi_dung" rows="16"
                class="admin-form__content"><?= e(old('noi_dung', $item['noi_dung'] ?? '')) ?></textarea>
            <div class="form-hint">
                Hỗ trợ HTML cơ bản: &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, &lt;li&gt;,
                &lt;strong&gt;, &lt;a&gt;, &lt;img&gt;.
            </div>
            <?php if (!empty($errors['noi_dung'])): ?>
                <div class="form-error"><?= e($errors['noi_dung']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('hinh_dai_dien') ?>">
            <label for="hinh_dai_dien">Ảnh đại diện</label>
            <input type="file" id="hinh_dai_dien" name="hinh_dai_dien"
                accept="image/jpeg,image/png,image/webp" data-preview="#program-image-preview">
            <div class="form-hint"><?= e($imageHint) ?></div>
            <?php if (!empty($errors['hinh_dai_dien'])): ?>
                <div class="form-error"><?= e($errors['hinh_dai_dien']) ?></div>
            <?php endif; ?>
            <?php if ($currentImage): ?>
                <img id="program-image-preview" class="image-preview"
                    src="<?= e(upload_url($currentImage)) ?>" alt="Ảnh đại diện hiện tại">
            <?php else: ?>
                <img id="program-image-preview" class="image-preview" alt="Xem trước ảnh đại diện" hidden>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('thu_tu') ?>">
            <label for="thu_tu">Thứ tự hiển thị</label>
            <input type="number" id="thu_tu" name="thu_tu" min="0" max="999999" step="1"
                value="<?= e(old('thu_tu', (string) ($item['thu_tu'] ?? '0'))) ?>">
            <div class="form-hint">Số nhỏ hiển thị trước. Bỏ trống sẽ lấy 0.</div>
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
            <div class="form-hint">Chương trình ẩn không hiển thị ở trang công khai và form đăng ký nhập học.</div>
            <?php if (!empty($errors['trang_thai'])): ?>
                <div class="form-error"><?= e($errors['trang_thai']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-navy">Lưu</button>
            <a class="btn btn-link" href="<?= e(url('/admin/chuong-trinh')) ?>">Hủy</a>
        </div>
    </form>
</section>
