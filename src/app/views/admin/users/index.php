<?php
use App\Core\Csrf;

$isFiltered = $keyword !== '';
$emptyMessage = $isFiltered ? 'Không có tài khoản nào phù hợp.' : 'Chưa có tài khoản nào.';
?>
<div class="admin-page-header">
    <div>
        <h1>Quản lý tài khoản</h1>
        <p class="admin-page-header__lead">
            Tổng số: <?= e($pager['total']) ?> tài khoản<?= $isFiltered ? ' phù hợp bộ lọc' : '' ?>.
        </p>
    </div>
    <a class="btn btn-gold" href="<?= e(url('/admin/tai-khoan/them')) ?>">+ Thêm mới</a>
</div>

<section class="admin-card">
    <form class="admin-filter" method="get" action="<?= e(url('/admin/tai-khoan')) ?>" role="search">
        <div class="admin-filter__field admin-filter__field--grow">
            <label class="visually-hidden" for="filter-keyword">Tìm theo họ tên hoặc email</label>
            <input type="search" id="filter-keyword" name="q" maxlength="150"
                value="<?= e($keyword) ?>" placeholder="Tìm theo họ tên hoặc email...">
        </div>
        <div class="admin-filter__actions">
            <button type="submit" class="btn btn-navy">Tìm kiếm</button>
            <?php if ($isFiltered): ?>
                <a class="btn btn-link" href="<?= e(url('/admin/tai-khoan')) ?>">Xóa lọc</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th scope="col" class="admin-table__index">STT</th>
                    <th scope="col">Họ tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Vai trò</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr>
                        <td colspan="7" class="admin-empty">
                            <?= e($emptyMessage) ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($items as $position => $account): ?>
                    <?php $isSelf = (int) $account['id'] === $currentUserId; ?>
                    <?php $isActive = (string) $account['trang_thai'] === '1'; ?>
                    <?php $editUrl = url('/admin/tai-khoan/' . $account['id'] . '/sua'); ?>
                    <?php $deleteUrl = url('/admin/tai-khoan/' . $account['id'] . '/xoa'); ?>
                    <tr>
                        <td class="admin-table__index"><?= e($pager['offset'] + $position + 1) ?></td>
                        <td>
                            <a class="table__strong" href="<?= e($editUrl) ?>"><?= e($account['ho_ten']) ?></a>
                            <?php if ($isSelf): ?>
                                <span class="admin-table__meta">Tài khoản của bạn</span>
                            <?php endif; ?>
                        </td>
                        <td class="table__nowrap"><?= e($account['email']) ?></td>
                        <td>
                            <span class="badge badge-role-<?= e($account['vai_tro']) ?>">
                                <?= e($roles[$account['vai_tro']] ?? $account['vai_tro']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $isActive ? 'badge-account-active' : 'badge-account-locked' ?>">
                                <?= e($statuses[(string) $account['trang_thai']] ?? $account['trang_thai']) ?>
                            </span>
                        </td>
                        <td class="table__nowrap"><?= e(format_date($account['ngay_tao'], 'd/m/Y')) ?></td>
                        <td>
                            <div class="table-actions">
                                <a class="btn btn-sm btn-outline" href="<?= e($editUrl) ?>">Sửa</a>
                                <?php if (!$isSelf): ?>
                                    <form method="post" action="<?= e($deleteUrl) ?>"
                                        onsubmit="return confirm('Xóa tài khoản này?')">
                                        <?= Csrf::field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require APP_PATH . '/views/partials/pagination.php'; ?>
</section>
