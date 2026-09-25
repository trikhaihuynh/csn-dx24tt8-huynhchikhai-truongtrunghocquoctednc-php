<section class="page-banner">
    <div class="container">
        <h1 class="page-banner__title">Liên hệ</h1>
        <p class="page-banner__subtitle"><?= e($schoolName) ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Thông tin liên hệ</h2>
            <p class="section-lead">Phụ huynh và học sinh có thể liên hệ nhà trường qua các kênh dưới đây để được tư vấn tuyển sinh và giải đáp thắc mắc.</p>
        </div>

        <div class="grid-3 contact-grid">
            <article class="contact-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                </span>
                <h3 class="contact-card__title">Địa chỉ</h3>
                <p class="contact-card__value"><?= $address !== '' ? e($address) : 'Đang cập nhật' ?></p>
            </article>
            <article class="contact-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
                </span>
                <h3 class="contact-card__title">Điện thoại</h3>
                <p class="contact-card__value">
                    <?php if ($phoneNumber !== ''): ?>
                        <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phoneNumber)) ?>"><?= e($phoneNumber) ?></a>
                    <?php else: ?>
                        Đang cập nhật
                    <?php endif; ?>
                </p>
            </article>
            <article class="contact-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                </span>
                <h3 class="contact-card__title">Email</h3>
                <p class="contact-card__value">
                    <?php if ($emailAddress !== ''): ?>
                        <a href="mailto:<?= e($emailAddress) ?>"><?= e($emailAddress) ?></a>
                    <?php else: ?>
                        Đang cập nhật
                    <?php endif; ?>
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section section--white">
    <div class="container contact-extra">
        <div class="contact-extra__block">
            <h2 class="section-title">Mạng xã hội</h2>
            <p class="section-lead">Theo dõi nhà trường để cập nhật hoạt động, sự kiện và thông tin tuyển sinh mới nhất.</p>
            <?php if ($socialLinks): ?>
                <ul class="social-buttons">
                    <?php foreach ($socialLinks as $networkName => $networkUrl): ?>
                        <li><a class="btn btn-outline" href="<?= e(safe_url($networkUrl)) ?>" target="_blank" rel="noopener noreferrer"><?= e($networkName) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="empty-state">Kênh mạng xã hội đang được cập nhật.</p>
            <?php endif; ?>
        </div>
        <div class="contact-extra__block contact-extra__block--highlight">
            <h2 class="contact-extra__title">Tư vấn tuyển sinh</h2>
            <p>Gửi phiếu đăng ký nhập học trực tuyến, bộ phận tuyển sinh sẽ liên hệ lại với phụ huynh trong thời gian sớm nhất.</p>
            <a class="btn btn-gold btn-lg" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký nhập học</a>
        </div>
    </div>
</section>
