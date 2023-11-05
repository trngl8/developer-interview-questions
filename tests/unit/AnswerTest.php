<?php

namespace App\Tests\Unit;

use App\Answer;
use App\DatabaseFactory;
use App\RecordsInterface;
use PHPUnit\Framework\TestCase;

class AnswerTest extends TestCase
{
    private RecordsInterface $database;

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
