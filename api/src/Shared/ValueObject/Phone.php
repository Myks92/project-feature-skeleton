<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use App\Shared\Assert;
use Stringable;

/**
 * @psalm-type PhoneCountryType = int<1,999>
 * @template-implements ValueObjectInterface<Phone>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\ValueObject\Test\PhoneTest
 */
abstract readonly class Phone implements ValueObjectInterface, Stringable
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
        Assert::regex((string)$country, self::PATTERN_COUNTRY);
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

        $country = mb_substr($phone, -1, (strlen($phone) - 10));
        $number = mb_substr($phone, -1, 10);

        return new static((int)$country, $number);
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

    final public function isEqual(ValueObjectInterface $object): bool
    {
        return $this->getFull() === $object->getFull();
    }
}
