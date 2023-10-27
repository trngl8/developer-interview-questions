<?php

namespace App;

class Database
{
    private $DB;

    public function __construct(string $dsn, ?string $username=null, ?string $password=null)
    {
        try {
            $pdoDB = new \PDO($dsn, $username, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
        $this->DB = $pdoDB;
        //echo "Connected to the postgres database successfully!"; //TODO: move to the logger
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
}