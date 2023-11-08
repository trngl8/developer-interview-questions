<?php

namespace App\Database;

use App\RecordsInterface;

abstract class DatabaseConnection implements RecordsInterface
{
    protected \PDO $DB;

    public function addRecord(string $table, array $data): int
    {
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(',', array_keys($data)),
            implode(',', array_fill(0, count($data), '?'))
        );
        $stmt = $this->DB->prepare($sql);
        $stmt->execute(array_values($data));
        return $this->DB->lastInsertId();
    }

    public function removeRecord(string $table, int $id): void
    {
        $sql = sprintf("DELETE FROM %s WHERE id=%d", $table, $id);
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
    }

    public function getArrayResult(string $sql): array
    {
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
