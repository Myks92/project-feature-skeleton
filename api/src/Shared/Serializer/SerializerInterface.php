<?php

declare(strict_types=1);

namespace App\Shared\Serializer;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface SerializerInterface
{
    public const FORMAT_JSON = 'json';

    /**
     * Serializes data in the appropriate format.
     * @param array<string, mixed> $context
     */
    public function serialize(mixed $data, string $format, array $context = []): string;

    /**
     * Deserializes data into the given type.
     *
     * @template T
     * @param class-string<T> $type
     * @param array<string, mixed> $context
     * @return T
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed;
}
