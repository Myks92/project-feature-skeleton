<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRootInterface;
use App\Shared\Aggregate\AggregateType;
use App\Shared\DomainEvent\DomainEventInterface;
use App\Shared\DomainEvent\EventDispatcherInterface;
use App\Shared\DomainEvent\ReleaseEventsInterface;
use App\Shared\EventStore\Event;
use App\Shared\EventStore\EventStoreInterface;
use App\Shared\Identifier\IdentifierGeneratorInterface;
use App\Shared\Serializer\SerializerInterface;
use DateTimeImmutable;
use LogicException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Flusher\Test\EventFlusherTest
 */
final readonly class EventFlusher implements FlusherInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EventStoreInterface $eventStore,
        private SerializerInterface $serializer,
        private IdentifierGeneratorInterface $identifier,
    ) {
    }

    public function flush(AggregateRootInterface ...$roots): void
    {
        foreach ($roots as $root) {
            if (!$root instanceof ReleaseEventsInterface) {
                throw new LogicException(sprintf('Root must implement %s', ReleaseEventsInterface::class));
            }
            $events = $root->releaseEvents();
            $this->dispatcher->dispatch(...$events);
            $this->saveEvents($root, $events);
        }
    }

    /**
     * @param list<DomainEventInterface> $events
     */
    private function saveEvents(AggregateRootInterface $aggregateRoot, array $events): void
    {
        $eventsStream = [];
        foreach ($events as $event) {
            $eventsStream[] = new Event(
                $this->identifier->generate(),
                $event::class,
                $this->serializer->serialize($event, 'json'),
                $aggregateRoot->getId()->getValue(),
                AggregateType::fromAggregateRoot($aggregateRoot)->toString(),
                new DateTimeImmutable()
            );
        }
        $this->eventStore->save($eventsStream);
    }
}
