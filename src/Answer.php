<?php

namespace App;

class Answer extends Model
{
    protected string $table = 'answers';

    public function getRate(int $id): int
    {
        $result = $this->DB->getRecord($this->table, $id);
        return $result['rate'];
    }
}
