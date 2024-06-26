<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\JsonType;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class JsonUnescapedType extends JsonType
{
    #[\Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        try {
            return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
        } catch (\JsonException $e) {
            /** @psalm-suppress TooManyArguments */
            throw SerializationFailed::new($value, 'json', $e->getMessage(), $e);
        }
    }
}
