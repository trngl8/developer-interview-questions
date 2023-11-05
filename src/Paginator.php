<?php

namespace App;

class Paginator
{
    public function paginate(array $items, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        return array_slice($items, $offset, $perPage);
    }
}
