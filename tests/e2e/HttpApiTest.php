<?php

namespace App\Tests\E2E;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpApiTest extends TestCase
{
    private HttpClientInterface $httpClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->httpClient = HttpClient::create();
    }

    public function testIndexSuccess(): void
    {
        $response = $this->httpClient->request('GET', 'http://localhost:8000/api');
        $result = $response->getStatusCode();
        $this->assertEquals(200, $result);
        $this->assertEquals('application/json', $response->getHeaders()['content-type'][0]);
    }

    public function testApiSuccess(): void
    {
        $response = $this->httpClient->request('POST', 'http://localhost:8000/api', [
            'json' => ['title' => 'What is the day today?'],
            'headers' => ['content-type' => 'application/json']
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $result = json_decode($response->getContent(), true);
        $this->assertEquals('success', $result['status']);
        $id = $result['id'];
        $response = $this->httpClient->request('DELETE', sprintf('http://localhost:8000/api/questions/%d/delete', $id));
        $this->assertEquals(200, $response->getStatusCode());
    }
}
