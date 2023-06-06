<?php

namespace App\Controller\Api;

use App\Service\ShortUrlService;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes\Tag;

#[Route('/api/short-url')]
#[Tag(name: 'Short Url')]
class ShortUrlController extends AbstractController
{
    public function __construct(private ShortUrlService $service)
    {
    }

    #[Route(name: 'api_short_url_post', methods: 'POST')]
    #[RequestBody(required: true, content: new JsonContent(example: '{"long_url":"https://google.com"}'))]
    public function postAction(Request $request): Response
    {
        $data = $request->toArray();

        if (!isset($data['long_url'])) {
            return new Response('Bad request', Response::HTTP_BAD_REQUEST);
        }


        return new Response($this->service->generateCode());
    }
}
