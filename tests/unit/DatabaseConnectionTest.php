<?php

namespace App\Tests\Unit;

use App\Database\SqliteDatabaseConnection;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testSqliteDatabaseConnectionFail(): void
    {
        $this->expectException(\Exception::class);
        new SqliteDatabaseConnection('sqlite://wrong_path.db');
    }

    public function testPostgresDatabaseConnectionFail(): void
    {
        $this->expectException(\Exception::class);
        new SqliteDatabaseConnection('pgsql://wrong_permissions/wrong_db');
    }
}
