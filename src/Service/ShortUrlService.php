<?php

namespace App\Service;

use App\Repository\ShortUrlRepository;
use Symfony\Component\String\ByteString;

class ShortUrlService
{
    public function __construct(private ShortUrlRepository $repository)
    {
    }

    public function generateRandomCode(int $length): string
    {
        return ByteString::fromRandom($length)->toString();
    }

    public function generateCode(): ?string
    {
        $code = $this->generateRandomCode(4);
        while (true) {
            if (!$this->repository->findOneBy(['shortId' => $code])) {
                return $code;
            }
            $code = $this->generateRandomCode();
        }
    }
}
