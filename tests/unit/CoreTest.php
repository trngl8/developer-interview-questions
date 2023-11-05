<?php

namespace App\Tests\Unit;

use App\Core;
use App\DatabaseConnection;
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
        $this->assertTrue((bool)$core->getRootDir());
    }

    public function testGetDatabaseSuccess(): void
    {
        $core = new Core('test', true);
        $core->init();
        $result = $core->getDatabase();
        $this->assertTrue((bool)$result);
    }

    public function testGetDatabaseException(): void
    {
        $core = new Core('debug', true);
        $core->init();
        $result = $core->getDatabase();
        $this->assertTrue((bool)$result);
    }

    public function testCoreRun(): void
    {
        $database = $this->createMock(DatabaseConnection::class);
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
        $database = $this->createMock(DatabaseConnection::class);
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
        $database = $this->createMock(DatabaseConnection::class);
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/',
            'POST',
            ['title' => 'test version?']
        );
        $model = new Question($database);
        $result = $core->run($request, $model);
        $this->assertTrue((bool)$result);
    }

    public function testGetExceptionResponse(): void
    {
        $core = new Core('test', true);
        $core->init();
        $result = $core->getExceptionResponse();
        $this->assertTrue((bool)$result);
    }
}
