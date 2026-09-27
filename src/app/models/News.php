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
        $statement = $this->db->prepare(
            "SELECT id, tieu_de, slug, tom_tat, hinh_dai_dien, ngay_dang
             FROM `{$this->table}`
             WHERE trang_thai = 'cong_khai' AND ngay_dang <= NOW()
             ORDER BY ngay_dang DESC, id DESC
             LIMIT :limit"
        );
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
