<?php

namespace App;

class Question extends Model
{
    protected string $table = 'questions';

    public function getRecords(Query $query): array
    {
        $query->select();
        return parent::getRecords($query);
    }

    public function addQuestion(array $data): array
    {
        $id = $this->DB->addRecord($this->table, $data);
        $data['id'] = $id;
        $this->records[$id] = $data;
        return $data;
    }

    public function addAnswer(&$question, array $data): int
    {
        $data['question_id'] = $question['id'];
        $question['answers'][] = $data;
        $this->records[$question['id']] = $question;
        return $this->DB->addRecord('answers', $data);
    }

}
