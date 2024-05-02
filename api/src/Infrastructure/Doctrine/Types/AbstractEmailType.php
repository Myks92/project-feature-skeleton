<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Infrastructure\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Types;

abstract class AbstractEmailType extends StringType
{
    #[\Override]
    final public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Email ? $value->getValue() : $value;
    }

    #[\Override]
    final public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        $className = $this->getClassName();

        if ($value instanceof $className) {
            return $value;
        }

        try {
            return empty($value) ? null : new $className($value);
        } catch (\InvalidArgumentException $exception) {
            throw SerializationFailed::new($value, Types::STRING, $exception->getMessage());
        }
    }

    /**
     * @return class-string<Email>
     */
    abstract protected function getClassName(): string;
}
