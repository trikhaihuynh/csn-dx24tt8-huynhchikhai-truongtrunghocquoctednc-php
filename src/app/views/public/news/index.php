<section class="page-banner">
    <div class="container">
        <h1 class="page-banner__title">Tin tức &amp; Sự kiện</h1>
        <p class="page-banner__subtitle">News &amp; Events</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($articles): ?>
            <div class="news-grid">
                <?php foreach ($articles as $article): ?>
                    <?php $articleUrl = url('/tin-tuc/' . $article['slug']); ?>
                    <article class="card news-card">
                        <a class="news-card__media" href="<?= e($articleUrl) ?>" tabindex="-1" aria-hidden="true">
                            <img class="card__image" src="<?= e(upload_url($article['hinh_dai_dien'])) ?>" alt="" loading="lazy" width="800" height="500">
                        </a>
                        <div class="card__body news-card__body">
                            <time class="card__meta news-card__date" datetime="<?= e(format_date($article['ngay_dang'], 'Y-m-d')) ?>"><?= e(format_date($article['ngay_dang'])) ?></time>
                            <h2 class="card__title news-card__title"><a href="<?= e($articleUrl) ?>"><?= e($article['tieu_de']) ?></a></h2>
                            <?php if (!empty($article['tom_tat'])): ?>
                                <p class="card__text news-card__summary"><?= e($article['tom_tat']) ?></p>
                            <?php endif; ?>
                            <a class="card__more news-card__more" href="<?= e($articleUrl) ?>">Đọc tiếp</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php require APP_PATH . '/views/partials/pagination.php'; ?>
        <?php else: ?>
            <p class="empty-state">Hiện chưa có bài viết nào được công bố.</p>
        <?php endif; ?>
    </div>
</section>
