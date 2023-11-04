<?php

namespace App;

class Answer
{
    private $DB;
    private string $table = 'answers';

    public function __construct(DatabaseConnection $pdoDB)
    {
        $this->DB = $pdoDB;
    }

    public function getAnswers(): array
    {
        return $this->DB->getRecords($this->table);
    }

    public function getAnswer(int $id): array
    {
        return $this->DB->getRecord($this->table, $id);
    }

    public function addAnswer(array $data): int
    {
        return $this->DB->addRecord($this->table, $data);
    }
}