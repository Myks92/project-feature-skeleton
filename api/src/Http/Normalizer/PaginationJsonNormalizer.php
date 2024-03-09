<?php

declare(strict_types=1);

namespace App\Http\Normalizer;

use App\Contracts\Paginator\PaginationInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class PaginationJsonNormalizer implements NormalizerInterface
{
    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true,
        ];
    }

    #[\Override]
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof PaginationInterface && $format === 'json';
    }

    /**
     * @return array{items: iterable<mixed, mixed>, pagination: array{count: int, total: int, perPage: int, page: int, pages: float}}
     */
    #[\Override]
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        if (!$object instanceof PaginationInterface) {
            throw new \InvalidArgumentException(sprintf('The object must implement "%s".', PaginationInterface::class));
        }

        return [
            'items' => $object->getItems(),
            'pagination' => [
                'count' => $object->count(),
                'total' => $object->getTotalItemCount(),
                'perPage' => $object->getItemNumberPerPage(),
                'page' => $object->getCurrentPageNumber(),
                'pages' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage()),
            ],
        ];
    }
}
