<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Shared\Assert;
use Stringable;

/**
 * @template-implements ValueObjectInterface<Uuid>
 * @psalm-consistent-constructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\ValueObject\Test\UuidTest
 */
abstract readonly class Uuid implements ValueObjectInterface, Stringable
{
    /**
     * @var non-empty-string
     */
    private string $value;

    /**
     * @param non-empty-string $value
     */
    public function __construct(string $value)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @return non-empty-string
     */
    final public function getValue(): string
    {
        return $this->value;
    }

    final public function isEqual(ValueObjectInterface $object): bool
    {
        return $this->value === $object->getValue();
    }
}
