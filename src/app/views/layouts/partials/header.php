<header class="site-header">
    <div class="container site-header__inner">
        <a class="brand" href="<?= e(url('/')) ?>" aria-label="<?= e(setting('ten_truong')) ?> – Trang chủ">
            <img class="brand__logo" src="<?= e(asset('img/logo.svg')) ?>" alt="Logo <?= e(setting('ten_truong')) ?>" width="52" height="60">
            <span class="brand__text">
                <span class="brand__name"><?= e(setting('ten_truong')) ?></span>
                <span class="brand__name-en"><?= e(setting('ten_truong_en')) ?></span>
            </span>
        </a>

        <button class="nav-toggle" type="button" aria-controls="site-nav" aria-expanded="false" aria-label="Mở menu">
            <span class="nav-toggle__bar"></span>
            <span class="nav-toggle__bar"></span>
            <span class="nav-toggle__bar"></span>
        </button>

        <div class="site-nav" id="site-nav">
            <?php require APP_PATH . '/views/layouts/partials/nav.php'; ?>
            <a class="btn btn-gold site-nav__cta" href="<?= e(url('/dang-ky-nhap-hoc')) ?>">Đăng ký ngay</a>
        </div>
    </div>
</header>
