<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueObject\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(\App\Infrastructure\ValueObject\Uuid::class)]
final class UuidTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = Uuid::uuid4()->toString());

        self::assertSame($value, $id->value);
    }

    public function testToString(): void
    {
        $id = new Id($value = Uuid::uuid4()->toString());

        self::assertSame($value, (string) $id);
    }

    public function testIncorrect(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Id('12345');
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        new Id('');
    }

    public function testEqual(): void
    {
        $id = new Id(Uuid::uuid4()->toString());
        $id2 = new Id(Uuid::uuid4()->toString());

        self::assertTrue($id->equals($id));
        self::assertFalse($id->equals($id2));
    }
}

/**
 * @internal
 */
final readonly class Id extends \App\Infrastructure\ValueObject\Uuid {}
