<?php

namespace App\Model;

class Answer extends AbstractModel
{
    protected string $table = 'answers';

    public function getRate(int $id): int
    {
        $result = $this->getRecord($id);
        return $result['rate'];
    }
}
