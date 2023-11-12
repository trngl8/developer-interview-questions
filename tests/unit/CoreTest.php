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
        $response = $core->getLastResponse();
        $this->assertEquals(200, $response->getStatusCode());
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
        $response = $core->getLastResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCorePostRun(): void
    {
        $core = new Core('test', true);
        $core->init();
        Request::create('/');
        $request = Request::create(
            '/',
            'POST',
            [],
            [],
            [],
            [],
            ['title' => 'test version?']
        );
        $core->run($request);
        $response = $core->getLastResponse();
        $this->assertEquals(200, $response->getStatusCode());
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
        $responseData = json_decode($core->getLastResponse()->getContent(), true);
        $this->assertGreaterThan(1, count($responseData));
    }

    public function testApiCreateDeleteSuccess(): void
    {
        $core = new Core('test', true);
        $core->init();
        $request = Request::create(
            '/api', 'POST', [], [], [], [], '{"title":"test version?"}'
        );
        $core->run($request);
        $this->assertEquals('{"status":"success"}', $core->getLastResponse()->getContent());

        $request = Request::create(
            '/api/questions/4/delete', 'DELETE'
        );
        $core->run($request);
        $this->assertEquals('{"status":"success"}', $core->getLastResponse()->getContent());
    }
}
