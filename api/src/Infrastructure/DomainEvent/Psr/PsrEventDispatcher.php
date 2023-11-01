<?php

declare(strict_types=1);

namespace App\Infrastructure\DomainEvent\Psr;

use App\Contracts\DomainEvent\DomainEventInterface;
use App\Contracts\DomainEvent\EventDispatcherInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class PsrEventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private \Psr\EventDispatcher\EventDispatcherInterface $dispatcher
    ) {}

    public function dispatch(DomainEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}
