<?php

declare(strict_types=1);

namespace App\Shared\EventDispatcher;

use App\Shared\Bus\Event\EventInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface EventDispatcherInterface
{
    /**
     * @param list<EventInterface> $events
     */
    public function dispatch(array $events): void;
}
