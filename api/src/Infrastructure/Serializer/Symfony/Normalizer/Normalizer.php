<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Symfony\Normalizer;

use App\Contracts\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface as SymfonyNormalizerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Normalizer implements NormalizerInterface
{
    public function __construct(
        private SymfonyNormalizerInterface $normalizer,
    ) {}

    #[\Override]
    public function normalize(mixed $object): null|array|\ArrayObject|bool|float|int|string
    {
        return $this->normalizer->normalize($object);
    }
}
