<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use InvalidArgumentException;
use Stringable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class AggregateType implements Stringable
{
    public function __construct(
        private string $aggregateType
    ) {
    }

    public function __toString(): string
    {
        return $this->aggregateType;
    }

    public function toString(): string
    {
        return $this->aggregateType;
    }

    /**
     * Use this factory when aggregate type should be detected based on given aggregate root.
     */
    public static function fromAggregateRoot(AggregateRootInterface $root): self
    {
        return new self($root::class);
    }

    /**
     * Use this factory when aggregate type equals to aggregate root class
     * The factory makes sure that the aggregate root class exists.
     * @throws InvalidArgumentException
     */
    public static function fromAggregateRootClass(string $aggregateRootClass): self
    {
        if (!class_exists($aggregateRootClass)) {
            throw new InvalidArgumentException(sprintf('Aggregate root class %s can not be found', $aggregateRootClass));
        }

        return new self($aggregateRootClass);
    }

    /**
     * Use this factory when the aggregate type is not equal to the aggregate root class.
     * @throws InvalidArgumentException
     */
    public static function fromString(string $aggregateTypeString): self
    {
        if (empty($aggregateTypeString)) {
            throw new InvalidArgumentException('AggregateType must be a non empty string');
        }

        return new self($aggregateTypeString);
    }

    public function isEqual(self $other): bool
    {
        return $this->aggregateType === $other->aggregateType;
    }
}
