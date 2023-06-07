<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Symfony\Normalizer;

use App\Contracts\Serializer\Normalizer\NormalizerInterface;
use ArrayObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface as SymfonyNormalizerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Infrastructure\Serializer\Symfony\Test\Normalizer\NormalizerTest
 */
final readonly class Normalizer implements NormalizerInterface
{
    public function __construct(
        private SymfonyNormalizerInterface $normalizer
    ) {
    }

    public function normalize(mixed $object): array|string|int|float|bool|ArrayObject|null
    {
        return $this->normalizer->normalize($object);
    }
}
