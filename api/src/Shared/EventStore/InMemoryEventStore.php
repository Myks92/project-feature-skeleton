<?php

declare(strict_types=1);

namespace App\Shared\EventStore;

use App\Shared\Aggregate\AggregateRoot;
use App\Shared\Aggregate\AggregateType;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\EventStore\Test\InMemoryEventStoreTest
 */
final class InMemoryEventStore implements EventStoreInterface
{
    /**
     * @var array<array-key, Event>
     */
    private array $events = [];

    /**
     * @return array<array-key, Event>
     */
    public function getAllUnpublished(): array
    {
        return $this->events;
    }

    /**
     * @return array<array-key, Event>
     */
    public function getAllForAggregate(AggregateRoot $aggregateRoot): array
    {
        return array_values(array_filter($this->events, static fn (Event $event): bool => $event->getAggregateId() === $aggregateRoot->getId()->getValue() &&
            $event->getAggregateType() === AggregateType::fromAggregateRoot($aggregateRoot)->toString()));
    }

    public function has(AggregateRoot $aggregateRoot): bool
    {
        foreach ($this->events as $existingEvent) {
            if (
                $existingEvent->getAggregateId() === $aggregateRoot->getId()->getValue() &&
                $existingEvent->getAggregateType() === AggregateType::fromAggregateRoot($aggregateRoot)->toString()
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array<array-key, Event> $events
     */
    public function save(array $events): void
    {
        $this->events = $events;
    }

    public function markPublished(Event $event): void
    {
        foreach ($this->events as $key => $existingEvent) {
            if ($existingEvent->getId() ===$event->getId()) {
                unset($this->events[$key]);
            }
        }
    }
}
