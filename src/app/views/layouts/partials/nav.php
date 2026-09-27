<?php
$menuItems = [
    '/' => 'Trang chủ',
    '/gioi-thieu' => 'Giới thiệu',
    '/chuong-trinh' => 'Chương trình đào tạo',
    '/dang-ky-nhap-hoc' => 'Tuyển sinh',
    '/hinh-anh' => 'Đời sống học sinh',
    '/tin-tuc' => 'Tin tức & Sự kiện',
    '/lien-he' => 'Liên hệ',
];
?>
<nav class="main-nav" aria-label="Menu chính">
    <ul class="main-nav__list">
        <?php foreach ($menuItems as $menuPath => $menuLabel): ?>
            <li class="main-nav__item">
                <a class="main-nav__link <?= is_active($menuPath) ?>" href="<?= e(url($menuPath)) ?>"<?= is_active($menuPath) ? ' aria-current="page"' : '' ?>><?= e($menuLabel) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
