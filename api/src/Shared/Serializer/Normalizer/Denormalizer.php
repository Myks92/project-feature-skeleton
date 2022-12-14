<?php

declare(strict_types=1);

namespace App\Shared\Serializer\Normalizer;

use Symfony\Component\Serializer\Context\Normalizer\JsonSerializableNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\SerializerContextBuilder;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface as SymfonyDenormalizerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Serializer\Test\Normalizer\DenormalizerTest
 */
final class Denormalizer implements DenormalizerInterface
{
    public function __construct(
        private readonly SymfonyDenormalizerInterface $denormalizer
    ) {
    }

    /**
     * @template T
     * @param class-string<T> $type
     * @return T
     */
    public function denormalize(mixed $data, string $type): object
    {
        $contextBuilder = (new SerializerContextBuilder())
            ->withCollectDenormalizationErrors(true);

        $contextBuilder = (new JsonSerializableNormalizerContextBuilder())
            ->withContext($contextBuilder)
            ->withAllowExtraAttributes(true);

        return $this->denormalizer->denormalize($data, $type, null, $contextBuilder->toArray());
    }
}
