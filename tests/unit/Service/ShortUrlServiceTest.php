<?php

namespace App\Service;

use App\Entity\ShortUrl;
use App\Helper\RandomStringGenerator;
use App\Repository\ShortUrlRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShortUrlServiceTest extends TestCase
{
    private MockObject $repository;
    private MockObject $randomString;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ShortUrlRepository::class);
        $this->randomString = $this->createMock(RandomStringGenerator::class);
    }

    public function testCreateUniqueShortUrl(): void
    {
        $service = $this->createService();

        $longUrl = 'http://xyz.com';
        $host = 'http://localhost';

        $shortIdOld = 'abcd';
        $shortIdNew = 'xyz1';
        $length = 4;

        $this->randomString->expects($this->exactly(2))
            ->method('generate')
            ->withConsecutive([$length], [$length])
            ->willReturnOnConsecutiveCalls($shortIdOld, $shortIdNew);

        $params = [4, 4];
        $this->randomString->expects($this->exactly(2))
            ->method('generate')
            ->willReturnCallback(fn(string $param) => $this::assertSame(\array_shift($params), $param))
            ->willReturnOnConsecutiveCalls($shortIdOld, $shortIdNew);


        $params = [
            [$shortIdOld, $host], [$shortIdNew, $host]
        ];
        $this->repository->expects($this->exactly(2))
            ->method('hasShortId')
            ->willReturnCallback(fn(string $param) => $this::assertSame(\array_shift($params), $param))
            ->willReturn(true, false);


        $actual = $service->createShortUrl($longUrl, $host);
        self::assertInstanceOf(ShortUrl::class, $actual);
        self::assertEquals($shortIdNew, $actual->getShortId());
        self::assertEquals($host, $actual->getHost());
        self::assertEquals($longUrl, $actual->getLongUrl());
        self::assertEquals("{$host}/$shortIdNew", $actual->getShortUrl());
    }

    protected function createService(): ShortUrlService
    {
        return new ShortUrlService($this->repository, $this->randomString);
    }
}
