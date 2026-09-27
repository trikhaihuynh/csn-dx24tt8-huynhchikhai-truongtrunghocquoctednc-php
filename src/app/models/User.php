<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class User extends Model
{
    public const ROLES = ['admin' => 'Quản trị', 'bien_tap' => 'Biên tập'];
    public const STATUSES = ['1' => 'Hoạt động', '0' => 'Đã khóa'];

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

    public function search(string $keyword, int $limit, int $offset): array
    {
        [$whereClause, $parameters] = $this->searchConditions($keyword);
        $statement = $this->db->prepare(
            "SELECT id, ho_ten, email, vai_tro, trang_thai, ngay_tao
             FROM `{$this->table}`
             {$whereClause}
             ORDER BY id ASC
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

    public function countSearch(string $keyword): int
    {
        [$whereClause, $parameters] = $this->searchConditions($keyword);
        $statement = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` {$whereClause}");
        $statement->execute($parameters);

        return (int) $statement->fetchColumn();
    }

    public function emailExists(string $email, ?int $ignoreId = null): bool
    {
        $existingUser = $this->findByEmail($email);

        return $existingUser !== null && (int) $existingUser['id'] !== $ignoreId;
    }

    private function searchConditions(string $keyword): array
    {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return ['', []];
        }
        $pattern = '%' . addcslashes($keyword, '%_\\') . '%';

        return [
            'WHERE ho_ten LIKE :keyword_name OR email LIKE :keyword_email',
            [':keyword_name' => $pattern, ':keyword_email' => $pattern],
        ];
    }
}
