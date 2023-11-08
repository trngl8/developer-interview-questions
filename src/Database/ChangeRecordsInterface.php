<?php

namespace App\Database;

interface ChangeRecordsInterface
{
    public function addRecord(string $table, array $data): int;
    public function removeRecord(string $table, int $id): void;
}
