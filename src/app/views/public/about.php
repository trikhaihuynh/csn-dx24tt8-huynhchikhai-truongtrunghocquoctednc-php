<section class="page-banner">
    <div class="container">
        <h1 class="page-banner__title">Giới thiệu</h1>
        <?php if ($schoolNameEnglish !== ''): ?>
            <p class="page-banner__subtitle"><?= e($schoolNameEnglish) ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="section section--white">
    <div class="container about-intro">
        <div class="about-intro__content">
            <h2 class="section-title"><?= e($schoolName) ?></h2>
            <p class="section-lead"><?= e($introduction) ?></p>
            <?php if ($slogan !== ''): ?>
                <blockquote class="about-intro__quote"><?= e($slogan) ?></blockquote>
            <?php endif; ?>
        </div>
        <img class="about-intro__image" src="<?= e(asset('img/hero-campus.svg')) ?>" alt="Hình minh họa khuôn viên trường" width="1600" height="800">
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid-2">
            <article class="value-panel">
                <h2 class="value-panel__title">Sứ mệnh</h2>
                <p class="value-panel__text">Xây dựng môi trường học tập chuẩn quốc tế, an toàn và thân thiện, nơi mỗi học sinh được khuyến khích khám phá năng lực bản thân, rèn luyện tư duy độc lập và tinh thần trách nhiệm với cộng đồng.</p>
            </article>
            <article class="value-panel">
                <h2 class="value-panel__title">Tầm nhìn</h2>
                <p class="value-panel__text">Trở thành ngôi trường trung học quốc tế được phụ huynh tin chọn, đào tạo thế hệ công dân toàn cầu giữ gìn bản sắc Việt Nam và sẵn sàng thành công ở bậc đại học trong và ngoài nước.</p>
            </article>
        </div>
    </div>
</section>

<section class="section section--white">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Giá trị cốt lõi</h2>
        </div>
        <div class="grid-4 feature-grid">
            <article class="feature-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/><path d="M9 12l2 2 4-4"/></svg>
                </span>
                <h3 class="feature-card__title">Chính trực</h3>
                <p class="feature-card__text">Trung thực trong học tập và ứng xử, giữ lời hứa và tôn trọng quy tắc chung.</p>
            </article>
            <article class="feature-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/><path d="M15 14.5c3 0 6 1.8 6 5.5"/></svg>
                </span>
                <h3 class="feature-card__title">Tôn trọng</h3>
                <p class="feature-card__text">Lắng nghe, sẻ chia và trân trọng sự khác biệt văn hóa trong cộng đồng nhà trường.</p>
            </article>
            <article class="feature-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 21h4"/><path d="M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2.1h5c0-.9.4-1.6 1-2.1A6 6 0 0 0 12 3z"/></svg>
                </span>
                <h3 class="feature-card__title">Sáng tạo</h3>
                <p class="feature-card__text">Khuyến khích đặt câu hỏi, thử nghiệm ý tưởng mới và giải quyết vấn đề thực tế.</p>
            </article>
            <article class="feature-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 5.6-7 10-7 10z"/><path d="M9 11h6"/><path d="M12 8v6"/></svg>
                </span>
                <h3 class="feature-card__title">Trách nhiệm</h3>
                <p class="feature-card__text">Có trách nhiệm với bản thân, gia đình, nhà trường và xã hội qua từng hành động.</p>
            </article>
        </div>
    </div>
</section>

<?php if ($programs): ?>
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <h2 class="section-title">Hệ chương trình</h2>
                <a class="section-heading__link" href="<?= e(url('/chuong-trinh')) ?>">Xem chi tiết các chương trình</a>
            </div>
            <ul class="program-list">
                <?php foreach ($programs as $program): ?>
                    <li class="program-list__item">
                        <a class="program-list__name" href="<?= e(url('/chuong-trinh/' . $program['slug'])) ?>"><?= e($program['ten']) ?></a>
                        <span class="program-list__description"><?= e($program['mo_ta']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
<?php endif; ?>

<section class="cta-banner">
    <div class="container cta-banner__inner">
        <div class="cta-banner__content">
            <h2 class="cta-banner__title">Đồng hành cùng <?= e($schoolName) ?></h2>
            <p class="cta-banner__text">Tìm hiểu chương trình học và đăng ký nhập học trực tuyến cho con ngay hôm nay.</p>
        </div>
        <div class="cta-banner__actions">
            <a class="btn btn-gold btn-lg" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký ngay</a>
            <a class="btn btn-outline-light btn-lg" href="<?= e(url('/lien-he')) ?>">Liên hệ</a>
        </div>
    </div>
</section>
