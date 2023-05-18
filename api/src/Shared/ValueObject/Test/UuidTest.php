<?php

declare(strict_types=1);

namespace App\Shared\ValueObject\Test;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(\App\Shared\ValueObject\Uuid::class)]
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
        /** @var non-empty-string $upper */
        $upper = mb_strtoupper($value);

        $id = new Id($upper);

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
        /** @psalm-suppress InvalidArgument */
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
 * @internal
 */
final readonly class Id extends \App\Shared\ValueObject\Uuid
{
}
