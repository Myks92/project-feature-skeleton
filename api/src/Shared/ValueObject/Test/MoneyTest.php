<?php

declare(strict_types=1);

namespace App\Shared\ValueObject\Test;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(\App\Shared\ValueObject\Money::class)]
final class MoneyTest extends TestCase
{
    public function testSuccess(): void
    {
        $money = new Money($amount = 100.0, $currency = $this->createCurrency());

        self::assertSame($amount, $money->getAmount());
        self::assertEquals($currency, $money->getCurrency());
    }

    public function testNotInteger(): void
    {
        $money = new Money($amount = 100.66, $currency = $this->createCurrency());

        self::assertSame($amount, $money->getAmount());
        self::assertEquals($currency, $money->getCurrency());
    }

    public function testToString(): void
    {
        $money = new Money($amount = 100, $currency = $this->createCurrency());
        /** @psalm-suppress RedundantCastGivenDocblockType */
        $currencyValue = $currency->getValue();

        self::assertSame($amount . ' ' . $currencyValue, (string)$money);
    }

    public function testEqual(): void
    {
        $currency = $this->createCurrency();

        $money = new Money(100, $currency);
        $money2 = new Money(200, $currency); // other

        self::assertTrue($money->isEqual($money));
        self::assertFalse($money->isEqual($money2));
    }

    public function testAddPositive(): void
    {
        $money = new Money(10, $this->createCurrency());
        $money = $money->add(10);

        self::assertSame(20.0, $money->getAmount());
    }

    public function testAddNegative(): void
    {
        $money = new Money(100, $this->createCurrency());
        $money = $money->add(-10);

        self::assertSame(90.0, $money->getAmount());
    }

    public function testMultiply(): void
    {
        $money = new Money(1200, $this->createCurrency());
        $money = $money->multiply(1.2);

        self::assertSame(1440.0, $money->getAmount());
    }

    public function testMultiplyInverse(): void
    {
        $money = new Money(1200, $this->createCurrency());
        $money = $money->multiply(0.3);

        self::assertSame(360.0, $money->getAmount());
    }

    public function testDivide(): void
    {
        $money = new Money(1200, $this->createCurrency());
        $money = $money->divide(1.2);

        self::assertSame(1000.0, $money->getAmount());
    }

    public function testDivideInverse(): void
    {
        $money = new Money(1200, $this->createCurrency());
        $money = $money->divide(0.3);

        self::assertSame(4000.0, $money->getAmount());
    }

    public function testDivideZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $money = new Money(1200, $this->createCurrency());
        /** @psalm-suppress UnusedMethodCall */
        $money->divide(0);
    }

    private function createCurrency(string $code = 'USD'): Currency
    {
        return new Currency($code);
    }
}

/**
 * @psalm-immutable
 * @see \App\Shared\ValueObject\Test\MoneyTest
 */
final class Money extends \App\Shared\ValueObject\Money
{
}
