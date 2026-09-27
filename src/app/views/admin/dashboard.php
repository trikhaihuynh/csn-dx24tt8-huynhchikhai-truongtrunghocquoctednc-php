<?php
use App\Core\Auth;

$currentUser = Auth::user();
$statIcons = [
    'news' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
    'programs' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
    'gallery' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
    'new-admissions' => '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>',
    'recent-admissions' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
];
?>
<div class="admin-page-header">
    <div>
        <h1>Trang chính</h1>
        <p class="admin-page-header__lead">Xin chào, <?= e($currentUser['ho_ten'] ?? '') ?>. Hôm nay là ngày <?= e(date('d/m/Y')) ?>.</p>
    </div>
</div>

<section class="admin-card dashboard-overview" aria-labelledby="dashboard-overview-title">
    <h2 id="dashboard-overview-title" class="admin-card__title">Tổng quan nhanh</h2>
    <div class="stat-grid">
        <?php foreach ($stats as $stat): ?>
            <a class="stat-card stat-card--<?= e($stat['key']) ?>" href="<?= e(url($stat['link'])) ?>">
                <span class="stat-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $statIcons[$stat['key']] ?? '' ?></svg>
                </span>
                <span class="stat-card__body">
                    <span class="stat-card__value"><?= e($stat['value']) ?></span>
                    <span class="stat-card__label"><?= e($stat['label']) ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<div class="dashboard-panels">
    <section class="admin-card dashboard-panel" aria-labelledby="latest-admissions-title">
        <div class="admin-card__header">
            <h2 id="latest-admissions-title" class="admin-card__title">Đăng ký mới nhất</h2>
            <a class="admin-card__link" href="<?= e(url('/admin/dang-ky')) ?>">Xem tất cả</a>
        </div>
        <?php if ($latestAdmissions === []): ?>
            <p class="admin-empty">Chưa có đăng ký nhập học nào.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Họ tên học sinh</th>
                            <th scope="col">Email</th>
                            <th scope="col">Điện thoại</th>
                            <th scope="col">Chương trình</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày gửi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestAdmissions as $registration): ?>
                            <tr>
                                <td class="table__strong"><?= e($registration['ho_ten']) ?></td>
                                <td><?= e($registration['email']) ?></td>
                                <td class="table__nowrap"><?= e($registration['dien_thoai']) ?></td>
                                <td><?= e($registration['ten_chuong_trinh'] ?? 'Chưa chọn') ?></td>
                                <td><span class="badge badge-<?= e($registration['trang_thai']) ?>"><?= e($admissionStatuses[$registration['trang_thai']] ?? $registration['trang_thai']) ?></span></td>
                                <td class="table__nowrap"><?= e(format_date($registration['ngay_tao'], 'd/m/Y H:i')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="admin-card dashboard-panel" aria-labelledby="recent-news-title">
        <div class="admin-card__header">
            <h2 id="recent-news-title" class="admin-card__title">Tin gần đây</h2>
            <a class="admin-card__link" href="<?= e(url('/admin/tin-tuc')) ?>">Xem tất cả</a>
        </div>
        <?php if ($recentNews === []): ?>
            <p class="admin-empty">Chưa có tin tức nào.</p>
        <?php else: ?>
            <ul class="recent-list">
                <?php foreach ($recentNews as $article): ?>
                    <li class="recent-list__item">
                        <div class="recent-list__main">
                            <span class="recent-list__title"><?= e($article['tieu_de']) ?></span>
                            <span class="recent-list__meta">Ngày đăng <?= e(format_date($article['ngay_dang'])) ?> · <?= e($article['luot_xem']) ?> lượt xem</span>
                        </div>
                        <span class="badge badge-<?= e($article['trang_thai']) ?>"><?= e($newsStatuses[$article['trang_thai']] ?? $article['trang_thai']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</div>
