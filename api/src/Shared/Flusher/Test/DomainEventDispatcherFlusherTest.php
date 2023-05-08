<?php

declare(strict_types=1);

namespace App\Shared\Flusher\Test;

use App\Shared\Aggregate\AggregateRootInterface;
use App\Shared\DomainEvent\DomainEventInterface;
use App\Shared\DomainEvent\EventDispatcherInterface;
use App\Shared\DomainEvent\ReleaseEventsInterface;
use App\Shared\Flusher\DomainEventDispatcherFlusher;
use App\Shared\Flusher\FlusherInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(DomainEventDispatcherFlusher::class)]
final class DomainEventDispatcherFlusherTest extends TestCase
{
    public function testInterface(): void
    {
        $dispatcher = $this->createStub(EventDispatcherInterface::class);
        $flusher = new DomainEventDispatcherFlusher($dispatcher);

        self::assertInstanceOf(FlusherInterface::class, $flusher);
    }

    public function testFlush(): void
    {
        $domainEvent = new class() implements DomainEventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $aggregateRoot = $this->createMockForIntersectionOfInterfaces([AggregateRootInterface::class, ReleaseEventsInterface::class]);
        $aggregateRoot->expects(self::once())->method('releaseEvents')->willReturn([$domainEvent]);

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::once())->method('dispatch')->with(
            self::equalTo($domainEvent)
        );

        $flusher = new DomainEventDispatcherFlusher($dispatcher);

        /** @var AggregateRootInterface&ReleaseEventsInterface $aggregateRoot */
        $flusher->flush($aggregateRoot);
    }

    public function testFlushManyAggregateRootInterface(): void
    {
        /** @var AggregateRootInterface&ReleaseEventsInterface $aggregateRoot1 */
        $aggregateRoot1 = $this->createStubForIntersectionOfInterfaces([AggregateRootInterface::class, ReleaseEventsInterface::class]);
        /** @var AggregateRootInterface&ReleaseEventsInterface $aggregateRoot2 */
        $aggregateRoot2 = $this->createStubForIntersectionOfInterfaces([AggregateRootInterface::class, ReleaseEventsInterface::class]);
        /** @var AggregateRootInterface&ReleaseEventsInterface $aggregateRoot3 */
        $aggregateRoot3 = $this->createStubForIntersectionOfInterfaces([AggregateRootInterface::class, ReleaseEventsInterface::class]);

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::exactly(3))->method('dispatch');

        $flusher = new DomainEventDispatcherFlusher($dispatcher);

        $flusher->flush($aggregateRoot1, $aggregateRoot2, $aggregateRoot3);
    }

    public function testFlushWithoutAggregateRootInterface(): void
    {
        $aggregateRoot = $this->createMockForIntersectionOfInterfaces([AggregateRootInterface::class, ReleaseEventsInterface::class]);
        $aggregateRoot->expects(self::never())->method('releaseEvents');

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::never())->method('dispatch');

        $flusher = new DomainEventDispatcherFlusher($dispatcher);

        $flusher->flush();
    }

    public function testFlushWithoutReleaseEventsInterface(): void
    {
        $aggregateRoot = $this->createStub(AggregateRootInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::never())->method('dispatch');

        $flusher = new DomainEventDispatcherFlusher($dispatcher);

        $this->expectExceptionMessage(sprintf('Root must implement %s', ReleaseEventsInterface::class));
        $flusher->flush($aggregateRoot);
    }
}
