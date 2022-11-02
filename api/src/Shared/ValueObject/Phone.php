<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Shared\Assert;
use Stringable;

/**
 * @psalm-immutable
 * @template-implements ValueObjectInterface<Phone>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\ValueObject\Test\PhoneTest
 */
abstract class Phone implements ValueObjectInterface, Stringable
{
    final public const PATTERN_COUNTRY = '/^\\d{1,3}$/';
    final public const PATTERN_NUMBER = '/^\\d{10}$/';

    private readonly int $country;
    private readonly string $number;

    public function __construct(int $country, string $number)
    {
        Assert::regex((string)$country, self::PATTERN_COUNTRY);
        Assert::regex($number, self::PATTERN_NUMBER);
        $this->country = $country;
        $this->number = $number;
    }

    public function __toString(): string
    {
        return $this->getFull();
    }

    final public function getFull(): string
    {
        return $this->getCountry() . $this->getNumber();
    }

    final public function getCountry(): int
    {
        return $this->country;
    }

    final public function getNumber(): string
    {
        return $this->number;
    }

    final public function isEqual(ValueObjectInterface $object): bool
    {
        return $this->getFull() === $object->getFull();
    }
}
