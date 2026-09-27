<?php
use App\Core\Csrf;

$isFiltered = $keyword !== '' || $status !== null;
?>
<div class="admin-page-header">
    <div>
        <h1>Quản lý tin tức</h1>
        <p class="admin-page-header__lead">
            Tổng số: <?= e($pager['total']) ?> tin<?= $isFiltered ? ' phù hợp bộ lọc' : '' ?>.
        </p>
    </div>
    <a class="btn btn-gold" href="<?= e(url('/admin/tin-tuc/them')) ?>">+ Thêm mới</a>
</div>

<section class="admin-card">
    <form class="admin-filter" method="get" action="<?= e(url('/admin/tin-tuc')) ?>" role="search">
        <div class="admin-filter__field admin-filter__field--grow">
            <label class="visually-hidden" for="filter-keyword">Tìm theo tiêu đề</label>
            <input type="search" id="filter-keyword" name="q" maxlength="200"
                value="<?= e($keyword) ?>" placeholder="Tìm theo tiêu đề...">
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
                <a class="btn btn-link" href="<?= e(url('/admin/tin-tuc')) ?>">Xóa lọc</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th scope="col" class="admin-table__index">STT</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Ngày đăng</th>
                    <th scope="col">Lượt xem</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr>
                        <td colspan="7" class="admin-empty">
                            <?= $isFiltered ? 'Không có tin tức nào phù hợp.' : 'Chưa có tin tức nào.' ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($items as $position => $article): ?>
                    <?php $editUrl = url('/admin/tin-tuc/' . $article['id'] . '/sua'); ?>
                    <?php $deleteUrl = url('/admin/tin-tuc/' . $article['id'] . '/xoa'); ?>
                    <?php $statusLabel = $statuses[$article['trang_thai']] ?? $article['trang_thai']; ?>
                    <tr>
                        <td class="admin-table__index"><?= e($pager['offset'] + $position + 1) ?></td>
                        <td>
                            <img class="admin-thumb" src="<?= e(upload_url($article['hinh_dai_dien'])) ?>"
                                alt="" width="72" height="45" loading="lazy">
                        </td>
                        <td class="admin-table__title">
                            <a class="table__strong" href="<?= e($editUrl) ?>"><?= e($article['tieu_de']) ?></a>
                            <span class="admin-table__meta">/tin-tuc/<?= e($article['slug']) ?></span>
                        </td>
                        <td>
                            <span class="badge badge-<?= e($article['trang_thai']) ?>"><?= e($statusLabel) ?></span>
                        </td>
                        <td class="table__nowrap"><?= e(format_date($article['ngay_dang'], 'd/m/Y H:i')) ?></td>
                        <td><?= e(number_format((int) $article['luot_xem'], 0, ',', '.')) ?></td>
                        <td>
                            <div class="table-actions">
                                <a class="btn btn-sm btn-outline" href="<?= e($editUrl) ?>">Sửa</a>
                                <form method="post" action="<?= e($deleteUrl) ?>"
                                    onsubmit="return confirm('Xóa tin tức này và ảnh đại diện?')">
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
