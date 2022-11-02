<?php

declare(strict_types=1);

namespace App\Shared\ValueObject\Test;

use App\Shared\ValueObject\Enum;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Shared\ValueObject\Enum
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class EnumTest extends TestCase
{
    public function testSuccessString(): void
    {
        $enum = new TestEnum(TestEnum::STRING);

        self::assertSame(TestEnum::STRING, $enum->getValue());
    }

    public function testSuccessInteger(): void
    {
        $enum = new TestEnum(TestEnum::INTEGER);

        self::assertSame(TestEnum::INTEGER, $enum->getValue());
    }

    public function testToString(): void
    {
        $enum = new TestEnum(TestEnum::INTEGER);

        self::assertSame((string)TestEnum::INTEGER, (string)$enum);
    }

    public function testInvalidBoolean(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestEnum(false);

        $this->expectException(InvalidArgumentException::class);
        new TestEnum(true);
    }

    public function testInvalidEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestEnum('');
    }

    public function testInvalidNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestEnum(null);
    }

    public function testInvalidZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestEnum(0);
    }

    /**
     * __callStatic().
     */
    public function testSuccessStaticAccess(): void
    {
        self::assertEquals(new TestEnum(TestEnum::STRING), TestEnum::string());
        self::assertEquals(new TestEnum(TestEnum::INTEGER), TestEnum::integer());
        self::assertEquals(new TestEnum(TestEnum::CAMEL_CASE), TestEnum::camelCase());
    }

    /**
     * @see Enum::__callStatic()
     */
    public function testInvalidStaticAccess(): void
    {
        $this->expectExceptionMessage('No static method or enum constant \'UNKNOWN\' in class ' . TestEnum::class);
        TestEnum::unknown();
    }

    public function testEquals(): void
    {
        $string = new TestEnum(TestEnum::STRING);
        $integer = new TestEnum(TestEnum::INTEGER);

        self::assertTrue($string->isEqual($string));
        self::assertFalse($string->isEqual($integer));
    }
}

/**
 * @psalm-immutable
 *
 * @method static TestEnum string()
 * @method static TestEnum integer()
 * @method static TestEnum camelCase()
 *
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
final class TestEnum extends Enum
{
    public const STRING = 'string';
    public const CAMEL_CASE = 'camel-case';
    public const INTEGER = 42;
}
