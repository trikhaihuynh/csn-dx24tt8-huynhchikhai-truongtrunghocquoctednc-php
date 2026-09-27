<?php
use App\Core\Csrf;

$isEditing = $item !== null;
$currentImage = $item['hinh_dai_dien'] ?? null;
$publishedAtValue = $isEditing ? format_date($item['ngay_dang'], 'Y-m-d\TH:i') : '';
$selectedStatus = old('trang_thai', $item['trang_thai'] ?? 'nhap');
$fieldClass = static fn (string $field): string => 'form-group' . (!empty($errors[$field]) ? ' has-error' : '');
$imageHint = 'Định dạng JPG, PNG hoặc WEBP, tối đa 5MB.';
if ($currentImage) {
    $imageHint .= ' Bỏ trống để giữ ảnh hiện tại.';
}
$publishedAtHint = $isEditing
    ? 'Bỏ trống để giữ ngày đăng hiện tại.'
    : 'Bỏ trống để lấy thời điểm hiện tại.';
?>
<div class="admin-page-header">
    <div>
        <h1><?= $isEditing ? 'Sửa tin tức' : 'Thêm tin tức' ?></h1>
        <?php if ($isEditing && $isPublished): ?>
            <p class="admin-page-header__lead">
                Đường dẫn:
                <a href="<?= e(url('/tin-tuc/' . $item['slug'])) ?>" target="_blank" rel="noopener">
                    /tin-tuc/<?= e($item['slug']) ?>
                </a>
            </p>
        <?php elseif ($isEditing): ?>
            <p class="admin-page-header__lead">Đường dẫn: /tin-tuc/<?= e($item['slug']) ?> (chưa công khai)</p>
        <?php endif; ?>
    </div>
    <a class="btn btn-outline" href="<?= e(url('/admin/tin-tuc')) ?>">&larr; Danh sách tin</a>
</div>

<section class="admin-card admin-form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error admin-form-card__alert" role="alert">
            Vui lòng kiểm tra lại các trường được đánh dấu.
        </div>
    <?php endif; ?>

    <form class="admin-form" method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data">
        <?= Csrf::field() ?>

        <div class="<?= $fieldClass('tieu_de') ?>">
            <label for="tieu_de">Tiêu đề <span class="required-mark">*</span></label>
            <input type="text" id="tieu_de" name="tieu_de" maxlength="200" required
                value="<?= e(old('tieu_de', $item['tieu_de'] ?? '')) ?>">
            <?php if (!empty($errors['tieu_de'])): ?>
                <div class="form-error"><?= e($errors['tieu_de']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('tom_tat') ?>">
            <label for="tom_tat">Tóm tắt</label>
            <textarea id="tom_tat" name="tom_tat" rows="3"
                maxlength="500"><?= e(old('tom_tat', $item['tom_tat'] ?? '')) ?></textarea>
            <div class="form-hint">Tối đa 500 ký tự, hiển thị trên thẻ tin ở trang công khai.</div>
            <?php if (!empty($errors['tom_tat'])): ?>
                <div class="form-error"><?= e($errors['tom_tat']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('noi_dung') ?>">
            <label for="noi_dung">Nội dung <span class="required-mark">*</span></label>
            <textarea id="noi_dung" name="noi_dung" rows="16" class="admin-form__content"
                required><?= e(old('noi_dung', $item['noi_dung'] ?? '')) ?></textarea>
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
                accept="image/jpeg,image/png,image/webp" data-preview="#news-image-preview">
            <div class="form-hint"><?= e($imageHint) ?></div>
            <?php if (!empty($errors['hinh_dai_dien'])): ?>
                <div class="form-error"><?= e($errors['hinh_dai_dien']) ?></div>
            <?php endif; ?>
            <?php if ($currentImage): ?>
                <img id="news-image-preview" class="image-preview"
                    src="<?= e(upload_url($currentImage)) ?>" alt="Ảnh đại diện hiện tại">
            <?php else: ?>
                <img id="news-image-preview" class="image-preview" alt="Xem trước ảnh đại diện" hidden>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('trang_thai') ?>">
            <label for="trang_thai">Trạng thái <span class="required-mark">*</span></label>
            <select id="trang_thai" name="trang_thai" required>
                <?php foreach ($statuses as $statusValue => $statusLabel): ?>
                    <option value="<?= e($statusValue) ?>"<?= $selectedStatus === $statusValue ? ' selected' : '' ?>>
                        <?= e($statusLabel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['trang_thai'])): ?>
                <div class="form-error"><?= e($errors['trang_thai']) ?></div>
            <?php endif; ?>
        </div>

        <div class="<?= $fieldClass('ngay_dang') ?>">
            <label for="ngay_dang">Ngày đăng</label>
            <input type="datetime-local" id="ngay_dang" name="ngay_dang"
                value="<?= e(old('ngay_dang', $publishedAtValue)) ?>">
            <div class="form-hint"><?= e($publishedAtHint) ?></div>
            <?php if (!empty($errors['ngay_dang'])): ?>
                <div class="form-error"><?= e($errors['ngay_dang']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-navy">Lưu</button>
            <a class="btn btn-link" href="<?= e(url('/admin/tin-tuc')) ?>">Hủy</a>
        </div>
    </form>
</section>
