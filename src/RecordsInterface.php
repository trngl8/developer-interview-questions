<?php

namespace App;

interface RecordsInterface
{
    public function getRecords(string $table): array;
    public function getRecord(string $table, int $id): array;
    public function addRecord(string $table, array $data): int;
    public function removeRecord(string $table, int $id): void;
}
