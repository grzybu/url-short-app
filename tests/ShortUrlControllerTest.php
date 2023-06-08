<?php

namespace App\Tests;

use App\Repository\ShortUrlRepository;
use App\Service\ShortUrlService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShortUrlControllerTest extends WebTestCase
{
    public function testBadRequest(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('POST', '/api/short-url', []);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testServerErrorResponse(): void
    {
        $client = static::createClient();
        $serviceMock = $this->getMockBuilder(ShortUrlService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createShortUrl'])
            ->getMock();
        $serviceMock->method('createShortUrl')->willThrowException(new \Exception());
        self::getContainer()->set('App\Service\ShortUrlService', $serviceMock);

        $longUrl = 'http://test.com';
        $client->jsonRequest('POST', '/api/short-url', ['long_url' => $longUrl]);

        $this->assertResponseStatusCodeSame(500);
    }

    public function testCreateShortUrl(): void
    {
        $longUrl = 'http://test.com';
        $client = static::createClient();
        $client->jsonRequest('POST', '/api/short-url', ['long_url' => $longUrl]);

        $this->assertResponseStatusCodeSame(201);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        self::assertArrayHasKey('shortUrl', $responseData);
        self::assertArrayHasKey('longUrl', $responseData);
        self::assertEquals('http://test.com', $responseData['longUrl']);
    }
}
