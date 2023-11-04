<?php

namespace App;

abstract class Model
{
    protected array $records;
    protected string $table;

    protected $DB;

    public function __construct(RecordsInterface $pdoDB)
    {
        $this->DB = $pdoDB;
        $this->records = [];
    }

    public function getRecords(): array
    {
        if (empty($this->records)) {
            $this->records = $this->DB->getRecords($this->table);
        }
        return $this->records;
    }
}
