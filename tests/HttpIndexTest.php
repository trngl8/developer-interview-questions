<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpIndexTest extends TestCase
{
    private HttpClientInterface $httpClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->httpClient = new MockHttpClient();
    }

    public function testIndexSuccess(): void
    {
        $response = $this->httpClient->request('GET', 'http://localhost:8000');
        $result = $response->getStatusCode();
        $this->assertEquals(200, $result);
    }
}
