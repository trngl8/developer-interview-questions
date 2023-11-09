<?php

namespace App\Database;

class PostgresDatabaseConnection extends DatabaseConnection
{
    public function __construct(string $dsn, ?string $username=null, ?string $password=null)
    {
        try {
            $pdoDB = new \PDO($dsn, $username, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        $this->DB = $pdoDB;
    }
}
