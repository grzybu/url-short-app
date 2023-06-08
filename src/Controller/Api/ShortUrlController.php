<?php

namespace App\Controller\Api;

use App\Service\ShortUrlService;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes\Tag;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Uid\Uuid;

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
        $requestData = $request->toArray();
        $host = $requestData['host'] ?? $request->getSchemeAndHttpHost();

        $longUrl = filter_var($requestData['long_url'] ?? null, FILTER_VALIDATE_URL);

        if (!$longUrl) {
            return new Response('Bad request', Response::HTTP_BAD_REQUEST);
        }

        try {
            $shortUrl = $this->service->createShortUrl($longUrl, $host);
            $jsonContent = $this->getSerializer()->serialize($shortUrl, 'json', [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]);
            return JsonResponse::fromJsonString($jsonContent, Response::HTTP_CREATED);
        } catch (\Throwable $throwable) {
            return new Response('Server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSerializer(): Serializer
    {
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'id'        => fn($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) => $innerObject instanceof Uuid ? $innerObject->toRfc4122() : '',
                'createdAt' => fn($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) => $innerObject instanceof \DateTime ? $innerObject->getTimestamp() : '',
                'updatedAt' => fn($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) => $innerObject instanceof \DateTime ? $innerObject->getTimestamp() : '',
            ],
        ];
        $encoders = [new JsonEncoder()];
        $normalizers = [new GetSetMethodNormalizer(defaultContext: $defaultContext)];

        return new Serializer($normalizers, $encoders);
    }
}
