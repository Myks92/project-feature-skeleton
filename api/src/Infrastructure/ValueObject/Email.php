<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueObject;

use App\Contracts\ValueObject\ValueObjectInterface;
use App\Infrastructure\Assert;

/**
 * @template-implements ValueObjectInterface<Email>
 *
 * @psalm-consistent-constructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract readonly class Email implements ValueObjectInterface, \Stringable
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
        Assert::email($value);
        $this->value = mb_strtolower($value);
    }

    /**
     * @return non-empty-string
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return non-empty-string
     */
    final public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return non-empty-string
     */
    final public function getLocal(): string
    {
        /** @var list<non-empty-string> $parts */
        $parts = explode('@', $this->getValue());

        return $parts[0];
    }

    /**
     * @return non-empty-string
     */
    final public function getDomain(): string
    {
        /** @var list<non-empty-string> $parts */
        $parts = explode('@', $this->getValue());

        return $parts[1];
    }

    #[\Override]
    final public function equals(ValueObjectInterface $object): bool
    {
        return $this->value === $object->value;
    }
}
