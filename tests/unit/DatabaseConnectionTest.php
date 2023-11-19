<?php

namespace App\Tests\Unit;

use App\Database\PostgresDatabaseConnection;
use App\Database\SqliteDatabaseConnection;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testSqliteDatabaseConnectionFail(): void
    {
        $this->expectException(\Exception::class);
        new SqliteDatabaseConnection(new \PDO('sqlite://wrong_path.db'));
    }

    public function testPostgresDatabaseConnectionFail(): void
    {
        $this->expectException(\Exception::class);
        new PostgresDatabaseConnection(new \PDO('postgres://wrong_permissions/wrong_db'));
    }
}
