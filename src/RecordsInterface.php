<?php

namespace App;

interface RecordsInterface
{
    public function getRecords(string $table, int $limit=0, array $order=[], array $where=[], array $having=[]): array;
    public function getRecord(string $table, int $id): array;
    public function addRecord(string $table, array $data): int;
    public function removeRecord(string $table, int $id): void;
}
