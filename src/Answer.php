<?php

namespace App;

class Answer extends Model
{
    protected string $table = 'answers';

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
