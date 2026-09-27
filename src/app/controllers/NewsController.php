<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Paginator;
use App\Models\News;

class NewsController extends Controller
{
    private const RELATED_NEWS_LIMIT = 3;

    public function index(): void
    {
        $newsModel = new News();
        $pageQuery = $this->query('page', '1');
        $requestedPage = is_string($pageQuery) ? (int) $pageQuery : 1;
        $pager = Paginator::make($newsModel->countPublished(), $requestedPage, $this->perPage());

        $this->view('public/news/index', [
            'title' => 'Tin tức & Sự kiện',
            'articles' => $newsModel->paginatePublished($pager['limit'], $pager['offset']),
            'pager' => $pager,
            'paginationPath' => '/tin-tuc',
        ]);
    }

    public function show(string $slug): void
    {
        $newsModel = new News();
        $article = $newsModel->findPublishedBySlug($slug);
        if ($article === null) {
            $this->notFound();
        }

        $newsModel->incrementViews((int) $article['id']);
        $article['luot_xem'] = (int) $article['luot_xem'] + 1;

        $relatedArticles = array_slice(array_values(array_filter(
            $newsModel->latestPublished(self::RELATED_NEWS_LIMIT + 1),
            fn (array $otherArticle): bool => (int) $otherArticle['id'] !== (int) $article['id']
        )), 0, self::RELATED_NEWS_LIMIT);

        $this->view('public/news/show', [
            'title' => $article['tieu_de'],
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }

    private function perPage(): int
    {
        $appConfig = (require APP_PATH . '/config/config.php')['app'];

        return (int) ($appConfig['per_page'] ?? 10);
    }
}
