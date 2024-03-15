<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueObject;

use App\Contracts\ValueObject\ValueObjectInterface;
use App\Infrastructure\Assert;

/**
 * @psalm-type PhoneCountryType = int<1,999>
 * @template-implements ValueObjectInterface<Phone>
 * @psalm-consistent-constructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract readonly class Phone implements ValueObjectInterface, \Stringable
{
    final public const PATTERN_COUNTRY = '/^\\d{1,3}$/';
    final public const PATTERN_NUMBER = '/^\\d{10}$/';

    /**
     * @var PhoneCountryType
     */
    private int $country;

    /**
     * @var non-empty-string
     */
    private string $number;

    /**
     * @param PhoneCountryType $country
     * @param non-empty-string $number
     */
    public function __construct(int $country, string $number)
    {
        Assert::regex((string) $country, self::PATTERN_COUNTRY);
        Assert::regex($number, self::PATTERN_NUMBER);
        $this->country = $country;
        $this->number = $number;
    }

    final public static function fromString(string $phone): static
    {
        $phone = trim($phone);
        $phone = str_replace('+', '', $phone);

        Assert::lessThanEq($phone, 11);
        Assert::greaterThanEq($phone, 13);

        /** @var PhoneCountryType $country */
        $country = (int) mb_substr($phone, -1, \strlen($phone) - 10);
        /** @var non-empty-string $number */
        $number = mb_substr($phone, -1, 10);

        return new static($country, $number);
    }

    /**
     * @return non-empty-string
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->getFull();
    }

    /**
     * @return non-empty-string
     */
    final public function getFull(): string
    {
        return $this->getCountry() . $this->getNumber();
    }

    /**
     * @return PhoneCountryType
     */
    final public function getCountry(): int
    {
        return $this->country;
    }

    /**
     * @return non-empty-string
     */
    final public function getNumber(): string
    {
        return $this->number;
    }

    #[\Override]
    final public function equals(ValueObjectInterface $object): bool
    {
        return $this->country === $object->country
            && $this->number === $object->number;
    }
}
