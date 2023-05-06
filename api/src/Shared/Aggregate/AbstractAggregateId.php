<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use App\Shared\Assert;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract class AbstractAggregateId implements AggregateIdInterface
{
    private readonly string $value;

    final public function __construct(string $value)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
    }

    final public function __toString(): string
    {
        return $this->getValue();
    }

    final public function getValue(): string
    {
        return $this->value;
    }

    final public function isEqual(AggregateIdInterface $aggregateId): bool
    {
        return $this->value === $aggregateId->getValue();
    }
}
