<section class="hero hero--home" style="background-image: url('<?= e(asset('img/hero-campus.svg')) ?>');">
    <div class="container hero__inner">
        <p class="hero__eyebrow"><?= e($schoolName) ?></p>
        <h1 class="hero__title"><?= e($slogan) ?></h1>
        <div class="hero__actions">
            <a class="btn btn-gold btn-lg" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký nhập học</a>
            <a class="btn btn-outline-light btn-lg" href="<?= e(url('/gioi-thieu')) ?>">Tìm hiểu thêm</a>
        </div>
    </div>
</section>

<section class="section section--white">
    <div class="container container--narrow text-center">
        <h2 class="section-title">Chào mừng đến với <?= e($schoolName) ?></h2>
        <p class="section-lead"><?= e($introduction) ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Vì sao chọn DNC</h2>
        </div>
        <div class="grid-3 feature-grid">
            <article class="feature-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9l10-5 10 5-10 5z"/><path d="M6 11v5c3 2 9 2 12 0v-5"/><path d="M22 9v6"/></svg>
                </span>
                <h3 class="feature-card__title">Học thuật xuất sắc</h3>
                <p class="feature-card__text">Chương trình học chuẩn quốc tế, đội ngũ giáo viên tận tâm và phương pháp học chủ động giúp học sinh đạt kết quả cao.</p>
            </article>
            <article class="feature-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.5 2.7 3.8 5.7 3.8 9s-1.3 6.3-3.8 9c-2.5-2.7-3.8-5.7-3.8-9S9.5 5.7 12 3z"/></svg>
                </span>
                <h3 class="feature-card__title">Tầm nhìn toàn cầu</h3>
                <p class="feature-card__text">Môi trường song ngữ, hoạt động giao lưu quốc tế giúp học sinh tự tin hội nhập và sẵn sàng cho bậc đại học.</p>
            </article>
            <article class="feature-card">
                <span class="icon-circle" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z"/></svg>
                </span>
                <h3 class="feature-card__title">Giáo dục toàn diện</h3>
                <p class="feature-card__text">Cân bằng giữa học tập, thể thao, nghệ thuật và kỹ năng sống để mỗi học sinh phát huy tối đa tiềm năng.</p>
            </article>
        </div>
    </div>
</section>

<section class="section section--white">
    <div class="container">
        <div class="section-heading">
            <h2 class="section-title">Chương trình đào tạo</h2>
            <a class="section-heading__link" href="<?= e(url('/chuong-trinh')) ?>">Xem tất cả chương trình</a>
        </div>
        <?php if ($programs): ?>
            <div class="grid-3">
                <?php foreach ($programs as $program): ?>
                    <?php $programUrl = url('/chuong-trinh/' . $program['slug']); ?>
                    <article class="card program-card">
                        <a href="<?= e($programUrl) ?>" tabindex="-1" aria-hidden="true">
                            <img class="card__image" src="<?= e(upload_url($program['hinh_dai_dien'])) ?>" alt="" loading="lazy" width="800" height="500">
                        </a>
                        <div class="card__body">
                            <h3 class="card__title"><a href="<?= e($programUrl) ?>"><?= e($program['ten']) ?></a></h3>
                            <p class="card__text"><?= e($program['mo_ta']) ?></p>
                            <a class="card__more" href="<?= e($programUrl) ?>">Xem chi tiết</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-state">Thông tin chương trình đào tạo đang được cập nhật.</p>
        <?php endif; ?>
    </div>
</section>

<section class="section section--navy">
    <div class="container">
        <div class="section-heading">
            <h2 class="section-title section-title--light">Đời sống học sinh</h2>
            <a class="section-heading__link section-heading__link--light" href="<?= e(url('/hinh-anh')) ?>">Xem thư viện ảnh</a>
        </div>
        <?php if ($galleryImages): ?>
            <div class="gallery-strip">
                <?php foreach ($galleryImages as $galleryImage): ?>
                    <a class="gallery-strip__item" href="<?= e(url('/hinh-anh')) ?>">
                        <img class="gallery-strip__image" src="<?= e(upload_url($galleryImage['duong_dan'])) ?>" alt="<?= e($galleryImage['tieu_de']) ?>" loading="lazy" width="800" height="500">
                        <span class="gallery-strip__caption"><?= e($galleryImage['tieu_de']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-state empty-state--light">Hình ảnh hoạt động đang được cập nhật.</p>
        <?php endif; ?>
    </div>
</section>

<section class="section">
    <div class="container home-news">
        <div class="home-news__main">
            <div class="section-heading">
                <h2 class="section-title">Tin tức mới nhất</h2>
                <a class="section-heading__link" href="<?= e(url('/tin-tuc')) ?>">Xem tất cả tin</a>
            </div>
            <?php if ($latestNews): ?>
                <div class="news-list">
                    <?php foreach ($latestNews as $newsItem): ?>
                        <?php $newsUrl = url('/tin-tuc/' . $newsItem['slug']); ?>
                        <article class="news-item">
                            <a class="news-item__thumb" href="<?= e($newsUrl) ?>" tabindex="-1" aria-hidden="true">
                                <img src="<?= e(upload_url($newsItem['hinh_dai_dien'])) ?>" alt="" loading="lazy" width="800" height="500">
                            </a>
                            <div class="news-item__body">
                                <time class="news-item__date" datetime="<?= e(format_date($newsItem['ngay_dang'], 'Y-m-d')) ?>"><?= e(format_date($newsItem['ngay_dang'])) ?></time>
                                <h3 class="news-item__title"><a href="<?= e($newsUrl) ?>"><?= e($newsItem['tieu_de']) ?></a></h3>
                                <p class="news-item__summary"><?= e($newsItem['tom_tat']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty-state">Chưa có tin tức mới.</p>
            <?php endif; ?>
        </div>

        <aside class="quick-links" aria-labelledby="quick-links-title">
            <h2 class="quick-links__title" id="quick-links-title">Liên kết nhanh</h2>
            <ul class="quick-links__list">
                <li><a href="<?= e(url('/gioi-thieu')) ?>">Giới thiệu trường</a></li>
                <li><a href="<?= e(url('/chuong-trinh')) ?>">Chương trình đào tạo</a></li>
                <li><a href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Tuyển sinh</a></li>
                <li><a href="<?= e(url('/hinh-anh')) ?>">Đời sống học sinh</a></li>
                <li><a href="<?= e(url('/tin-tuc')) ?>">Tin tức &amp; Sự kiện</a></li>
                <li><a href="<?= e(url('/lien-he')) ?>">Liên hệ</a></li>
            </ul>
        </aside>
    </div>
</section>

<section class="cta-banner">
    <div class="container cta-banner__inner">
        <div class="cta-banner__content">
            <h2 class="cta-banner__title">Tuyển sinh năm học mới</h2>
            <p class="cta-banner__text">Đăng ký trực tuyến chỉ trong vài phút, nhà trường sẽ liên hệ tư vấn chương trình phù hợp cho con.</p>
        </div>
        <div class="cta-banner__actions">
            <a class="btn btn-gold btn-lg" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký ngay</a>
            <a class="btn btn-outline-light btn-lg" href="<?= e(url('/lien-he')) ?>">Liên hệ tư vấn</a>
        </div>
    </div>
</section>
