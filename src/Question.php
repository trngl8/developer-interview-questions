<?php

namespace App;

class Question
{
    private $DB;

    public function __construct(Database $pdoDB)
    {
        $this->DB = $pdoDB;
    }

    public function getQuestions(): array
    {
        return $this->DB->getRecords('questions');
    }

    public function getQuestion(int $id): array
    {
        return $this->DB->getRecord('questions', $id);
    }

}
