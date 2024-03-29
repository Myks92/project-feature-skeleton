<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueObject;

use App\Contracts\ValueObject\ValueObjectInterface;
use App\Infrastructure\Assert;

/**
 * @template-implements ValueObjectInterface<Uuid>
 * @psalm-consistent-constructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract readonly class Uuid implements ValueObjectInterface, \Stringable
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
    #[\Override]
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

    #[\Override]
    final public function equals(ValueObjectInterface $object): bool
    {
        return $this->value === $object->value;
    }
}
