<?php

declare(strict_types=1);

namespace App\Shared\ValueObject\Test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Shared\ValueObject\Uuid
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class UuidTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = Uuid::uuid4()->toString());

        self::assertSame($value, $id->getValue());
    }

    public function testToString(): void
    {
        $id = new Id($value = Uuid::uuid4()->toString());

        self::assertSame($value, (string)$id);
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $id = new Id(mb_strtoupper($value));

        self::assertSame($value, $id->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Id('12345');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Id('');
    }

    public function testEqual(): void
    {
        $id = new Id(Uuid::uuid4()->toString());
        $id2 = new Id(Uuid::uuid4()->toString());

        self::assertTrue($id->isEqual($id));
        self::assertFalse($id->isEqual($id2));
    }
}

/**
 * @psalm-immutable
 */
final class Id extends \App\Shared\ValueObject\Uuid
{
}
