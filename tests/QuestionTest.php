<?php

namespace App\Tests;

use App\Question;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    public function testQuestion(): void
    {
        $question = new Question();
        $records = $question->getRecords();
        $this->assertGreaterThan(0, count($records));
    }
}
