<?php
$currentPage = (int) ($pager['page'] ?? 1);
$totalPages = (int) ($pager['pages'] ?? 1);
$basePath = $paginationPath ?? App\Core\Router::normalizePath($_SERVER['REQUEST_URI'] ?? '/');
$preservedQuery = array_filter(
    $_GET,
    fn (mixed $value, mixed $key): bool => $key !== 'page' && (is_string($value) || is_array($value)),
    ARRAY_FILTER_USE_BOTH
);
$pageUrl = static function (int $pageNumber) use ($basePath, $preservedQuery): string {
    $query = $pageNumber > 1 ? $preservedQuery + ['page' => $pageNumber] : $preservedQuery;
    $queryString = http_build_query($query);

    return url($basePath) . ($queryString !== '' ? '?' . $queryString : '');
};
$pageWindow = 2;
$visiblePages = [];
for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) {
    if ($pageNumber === 1 || $pageNumber === $totalPages || abs($pageNumber - $currentPage) <= $pageWindow) {
        $visiblePages[] = $pageNumber;
    }
}
?>
<?php if ($totalPages > 1): ?>
    <nav class="pagination-nav" aria-label="Phân trang">
        <ul class="pagination">
            <li class="pagination__item<?= $currentPage <= 1 ? ' is-disabled' : '' ?>">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= e($pageUrl($currentPage - 1)) ?>" rel="prev" aria-label="Trang trước">&laquo;</a>
                <?php else: ?>
                    <span aria-hidden="true">&laquo;</span>
                <?php endif; ?>
            </li>
            <?php $previousPage = 0; ?>
            <?php foreach ($visiblePages as $pageNumber): ?>
                <?php if ($pageNumber - $previousPage > 1): ?>
                    <li class="pagination__item is-ellipsis"><span aria-hidden="true">&hellip;</span></li>
                <?php endif; ?>
                <?php if ($pageNumber === $currentPage): ?>
                    <li class="pagination__item active"><span aria-current="page"><?= e($pageNumber) ?></span></li>
                <?php else: ?>
                    <li class="pagination__item"><a href="<?= e($pageUrl($pageNumber)) ?>" aria-label="Trang <?= e($pageNumber) ?>"><?= e($pageNumber) ?></a></li>
                <?php endif; ?>
                <?php $previousPage = $pageNumber; ?>
            <?php endforeach; ?>
            <li class="pagination__item<?= $currentPage >= $totalPages ? ' is-disabled' : '' ?>">
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= e($pageUrl($currentPage + 1)) ?>" rel="next" aria-label="Trang sau">&raquo;</a>
                <?php else: ?>
                    <span aria-hidden="true">&raquo;</span>
                <?php endif; ?>
            </li>
        </ul>
        <p class="pagination__summary">Trang <?= e($currentPage) ?> / <?= e($totalPages) ?></p>
    </nav>
<?php endif; ?>
