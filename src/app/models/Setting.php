<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

final class Setting
{
    private const TABLE = 'cai_dat';

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::get();
    }

    public function all(): array
    {
        $settings = [];
        foreach ($this->db->query('SELECT khoa, gia_tri FROM `' . self::TABLE . '`') as $row) {
            $settings[$row['khoa']] = (string) $row['gia_tri'];
        }

        return $settings;
    }

    public function get(string $key, string $default = ''): string
    {
        $statement = $this->db->prepare('SELECT gia_tri FROM `' . self::TABLE . '` WHERE khoa = ?');
        $statement->execute([$key]);
        $value = $statement->fetchColumn();

        return $value === false || $value === null ? $default : (string) $value;
    }

    public function set(string $key, ?string $value): void
    {
        $this->db->prepare(
            'INSERT INTO `' . self::TABLE . '` (khoa, gia_tri) VALUES (?, ?) ON DUPLICATE KEY UPDATE gia_tri = ?'
        )->execute([$key, $value, $value]);
    }

    public function allWithDescriptions(): array
    {
        return $this->db->query('SELECT khoa, gia_tri, mo_ta FROM `' . self::TABLE . '` ORDER BY khoa')->fetchAll();
    }

    public function saveMany(array $values): void
    {
        $this->db->beginTransaction();
        try {
            foreach ($values as $key => $value) {
                $this->set((string) $key, $value);
            }
            $this->db->commit();
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }
}
