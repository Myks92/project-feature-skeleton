<?php

declare(strict_types=1);

namespace App\Shared\EventDispatcher;

use App\Shared\Bus\Event\EventInterface;
use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcher;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\EventDispatcher\Test\EventDispatcherTest
 */
final class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private readonly PsrEventDispatcher $dispatcher
    ) {
    }

    /**
     * @param list<EventInterface> $events
     */
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}
