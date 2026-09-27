<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;
use PDOStatement;

final class GalleryImage extends Model
{
    public const STATUSES = ['1' => 'Hiển thị', '0' => 'Ẩn'];

    private const ALBUM_LABELS = [
        'co-so-vat-chat' => 'Cơ sở vật chất',
        'hoat-dong' => 'Hoạt động',
        'su-kien' => 'Sự kiện',
    ];

    protected string $table = 'hinh_anh';

    public static function albumLabel(?string $album): string
    {
        $album = (string) $album;
        if ($album === '') {
            return 'Chưa phân album';
        }

        return self::ALBUM_LABELS[$album] ?? mb_convert_case(str_replace('-', ' ', $album), MB_CASE_TITLE, 'UTF-8');
    }

    public function latestActive(int $limit): array
    {
        $statement = $this->db->prepare(
            "SELECT id, tieu_de, duong_dan, mo_ta, album
             FROM `{$this->table}`
             WHERE trang_thai = 1
             ORDER BY ngay_tao DESC, id DESC
             LIMIT :limit"
        );
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countActive(): int
    {
        $statement = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` WHERE trang_thai = 1");
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function albums(bool $activeOnly = false): array
    {
        $statusCondition = $activeOnly ? 'AND trang_thai = 1' : '';
        $statement = $this->db->prepare(
            "SELECT DISTINCT album
             FROM `{$this->table}`
             WHERE album IS NOT NULL AND album <> '' {$statusCondition}
             ORDER BY album ASC"
        );
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function activeByAlbum(?string $album, int $limit, int $offset): array
    {
        [$whereClause, $parameters] = $this->activeConditions($album);
        $statement = $this->db->prepare(
            "SELECT id, tieu_de, duong_dan, mo_ta, album
             FROM `{$this->table}`
             {$whereClause}
             ORDER BY album ASC, thu_tu ASC, ngay_tao DESC, id DESC
             LIMIT :limit OFFSET :offset"
        );
        $this->bindParameters($statement, $parameters);
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countActiveByAlbum(?string $album): int
    {
        [$whereClause, $parameters] = $this->activeConditions($album);
        $statement = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` {$whereClause}");
        $this->bindParameters($statement, $parameters);
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function search(string $keyword, ?string $album, int $limit, int $offset): array
    {
        [$whereClause, $parameters] = $this->searchConditions($keyword, $album);
        $statement = $this->db->prepare(
            "SELECT id, tieu_de, duong_dan, mo_ta, album, thu_tu, trang_thai, ngay_tao
             FROM `{$this->table}`
             {$whereClause}
             ORDER BY ngay_tao DESC, id DESC
             LIMIT :limit OFFSET :offset"
        );
        $this->bindParameters($statement, $parameters);
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countSearch(string $keyword, ?string $album): int
    {
        [$whereClause, $parameters] = $this->searchConditions($keyword, $album);
        $statement = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` {$whereClause}");
        $this->bindParameters($statement, $parameters);
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    private function activeConditions(?string $album): array
    {
        $conditions = ['trang_thai = 1'];
        $parameters = [];
        if ($album !== null && $album !== '') {
            $conditions[] = 'album = :album';
            $parameters[':album'] = $album;
        }

        return ['WHERE ' . implode(' AND ', $conditions), $parameters];
    }

    private function searchConditions(string $keyword, ?string $album): array
    {
        $conditions = [];
        $parameters = [];
        $keyword = trim($keyword);
        if ($keyword !== '') {
            $conditions[] = 'tieu_de LIKE :keyword';
            $parameters[':keyword'] = '%' . addcslashes($keyword, '%_\\') . '%';
        }
        if ($album !== null && $album !== '') {
            $conditions[] = 'album = :album';
            $parameters[':album'] = $album;
        }

        return [$conditions === [] ? '' : 'WHERE ' . implode(' AND ', $conditions), $parameters];
    }

    private function bindParameters(PDOStatement $statement, array $parameters): void
    {
        foreach ($parameters as $placeholder => $value) {
            $statement->bindValue($placeholder, $value, PDO::PARAM_STR);
        }
    }
}
