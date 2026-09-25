<?php

declare(strict_types=1);

namespace App\Core;

final class Paginator
{
    public static function make(int $total, int $page, int $perPage = 10): array
    {
        $perPage = max(1, $perPage);
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min(max(1, $page), $pages);

        return [
            'total' => $total,
            'page' => $page,
            'pages' => $pages,
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
        ];
    }
}
