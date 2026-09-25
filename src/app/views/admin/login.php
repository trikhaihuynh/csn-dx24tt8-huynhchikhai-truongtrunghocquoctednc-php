<?php
use App\Core\Csrf;

$schoolName = setting('ten_truong', APP_NAME);
?>
<section class="login-page">
    <div class="login-card">
        <div class="login-card__header">
            <img class="login-card__logo" src="<?= e(asset('img/logo.svg')) ?>" alt="Logo <?= e($schoolName) ?>" width="64" height="74">
            <h1 class="login-card__title">Đăng nhập quản trị</h1>
            <p class="login-card__subtitle"><?= e($schoolName) ?></p>
        </div>

        <form method="post" action="<?= e(url('/admin/login')) ?>" class="login-form" novalidate>
            <?= Csrf::field() ?>

            <div class="form-group<?= !empty($errors['email']) ? ' has-error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" maxlength="150" autocomplete="username" required autofocus>
                <?php if (!empty($errors['email'])): ?>
                    <div class="form-error"><?= e($errors['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group<?= !empty($errors['mat_khau']) ? ' has-error' : '' ?>">
                <label for="mat_khau">Mật khẩu</label>
                <input type="password" id="mat_khau" name="mat_khau" maxlength="255" autocomplete="current-password" required>
                <?php if (!empty($errors['mat_khau'])): ?>
                    <div class="form-error"><?= e($errors['mat_khau']) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-navy login-form__submit">Đăng nhập</button>
        </form>

        <a class="login-card__back" href="<?= e(url('/')) ?>">&larr; Về trang chủ</a>
    </div>
</section>
