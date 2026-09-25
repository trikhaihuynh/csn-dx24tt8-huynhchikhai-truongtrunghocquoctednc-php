<?php
use App\Core\Csrf;

$isFiltered = $keyword !== '' || $album !== '';
?>
<div class="admin-page-header">
    <div>
        <h1>Quản lý hình ảnh</h1>
        <p class="admin-page-header__lead">
            Tổng số: <?= e($pager['total']) ?> hình ảnh<?= $isFiltered ? ' phù hợp bộ lọc' : '' ?>.
        </p>
    </div>
    <a class="btn btn-gold" href="<?= e(url('/admin/hinh-anh/them')) ?>">+ Thêm mới</a>
</div>

<section class="admin-card">
    <form class="admin-filter" method="get" action="<?= e(url('/admin/hinh-anh')) ?>" role="search">
        <div class="admin-filter__field admin-filter__field--grow">
            <label class="visually-hidden" for="filter-keyword">Tìm theo tiêu đề</label>
            <input type="search" id="filter-keyword" name="q" maxlength="200"
                value="<?= e($keyword) ?>" placeholder="Tìm theo tiêu đề ảnh...">
        </div>
        <div class="admin-filter__field">
            <label class="visually-hidden" for="filter-album">Album</label>
            <select id="filter-album" name="album">
                <option value="">Tất cả album</option>
                <?php foreach ($albums as $albumValue => $albumLabel): ?>
                    <?php $albumValue = (string) $albumValue; ?>
                    <option value="<?= e($albumValue) ?>"<?= $album === $albumValue ? ' selected' : '' ?>>
                        <?= e($albumLabel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-filter__actions">
            <button type="submit" class="btn btn-navy">Tìm kiếm</button>
            <?php if ($isFiltered): ?>
                <a class="btn btn-link" href="<?= e(url('/admin/hinh-anh')) ?>">Xóa lọc</a>
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
                    <th scope="col">Album</th>
                    <th scope="col">Thứ tự</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr>
                        <td colspan="8" class="admin-empty">
                            <?= $isFiltered ? 'Không có hình ảnh nào phù hợp.' : 'Chưa có hình ảnh nào.' ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($items as $position => $image): ?>
                    <?php
                    $editUrl = url('/admin/hinh-anh/' . $image['id'] . '/sua');
                    $deleteUrl = url('/admin/hinh-anh/' . $image['id'] . '/xoa');
                    $imageUrl = upload_url($image['duong_dan']);
                    $isVisible = (string) $image['trang_thai'] === '1';
                    $imageAlbum = (string) ($image['album'] ?? '');
                    ?>
                    <tr>
                        <td class="admin-table__index"><?= e($pager['offset'] + $position + 1) ?></td>
                        <td>
                            <a href="<?= e($imageUrl) ?>" target="_blank" rel="noopener">
                                <img class="admin-thumb" src="<?= e($imageUrl) ?>"
                                    alt="<?= e($image['tieu_de']) ?>" width="72" height="45" loading="lazy">
                            </a>
                        </td>
                        <td class="admin-table__title">
                            <a class="table__strong" href="<?= e($editUrl) ?>"><?= e($image['tieu_de']) ?></a>
                            <?php if (!empty($image['mo_ta'])): ?>
                                <span class="admin-table__meta"><?= e(mb_strimwidth($image['mo_ta'], 0, 90, '…', 'UTF-8')) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="table__nowrap">
                            <?php if ($imageAlbum !== ''): ?>
                                <?= e($albums[$imageAlbum] ?? $imageAlbum) ?>
                            <?php else: ?>
                                <span class="admin-table__meta">Chưa phân album</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($image['thu_tu']) ?></td>
                        <td>
                            <span class="badge <?= $isVisible ? 'badge-visible' : 'badge-hidden' ?>">
                                <?= e($statuses[$isVisible ? '1' : '0']) ?>
                            </span>
                        </td>
                        <td class="table__nowrap"><?= e(format_date($image['ngay_tao'], 'd/m/Y H:i')) ?></td>
                        <td>
                            <div class="table-actions">
                                <a class="btn btn-sm btn-outline" href="<?= e($editUrl) ?>">Sửa</a>
                                <form method="post" action="<?= e($deleteUrl) ?>" data-confirm="Xóa hình ảnh này và tệp ảnh kèm theo?">
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
