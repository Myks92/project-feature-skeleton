<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Contracts\ValueObject\ValueObjectInterface;
use App\Shared\Assert;
use Stringable;

/**
 * @template-implements ValueObjectInterface<Name>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract readonly class Name implements ValueObjectInterface, Stringable
{
    /**
     * @param non-empty-string $first
     * @param non-empty-string $last
     * @param non-empty-string|null $middle
     */
    public function __construct(
        private string $first,
        private string $last,
        private ?string $middle = null
    ) {
        Assert::notEmpty($first);
        Assert::notEmpty($last);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->getFull();
    }

    /**
     * @return non-empty-string
     */
    final public function getFirst(): string
    {
        return $this->first;
    }

    /**
     * @return non-empty-string
     */
    final public function getLast(): string
    {
        return $this->last;
    }

    /**
     * @return non-empty-string|null
     */
    final public function getMiddle(): ?string
    {
        return $this->middle;
    }

    /**
     * @return non-empty-string
     */
    final public function getFull(string $separator = ' '): string
    {
        /** @var non-empty-string */
        return implode($separator, array_filter([
            $this->first,
            $this->last,
            $this->middle,
        ]));
    }

    final public function isEqual(ValueObjectInterface $object): bool
    {
        return $this->getFull() === $object->getFull();
    }
}
