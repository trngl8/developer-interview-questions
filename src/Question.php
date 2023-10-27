<?php

namespace App;

class Question
{
    private $DB;
    private string $table = 'questions';

    public function __construct(Database $pdoDB)
    {
        $this->DB = $pdoDB;
    }

    public function getQuestions(): array
    {
        return $this->DB->getRecords($this->table);
    }

    public function getQuestion(int $id): array
    {
        return $this->DB->getRecord($this->table, $id);
    }

    public function addQuestion(array $data): int
    {
        return $this->DB->addRecord($this->table, $data);
    }

}
