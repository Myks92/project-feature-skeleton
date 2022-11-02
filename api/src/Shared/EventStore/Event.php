<?php

declare(strict_types=1);

namespace App\Shared\EventStore;

use App\Shared\Bus\Event\EventInterface;
use DateTimeImmutable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\EventStore\Test\EventTest
 */
final class Event implements EventInterface
{
    /**
     * @param class-string $type
     */
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly mixed $payload,
        private readonly string $aggregateId,
        private readonly string $aggregateType,
        private readonly DateTimeImmutable $occurredDate,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return class-string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getAggregateType(): string
    {
        return $this->aggregateType;
    }

    public function getOccurredDate(): DateTimeImmutable
    {
        return $this->occurredDate;
    }
}
