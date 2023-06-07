<?php

namespace App\Helper;

use Symfony\Component\String\ByteString;

class RandomStringGenerator
{
    public function generate(int $length): string
    {
        return ByteString::fromRandom($length)->toString();
    }
}
