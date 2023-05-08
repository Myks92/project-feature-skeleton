<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRootInterface;
use App\Shared\DomainEvent\EventDispatcherInterface;
use App\Shared\DomainEvent\ReleaseEventsInterface;
use LogicException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Flusher\Test\DomainEventDispatcherFlusherTest
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
