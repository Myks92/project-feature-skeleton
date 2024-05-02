<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Infrastructure\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Types;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract class AbstractUuidType extends GuidType
{
    #[\Override]
    final public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value->value;
        }

        throw ValueNotConvertible::new($value, Types::GUID);
    }

    #[\Override]
    final public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Uuid
    {
        if (empty($value)) {
            return null;
        }

        $className = $this->getClassName();

        if ($value instanceof $className) {
            return $value;
        }

        try {
            $uuid = new $className($value);
        } catch (\InvalidArgumentException $e) {
            throw SerializationFailed::new($value, Types::GUID, $e->getMessage());
        }

        return $uuid;
    }

    /**
     * @return class-string<Uuid>
     */
    abstract protected function getClassName(): string;
}
