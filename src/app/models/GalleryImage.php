<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class GalleryImage extends Model
{
    protected string $table = 'hinh_anh';

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
}
