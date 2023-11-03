<?php

namespace App;

abstract class DatabaseConnection
{
    private $DB;

    public function __construct(string $dsn, ?string $username=null, ?string $password=null)
    {
        try {
            $pdoDB = new \PDO($dsn, $username, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        $this->DB = $pdoDB;
    }

    public function getRecords(string $table): array
    {
        $sql = sprintf("SELECT * FROM %s", $table);
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

    public function removeRecord(int $id): void
    {
        $sql = sprintf("DELETE FROM questions WHERE id=%d", $id);
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
    }

}
