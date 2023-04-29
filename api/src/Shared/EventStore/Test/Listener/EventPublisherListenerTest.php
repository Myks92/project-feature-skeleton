<?php

declare(strict_types=1);

namespace App\Shared\EventStore\Test\Listener;

use App\Shared\Bus\Event\EventBusInterface;
use App\Shared\Bus\Event\EventInterface;
use App\Shared\EventStore\Event;
use App\Shared\EventStore\EventStoreInterface;
use App\Shared\EventStore\Listener\EventPublisherListener;
use App\Shared\Serializer\SerializerInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(EventPublisherListener::class)]
final class EventPublisherListenerTest extends TestCase
{
    private EventDispatcher $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testPublished(): void
    {
        $domainEvent = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000001';
        };

        $event = new Event(
            $domainEvent->id,
            $domainEvent::class,
            json_encode($domainEvent, JSON_THROW_ON_ERROR),
            '00000000-0000-0000-0000-000000000002',
            'aggregate',
            new DateTimeImmutable()
        );

        $eventStore = $this->createMock(EventStoreInterface::class);
        $eventStore->expects(self::once())->method('getAllUnpublished')->willReturn([$event]);
        $eventStore->expects(self::once())->method('markPublished')->with(
            self::equalTo($event),
        );

        $eventBus = $this->createMock(EventBusInterface::class);
        $eventBus->expects(self::once())->method('dispatch')->with(
            self::equalTo($domainEvent),
        );

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::once())->method('deserialize')->with(
            self::equalTo($event),
            self::equalTo($event->getType()),
            self::equalTo('json'),
        )->willReturn($domainEvent);

        $listener = new EventPublisherListener($eventStore, $eventBus, $serializer);

        $this->dispatcher->addListener(Events::postFlush, $listener);
        $objectManager = $this->createStub(EntityManagerInterface::class);
        $domainEvent = new PostFlushEventArgs($objectManager);
        $this->dispatcher->dispatch($domainEvent, Events::postFlush);
    }

    public function testNotSupport(): void
    {
        $domainEvent = new class() {
            public string $id = '00000000-0000-0000-0000-000000000001';
        };

        $event = new Event(
            $domainEvent->id,
            $domainEvent::class,
            json_encode($domainEvent, JSON_THROW_ON_ERROR),
            '00000000-0000-0000-0000-000000000002',
            'aggregate',
            new DateTimeImmutable()
        );

        $eventStore = $this->createMock(EventStoreInterface::class);
        $eventStore->expects(self::once())->method('getAllUnpublished')->willReturn([$event]);
        $eventStore->expects(self::never())->method('markPublished');

        $eventBus = $this->createMock(EventBusInterface::class);
        $eventBus->expects(self::never())->method('dispatch');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::once())->method('deserialize')->willReturn($domainEvent);

        $listener = new EventPublisherListener($eventStore, $eventBus, $serializer);

        $this->dispatcher->addListener(Events::postFlush, $listener);
        $objectManager = $this->createStub(EntityManagerInterface::class);
        $domainEvent = new PostFlushEventArgs($objectManager);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Event not supported.');
        $this->dispatcher->dispatch($domainEvent, Events::postFlush);
    }

    public function testNoPublished(): void
    {
        $eventStore = $this->createMock(EventStoreInterface::class);
        $eventStore->expects(self::once())->method('getAllUnpublished')->willReturn([]);
        $eventStore->expects(self::never())->method('markPublished');

        $eventBus = $this->createMock(EventBusInterface::class);
        $eventBus->expects(self::never())->method('dispatch');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::never())->method('deserialize');

        $listener = new EventPublisherListener($eventStore, $eventBus, $serializer);

        $this->dispatcher->addListener(Events::postFlush, $listener);
        $objectManager = $this->createStub(EntityManagerInterface::class);
        $event = new PostFlushEventArgs($objectManager);
        $this->dispatcher->dispatch($event, Events::postFlush);
    }
}
