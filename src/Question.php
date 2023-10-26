<?php

namespace App;

class Question
{
    private $DB;

    public function __construct(string $dsn, string $username, string $password)
    {
        try {
            $pdoDB = new \PDO($dsn, $username, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
        $this->DB = $pdoDB;
        //echo "Connected to the postgres database successfully!"; //TODO: move to the logger
    }

    public function getRecords(): array
    {
        $sql = "SELECT * FROM questions";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
