<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;
use InvalidArgumentException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Doctrine\Test\Types\AbstractUuidTypeTest
 */
abstract class AbstractAggregateIdType extends GuidType
{
    final public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof AbstractAggregateId) {
            return $value->getValue();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [$this->getName()]);
    }

    final public function convertToPHPValue($value, AbstractPlatform $platform): ?AbstractAggregateId
    {
        if (empty($value)) {
            return null;
        }
        $className = $this->getClassName();

        if ($value instanceof $className) {
            return $value;
        }

        try {
            $aggregateId = new $className($value);
        } catch (InvalidArgumentException) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $aggregateId;
    }

    final public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return class-string<AbstractAggregateId>
     */
    abstract protected function getClassName(): string;
}
