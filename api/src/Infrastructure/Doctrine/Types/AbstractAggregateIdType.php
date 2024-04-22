<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Infrastructure\Aggregate\AbstractAggregateId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract class AbstractAggregateIdType extends GuidType
{
    #[\Override]
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

    #[\Override]
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
        } catch (\InvalidArgumentException) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $aggregateId;
    }

    /**
     * @return class-string<AbstractAggregateId>
     */
    abstract protected function getClassName(): string;
}
