<?php

namespace App\Tests\Unit;

use App\DatabaseFactory;
use App\RecordsInterface;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private RecordsInterface $database;

    public function setUp(): void {
        parent::setUp();
        $this->database = DatabaseFactory::create('sqlite://'. __DIR__ . '/../../var/test.db');
    }

    public function testDatabaseRecords(): void
    {
        $records = $this->database->getRecords('questions');
        $this->assertGreaterThan(3, count($records));
    }

    public function testDatabaseRecordsLimit(): void
    {
        $records = $this->database->getRecords('questions', 2);
        $this->assertCount(2, $records);
    }

    public function testDatabaseRecordsWhere(): void
    {
        $records = $this->database->getRecords('questions', 3, [], ['id > 0']);
        $this->assertCount(3, $records);
    }

    public function testDatabaseRecordsHaving(): void
    {
        $records = $this->database->getRecords('questions', 3, [], [], ['c > 0']);
        $this->assertCount(1, $records);
    }

    public function testDatabaseRecord(): void
    {
        $record = $this->database->getRecord('questions', 1);
        $this->assertEquals('What is an abstract class?', $record['title']);
    }

    public function testDatabaseAddRecord(): void
    {
        $id = $this->database->addRecord('questions', ['title' => 'How is going on?', 'created_at' => date('Y-m-d H:i:s')]);
        $this->assertEquals(4, $id);
    }

    public function testDatabaseRemoveRecord(): void
    {
        $this->database->removeRecord('questions', 4);
        $records = $this->database->getRecords('questions');
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
