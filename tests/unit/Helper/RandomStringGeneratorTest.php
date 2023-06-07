<?php

namespace App\Helper;

use PHPUnit\Framework\TestCase;

class RandomStringGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $random = new RandomStringGenerator();
        $string = $random->generate(10);
        self::assertEquals(10, strlen($string));
    }
}
