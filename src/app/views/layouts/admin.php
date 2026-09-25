<?php
use App\Core\Auth;
use App\Core\Csrf;

$schoolName = setting('ten_truong', APP_NAME);
$pageTitle = (!empty($title) ? $title . ' | ' : '') . 'Trang quản trị – ' . $schoolName;
$currentUser = Auth::user();
$adminTabs = [
    ['path' => '/admin', 'label' => 'Trang chính', 'exact' => true, 'adminOnly' => false],
    ['path' => '/admin/tin-tuc', 'label' => 'Tin tức', 'exact' => false, 'adminOnly' => false],
    ['path' => '/admin/chuong-trinh', 'label' => 'Chương trình', 'exact' => false, 'adminOnly' => false],
    ['path' => '/admin/hinh-anh', 'label' => 'Hình ảnh', 'exact' => false, 'adminOnly' => false],
    ['path' => '/admin/dang-ky', 'label' => 'Đăng ký nhập học', 'exact' => false, 'adminOnly' => false],
    ['path' => '/admin/cai-dat', 'label' => 'Cài đặt', 'exact' => false, 'adminOnly' => true],
    ['path' => '/admin/tai-khoan', 'label' => 'Tài khoản', 'exact' => false, 'adminOnly' => true],
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" href="<?= e(asset('img/logo.svg')) ?>" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="admin-body">
    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <a class="admin-brand" href="<?= e(url('/admin')) ?>">
                <img src="<?= e(asset('img/logo.svg')) ?>" alt="Logo" width="36" height="42">
                <span class="admin-brand__text"><?= e($schoolName) ?> – Trang quản trị</span>
            </a>
            <?php if ($currentUser): ?>
                <div class="admin-user">
                    <span class="admin-user__name"><?= e($currentUser['ho_ten'] ?? '') ?></span>
                    <form method="post" action="<?= e(url('/admin/logout')) ?>" class="admin-user__logout">
                        <?= Csrf::field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-light">Đăng xuất</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($currentUser): ?>
        <nav class="admin-tabs" aria-label="Điều hướng quản trị">
            <ul class="admin-tabs__list">
                <?php foreach ($adminTabs as $tab): ?>
                    <?php if ($tab['adminOnly'] && !Auth::is('admin')) {
                        continue;
                    } ?>
                    <li><a class="admin-tabs__link <?= is_active($tab['path'], $tab['exact']) ?>" href="<?= e(url($tab['path'])) ?>"><?= e($tab['label']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>

    <main class="admin-main">
        <?php require APP_PATH . '/views/layouts/partials/flash.php'; ?>
        <div class="admin-content">
            <?= $content ?>
        </div>
    </main>

    <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
