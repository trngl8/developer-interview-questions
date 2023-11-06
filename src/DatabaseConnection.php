<?php

namespace App;

abstract class DatabaseConnection implements RecordsInterface
{
    protected \PDO $DB;

    public function getRecords(string $table, int $limit=0, array $order=[], array $where=[], array $having=[]): array
    {
        $sql = '';
        $addSelect = [];
        if (!empty($order)) {
            $sql .= sprintf(" ORDER BY %s", implode(',', $order));
        }
        if (!empty($where)) {
            $sql .= sprintf(" WHERE %s", implode(' AND ', $where));
        }
        if (!empty($having)) {
            $addSelect = [', COUNT(*) AS c'];
            $sql .= sprintf(" group by id HAVING %s", implode(' AND ', $having));
        }
        if ($limit > 0) {
            $sql .= sprintf(" LIMIT %d", $limit);
        }
        $sqlSelect = sprintf("SELECT * %s FROM %s %s", implode(' , ', $addSelect), $table, $sql);
        return $this->getArrayResult($sqlSelect);
    }

    public function getRecord(string $table, int $id): array
    {
        $sql = sprintf("SELECT * FROM %s WHERE id=%d", $table, $id);
        return $this->getArrayResult($sql)[0];
    }

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

    private function getArrayResult(string $sql): array
    {
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
