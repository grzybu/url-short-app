<?php

namespace App\Service;

use App\Entity\ShortUrl;
use App\Helper\RandomStringGenerator;
use App\Repository\ShortUrlRepository;
use Symfony\Component\String\ByteString;

class ShortUrlService
{
    public function __construct(
        private ShortUrlRepository $repository,
        private RandomStringGenerator $randomString
    ) {
    }

    public function generateCode(string $host, int $length = 4): ?string
    {
        $code = $this->randomString->generate($length);
        while (true) {
            if (!$this->repository->hasShortId($code, $host)) {
                return $code;
            }
            $code = $this->randomString->generate($length);
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
