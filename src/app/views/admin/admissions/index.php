<?php
$isFiltered = $keyword !== '' || $status !== null;
$emptyMessage = $isFiltered ? 'Không có hồ sơ nào phù hợp.' : 'Chưa có hồ sơ đăng ký nào.';
?>
<div class="admin-page-header">
    <div>
        <h1>Đăng ký nhập học</h1>
        <p class="admin-page-header__lead">
            Tổng số: <?= e($pager['total']) ?> hồ sơ<?= $isFiltered ? ' phù hợp bộ lọc' : '' ?>.
        </p>
    </div>
</div>

<section class="admin-card">
    <form class="admin-filter" method="get" action="<?= e(url('/admin/dang-ky')) ?>" role="search">
        <div class="admin-filter__field admin-filter__field--grow">
            <label class="visually-hidden" for="filter-keyword">
                Tìm theo họ tên, email hoặc điện thoại
            </label>
            <input type="search" id="filter-keyword" name="q" maxlength="150"
                value="<?= e($keyword) ?>" placeholder="Tìm theo họ tên, email, điện thoại...">
        </div>
        <div class="admin-filter__field">
            <label class="visually-hidden" for="filter-status">Trạng thái</label>
            <select id="filter-status" name="trang_thai">
                <option value="">Tất cả trạng thái</option>
                <?php foreach ($statuses as $statusValue => $statusLabel): ?>
                    <option value="<?= e($statusValue) ?>"<?= $status === $statusValue ? ' selected' : '' ?>>
                        <?= e($statusLabel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-filter__actions">
            <button type="submit" class="btn btn-navy">Tìm kiếm</button>
            <?php if ($isFiltered): ?>
                <a class="btn btn-link" href="<?= e(url('/admin/dang-ky')) ?>">Xóa lọc</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th scope="col" class="admin-table__index">STT</th>
                    <th scope="col">Học sinh</th>
                    <th scope="col">Phụ huynh</th>
                    <th scope="col">Email</th>
                    <th scope="col">Điện thoại</th>
                    <th scope="col">Khối</th>
                    <th scope="col">Chương trình</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Ngày gửi</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr>
                        <td colspan="10" class="admin-empty">
                            <?= e($emptyMessage) ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($items as $position => $registration): ?>
                    <?php $showUrl = url('/admin/dang-ky/' . $registration['id']); ?>
                    <?php $statusLabel = $statuses[$registration['trang_thai']] ?? $registration['trang_thai']; ?>
                    <tr>
                        <td class="admin-table__index"><?= e($pager['offset'] + $position + 1) ?></td>
                        <td>
                            <a class="table__strong" href="<?= e($showUrl) ?>"><?= e($registration['ho_ten']) ?></a>
                        </td>
                        <td>
                            <?= e($registration['ten_phu_huynh'] ?? '') ?>
                            <?php if (!empty($registration['quan_he'])): ?>
                                <span class="admin-table__meta"><?= e($registration['quan_he']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="table__nowrap"><?= e($registration['email']) ?></td>
                        <td class="table__nowrap"><?= e($registration['dien_thoai']) ?></td>
                        <td><?= e($registration['khoi_lop'] ?? '') ?></td>
                        <td><?= e($registration['ten_chuong_trinh'] ?? 'Chưa chọn') ?></td>
                        <td>
                            <span class="badge badge-<?= e($registration['trang_thai']) ?>">
                                <?= e($statusLabel) ?>
                            </span>
                        </td>
                        <td class="table__nowrap">
                            <?= e(format_date($registration['ngay_tao'])) ?>
                            <span class="admin-table__meta">
                                <?= e(format_date($registration['ngay_tao'], 'H:i')) ?>
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-outline" href="<?= e($showUrl) ?>">Xem</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require APP_PATH . '/views/partials/pagination.php'; ?>
</section>
