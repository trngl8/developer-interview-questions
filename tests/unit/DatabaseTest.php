<?php

namespace App\Tests\Unit;

use App\Database\ChangeRecordsInterface;
use App\Database\DatabaseFactory;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private ChangeRecordsInterface $database;

    public function setUp(): void {
        parent::setUp();
        $this->database = DatabaseFactory::create('sqlite://'. __DIR__ . '/../../var/test.db');
    }

    public function testDatabaseRecords(): void
    {
        $records = $this->database->getArrayResult('SELECT * FROM questions');
        $this->assertGreaterThan(1, count($records));
    }

    public function testDatabaseRecordsLimit(): void
    {
        $records = $this->database->getArrayResult('SELECT * FROM questions LIMIT 2');
        $this->assertCount(2, $records);
    }

    public function testDatabaseRecordsWhere(): void
    {
        $records = $this->database->getArrayResult('SELECT * FROM questions WHERE id=1');
        $this->assertCount(1, $records);
    }

    public function testDatabaseAddRecord(): void
    {
        $id = $this->database->addRecord('questions', ['title' => 'How is going on?', 'created_at' => date('Y-m-d H:i:s')]);
        $this->assertEquals(4, $id);
    }

    public function testDatabaseRemoveRecord(): void
    {
        $this->database->removeRecord('questions', 4);
        $records = $this->database->getArrayResult('SELECT * FROM questions');
        $this->assertCount(3, $records);
    }

    public function testDatabaseErrorType(): void
    {
        $this->expectException(\Exception::class);
        DatabaseFactory::create('error://test');
    }

    public function testDatabasePostgresType(): void
    {
        $this->expectException(\Exception::class);
        DatabaseFactory::create('postgres://test:123@localhost:5432/test');
    }
}
