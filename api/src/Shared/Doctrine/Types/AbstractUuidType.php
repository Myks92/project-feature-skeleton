<?php

declare(strict_types=1);

namespace App\Shared\Doctrine\Types;

use App\Shared\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;
use InvalidArgumentException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract class AbstractUuidType extends GuidType
{
    #[\Override]
    final public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value->getValue();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [$this->getName()]);
    }

    #[\Override]
    final public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
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
        } catch (InvalidArgumentException) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $uuid;
    }

    #[\Override]
    final public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return class-string<Uuid>
     */
    abstract protected function getClassName(): string;
}
