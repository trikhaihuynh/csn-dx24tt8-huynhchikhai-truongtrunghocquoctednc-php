<section class="page-banner">
    <div class="container">
        <p class="page-banner__eyebrow">Chương trình đào tạo</p>
        <h1 class="page-banner__title"><?= e($program['ten']) ?></h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <nav class="breadcrumb" aria-label="Đường dẫn">
            <a href="<?= e(url('/')) ?>">Trang chủ</a>
            <span aria-hidden="true">/</span>
            <a href="<?= e(url('/chuong-trinh')) ?>">Chương trình đào tạo</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page"><?= e($program['ten']) ?></span>
        </nav>

        <div class="program-detail">
            <article class="program-detail__main">
                <img class="program-detail__image" src="<?= e(upload_url($program['hinh_dai_dien'])) ?>" alt="<?= e($program['ten']) ?>" width="800" height="500">
                <div class="program-detail__body">
                    <?php if (!empty($program['mo_ta'])): ?>
                        <p class="program-detail__lead"><?= e($program['mo_ta']) ?></p>
                    <?php endif; ?>
                    <div class="rich-content"><?= $program['noi_dung'] ?? '' /* {{-- HTML admin nhập --}} */ ?></div>
                    <div class="program-detail__actions">
                        <a class="btn btn-gold btn-lg" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký ngay</a>
                        <a class="btn btn-outline" href="<?= e(url('/chuong-trinh')) ?>">Tất cả chương trình</a>
                    </div>
                </div>
            </article>

            <aside class="program-detail__aside">
                <div class="aside-card">
                    <h2 class="aside-card__title">Chương trình khác</h2>
                    <?php if ($otherPrograms): ?>
                        <ul class="aside-list">
                            <?php foreach ($otherPrograms as $otherProgram): ?>
                                <?php $otherProgramUrl = url('/chuong-trinh/' . $otherProgram['slug']); ?>
                                <li class="aside-list__item">
                                    <a class="aside-list__media" href="<?= e($otherProgramUrl) ?>" tabindex="-1" aria-hidden="true">
                                        <img class="aside-list__image" src="<?= e(upload_url($otherProgram['hinh_dai_dien'])) ?>" alt="" loading="lazy" width="160" height="100">
                                    </a>
                                    <div class="aside-list__text">
                                        <a class="aside-list__name" href="<?= e($otherProgramUrl) ?>"><?= e($otherProgram['ten']) ?></a>
                                        <?php if (!empty($otherProgram['mo_ta'])): ?>
                                            <p class="aside-list__description"><?= e($otherProgram['mo_ta']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">Chưa có chương trình khác.</p>
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
