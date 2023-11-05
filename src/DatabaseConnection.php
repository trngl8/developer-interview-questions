<?php

namespace App;

abstract class DatabaseConnection implements RecordsInterface
{
    protected $DB;

    public function getRecords(string $table, int $limit=0, array $order=[]): array
    {
        $sql = sprintf("SELECT * FROM %s", $table);
        if($limit) {
            $sql .= sprintf(" LIMIT %d", $limit);
        }
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRecord(string $table, int $id): array
    {
        $sql = sprintf("SELECT * FROM %s WHERE id=%d", $table, $id);
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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

}
