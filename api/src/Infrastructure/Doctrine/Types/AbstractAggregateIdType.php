<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Infrastructure\Aggregate\AggregateId;
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

        if ($value instanceof AggregateId) {
            return $value->getValue();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [$this->getName()]);
    }

    #[\Override]
    final public function convertToPHPValue($value, AbstractPlatform $platform): ?AggregateId
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
     * @return class-string<AggregateId>
     */
    abstract protected function getClassName(): string;
}
