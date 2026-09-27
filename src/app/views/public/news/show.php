<section class="page-banner">
    <div class="container">
        <p class="page-banner__eyebrow">Tin tức &amp; Sự kiện</p>
        <h1 class="page-banner__title"><?= e($article['tieu_de']) ?></h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <nav class="breadcrumb" aria-label="Đường dẫn">
            <a href="<?= e(url('/')) ?>">Trang chủ</a>
            <span aria-hidden="true">/</span>
            <a href="<?= e(url('/tin-tuc')) ?>">Tin tức &amp; Sự kiện</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page"><?= e($article['tieu_de']) ?></span>
        </nav>

        <div class="news-detail">
            <article class="news-detail__main">
                <img class="news-detail__image" src="<?= e(upload_url($article['hinh_dai_dien'])) ?>" alt="<?= e($article['tieu_de']) ?>" width="800" height="500">
                <div class="news-detail__body">
                    <p class="news-detail__meta">
                        <span>Ngày đăng: <time datetime="<?= e(format_date($article['ngay_dang'], 'Y-m-d')) ?>"><?= e(format_date($article['ngay_dang'])) ?></time></span>
                        <span>Lượt xem: <?= e(number_format((int) $article['luot_xem'], 0, ',', '.')) ?></span>
                    </p>
                    <?php if (!empty($article['tom_tat'])): ?>
                        <p class="news-detail__lead"><?= e($article['tom_tat']) ?></p>
                    <?php endif; ?>
                    <div class="rich-content"><?= $article['noi_dung'] /* {{-- HTML admin nhập --}} */ ?></div>
                    <div class="news-detail__actions">
                        <a class="btn btn-outline" href="<?= e(url('/tin-tuc')) ?>">Tất cả tin tức</a>
                    </div>
                </div>
            </article>

            <aside class="news-detail__aside">
                <div class="aside-card">
                    <h2 class="aside-card__title">Tin mới khác</h2>
                    <?php if ($relatedArticles): ?>
                        <ul class="aside-list">
                            <?php foreach ($relatedArticles as $relatedArticle): ?>
                                <?php $relatedArticleUrl = url('/tin-tuc/' . $relatedArticle['slug']); ?>
                                <li class="aside-list__item">
                                    <a class="aside-list__media" href="<?= e($relatedArticleUrl) ?>" tabindex="-1" aria-hidden="true">
                                        <img class="aside-list__image" src="<?= e(upload_url($relatedArticle['hinh_dai_dien'])) ?>" alt="" loading="lazy" width="160" height="100">
                                    </a>
                                    <div class="aside-list__text">
                                        <a class="aside-list__name" href="<?= e($relatedArticleUrl) ?>"><?= e($relatedArticle['tieu_de']) ?></a>
                                        <time class="aside-list__date" datetime="<?= e(format_date($relatedArticle['ngay_dang'], 'Y-m-d')) ?>"><?= e(format_date($relatedArticle['ngay_dang'])) ?></time>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">Chưa có tin tức khác.</p>
                    <?php endif; ?>
                </div>

                <div class="aside-card aside-card--navy">
                    <h2 class="aside-card__title aside-card__title--light">Tuyển sinh</h2>
                    <p class="aside-card__text">Gửi hồ sơ đăng ký trực tuyến, nhà trường sẽ liên hệ tư vấn trong thời gian sớm nhất.</p>
                    <a class="btn btn-gold btn-block" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký ngay</a>
                </div>
            </aside>
        </div>
    </div>
</section>
