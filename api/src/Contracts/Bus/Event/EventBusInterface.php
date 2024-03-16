<?php

declare(strict_types=1);

namespace App\Contracts\Bus\Event;

use App\Contracts\DomainEvent\DomainEventInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface EventBusInterface
{
    /**
     * @template T
     *
     * @param EventInterface<T>|DomainEventInterface $event
     */
    public function dispatch(EventInterface|DomainEventInterface $event, array $metadata = []): void;
}
