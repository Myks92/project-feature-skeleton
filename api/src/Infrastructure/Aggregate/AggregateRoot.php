<?php

declare(strict_types=1);

namespace App\Infrastructure\Aggregate;

use App\Contracts\Aggregate\AggregateRootInterface;
use App\Contracts\Aggregate\AggregateVersioningInterface;
use App\Contracts\DomainEvent\DomainEventInterface;
use App\Contracts\DomainEvent\ReleaseEventsInterface;
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

    /**
     * @return list<DomainEventInterface>
     */
    #[\Override]
    final public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }

    #[\Override]
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
