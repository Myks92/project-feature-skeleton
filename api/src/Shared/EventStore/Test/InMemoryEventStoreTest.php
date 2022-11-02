<?php

declare(strict_types=1);

namespace App\Shared\EventStore\Test;

use App\Shared\Aggregate\AbstractAggregateId;
use App\Shared\Aggregate\AggregateRoot;
use App\Shared\Aggregate\AggregateType;
use App\Shared\Bus\Event\EventInterface;
use App\Shared\EventStore\Event;
use App\Shared\EventStore\InMemoryEventStore;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Shared\EventStore\InMemoryEventStoreTest
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class InMemoryEventStoreTest extends TestCase
{
    public function testSave(): void
    {
        $id = $this->createMock(AbstractAggregateId::class);
        $id->method('getValue')->willReturn('00000000-0000-0000-0000-000000000000');

        $domainEvent = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->expects(self::once())->method('getId')->willReturn($id);
        $aggregateRoot->method('releaseEvents')->willReturn([$domainEvent]);

        $event = new Event(
            $domainEvent->id,
            $domainEvent::class,
            json_encode($domainEvent, JSON_THROW_ON_ERROR),
            $id->getValue(),
            AggregateType::fromAggregateRoot($aggregateRoot)->toString(),
            new DateTimeImmutable()
        );

        $store = new InmemoryEventStore();

        $store->save([$event]);

        self::assertTrue($store->has($aggregateRoot));
    }

    public function testHas(): void
    {
        $id = $this->createMock(AbstractAggregateId::class);
        $id->method('getValue')->willReturn('00000000-0000-0000-0000-000000000000');

        $domainEvent = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->expects(self::once())->method('getId')->willReturn($id);
        $aggregateRoot->method('releaseEvents')->willReturn([$domainEvent]);

        $event = new Event(
            $domainEvent->id,
            $domainEvent::class,
            json_encode($domainEvent, JSON_THROW_ON_ERROR),
            $id->getValue(),
            AggregateType::fromAggregateRoot($aggregateRoot)->toString(),
            new DateTimeImmutable()
        );

        $store = new InmemoryEventStore();

        $store->save([$event]);

        self::assertTrue($store->has($aggregateRoot));
    }

    public function testGetAllUnpublished(): void
    {
        $id = $this->createMock(AbstractAggregateId::class);
        $id->method('getValue')->willReturn('00000000-0000-0000-0000-000000000000');

        $domainEvent = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->method('releaseEvents')->willReturn([$domainEvent]);

        $event = new Event(
            $domainEvent->id,
            $domainEvent::class,
            json_encode($domainEvent, JSON_THROW_ON_ERROR),
            $id->getValue(),
            AggregateType::fromAggregateRoot($aggregateRoot)->toString(),
            new DateTimeImmutable()
        );

        $store = new InmemoryEventStore();

        $store->save([$event]);

        $events = $store->getAllUnpublished();

        self::assertEquals($events, [$event]);
    }

    public function testGetAllUnpublishedEmpty(): void
    {
        $store = new InmemoryEventStore();

        $events = $store->getAllUnpublished();

        self::assertEquals($events, []);
    }

    public function testGetAllForAggregate(): void
    {
        $id = $this->createMock(AbstractAggregateId::class);
        $id->method('getValue')->willReturn('00000000-0000-0000-0000-000000000000');

        $domainEvent = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->expects(self::once())->method('getId')->willReturn($id);
        $aggregateRoot->method('releaseEvents')->willReturn([$domainEvent]);

        $event = new Event(
            $domainEvent->id,
            $domainEvent::class,
            json_encode($domainEvent, JSON_THROW_ON_ERROR),
            $id->getValue(),
            AggregateType::fromAggregateRoot($aggregateRoot)->toString(),
            new DateTimeImmutable()
        );

        $store = new InmemoryEventStore();

        $store->save([$event]);

        $events = $store->getAllForAggregate($aggregateRoot);

        self::assertEquals($events, [$event]);
    }

    public function testGetAllForAggregateEmpty(): void
    {
        $id = $this->createMock(AbstractAggregateId::class);
        $id->method('getValue')->willReturn('00000000-0000-0000-0000-000000000000');

        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->expects(self::never())->method('getId')->willReturn($id);

        $store = new InmemoryEventStore();

        $events = $store->getAllForAggregate($aggregateRoot);

        self::assertEquals($events, []);
    }

    public function testMarkPublished(): void
    {
        $id = $this->createMock(AbstractAggregateId::class);
        $id->method('getValue')->willReturn('00000000-0000-0000-0000-000000000001');

        $domainEvent1 = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000002';
        };

        $domainEvent2 = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000003';
        };

        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->expects(self::never())->method('getId')->willReturn($id);
        $aggregateRoot->method('releaseEvents')->willReturn([$domainEvent1, $domainEvent2]);

        $event1 = new Event(
            $domainEvent1->id,
            $domainEvent1::class,
            json_encode($domainEvent1, JSON_THROW_ON_ERROR),
            $id->getValue(),
            AggregateType::fromAggregateRoot($aggregateRoot)->toString(),
            new DateTimeImmutable()
        );

        $event2 = new Event(
            $domainEvent2->id,
            $domainEvent2::class,
            json_encode($domainEvent2, JSON_THROW_ON_ERROR),
            $id->getValue(),
            AggregateType::fromAggregateRoot($aggregateRoot)->toString(),
            new DateTimeImmutable()
        );

        $store = new InmemoryEventStore();

        $store->save([$event1, $event2]);

        $events = $store->getAllUnpublished();

        self::assertEquals($events, [$event1, $event2]);

        $store->markPublished($event2);

        $events = $store->getAllUnpublished();

        self::assertEquals($events, [$event1]);
    }
}
