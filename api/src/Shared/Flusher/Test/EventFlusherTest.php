<?php

declare(strict_types=1);

namespace App\Shared\Flusher\Test;

use App\Shared\Aggregate\AggregateRoot;
use App\Shared\Aggregate\AggregateType;
use App\Shared\Bus\Event\EventInterface;
use App\Shared\EventDispatcher\EventDispatcherInterface;
use App\Shared\EventStore\Event;
use App\Shared\EventStore\EventStoreInterface;
use App\Shared\Flusher\EventFlusher;
use App\Shared\Flusher\FlusherInterface;
use App\Shared\Identifier\IdentifierGeneratorInterface;
use App\Shared\Serializer\SerializerInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(EventFlusher::class)]
final class EventFlusherTest extends TestCase
{
    public function testInterface(): void
    {
        $dispatcher = $this->createStub(EventDispatcherInterface::class);
        $store = $this->createStub(EventStoreInterface::class);
        $serializer = $this->createStub(SerializerInterface::class);
        $identifier = $this->createStub(IdentifierGeneratorInterface::class);
        $flusher = new EventFlusher($dispatcher, $store, $serializer, $identifier);

        self::assertInstanceOf(FlusherInterface::class, $flusher);
    }

    public function testFlush(): void
    {
        $domainEvent = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->expects(self::once())->method('releaseEvents')->willReturn([$domainEvent]);

        $event = new Event(
            $domainEvent->id,
            $domainEvent::class,
            json_encode($domainEvent, JSON_THROW_ON_ERROR),
            $aggregateRoot->getId()->getValue(),
            AggregateType::fromAggregateRoot($aggregateRoot)->toString(),
            new DateTimeImmutable()
        );

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::once())->method('dispatch')->with(
            self::equalTo([$domainEvent])
        );

        $store = $this->createMock(EventStoreInterface::class);
        $store->expects(self::once())->method('save')->with(
            self::callback(static function (array $events) use ($event): bool {
                self::assertCount(1, $events);
                $eventStream = end($events);
                self::assertInstanceOf(Event::class, $eventStream);
                self::assertSame($eventStream->getId(), $event->getId());
                self::assertSame($eventStream->getType(), $event->getType());
                self::assertSame($eventStream->getPayload(), $event->getPayload());
                self::assertSame($eventStream->getAggregateId(), $event->getAggregateId());
                self::assertSame($eventStream->getAggregateType(), $event->getAggregateType());
                self::assertNotEmpty($event->getOccurredDate());
                return true;
            }),
        );

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::once())->method('serialize')->with(
            self::equalTo($domainEvent),
            self::equalTo('json'),
        )->willReturn(json_encode($domainEvent, JSON_THROW_ON_ERROR));

        $identifier = $this->createMock(IdentifierGeneratorInterface::class);
        $identifier->expects(self::once())->method('generate')->willReturn('00000000-0000-0000-0000-000000000000');

        $flusher = new EventFlusher($dispatcher, $store, $serializer, $identifier);

        $flusher->flush($aggregateRoot);
    }

    public function testFlushManyAggregateRoot(): void
    {
        $aggregateRoot1 = $this->createStub(AggregateRoot::class);
        $aggregateRoot2 = $this->createStub(AggregateRoot::class);
        $aggregateRoot3 = $this->createStub(AggregateRoot::class);

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::exactly(3))->method('dispatch');

        $store = $this->createMock(EventStoreInterface::class);
        $store->expects(self::exactly(3))->method('save');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::never())->method('serialize');

        $identifier = $this->createMock(IdentifierGeneratorInterface::class);
        $identifier->expects(self::never())->method('generate');

        $flusher = new EventFlusher($dispatcher, $store, $serializer, $identifier);

        $flusher->flush($aggregateRoot1, $aggregateRoot2, $aggregateRoot3);
    }

    public function testFlushWithoutAggregateRoot(): void
    {
        $aggregateRoot = $this->createMock(AggregateRoot::class);
        $aggregateRoot->expects(self::never())->method('releaseEvents');

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::never())->method('dispatch');

        $store = $this->createMock(EventStoreInterface::class);
        $store->expects(self::never())->method('save');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::never())->method('serialize');

        $identifier = $this->createMock(IdentifierGeneratorInterface::class);
        $identifier->expects(self::never())->method('generate');

        $flusher = new EventFlusher($dispatcher, $store, $serializer, $identifier);

        $flusher->flush();
    }
}
