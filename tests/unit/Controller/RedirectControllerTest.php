<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectControllerTest extends TestCase
{
    public function testCanRedirect()
    {
        $controller = new RedirectController();

        $longUrl = 'http://long.com';

        $shortUrl = (new ShortUrl())
            ->setLongUrl($longUrl);

        self::assertInstanceOf(RedirectResponse::class, $controller->redirectAction($shortUrl));
    }

    public function testNotFoundRedirect()
    {
        $controller = new RedirectController();

        $notFoundResponse = $controller->redirectAction(null);
        self::assertInstanceOf(Response::class, $notFoundResponse);
        self::assertEquals(Response::HTTP_NOT_FOUND, $notFoundResponse->getStatusCode());
    }
}
