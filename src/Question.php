<?php

namespace App;

class Question extends Model
{
    protected string $table = 'questions';

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

    public function addAnswer($question, array $data): int
    {
        $data['question_id'] = $question['id'];
        $question['answers'][] = $data;
        $this->records[$question['id']] = $question;
        return $this->DB->addRecord('answers', $data);
    }

}
