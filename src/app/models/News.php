<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class News extends Model
{
    public const STATUSES = ['nhap' => 'Nháp', 'cong_khai' => 'Công khai'];

    private const SLUG_BASE_MAX_LENGTH = 200;
    private const DEFAULT_SLUG = 'tin-tuc';

    protected string $table = 'tin_tuc';

    public static function isPublished(array $article): bool
    {
        $publishedAt = strtotime((string) ($article['ngay_dang'] ?? ''));

        return ($article['trang_thai'] ?? '') === 'cong_khai' && $publishedAt !== false && $publishedAt <= time();
    }

    public function latestPublished(int $limit): array
    {
        return $this->paginatePublished($limit, 0);
    }

    public function countPublished(): int
    {
        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM `{$this->table}`
             WHERE trang_thai = 'cong_khai' AND ngay_dang <= NOW()"
        );
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function paginatePublished(int $limit, int $offset): array
    {
        $statement = $this->db->prepare(
            "SELECT id, tieu_de, slug, tom_tat, hinh_dai_dien, ngay_dang
             FROM `{$this->table}`
             WHERE trang_thai = 'cong_khai' AND ngay_dang <= NOW()
             ORDER BY ngay_dang DESC, id DESC
             LIMIT :limit OFFSET :offset"
        );
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findPublishedBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(
            "SELECT id, tieu_de, slug, tom_tat, noi_dung, hinh_dai_dien, ngay_dang, luot_xem
             FROM `{$this->table}`
             WHERE slug = ? AND trang_thai = 'cong_khai' AND ngay_dang <= NOW()
             LIMIT 1"
        );
        $statement->execute([$slug]);
        $article = $statement->fetch();

        return $article !== false && $article['slug'] === $slug ? $article : null;
    }

    public function incrementViews(int $id): void
    {
        $statement = $this->db->prepare(
            "UPDATE `{$this->table}`
             SET luot_xem = luot_xem + 1, ngay_cap_nhat = ngay_cap_nhat
             WHERE id = ?"
        );
        $statement->execute([$id]);
    }

    public function latest(int $limit): array
    {
        $statement = $this->db->prepare(
            "SELECT id, tieu_de, slug, trang_thai, ngay_dang, luot_xem, ngay_tao
             FROM `{$this->table}`
             ORDER BY ngay_tao DESC, id DESC
             LIMIT :limit"
        );
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function search(string $keyword, ?string $status, int $limit, int $offset): array
    {
        [$whereClause, $parameters] = $this->searchConditions($keyword, $status);
        $statement = $this->db->prepare(
            "SELECT id, tieu_de, slug, hinh_dai_dien, trang_thai, ngay_dang, luot_xem
             FROM `{$this->table}`
             {$whereClause}
             ORDER BY ngay_dang DESC, id DESC
             LIMIT :limit OFFSET :offset"
        );
        foreach ($parameters as $placeholder => $value) {
            $statement->bindValue($placeholder, $value);
        }
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countSearch(string $keyword, ?string $status): int
    {
        [$whereClause, $parameters] = $this->searchConditions($keyword, $status);
        $statement = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` {$whereClause}");
        $statement->execute($parameters);

        return (int) $statement->fetchColumn();
    }

    public function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $statement = $this->db->prepare(
            "SELECT COUNT(*) FROM `{$this->table}` WHERE slug = :slug AND id <> :ignore_id"
        );
        $statement->bindValue(':slug', $slug);
        $statement->bindValue(':ignore_id', $ignoreId ?? 0, PDO::PARAM_INT);
        $statement->execute();

        return (int) $statement->fetchColumn() > 0;
    }

    public function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $base = trim(substr($base, 0, self::SLUG_BASE_MAX_LENGTH), '-');
        if ($base === '') {
            $base = self::DEFAULT_SLUG;
        }

        $slug = $base;
        $suffix = 2;
        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function searchConditions(string $keyword, ?string $status): array
    {
        $conditions = [];
        $parameters = [];
        $keyword = trim($keyword);
        if ($keyword !== '') {
            $conditions[] = 'tieu_de LIKE :keyword';
            $parameters[':keyword'] = '%' . addcslashes($keyword, '%_\\') . '%';
        }
        if ($status !== null && array_key_exists($status, self::STATUSES)) {
            $conditions[] = 'trang_thai = :status';
            $parameters[':status'] = $status;
        }

        return [$conditions === [] ? '' : 'WHERE ' . implode(' AND ', $conditions), $parameters];
    }
}
