<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueObject\Test;

use App\Infrastructure\ValueObject\Currency;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Currency::class)]
final class CurrencyTest extends TestCase
{
    public function testSuccess(): void
    {
        $currency = Currency::USD;
        self::assertSame(Currency::USD->value, $currency->getValue());
    }

    public function testEqual(): void
    {
        $currency = Currency::USD;
        $currency2 = Currency::EUR; // other

        self::assertTrue($currency->equals($currency));
        self::assertFalse($currency->equals($currency2));
    }
}
