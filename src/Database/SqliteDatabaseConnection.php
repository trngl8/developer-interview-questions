<?php

namespace App\Database;

class SqliteDatabaseConnection extends DatabaseConnection
{
    public function getType(): string
    {
        return 'sqlite';
    }
}
