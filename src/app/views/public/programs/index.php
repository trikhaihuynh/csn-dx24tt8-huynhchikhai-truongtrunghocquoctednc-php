<section class="page-banner">
    <div class="container">
        <h1 class="page-banner__title">Chương trình đào tạo</h1>
        <p class="page-banner__subtitle">Academic Programs</p>
    </div>
</section>

<section class="section section--white">
    <div class="container">
        <h2 class="section-title">Khung chương trình</h2>
        <p class="section-lead">Nhà trường triển khai song song các hệ chương trình quốc tế và chương trình quốc gia, giúp học sinh lựa chọn lộ trình học tập phù hợp với năng lực và định hướng đại học.</p>

        <?php if ($programs): ?>
            <div class="program-rows">
                <?php foreach ($programs as $program): ?>
                    <?php $programUrl = url('/chuong-trinh/' . $program['slug']); ?>
                    <article class="program-row">
                        <div class="program-row__content">
                            <span class="icon-circle program-row__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9l10-5 10 5-10 5z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/><path d="M22 9v6"/></svg>
                            </span>
                            <div class="program-row__text">
                                <h3 class="program-row__title"><a href="<?= e($programUrl) ?>"><?= e($program['ten']) ?></a></h3>
                                <?php if (!empty($program['mo_ta'])): ?>
                                    <p class="program-row__description"><?= e($program['mo_ta']) ?></p>
                                <?php endif; ?>
                                <a class="card__more" href="<?= e($programUrl) ?>">Xem chi tiết</a>
                            </div>
                        </div>
                        <a class="program-row__media" href="<?= e($programUrl) ?>" tabindex="-1" aria-hidden="true">
                            <img class="program-row__image" src="<?= e(upload_url($program['hinh_dai_dien'])) ?>" alt="" loading="lazy" width="800" height="500">
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-state">Hiện chưa có chương trình đào tạo nào được công bố.</p>
        <?php endif; ?>
    </div>
</section>

<section class="cta-banner">
    <div class="container cta-banner__inner">
        <div class="cta-banner__content">
            <h2 class="cta-banner__title">Chọn lộ trình phù hợp cho con</h2>
            <p class="cta-banner__text">Đăng ký nhập học trực tuyến, bộ phận tuyển sinh sẽ liên hệ tư vấn chương trình chi tiết.</p>
        </div>
        <div class="cta-banner__actions">
            <a class="btn btn-gold btn-lg" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký ngay</a>
            <a class="btn btn-outline-light btn-lg" href="<?= e(url('/lien-he')) ?>">Liên hệ tư vấn</a>
        </div>
    </div>
</section>
