<?php

namespace App;

class Answer extends Model
{
    protected string $table = 'answers';

    public function getRate(int $id): int
    {
        $result = $this->getRecord($id);
        return $result['rate'];
    }
}
