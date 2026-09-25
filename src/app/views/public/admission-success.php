<section class="page-banner">
    <div class="container">
        <h1 class="page-banner__title">Đăng ký nhập học</h1>
        <p class="page-banner__subtitle">Admission Application</p>
    </div>
</section>

<section class="section admission-section">
    <div class="container container--narrow">
        <div class="form-card admission-success">
            <span class="admission-success__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
            </span>
            <h2 class="admission-success__title">Gửi đăng ký thành công</h2>
            <p class="admission-success__text">Cảm ơn phụ huynh đã quan tâm và gửi phiếu đăng ký nhập học. Bộ phận tuyển sinh sẽ xem xét hồ sơ và liên hệ lại qua điện thoại hoặc email trong thời gian sớm nhất.</p>
            <?php if ($phoneNumber !== '' || $emailAddress !== ''): ?>
                <p class="admission-success__contact">
                    Cần hỗ trợ thêm, vui lòng liên hệ
                    <?php if ($phoneNumber !== ''): ?>
                        <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phoneNumber)) ?>"><?= e($phoneNumber) ?></a>
                    <?php endif; ?>
                    <?php if ($phoneNumber !== '' && $emailAddress !== ''): ?>hoặc<?php endif; ?>
                    <?php if ($emailAddress !== ''): ?>
                        <a href="mailto:<?= e($emailAddress) ?>"><?= e($emailAddress) ?></a>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <div class="admission-success__actions">
                <a class="btn btn-navy" href="<?= e(url('/')) ?>">Về trang chủ</a>
                <a class="btn btn-outline" href="<?= e(url('/chuong-trinh')) ?>">Xem chương trình đào tạo</a>
            </div>
        </div>
    </div>
</section>
