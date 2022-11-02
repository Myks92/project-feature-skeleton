<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use InvalidArgumentException;
use Stringable;

/**
 * @psalm-immutable
 * @template-implements ValueObjectInterface<Url>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\ValueObject\Test\UrlTest
 */
abstract class Url implements ValueObjectInterface, Stringable
{
    private readonly string $value;

    public function __construct(string $value)
    {
        if (false === filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid Url.');
        }
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    final public function getValue(): string
    {
        return $this->value;
    }

    final public function isEqual(ValueObjectInterface $object): bool
    {
        return $this->getValue() === $object->getValue();
    }
}
