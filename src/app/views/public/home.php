<section class="hero">
    <div class="container hero__inner">
        <p class="hero__eyebrow"><?= e($schoolName) ?></p>
        <h1 class="hero__title"><?= e($slogan) ?></h1>
        <div class="hero__actions">
            <a class="btn btn-gold btn-lg" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký nhập học</a>
            <a class="btn btn-outline-light btn-lg" href="<?= e(url('/gioi-thieu')) ?>">Tìm hiểu thêm</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container container--narrow text-center">
        <h2 class="section-title">Chào mừng đến với <?= e($schoolName) ?></h2>
        <p class="section-lead"><?= e($introduction) ?></p>
    </div>
</section>
