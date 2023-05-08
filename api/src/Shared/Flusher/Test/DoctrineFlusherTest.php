<?php

declare(strict_types=1);

namespace App\Shared\Flusher\Test;

use App\Shared\Aggregate\AggregateRootInterface;
use App\Shared\Flusher\DoctrineFlusher;
use App\Shared\Flusher\FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(DoctrineFlusher::class)]
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
        $aggregateRoot = $this->createMock(AggregateRootInterface::class);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())->method('flush');

        $flusher = new DoctrineFlusher($em);

        $flusher->flush($aggregateRoot);
    }

    public function testFlushWithOutAggregateRootInterface(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())->method('flush');

        $flusher = new DoctrineFlusher($em);

        $flusher->flush();
    }
}
