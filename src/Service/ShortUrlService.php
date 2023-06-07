<?php

namespace App\Service;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use Symfony\Component\String\ByteString;

readonly class ShortUrlService
{
    public function __construct(private ShortUrlRepository $repository)
    {
    }

    public function generateRandomCode(int $length): string
    {
        return ByteString::fromRandom($length)->toString();
    }

    public function generateCode(string $host, int $length = 4): ?string
    {
        $code = $this->generateRandomCode($length);
        while (true) {
            if (!$this->repository->findOneBy(['shortId' => $code, 'host' => $host])) {
                return $code;
            }
            $code = $this->generateRandomCode($length);
        }
    }

    public function createShortUrl(mixed $longUrl, string $host): ?ShortUrl
    {
        $shortId = $this->generateCode($host, 4);
        $shortUrl = (new ShortUrl())
            ->setLongUrl($longUrl)
            ->setShortId($shortId)
            ->setHost($host)
            ->setShortUrl("{$host}/{$shortId}");

        $this->repository->save($shortUrl, true);

        return $shortUrl;
    }
}
