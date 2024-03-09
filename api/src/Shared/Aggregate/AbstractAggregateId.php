<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use App\Contracts\Aggregate\AggregateIdInterface;
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

    #[\Override]
    final public function __toString(): string
    {
        return $this->getValue();
    }

    #[\Override]
    final public function getValue(): string
    {
        return $this->value;
    }

    #[\Override]
    final public function isEqual(AggregateIdInterface $aggregateId): bool
    {
        return $this->value === $aggregateId->getValue();
    }
}
