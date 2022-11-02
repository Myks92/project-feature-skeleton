<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Shared\Assert;
use Stringable;

/**
 * @psalm-immutable
 * @template-implements ValueObjectInterface<Email>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\ValueObject\Test\EmailTest
 */
abstract class Email implements ValueObjectInterface, Stringable
{
    private readonly string $value;

    public function __construct(string $value)
    {
        Assert::email($value);
        $this->value = mb_strtolower($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    final public function getValue(): string
    {
        return $this->value;
    }

    final public function getLocal(): string
    {
        $parts = explode('@', $this->getValue());
        return $parts[0];
    }

    final public function getDomain(): string
    {
        $parts = explode('@', $this->getValue());
        return $parts[1];
    }

    final public function isEqual(ValueObjectInterface $object): bool
    {
        return $this->value === $object->getValue();
    }
}
