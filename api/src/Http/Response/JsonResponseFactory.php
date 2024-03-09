<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Contracts\Serializer\SerializerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class JsonResponseFactory
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    public function __invoke(array|object $data, int $status = 200, array $headers = []): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($data, SerializerInterface::FORMAT_JSON),
            $status,
        );
    }
}
