<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class Program extends Model
{
    public const STATUSES = ['1' => 'Hiển thị', '0' => 'Ẩn'];

    private const SLUG_BASE_MAX_LENGTH = 150;
    private const DEFAULT_SLUG = 'chuong-trinh';

    protected string $table = 'chuong_trinh';

    public function allActive(): array
    {
        $statement = $this->db->prepare(
            "SELECT id, ten, slug, mo_ta, hinh_dai_dien, thu_tu
             FROM `{$this->table}`
             WHERE trang_thai = 1
             ORDER BY thu_tu ASC, id ASC"
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    public function isActive(int $id): bool
    {
        $statement = $this->db->prepare(
            "SELECT 1 FROM `{$this->table}` WHERE id = ? AND trang_thai = 1 LIMIT 1"
        );
        $statement->execute([$id]);

        return $statement->fetchColumn() !== false;
    }

    public function findActiveBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(
            "SELECT id, ten, slug, mo_ta, noi_dung, hinh_dai_dien, thu_tu
             FROM `{$this->table}`
             WHERE slug = ? AND trang_thai = 1
             LIMIT 1"
        );
        $statement->execute([$slug]);
        $program = $statement->fetch();

        return $program !== false && $program['slug'] === $slug ? $program : null;
    }

    public function countActive(): int
    {
        $statement = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` WHERE trang_thai = 1");
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function search(string $keyword, ?int $status, int $limit, int $offset): array
    {
        [$whereClause, $parameters] = $this->searchConditions($keyword, $status);
        $statement = $this->db->prepare(
            "SELECT program.id, program.ten, program.slug, program.hinh_dai_dien, program.thu_tu,
                    program.trang_thai, program.ngay_cap_nhat,
                    (SELECT COUNT(*) FROM dang_ky_nhap_hoc WHERE dang_ky_nhap_hoc.chuong_trinh_id = program.id)
                        AS so_dang_ky
             FROM `{$this->table}` AS program
             {$whereClause}
             ORDER BY program.thu_tu ASC, program.id ASC
             LIMIT :limit OFFSET :offset"
        );
        foreach ($parameters as $placeholder => $value) {
            $statement->bindValue($placeholder, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countSearch(string $keyword, ?int $status): int
    {
        [$whereClause, $parameters] = $this->searchConditions($keyword, $status);
        $statement = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` AS program {$whereClause}");
        foreach ($parameters as $placeholder => $value) {
            $statement->bindValue($placeholder, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function countAdmissions(int $id): int
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM dang_ky_nhap_hoc WHERE chuong_trinh_id = ?');
        $statement->execute([$id]);

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

    private function searchConditions(string $keyword, ?int $status): array
    {
        $conditions = [];
        $parameters = [];
        $keyword = trim($keyword);
        if ($keyword !== '') {
            $conditions[] = 'program.ten LIKE :keyword';
            $parameters[':keyword'] = '%' . addcslashes($keyword, '%_\\') . '%';
        }
        if ($status !== null && array_key_exists($status, self::STATUSES)) {
            $conditions[] = 'program.trang_thai = :status';
            $parameters[':status'] = $status;
        }

        return [$conditions === [] ? '' : 'WHERE ' . implode(' AND ', $conditions), $parameters];
    }
}
