<?php

namespace App;

abstract class Model
{
    protected array $records;
    protected int $limit;
    protected string $table;
    protected RecordsInterface $DB;
    protected array $order;

    public function __construct(RecordsInterface $pdoDB)
    {
        $this->DB = $pdoDB;
        $this->records = [];
        $this->order = ['id'];
        $this->limit = 0;
    }

    public function getRecords(Query $query): array
    {
        if (empty($this->records)) {
            $sql = $query->getSql();
            $this->records = $this->DB->getArrayResult($sql);
        }
        return $this->records;
    }

    public function getRecord(int $id): array
    {
        if(!array_key_exists($id, $this->records)) {
            $this->records[$id] = $this->DB->getRecord($this->table, $id);
        }
        return $this->records[$id];
    }

    public function getTable(): string
    {
        return $this->table;
    }

}
