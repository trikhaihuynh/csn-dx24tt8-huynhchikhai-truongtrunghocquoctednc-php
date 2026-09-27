<?php
$galleryUrl = static fn (?string $album = null): string => url('/hinh-anh') . ($album !== null ? '?' . http_build_query(['album' => $album]) : '');
?>
<section class="page-banner">
    <div class="container">
        <h1 class="page-banner__title">Đời sống học sinh</h1>
        <p class="page-banner__subtitle">Student Life Gallery</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($albums): ?>
            <nav class="gallery-filter" aria-label="Lọc theo album">
                <a class="gallery-filter__link<?= $selectedAlbum === null ? ' active' : '' ?>" href="<?= e($galleryUrl()) ?>"<?= $selectedAlbum === null ? ' aria-current="page"' : '' ?>>Tất cả</a>
                <?php foreach ($albums as $album => $albumLabel): ?>
                    <?php $isSelected = $selectedAlbum === (string) $album; ?>
                    <a class="gallery-filter__link<?= $isSelected ? ' active' : '' ?>" href="<?= e($galleryUrl((string) $album)) ?>"<?= $isSelected ? ' aria-current="page"' : '' ?>><?= e($albumLabel) ?></a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <?php if ($images): ?>
            <div class="gallery-grid">
                <?php foreach ($images as $image): ?>
                    <?php $imageUrl = upload_url($image['duong_dan']); ?>
                    <figure class="gallery-item">
                        <a class="gallery-item__link" href="<?= e($imageUrl) ?>" data-lightbox data-caption="<?= e($image['tieu_de']) ?>">
                            <img class="gallery-item__image" src="<?= e($imageUrl) ?>" alt="<?= e($image['tieu_de']) ?>" loading="lazy" width="800" height="500">
                        </a>
                        <figcaption class="gallery-item__caption">
                            <span class="gallery-item__title"><?= e($image['tieu_de']) ?></span>
                            <?php if (!empty($image['album'])): ?>
                                <span class="gallery-item__album"><?= e($albums[$image['album']] ?? $image['album']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($image['mo_ta'])): ?>
                                <span class="gallery-item__description"><?= e($image['mo_ta']) ?></span>
                            <?php endif; ?>
                        </figcaption>
                    </figure>
                <?php endforeach; ?>
            </div>

            <?php require APP_PATH . '/views/partials/pagination.php'; ?>
        <?php elseif ($selectedAlbum !== null): ?>
            <p class="empty-state">Album này chưa có hình ảnh. <a href="<?= e($galleryUrl()) ?>">Xem tất cả hình ảnh</a></p>
        <?php else: ?>
            <p class="empty-state">Hình ảnh hoạt động đang được cập nhật.</p>
        <?php endif; ?>
    </div>
</section>
