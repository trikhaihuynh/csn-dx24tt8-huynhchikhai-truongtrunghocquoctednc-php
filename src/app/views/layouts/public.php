<?php
$schoolName = setting('ten_truong', APP_NAME);
$pageTitle = !empty($title) ? $title . ' | ' . $schoolName : $schoolName;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e(setting('gioi_thieu', $schoolName)) ?>">
    <link rel="icon" href="<?= e(asset('img/logo.svg')) ?>" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body>
    <a class="skip-link" href="#main-content">Bỏ qua đến nội dung chính</a>
    <?php require APP_PATH . '/views/layouts/partials/header.php'; ?>

    <main id="main-content" class="site-main">
        <?php require APP_PATH . '/views/layouts/partials/flash.php'; ?>
        <?= $content ?>
    </main>

    <?php require APP_PATH . '/views/layouts/partials/footer.php'; ?>
    <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
