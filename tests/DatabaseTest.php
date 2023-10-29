<?php

namespace App\Tests;

use App\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $database;

    public function setUp(): void {
        parent::setUp();
        $this->database = new Database("sqlite://" . __DIR__ . sprintf("/../var/%s.db", 'test'));
    }

    public function testDatabaseRecords(): void
    {
        $records = $this->database->getRecords('questions');
        $this->assertCount(1, $records);
    }

    public function testDatabaseRecord(): void
    {
        $record = $this->database->getRecord('questions', 1);
        $this->assertEquals('What is your name?', $record['title']);
    }

    public function testDatabaseAddRecord(): void
    {
        $id = $this->database->addRecord('questions', ['title' => 'How is going on?', 'created_at' => date('Y-m-d H:i:s')]);
        $this->assertEquals(2, $id);
    }

}