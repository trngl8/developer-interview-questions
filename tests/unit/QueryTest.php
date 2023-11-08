<?php

namespace App\Tests\Unit;

use App\Database\Query;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function testWhereQuery()
    {
        $query = new Query('questions');
        $query->select(['id', 'question', 'created_at'])
            ->addWhere(['id => 1'])
            ->addOrder(['id'])
            ->addLimit(10);
        $result = $query->getSql();
        $this->assertEquals('SELECT id,question,created_at FROM questions WHERE id => 1 ORDER BY id LIMIT 10', $result);
    }

    public function testHavingQuery()
    {
        $query = new Query('questions');
        $query->select(['id', 'question', 'created_at', 'COUNT(*) AS c'])
            ->addGroupBy(['id'])
            ->addHaving(['c > 1'])
            ->addOrder(['id'])
            ->addLimit(10);
        $result = $query->getSql();
        $this->assertEquals('SELECT id,question,created_at,COUNT(*) AS c FROM questions GROUP BY id HAVING c > 1 ORDER BY id LIMIT 10', $result);
    }
}
