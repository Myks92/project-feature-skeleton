<?php

declare(strict_types=1);

namespace App\Shared\DomainEvent;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcher;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\DomainEvent\Test\EventDispatcherTest
 */
final readonly class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private PsrEventDispatcher $dispatcher
    ) {
    }

    public function dispatch(DomainEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}
