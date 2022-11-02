<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Shared\Assert;
use Stringable;

/**
 * @psalm-immutable
 * @template-implements ValueObjectInterface<Name>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\ValueObject\Test\NameTest
 */
abstract class Name implements ValueObjectInterface, Stringable
{
    private readonly string $first;
    private readonly string $last;

    public function __construct(string $first, string $last, private readonly ?string $middle = null)
    {
        Assert::notEmpty($first);
        Assert::notEmpty($last);
        $this->first = $first;
        $this->last = $last;
    }

    public function __toString(): string
    {
        return $this->getFull();
    }

    final public function getFirst(): string
    {
        return $this->first;
    }

    final public function getLast(): string
    {
        return $this->last;
    }

    final public function getMiddle(): ?string
    {
        return $this->middle;
    }

    final public function getFull(string $separator = ' '): string
    {
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
