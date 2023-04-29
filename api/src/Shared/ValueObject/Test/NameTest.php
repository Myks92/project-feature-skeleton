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
#[CoversClass(\App\Shared\ValueObject\Name::class)]
final class NameTest extends TestCase
{
    public function testSuccess(): void
    {
        $name = new Name($first = 'First', $last = 'Last', $middle = 'Middle');

        self::assertSame($first, $name->getFirst());
        self::assertSame($last, $name->getLast());
        self::assertSame($middle, $name->getMiddle());
        self::assertSame($first . ' ' . $last . ' ' . $middle, $name->getFull());
    }

    public function testToString(): void
    {
        $name = new Name($first = 'First', $last = 'Last', $middle = 'Middle');

        self::assertSame($first . ' ' . $last . ' ' . $middle, (string)$name);
    }

    public function testFirstEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Name('', 'Last', 'Middle');
    }

    public function testLastEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Name('First', '', 'Middle');
    }

    public function testMiddleEmpty(): void
    {
        $name = new Name($first = 'First', $last = 'Last');

        self::assertSame($first, $name->getFirst());
        self::assertSame($last, $name->getLast());
        self::assertNull($name->getMiddle());
        self::assertSame($first . ' ' . $last, $name->getFull());
    }

    public function testEqual(): void
    {
        $name = new Name('First', 'Last', 'Middle');
        $name2 = new Name('First 2', 'Last 2', 'Middle 2'); // other

        self::assertTrue($name->isEqual($name));
        self::assertFalse($name->isEqual($name2));
    }
}

/**
 * @psalm-immutable
 * @see \App\Shared\ValueObject\Test\NameTest
 */
final class Name extends \App\Shared\ValueObject\Name
{
}
