<?php

declare(strict_types=1);

namespace App\Contracts\Bus\Event;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface EventBusInterface
{
    /**
     * @template T
     *
     * @param EventInterface<T> $event
     */
    public function dispatch(EventInterface $event, array $metadata = []): void;
}
