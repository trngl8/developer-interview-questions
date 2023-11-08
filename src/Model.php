<?php

namespace App;

use App\Database\Query;

abstract class Model
{
    protected array $records;
    protected string $table;
    protected RecordsInterface $DB;

    public function __construct(RecordsInterface $pdoDB)
    {
        $this->DB = $pdoDB;
        $this->records = [];
    }

    public function getRecords(): array
    {
        if (empty($this->records)) {
            $query = new Query($this->table);
            $query->select();
            $sql = $query->getSql();
            $this->records = $this->DB->getArrayResult($sql);
        }
        return $this->records;
    }

    public function getRecord(int $id): array
    {
        if(!array_key_exists($id, $this->records)) {
            $query = new Query($this->table);
            $query->select();
            $query->addWhere(['id=' . $id]);
            $sql = $query->getSql();
            $this->records[$id] = $this->DB->getArrayResult($sql)[0];
        }
        return $this->records[$id];
    }

    public function getTable(): string
    {
        return $this->table;
    }

}
