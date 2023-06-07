<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    #[Route('/{shortId}', methods: 'GET')]
    public function redirectAction(?ShortUrl $shortUrl = null): Response
    {
        if (null === $shortUrl) {
            return new Response('not found', Response::HTTP_NOT_FOUND);
        }

        return new RedirectResponse($shortUrl->getLongUrl());
    }
}
