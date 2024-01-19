<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Contracts\ValueObject\ValueObjectInterface;

/**
 * @template-implements ValueObjectInterface<Url>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract readonly class Url implements ValueObjectInterface, \Stringable
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
        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Invalid Url.');
        }
        $this->value = $value;
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

    final public function equals(ValueObjectInterface $object): bool
    {
        return $this->value === $object->value;
    }
}
