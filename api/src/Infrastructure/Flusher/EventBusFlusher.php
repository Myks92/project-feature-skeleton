<?php

declare(strict_types=1);

namespace App\Infrastructure\Flusher;

use App\Contracts\Aggregate\AggregateRootInterface;
use App\Contracts\Bus\Event\EventBusInterface;
use App\Contracts\DomainEvent\ReleaseEventsInterface;
use App\Contracts\Flusher\FlusherInterface;

final readonly class EventBusFlusher implements FlusherInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
    ) {}

    #[\Override]
    public function flush(AggregateRootInterface ...$roots): void
    {
        foreach ($roots as $root) {
            if (!$root instanceof ReleaseEventsInterface) {
                throw new \LogicException(sprintf('Root must implement %s', ReleaseEventsInterface::class));
            }
            $events = $root->releaseEvents();
            foreach ($events as $event) {
                $this->eventBus->dispatch($event);
            }
        }
    }
}
