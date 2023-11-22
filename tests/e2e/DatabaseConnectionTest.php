<?php

namespace App\Tests\E2E;

use App\Database\DatabaseFactory;
use App\Exception\DatabaseException;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testSqliteDatabaseConnection(): void
    {
        $database = DatabaseFactory::create('sqlite://'. __DIR__ . '/../../var/test.db');
        $this->assertEquals('sqlite', $database->getType());
    }
    public function testWrongDsnConnectionFail(): void
    {
        $this->expectException(DatabaseException::class);
        DatabaseFactory::create('wrong_dsn');
    }

    public function testSqliteDatabaseConnectionFail(): void
    {
        $this->expectException(DatabaseException::class);
        DatabaseFactory::create('sqlite://wrong_path.db');
    }

    public function testPostgresDatabaseConnectionFail(): void
    {
        $this->expectException(DatabaseException::class);
        DatabaseFactory::create('postgres://wrong_user:wrong_pass@wrong_host:111/wrong_db');
    }
}
