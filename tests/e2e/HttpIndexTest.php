<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpIndexTest extends TestCase
{
    private HttpClientInterface $httpClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->httpClient = HttpClient::create();
    }

    public function testIndexSuccess(): void
    {
        $response = $this->httpClient->request('GET', 'http://localhost:8000');
        $result = $response->getStatusCode();
        $this->assertEquals(200, $result);
    }

    public function testIndexPostSuccess(): void
    {
        $response = $this->httpClient->request('POST', 'http://localhost:8000', ['body' => ['question' => 'What is the day today?']]);
        $result = $response->getStatusCode();
        $this->assertEquals(301, $result);
    }

    public function testIndexPostFail(): void
    {
        $response = $this->httpClient->request('POST', 'http://localhost:8000', ['body' => ['test' => 'Your question?']]);
        $result = $response->getStatusCode();
        $this->assertEquals(301, $result);
    }
}
