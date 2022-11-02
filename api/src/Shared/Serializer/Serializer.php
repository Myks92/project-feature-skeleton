<?php

declare(strict_types=1);

namespace App\Shared\Serializer;

use Symfony\Component\Serializer\Context\Encoder\JsonEncoderContextBuilder;
use Symfony\Component\Serializer\Context\Normalizer\JsonSerializableNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\SerializerContextBuilder;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Serializer\Test\SerializerTest
 */
final class Serializer implements SerializerInterface
{
    public function __construct(
        private readonly \Symfony\Component\Serializer\SerializerInterface $serializer,
    ) {
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        $contextBuilder = (new JsonEncoderContextBuilder())
            ->withContext($context)
            ->withEncodeOptions(JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);

        return $this->serializer->serialize($data, $format, $contextBuilder->toArray());
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        $contextBuilder = (new SerializerContextBuilder())
            ->withContext($context)
            ->withCollectDenormalizationErrors(true);

        $contextBuilder = (new JsonEncoderContextBuilder())
            ->withContext($contextBuilder)
            ->withDecodeOptions(JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);

        $contextBuilder = (new JsonSerializableNormalizerContextBuilder())
            ->withContext($contextBuilder)
            ->withAllowExtraAttributes(true);

        return $this->serializer->deserialize($data, $type, $format, $contextBuilder->toArray());
    }
}
