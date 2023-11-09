<?php

namespace App\Tests\Unit;

use App\Core;
use App\Database\DatabaseConnection;
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
        $result = $core->connectDatabase();
        $this->assertTrue((bool)$result);
    }

    public function testGetDatabaseException(): void
    {
        $core = new Core('debug', true);
        $core->init();
        $result = $core->connectDatabase();
        $this->assertTrue((bool)$result);
    }

    public function testCoreRun(): void
    {
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/'
        );
        $core->run($request);
        $this->assertTrue((bool)$core->getLastResponse());
    }

    public function testCoreEmptyPostRun(): void
    {
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/',
            'POST'
        );
        $core->run($request);
        $this->assertTrue((bool)$core->getLastResponse());
    }

    public function testCorePostRun(): void
    {
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/',
            'POST',
            ['title' => 'test version?']
        );
        $core->run($request);
        $this->assertTrue((bool)$core->getLastResponse());
    }

    public function testGetExceptionResponse(): void
    {
        $core = new Core('test', true);
        $core->init();
        $result = $core->getExceptionResponse();
        $this->assertTrue((bool)$result);
    }

    public function testApiRun(): void
    {
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/api'
        );
        $core->run($request);
        $this->assertTrue((bool)$core->getLastResponse());
    }
}
