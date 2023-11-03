<?php

namespace App\Tests\Unit;

use App\Core;
use App\Database;
use App\Question;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CoreTest extends TestCase
{
    public function testCoreSuccess(): void
    {
        $core = new Core('test', true);
        $core->init();
        $result = $core->getTemplateEngine();
        $this->assertTrue((bool)$result);
    }

    public function testCoreRun(): void
    {
        $database = $this->createMock(Database::class);
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/'
        );
        $model = new Question($database);
        $result = $core->run($request, $model);
        $this->assertTrue((bool)$result);
    }

    public function testCoreEmptyPostRun(): void
    {
        $database = $this->createMock(Database::class);
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/',
            'POST'
        );
        $model = new Question($database);
        $result = $core->run($request, $model);
        $this->assertTrue((bool)$result);
    }

    public function testCorePostRun(): void
    {
        $database = $this->createMock(Database::class);
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/',
            'POST',
            ['question' => 'test version?']
        );
        $model = new Question($database);
        $result = $core->run($request, $model);
        $this->assertTrue((bool)$result);
    }
}
