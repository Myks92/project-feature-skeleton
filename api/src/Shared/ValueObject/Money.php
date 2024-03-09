<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Contracts\ValueObject\ValueObjectInterface;
use App\Shared\Assert;

/**
 * @template-implements ValueObjectInterface<Money>
 * @psalm-consistent-constructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract readonly class Money implements ValueObjectInterface, \Stringable
{
    public function __construct(
        private float $amount,
        private Currency $currency,
    ) {}

    /**
     * @return non-empty-string
     */
    #[\Override]
    public function __toString(): string
    {
        $currencyValue = $this->getCurrency()->getValue();

        return $this->amount . ' ' . $currencyValue;
    }

    /**
     * Add an integer quantity to the amount and returns a new Money object.
     * Use a negative quantity for subtraction.
     */
    final public function add(float $quantity): static
    {
        $amount = $this->getAmount() + $quantity;

        return new static($amount, $this->getCurrency());
    }

    final public function getAmount(): float
    {
        return $this->amount;
    }

    final public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * Represents the multiply value by the given factor.
     */
    final public function multiply(float $multiplier): static
    {
        $amount = $this->getAmount() * $multiplier;

        return new static((int) $amount, $this->getCurrency());
    }

    /**
     * Represents the divided value by the given factor.
     */
    final public function divide(float $divisor): static
    {
        Assert::greaterThan($divisor, 0);
        $amount = $this->getAmount() / $divisor;

        return new static((int) $amount, $this->getCurrency());
    }

    #[\Override]
    final public function equals(ValueObjectInterface $object): bool
    {
        return
            $this->amount === $object->amount
            && $this->currency === $object->currency;
    }
}
