<?php

namespace App\Tests;

use App\Question;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    public function testQuestion(): void
    {
        $question = new Question();
        $question->getRecords();
        $this->assertTrue(true);
    }
}