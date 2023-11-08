<?php

namespace App\Tests\Unit;

use App\Database\DatabaseFactory;
use App\Database\Query;
use App\Question;
use App\RecordsInterface;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    private RecordsInterface $database;

    public function setUp(): void
    {
        parent::setUp();
        $this->database = DatabaseFactory::create('sqlite://'. __DIR__ . '/../../var/test.db');
    }

    public function testQuestionsSuccess(): void
    {
        $model = new Question($this->database);
        $query = new Query($model->getTable());
        $records = $model->getRecords($query);
        $this->assertGreaterThan(0, count($records));
    }

    public function testQuestionSuccess(): void
    {
        $model = new Question($this->database);
        $record = $model->getRecord(1);
        $this->assertEquals('What is an abstract class?', $record['title']);
    }

    public function testAddQuestionSuccess(): void
    {
        $model = new Question($this->database);
        $record = $model->addQuestion([
            'title' => 'How is going on?',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->assertGreaterThan( 1, $record['id']);
    }

    public function testAddAnswerSuccess(): void
    {
        $model = new Question($this->database);
        $question = $model->addQuestion([
            'title' => 'How is going on?',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $model->addAnswer($question, [
            'body' => 'It is going on well',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->assertEquals('It is going on well', $question['answers'][0]['body']);
    }
}
