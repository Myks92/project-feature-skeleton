<?php

declare(strict_types=1);

namespace App\Shared\Flusher\Test;

use App\Shared\Aggregate\AggregateRoot;
use App\Shared\Flusher\DoctrineFlusher;
use App\Shared\Flusher\FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Shared\Flusher\DoctrineFlusher
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class DoctrineFlusherTest extends TestCase
{
    public function testInterface(): void
    {
        $em = $this->createStub(EntityManagerInterface::class);
        $flusher = new DoctrineFlusher($em);

        self::assertInstanceOf(FlusherInterface::class, $flusher);
    }

    public function testFlush(): void
    {
        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->expects(self::never())->method('releaseEvents');

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())->method('flush');

        $flusher = new DoctrineFlusher($em);

        $flusher->flush($aggregateRoot);
    }

    public function testFlushWithOutAggregateRoot(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())->method('flush');

        $flusher = new DoctrineFlusher($em);

        $flusher->flush();
    }
}
