<?php

namespace App;

class Question
{
    private $DB;

    public function __construct()
    {
        $dsn = "pgsql:host=localhost;port=5432;dbname=postgres;";
        try {
            $pdoDB = new \PDO($dsn, 'postgres', 'postgres', [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
        $this->DB = $pdoDB;
        echo "Connected to the postgres database successfully!";
    }

    public function getRecords(): array
    {
        $sql = "SELECT * FROM questions";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
