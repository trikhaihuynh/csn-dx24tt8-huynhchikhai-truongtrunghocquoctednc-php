<?php
use App\Core\Csrf;

$emptyValue = '<span class="detail-list__empty">Chưa cung cấp</span>';
$showValue = static fn (mixed $value): string => trim((string) $value) !== '' ? e($value) : $emptyValue;
$selectedStatus = old('trang_thai', $item['trang_thai']);
$adminNote = old('ghi_chu_admin', $item['ghi_chu_admin'] ?? '');
$currentStatusLabel = $statuses[$item['trang_thai']] ?? $item['trang_thai'];
$fieldClass = static fn (string $field): string => 'form-group' . (!empty($errors[$field]) ? ' has-error' : '');
?>
<div class="admin-page-header">
    <div>
        <h1>Hồ sơ đăng ký #<?= e($item['id']) ?></h1>
        <p class="admin-page-header__lead">
            Gửi lúc <?= e(format_date($item['ngay_tao'], 'd/m/Y H:i')) ?>
            · Cập nhật lần cuối <?= e(format_date($item['ngay_cap_nhat'], 'd/m/Y H:i')) ?>
        </p>
    </div>
    <a class="btn btn-outline" href="<?= e(url('/admin/dang-ky')) ?>">&larr; Danh sách đăng ký</a>
</div>

<div class="admission-detail">
    <div class="admission-detail__main">
        <section class="admin-card detail-section" aria-labelledby="student-info-title">
            <h2 id="student-info-title" class="detail-section__title">Thông tin học sinh</h2>
            <dl class="detail-list">
                <dt>Họ tên học sinh</dt>
                <dd class="detail-list__strong"><?= $showValue($item['ho_ten']) ?></dd>
                <dt>Ngày sinh</dt>
                <dd><?= $showValue(format_date($item['ngay_sinh'])) ?></dd>
                <dt>Giới tính</dt>
                <dd><?= $showValue($genders[$item['gioi_tinh'] ?? ''] ?? '') ?></dd>
                <dt>Quốc tịch</dt>
                <dd><?= $showValue($item['quoc_tich']) ?></dd>
            </dl>
        </section>

        <section class="admin-card detail-section" aria-labelledby="parent-info-title">
            <h2 id="parent-info-title" class="detail-section__title">Thông tin phụ huynh</h2>
            <dl class="detail-list">
                <dt>Họ tên phụ huynh</dt>
                <dd><?= $showValue($item['ten_phu_huynh']) ?></dd>
                <dt>Quan hệ với học sinh</dt>
                <dd><?= $showValue($item['quan_he']) ?></dd>
                <dt>Email</dt>
                <dd><a href="mailto:<?= e($item['email']) ?>"><?= e($item['email']) ?></a></dd>
                <dt>Số điện thoại</dt>
                <dd><a href="tel:<?= e($item['dien_thoai']) ?>"><?= e($item['dien_thoai']) ?></a></dd>
            </dl>
        </section>

        <section class="admin-card detail-section" aria-labelledby="education-info-title">
            <h2 id="education-info-title" class="detail-section__title">Học vấn</h2>
            <dl class="detail-list">
                <dt>Trường đang học</dt>
                <dd><?= $showValue($item['truong_hien_tai']) ?></dd>
                <dt>Khối lớp đăng ký</dt>
                <dd><?= $showValue($item['khoi_lop']) ?></dd>
                <dt>Chương trình đăng ký</dt>
                <dd><?= $showValue($item['ten_chuong_trinh']) ?></dd>
                <dt>Học bạ</dt>
                <dd>
                    <?php if ($transcriptUrl !== null): ?>
                        <a href="<?= e($transcriptUrl) ?>" target="_blank" rel="noopener">Tải học bạ</a>
                    <?php elseif (!empty($item['tep_hoc_ba'])): ?>
                        <span class="detail-list__empty">Tệp học bạ không còn trên máy chủ</span>
                    <?php else: ?>
                        <span class="detail-list__empty">Không đính kèm</span>
                    <?php endif; ?>
                </dd>
            </dl>
        </section>

        <section class="admin-card detail-section" aria-labelledby="extra-info-title">
            <h2 id="extra-info-title" class="detail-section__title">Thông tin thêm</h2>
            <dl class="detail-list">
                <dt>Biết đến trường qua</dt>
                <dd><?= $showValue($item['nguon_biet_den']) ?></dd>
                <dt>Ghi chú của phụ huynh</dt>
                <dd>
                    <?php if (trim((string) $item['ghi_chu']) !== ''): ?>
                        <?= nl2br(e($item['ghi_chu'])) ?>
                    <?php else: ?>
                        <?= $emptyValue ?>
                    <?php endif; ?>
                </dd>
            </dl>
        </section>
    </div>

    <aside class="admission-detail__side">
        <section class="admin-card detail-section" aria-labelledby="processing-title">
            <h2 id="processing-title" class="detail-section__title">Xử lý hồ sơ</h2>
            <p class="detail-section__status">
                Trạng thái hiện tại:
                <span class="badge badge-<?= e($item['trang_thai']) ?>"><?= e($currentStatusLabel) ?></span>
            </p>
            <form class="admin-form" method="post" action="<?= e($formAction) ?>">
                <?= Csrf::field() ?>
                <div class="<?= $fieldClass('trang_thai') ?>">
                    <label for="trang_thai">Trạng thái <span class="required-mark">*</span></label>
                    <select id="trang_thai" name="trang_thai" required>
                        <?php foreach ($statuses as $statusValue => $statusLabel): ?>
                            <?php $isSelected = $selectedStatus === $statusValue; ?>
                            <option value="<?= e($statusValue) ?>"<?= $isSelected ? ' selected' : '' ?>>
                                <?= e($statusLabel) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['trang_thai'])): ?>
                        <div class="form-error"><?= e($errors['trang_thai']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="<?= $fieldClass('ghi_chu_admin') ?>">
                    <label for="ghi_chu_admin">Ghi chú xử lý</label>
                    <textarea id="ghi_chu_admin" name="ghi_chu_admin" rows="5"
                        maxlength="2000"><?= e($adminNote) ?></textarea>
                    <div class="form-hint">Chỉ hiển thị trong trang quản trị, tối đa 2000 ký tự.</div>
                    <?php if (!empty($errors['ghi_chu_admin'])): ?>
                        <div class="form-error"><?= e($errors['ghi_chu_admin']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-navy">Lưu</button>
                    <a class="btn btn-link" href="<?= e(url('/admin/dang-ky')) ?>">Hủy</a>
                </div>
            </form>
        </section>

        <section class="admin-card detail-section detail-section--danger" aria-labelledby="delete-title">
            <h2 id="delete-title" class="detail-section__title">Xóa hồ sơ</h2>
            <p class="form-hint">Xóa vĩnh viễn hồ sơ này cùng tệp học bạ đính kèm.</p>
            <form method="post" action="<?= e($deleteAction) ?>"
                onsubmit="return confirm('Xóa hồ sơ đăng ký này và tệp học bạ?')">
                <?= Csrf::field() ?>
                <button type="submit" class="btn btn-sm btn-danger">Xóa hồ sơ</button>
            </form>
        </section>
    </aside>
</div>
