<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class News extends Model
{
    public const STATUSES = ['nhap' => 'Nháp', 'cong_khai' => 'Công khai'];

    protected string $table = 'tin_tuc';

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
}
