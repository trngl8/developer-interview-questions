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

    public function getRecords(): array
    {
        if (empty($this->records)) {
            $this->records = $this->DB->getRecords($this->table, $this->limit, $this->order);
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
}
