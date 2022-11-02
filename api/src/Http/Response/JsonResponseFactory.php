<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Shared\Serializer\SerializerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class JsonResponseFactory
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(object|array $data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($data, SerializerInterface::FORMAT_JSON),
            $status,
        );
    }
}
