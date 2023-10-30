<?php

namespace App\Tests;

use App\Database;
use App\DatabaseFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

class DatabaseTest extends TestCase
{
    private $database;

    public function setUp(): void {
        parent::setUp();
        $this->database = DatabaseFactory::create('sqlite://'. __DIR__ . '/../var/test.db');
    }

    public function testDatabaseRecords(): void
    {
        $records = $this->database->getRecords('questions');
        $this->assertGreaterThan(0, count($records));
    }

    public function testDatabaseRecord(): void
    {
        $record = $this->database->getRecord('questions', 1);
        $this->assertEquals('How is going on?', $record['title']);
    }

    public function testDatabaseAddRecord(): void
    {
        $id = $this->database->addRecord('questions', ['title' => 'How is going on?', 'created_at' => date('Y-m-d H:i:s')]);
        $this->assertGreaterThan(1, $id);
    }

}
