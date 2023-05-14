<?php

declare(strict_types=1);

namespace Test\Functional;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Json
{
    /**
     * @noRector
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function decode(string $data): array
    {
        /** @psalm-var array */
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @noRector
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function encode(mixed $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
