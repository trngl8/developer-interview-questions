<?php

namespace App\Database;

class PostgresDatabaseConnection extends DatabaseConnection
{
    public function getType(): string
    {
        return 'postgres';
    }
}
