<?php

namespace App\Tests\Unit;

use App\Paginator;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    public function testPaginatorSuccess(): void
    {
        $paginator = new Paginator();
        $result = $paginator->paginate(range(1, 100), 1, 10);
        $this->assertEquals(range(1, 10), $result);
    }
}
