<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use App\Shared\DomainEvent\DomainEventInterface;
use App\Shared\DomainEvent\ReleaseEventsInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[ORM\MappedSuperclass]
abstract class AggregateRoot implements AggregateRootInterface, AggregateVersioningInterface, ReleaseEventsInterface
{
    #[ORM\Version]
    protected int $aggregateVersion = 0;

    /**
     * @var list<DomainEventInterface>
     */
    private array $recordedEvents = [];

    abstract public function getId(): AggregateIdInterface;

    final public function isEquals(self $entity): bool
    {
        return $this->getId()->getValue() === $entity->getId()->getValue();
    }

    /**
     * @return list<DomainEventInterface>
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

    final protected function recordEvent(DomainEventInterface $event): void
    {
        ++$this->aggregateVersion;
        $this->recordedEvents[] = $event;
    }
}
