<?php

namespace App;

class Query
{
    private string $sql;

    private string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function select(array $columns): self
    {
        $this->sql = sprintf("SELECT %s FROM %s", implode(',', $columns), $this->table);
        return $this;
    }

    public function addWhere(array $conditions): self
    {
        $this->sql .= sprintf(" WHERE %s", implode(' AND ', $conditions));
        return $this;
    }

    public function addLimit(int $limit): self
    {
        $this->sql .= sprintf(" LIMIT %d", $limit);
        return $this;
    }

    public function addOrder(array $order): self
    {
        $this->sql .= sprintf(" ORDER BY %s", implode(',', $order));
        return $this;
    }

    public function addHaving(array $conditions): self
    {
        $this->sql .= sprintf(" HAVING %s", implode(' AND ', $conditions));
        return $this;
    }

    public function addGroupBy(array $columns): self
    {
        $this->sql .= sprintf(" GROUP BY %s", implode(',', $columns));
        return $this;
    }

    public function getSql(): string
    {
        return $this->sql;
    }
}
