<?php

declare(strict_types=1);

namespace App\Shared\ValueObject\Test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 *
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(\App\Shared\ValueObject\Currency::class)]
final class CurrencyTest extends TestCase
{
    public function testSuccess(): void
    {
        $currency = new Currency($code = 'USD');

        self::assertSame($code, $currency->getValue());
        self::assertIsArray($currency::toArray());
    }

    public function testEqual(): void
    {
        $code = 'USD';

        $currency = new Currency($code);
        $currency2 = new Currency('EUR'); // other

        self::assertTrue($currency->isEqual($currency));
        self::assertFalse($currency->isEqual($currency2));
    }

    public function testToString(): void
    {
        $currency = new Currency($code = 'USD');

        self::assertSame($code, (string)$currency);
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Currency('NON');
    }
}

/**
 * @psalm-immutable
 * @see \App\Shared\ValueObject\Test\CurrencyTest
 */
final class Currency extends \App\Shared\ValueObject\Currency
{
}
