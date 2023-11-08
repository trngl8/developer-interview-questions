<?php

namespace App;

interface RecordsInterface
{
    public function addRecord(string $table, array $data): int;
    public function removeRecord(string $table, int $id): void;
}
