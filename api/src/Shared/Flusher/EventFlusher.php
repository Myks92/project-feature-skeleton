<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRoot;
use App\Shared\Aggregate\AggregateType;
use App\Shared\EventDispatcher\EventDispatcherInterface;
use App\Shared\EventStore\Event;
use App\Shared\EventStore\EventStoreInterface;
use App\Shared\Identifier\IdentifierGeneratorInterface;
use App\Shared\Serializer\SerializerInterface;
use DateTimeImmutable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Flusher\Test\EventFlusherTest
 */
final class EventFlusher implements FlusherInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly EventStoreInterface $eventStore,
        private readonly SerializerInterface $serializer,
        private readonly IdentifierGeneratorInterface $identifier,
    ) {
    }

    public function flush(AggregateRoot ...$roots): void
    {
        foreach ($roots as $root) {
            $events = $root->releaseEvents();
            $this->dispatcher->dispatch($events);
            $this->saveEvents($root, $events);
        }
    }

    /**
     * @param array<array-key, \App\Shared\Bus\Event\EventInterface> $events
     */
    private function saveEvents(AggregateRoot $aggregateRoot, array $events): void
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
