<?php

namespace App\Tests\Unit;

use App\Database\ChangeRecordsInterface;
use App\Database\DatabaseFactory;
use App\Model\Answer;
use PHPUnit\Framework\TestCase;

class AnswerTest extends TestCase
{
    private ChangeRecordsInterface $database;

    public function setUp(): void
    {
        parent::setUp();
        $this->database = DatabaseFactory::create('sqlite://' . __DIR__ . '/../../var/test.db');
    }

    public function testGetRate(): void
    {
        $model = new Answer($this->database);
        $rate = $model->getRate(1);
        $this->assertEquals(0, $rate);
    }
}
