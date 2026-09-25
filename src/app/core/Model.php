<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{
    protected string $table;
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::get();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE id = ?");
        $statement->execute([$id]);

        return $statement->fetch() ?: null;
    }

    public function delete(int $id): void
    {
        $this->db->prepare("DELETE FROM `{$this->table}` WHERE id = ?")->execute([$id]);
    }

    public function create(array $data): int
    {
        $columns = array_keys($data);
        $sql = "INSERT INTO `{$this->table}` (`" . implode('`,`', $columns) . '`) VALUES ('
            . implode(',', array_fill(0, count($columns), '?')) . ')';
        $this->db->prepare($sql)->execute(array_values($data));

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $assignments = implode(', ', array_map(static fn (string $column): string => "`$column` = ?", array_keys($data)));
        $this->db->prepare("UPDATE `{$this->table}` SET $assignments WHERE id = ?")
            ->execute([...array_values($data), $id]);
    }
}
