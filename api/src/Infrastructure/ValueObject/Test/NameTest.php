<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueObject\Test;

use App\Infrastructure\ValueObject\Name as SharedName;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(SharedName::class)]
final class NameTest extends TestCase
{
    public function testSuccess(): void
    {
        $name = new Name($last = 'Last', $first = 'First', $middle = 'Middle');

        self::assertSame($last, $name->getLast());
        self::assertSame($first, $name->getFirst());
        self::assertSame($middle, $name->getMiddle());
        self::assertSame($last . ' ' . $first . ' ' . $middle, $name->getFull());
    }

    public function testToString(): void
    {
        $name = new Name($last = 'Last', $first = 'First', $middle = 'Middle');

        self::assertSame($last . ' ' . $first . ' ' . $middle, (string) $name);
    }

    public function testLastEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @psalm-suppress  InvalidArgument */
        new Name('', 'First', 'Middle');
    }

    public function testFirstEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @psalm-suppress  InvalidArgument */
        new Name('Last', '', 'Middle');
    }

    public function testMiddleEmpty(): void
    {
        $name = new Name($last = 'Last', $first = 'First');

        self::assertSame($last, $name->getLast());
        self::assertSame($first, $name->getFirst());
        self::assertNull($name->getMiddle());
        self::assertSame($last . ' ' . $first, $name->getFull());
    }

    public function testEqual(): void
    {
        $name = new Name('Last', 'First', 'Middle');
        $name2 = new Name('Last 2', 'First 2', 'Middle 2'); // other

        self::assertTrue($name->equals($name));
        self::assertFalse($name->equals($name2));
    }
}

final readonly class Name extends SharedName {}
