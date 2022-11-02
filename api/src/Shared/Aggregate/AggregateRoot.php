<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use App\Shared\Bus\Event\EventInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
abstract class AggregateRoot implements AggregateRootInterface, AggregateVersioningInterface
{
    protected int $aggregateVersion = 0;

    /**
     * List of events that are not committed to the EventStore.
     *
     * @var list<EventInterface>
     */
    private array $recordedEvents = [];

    abstract public function getId(): AggregateIdInterface;

    final public function isEquals(self $entity): bool
    {
        return $this->getId()->getValue() === $entity->getId()->getValue();
    }

    /**
     * @return list<EventInterface>
     */
    final public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }

    final public function getAggregateVersion(): int
    {
        return $this->aggregateVersion;
    }

    final protected function recordEvent(EventInterface $event): void
    {
        ++$this->aggregateVersion;
        $this->recordedEvents[] = $event;
    }
}
