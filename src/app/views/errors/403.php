<section class="section error-page">
    <div class="container container--narrow text-center">
        <p class="error-page__code">403</p>
        <h1 class="section-title">Không có quyền truy cập</h1>
        <p class="section-lead"><?= e($message ?? 'Bạn không có quyền thực hiện thao tác này.') ?></p>
        <a class="btn btn-navy" href="<?= e(url('/')) ?>">Về trang chủ</a>
    </div>
</section>
