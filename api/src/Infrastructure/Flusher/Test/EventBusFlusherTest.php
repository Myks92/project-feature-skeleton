<?php

declare(strict_types=1);

namespace App\Infrastructure\Flusher\Test;

use App\Contracts\Aggregate\AggregateIdInterface;
use App\Contracts\Aggregate\AggregateRootInterface;
use App\Contracts\Bus\Event\EventBusInterface;
use App\Contracts\DomainEvent\DomainEventInterface;
use App\Contracts\DomainEvent\ReleaseEventsInterface;
use App\Contracts\Flusher\FlusherInterface;
use App\Infrastructure\Aggregate\AbstractAggregateId;
use App\Infrastructure\Flusher\EventBusFlusher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(EventBusFlusher::class)]
final class EventBusFlusherTest extends TestCase
{
    public function testInterface(): void
    {
        $flusher = $this->createStub(EventBusInterface::class);
        $flusher = new EventBusFlusher($flusher);

        self::assertInstanceOf(FlusherInterface::class, $flusher);
    }

    public function testFlush(): void
    {
        $domainEvent = new class () implements DomainEventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $aggregateRoot = $this->createMockForIntersectionOfInterfaces([AggregateRootInterface::class, ReleaseEventsInterface::class]);
        $aggregateRoot->expects(self::once())->method('releaseEvents')->willReturn([$domainEvent]);

        $flusher = $this->createMock(EventBusInterface::class);
        $flusher->expects(self::once())->method('dispatch')->with(
            self::equalTo($domainEvent),
        );

        $flusher = new EventBusFlusher($flusher);

        /** @var AggregateRootInterface&ReleaseEventsInterface $aggregateRoot */
        $flusher->flush($aggregateRoot);
    }

    public function testFlushManyAggregateRootInterface(): void
    {
        $aggregateRoot = new class () implements AggregateRootInterface, ReleaseEventsInterface {
            public function getId(): AggregateIdInterface
            {
                return new class ('00000000-0000-0000-0000-000000000001') extends AbstractAggregateId {};
            }

            public function releaseEvents(): array
            {
                return [new class () implements DomainEventInterface {
                    public string $id = '00000000-0000-0000-0000-000000000001';
                }];
            }
        };

        $flusher = $this->createMock(EventBusInterface::class);
        $flusher->expects(self::exactly(3))->method('dispatch');

        $flusher = new EventBusFlusher($flusher);

        $flusher->flush($aggregateRoot, $aggregateRoot, $aggregateRoot);
    }

    public function testFlushWithoutAggregateRootInterface(): void
    {
        $aggregateRoot = $this->createMockForIntersectionOfInterfaces([AggregateRootInterface::class, ReleaseEventsInterface::class]);
        $aggregateRoot->expects(self::never())->method('releaseEvents');

        $flusher = $this->createMock(EventBusInterface::class);
        $flusher->expects(self::never())->method('dispatch');

        $flusher = new EventBusFlusher($flusher);

        $flusher->flush();
    }

    public function testFlushWithoutReleaseEventsInterface(): void
    {
        $aggregateRoot = $this->createStub(AggregateRootInterface::class);
        $flusher = $this->createMock(EventBusInterface::class);
        $flusher->expects(self::never())->method('dispatch');

        $flusher = new EventBusFlusher($flusher);

        $this->expectExceptionMessage(sprintf('Root must implement %s', ReleaseEventsInterface::class));
        $flusher->flush($aggregateRoot);
    }
}
