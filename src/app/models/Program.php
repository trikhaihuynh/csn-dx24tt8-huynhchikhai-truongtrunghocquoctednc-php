<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Program extends Model
{
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
}
