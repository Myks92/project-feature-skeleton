<?php

declare(strict_types=1);

namespace App\Shared\ValueObject\Test;

use App\Shared\ValueObject\Phone as SharedPhone;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-import-type PhoneCountryType from \App\Shared\ValueObject\Phone
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(SharedPhone::class)]
final class PhoneTest extends TestCase
{
    public function testSuccess(): void
    {
        $phone = $this->createPhone($country = 7, $number = '9995552233');

        self::assertSame($country, $phone->getCountry());
        self::assertSame($number, $phone->getNumber());
        self::assertSame($country . $number, $phone->getFull());
    }

    public function testToString(): void
    {
        $phone = $this->createPhone($country = 7, $number = '9995552233');

        self::assertSame($country . $number, (string)$phone);
    }

    public function testEqual(): void
    {
        $number = '9995552233';

        $phone = new Phone(7, $number);
        $phone2 = new Phone(98, $number);

        self::assertTrue($phone->equals($phone));
        self::assertFalse($phone->equals($phone2));
    }

    public function testNegativeCountry(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        new Phone(-7, '9995552233');
    }

    public function testCountryLengthMax(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        new Phone(3333, '9995552233');
    }

    public function testNumberLengthMin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Phone(7, '999555223');
    }

    public function testNumberLengthMax(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Phone(7, '99955522331');
    }

    /**
     * @param PhoneCountryType $country
     * @param non-empty-string $number
     */
    private function createPhone(int $country = 7, string $number = '9995552233'): Phone
    {
        return new Phone($country, $number);
    }
}

final readonly class Phone extends SharedPhone {}
