<?php

namespace App\Tests;

use App\Question;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

class QuestionTest extends TestCase
{
    private $dotenv;

    public function setUp(): void
    {
        parent::setUp();
        $this->dotenv = new Dotenv();
        $this->dotenv->load(__DIR__.'/../.env');
    }

    public function testQuestion(): void
    {
        $question = new Question(sprintf("pgsql:host=localhost;port=5432;dbname=%s;", $_ENV['POSTGRES_DB']),
            $_ENV['POSTGRES_USER'],
            $_ENV['POSTGRES_PASSWORD']);
        $records = $question->getRecords();
        $this->assertGreaterThan(0, count($records));
    }
}
