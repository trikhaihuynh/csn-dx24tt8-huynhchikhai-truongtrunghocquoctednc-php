<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class User extends Model
{
    public const ROLES = ['admin' => 'Quản trị', 'bien_tap' => 'Biên tập'];

    protected string $table = 'nguoi_dung';

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db->prepare(
            "SELECT id, ho_ten, email, mat_khau, vai_tro, trang_thai, ngay_tao, ngay_cap_nhat
             FROM `{$this->table}`
             WHERE email = ?
             LIMIT 1"
        );
        $statement->execute([$email]);

        return $statement->fetch() ?: null;
    }

    public function findActiveByEmail(string $email): ?array
    {
        $statement = $this->db->prepare(
            "SELECT id, ho_ten, email, mat_khau, vai_tro, trang_thai
             FROM `{$this->table}`
             WHERE email = ? AND trang_thai = 1
             LIMIT 1"
        );
        $statement->execute([$email]);

        return $statement->fetch() ?: null;
    }
}
