<?php

namespace App;

class SqliteDatabaseConnection extends DatabaseConnection
{
    public function __construct(string $dsn)
    {
        try {
            $pdoDB = new \PDO($dsn);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        $this->DB = $pdoDB;
    }
}
