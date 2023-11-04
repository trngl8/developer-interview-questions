<?php

namespace App;

class Question
{
    private $DB;
    private string $table = 'questions';

    private array $records;

    public function __construct(DatabaseConnection $pdoDB)
    {
        $this->DB = $pdoDB;
        $this->records = [];
    }

    public function getQuestions(): array
    {
        if (empty($this->records)) {
            $this->records = $this->DB->getRecords($this->table);
        }
        return $this->records;
    }

    public function getQuestion(int $id): array
    {
        if(array_key_exists($id, $this->records)) {
            $this->records[$id] = $this->DB->getRecord($this->table, $id);
        }
        return $this->records[$id];
    }

    public function addQuestion(array $data): array
    {
        $id = $this->DB->addRecord($this->table, $data);
        $data['id'] = $id;
        $this->records[$id] = $data;
        return $data;
    }

    public function addAnswer(int $questionId, array $data): int
    {
        $question = $this->getQuestion($questionId);
        $data['question_id'] = $questionId;
        $question['answers'][] = $data;
        $this->records[$questionId] = $question;
        return $this->DB->addRecord('answers', $data);
    }

}
