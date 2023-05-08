<?php

declare(strict_types=1);

namespace App\Shared\Flusher\Test;

use App\Shared\Aggregate\AggregateRootInterface;
use App\Shared\Flusher\FlusherInterface;
use App\Shared\Flusher\FlushersFlusher;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(FlushersFlusher::class)]
final class FlushersFlusherTest extends TestCase
{
    public function testInterface(): void
    {
        $flusher1 = $this->createStub(FlusherInterface::class);
        $flusher2 = $this->createStub(FlusherInterface::class);
        $flushers = new FlushersFlusher([$flusher1, $flusher2]);

        self::assertInstanceOf(FlusherInterface::class, $flushers);
    }

    public function testFlush(): void
    {
        $aggregateRoot = $this->createMock(AggregateRootInterface::class);

        $flusher1 = $this->createMock(FlusherInterface::class);
        $flusher1->expects(self::once())->method('flush')->with(
            self::equalTo($aggregateRoot)
        );

        $flusher2 = $this->createMock(FlusherInterface::class);
        $flusher2->expects(self::once())->method('flush')->with(
            self::equalTo($aggregateRoot)
        );

        $flushers = new FlushersFlusher([$flusher1, $flusher2]);

        $flushers->flush($aggregateRoot);
    }

    public function testConstructInstanceOf(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an instance of App\Shared\Flusher\FlusherInterface. Got: stdClass');
        /** @psalm-suppress InvalidArgument */
        $flushers = new FlushersFlusher([new stdClass()]);
        $flushers->flush();
    }
}
