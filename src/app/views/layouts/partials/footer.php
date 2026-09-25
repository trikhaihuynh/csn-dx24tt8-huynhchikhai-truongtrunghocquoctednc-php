<?php
$phoneNumber = setting('dien_thoai');
$emailAddress = setting('email');
$socialLinks = array_filter([
    'Facebook' => setting('facebook'),
    'YouTube' => setting('youtube'),
]);
?>
<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__col">
            <h2 class="site-footer__title">Thông tin liên hệ</h2>
            <ul class="site-footer__list">
                <?php if (setting('dia_chi') !== ''): ?>
                    <li><span class="site-footer__label">Địa chỉ:</span> <?= e(setting('dia_chi')) ?></li>
                <?php endif; ?>
                <?php if ($phoneNumber !== ''): ?>
                    <li><span class="site-footer__label">Điện thoại:</span>
                        <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phoneNumber)) ?>"><?= e($phoneNumber) ?></a></li>
                <?php endif; ?>
                <?php if ($emailAddress !== ''): ?>
                    <li><span class="site-footer__label">Email:</span>
                        <a href="mailto:<?= e($emailAddress) ?>"><?= e($emailAddress) ?></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="site-footer__col">
            <h2 class="site-footer__title">Liên kết nhanh</h2>
            <ul class="site-footer__list">
                <li><a href="<?= e(url('/gioi-thieu')) ?>">Giới thiệu</a></li>
                <li><a href="<?= e(url('/chuong-trinh')) ?>">Chương trình đào tạo</a></li>
                <li><a href="<?= e(url('/tin-tuc')) ?>">Tin tức &amp; Sự kiện</a></li>
                <li><a href="<?= e(url('/lien-he')) ?>">Liên hệ</a></li>
            </ul>
        </div>

        <div class="site-footer__col">
            <h2 class="site-footer__title">Tuyển sinh</h2>
            <ul class="site-footer__list">
                <li><a href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký nhập học</a></li>
                <li><a href="<?= e(url('/chuong-trinh')) ?>">Chương trình đăng ký</a></li>
                <li><a href="<?= e(url('/hinh-anh')) ?>">Đời sống học sinh</a></li>
            </ul>
        </div>

        <div class="site-footer__col">
            <h2 class="site-footer__title">Mạng xã hội</h2>
            <?php if ($socialLinks): ?>
                <ul class="social-links">
                    <?php foreach ($socialLinks as $networkName => $networkUrl): ?>
                        <li><a class="social-links__item" href="<?= e($networkUrl) ?>" target="_blank" rel="noopener noreferrer"><?= e($networkName) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="site-footer__bottom">
        <div class="container">
            &copy; <?= e(date('Y')) ?> <?= e(setting('ten_truong')) ?>. Bảo lưu mọi quyền.
        </div>
    </div>
</footer>
