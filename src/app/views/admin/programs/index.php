<?php
use App\Core\Csrf;

$isFiltered = $keyword !== '' || $status !== null;
?>
<div class="admin-page-header">
    <div>
        <h1>Quản lý chương trình đào tạo</h1>
        <p class="admin-page-header__lead">
            Tổng số: <?= e($pager['total']) ?> chương trình<?= $isFiltered ? ' phù hợp bộ lọc' : '' ?>.
        </p>
    </div>
    <a class="btn btn-gold" href="<?= e(url('/admin/chuong-trinh/them')) ?>">+ Thêm mới</a>
</div>

<section class="admin-card">
    <form class="admin-filter" method="get" action="<?= e(url('/admin/chuong-trinh')) ?>" role="search">
        <div class="admin-filter__field admin-filter__field--grow">
            <label class="visually-hidden" for="filter-keyword">Tìm theo tên chương trình</label>
            <input type="search" id="filter-keyword" name="q" maxlength="150"
                value="<?= e($keyword) ?>" placeholder="Tìm theo tên chương trình...">
        </div>
        <div class="admin-filter__field">
            <label class="visually-hidden" for="filter-status">Trạng thái</label>
            <select id="filter-status" name="trang_thai">
                <option value="">Tất cả trạng thái</option>
                <?php foreach ($statuses as $statusValue => $statusLabel): ?>
                    <?php $statusValue = (string) $statusValue; ?>
                    <option value="<?= e($statusValue) ?>"<?= $status === $statusValue ? ' selected' : '' ?>>
                        <?= e($statusLabel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-filter__actions">
            <button type="submit" class="btn btn-navy">Tìm kiếm</button>
            <?php if ($isFiltered): ?>
                <a class="btn btn-link" href="<?= e(url('/admin/chuong-trinh')) ?>">Xóa lọc</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th scope="col" class="admin-table__index">STT</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Tên chương trình</th>
                    <th scope="col">Thứ tự</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Ngày cập nhật</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr>
                        <td colspan="7" class="admin-empty">
                            <?= $isFiltered ? 'Không có chương trình nào phù hợp.' : 'Chưa có chương trình nào.' ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($items as $position => $program): ?>
                    <?php
                    $editUrl = url('/admin/chuong-trinh/' . $program['id'] . '/sua');
                    $deleteUrl = url('/admin/chuong-trinh/' . $program['id'] . '/xoa');
                    $isVisible = (string) $program['trang_thai'] === '1';
                    $admissionCount = (int) $program['so_dang_ky'];
                    $deleteConfirm = 'Xóa chương trình này và ảnh đại diện?';
                    if ($admissionCount > 0) {
                        $deleteConfirm .= ' Có ' . $admissionCount . ' hồ sơ đăng ký đang chọn chương trình này.'
                            . ' Các hồ sơ đăng ký sẽ mất liên kết chương trình.';
                    }
                    ?>
                    <tr>
                        <td class="admin-table__index"><?= e($pager['offset'] + $position + 1) ?></td>
                        <td>
                            <img class="admin-thumb" src="<?= e(upload_url($program['hinh_dai_dien'])) ?>"
                                alt="" width="72" height="45" loading="lazy">
                        </td>
                        <td class="admin-table__title">
                            <a class="table__strong" href="<?= e($editUrl) ?>"><?= e($program['ten']) ?></a>
                            <span class="admin-table__meta">/chuong-trinh/<?= e($program['slug']) ?></span>
                        </td>
                        <td><?= e($program['thu_tu']) ?></td>
                        <td>
                            <span class="badge <?= $isVisible ? 'badge-visible' : 'badge-hidden' ?>">
                                <?= e($statuses[$isVisible ? '1' : '0']) ?>
                            </span>
                        </td>
                        <td class="table__nowrap"><?= e(format_date($program['ngay_cap_nhat'], 'd/m/Y H:i')) ?></td>
                        <td>
                            <div class="table-actions">
                                <a class="btn btn-sm btn-outline" href="<?= e($editUrl) ?>">Sửa</a>
                                <form method="post" action="<?= e($deleteUrl) ?>" data-confirm="<?= e($deleteConfirm) ?>">
                                    <?= Csrf::field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require APP_PATH . '/views/partials/pagination.php'; ?>
</section>
