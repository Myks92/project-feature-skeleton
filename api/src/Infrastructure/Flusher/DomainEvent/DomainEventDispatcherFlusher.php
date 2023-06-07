<?php

declare(strict_types=1);

namespace App\Infrastructure\Flusher\DomainEvent;

use App\Contracts\Aggregate\AggregateRootInterface;
use App\Contracts\DomainEvent\EventDispatcherInterface;
use App\Contracts\DomainEvent\ReleaseEventsInterface;
use App\Contracts\Flusher\FlusherInterface;
use LogicException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class DomainEventDispatcherFlusher implements FlusherInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
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
        }
    }
}
