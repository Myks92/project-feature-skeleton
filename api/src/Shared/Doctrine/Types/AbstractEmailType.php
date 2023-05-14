<?php

declare(strict_types=1);

namespace App\Shared\Doctrine\Types;

use App\Shared\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use InvalidArgumentException;

abstract class AbstractEmailType extends StringType
{
    final public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Email ? $value->getValue() : $value;
    }

    final public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        $className = $this->getClassName();

        if ($value instanceof $className) {
            return $value;
        }

        try {
            return empty($value) ? null : new $className($value);
        } catch (InvalidArgumentException) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
    }

    final public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return class-string<Email>
     */
    abstract protected function getClassName(): string;
}
