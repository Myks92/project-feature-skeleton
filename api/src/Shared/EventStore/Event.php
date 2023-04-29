<?php

declare(strict_types=1);

namespace App\Shared\EventStore;

use App\Shared\Bus\Event\EventInterface;
use DateTimeImmutable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\EventStore\Test\EventTest
 */
final readonly class Event implements EventInterface
{
    /**
     * @param class-string $type
     */
    public function __construct(
        private string $id,
        private string $type,
        private mixed $payload,
        private string $aggregateId,
        private string $aggregateType,
        private DateTimeImmutable $occurredDate,
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
