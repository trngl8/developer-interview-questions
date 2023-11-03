<?php

namespace App\Tests\Unit;

use App\Core;
use PHPUnit\Framework\TestCase;

class CoreTest extends TestCase
{
    public function testCoreSuccess(): void
    {
        $core = new Core('test', true);
        $core->init();
        $result = $core->getTemplateEngine();
        $this->assertTrue((bool)$result);
    }
}
