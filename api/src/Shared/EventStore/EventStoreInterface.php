<?php

declare(strict_types=1);

namespace App\Shared\EventStore;

use App\Shared\Aggregate\AggregateRoot;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface EventStoreInterface
{
    /**
     * @return Event[]
     */
    public function getAllUnpublished(): array;

    /**
     * @return Event[]
     */
    public function getAllForAggregate(AggregateRoot $aggregateRoot): array;

    public function has(AggregateRoot $aggregateRoot): bool;

    /**
     * @param Event[] $events
     */
    public function save(array $events): void;

    public function markPublished(Event $event): void;
}
