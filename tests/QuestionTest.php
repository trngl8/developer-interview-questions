<?php

namespace App\Tests;

use App\DatabaseFactory;
use App\Question;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    private $database;

    public function setUp(): void
    {
        parent::setUp();
        $this->database = DatabaseFactory::create('sqlite://'. __DIR__ . '/../var/test.db');
    }

    public function testQuestion(): void
    {
        $model = new Question($this->database);
        $records = $model->getQuestions();
        $this->assertGreaterThan(0, count($records));
    }
}
